<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AIInsightsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\AIInsight;
use Carbon\Carbon;


class AIInsightController extends Controller
{
    protected $aiService;

    public function __construct(AIInsightsService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function generateReflection(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string',
            'usage_time' => 'required|string',
        ]);

        return response()->json($this->aiService->generateReflectionQuestion($request->app_name, $request->usage_time)
        , 200);
    }

    public function analyzeEmotion(Request $request)
    {
        $request->validate([
            'response' => 'required|string',
        ]);

        return response()->json($this->aiService->analyzeEmotion($request->response), 200);
    }

    public function suggestActivity(Request $request)
    {
        $request->validate([
            'emotion' => 'required|string',
        ]);

        return response()->json($this->aiService->suggestAlternativeActivity($request->emotion), 200);
    }

    public function generateAndStoreInsight(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string',
            'usage_time' => 'required|string',
            'response' => 'required|string',
        ]);
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        try {
            $questionResponse = Http::post('http://127.0.0.1:5000/generate-question', [
                'app_name' => $request->app_name,
                'usage_time' => $request->usage_time
            ]);
            $question = $questionResponse->json('question') ?? 'No question generated';
            $emotionResponse = Http::post('http://127.0.0.1:5000/analyze-emotion', [
                'response' => $request->response
            ]);
            $emotionData = $emotionResponse->json();
            $analysis = $emotionData['emotion'] ?? 'unknown';
            $category = $emotionData['category'] ?? 'neutral';
            $suggestion = null;
            if ($category === 'negative') {
                $suggestionResponse = Http::post('http://127.0.0.1:5000/suggest-activity', [
                    'emotion' => $analysis
                ]);
                $suggestion = $suggestionResponse->json('suggestion') ?? null;
            }
            $insight = AIInsight::create([
                'user_id' => $user->id,
                'insight_date' => now()->toDateString(),
                'reflection_question' => $question,
                'user_response' => $request->response,
                'analysis' => $analysis,
                'recommendations' => $suggestion ? [$suggestion] : [],
            ]);
            return response()->json([
                'message' => 'Insight saved successfully.',
                'data' => $insight,
                'allow_limit' => in_array($category, ['positive', 'neutral']),
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Insight generation error: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
    
    
}
