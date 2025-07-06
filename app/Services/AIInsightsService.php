<?php

namespace App\Services;

use GuzzleHttp\Client;

class AIInsightsService
{
    protected $client;
    protected $flaskUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->flaskUrl = env('FLASK_AI_URL', 'http://127.0.0.1:5000'); // Store Flask URL in .env
    }

    /**
     * Call AI API to generate reflection question.
     */
    public function generateReflectionQuestion($appName, $usageTime)
    {
        $response = $this->client->post("{$this->flaskUrl}/generate-question", [
            'json' => [
                'app_name' => $appName,
                'usage_time' => $usageTime,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Call AI API to analyze emotion.
     */
    public function analyzeEmotion($userResponse)
    {
        $response = $this->client->post("{$this->flaskUrl}/analyze-emotion", [
            'json' => ['response' => $userResponse],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Call AI API to suggest an alternative activity.
     */
    public function suggestAlternativeActivity($emotion)
    {
        $response = $this->client->post("{$this->flaskUrl}/suggest-activity", [
            'json' => ['emotion' => $emotion],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
