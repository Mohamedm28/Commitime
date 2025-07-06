<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\ChildRegistrationConfirmation;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Models\PasswordReset;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'is_under_18' => 'required|boolean',
            'password' => $request->is_under_18 ? 'nullable' : 'required|string|min:8',
            'parent_email' => $request->is_under_18 ? 'required|email' : 'nullable',
        ]);
        $user = User::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'is_under_18' => $validatedData['is_under_18'],
            'parent_email' => $validatedData['is_under_18'] ? $validatedData['parent_email'] : null,
            'password' => isset($validatedData['password']) ? Hash::make($validatedData['password']) : null,
        ]);

        if ($user->is_under_18) {
            if (view()->exists('emails.child_registration_confirmation')) {
                Mail::to($validatedData['parent_email'])->send(new ChildRegistrationConfirmation($user));
            } else {
                return response()->json(['error' => 'Email template not found'], 500);
            }
        }

        return response()->json([
            'message' => 'User registered successfully!',
            'user' => [],
        ], 201);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'nullable|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        if ($user->is_under_18) {
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'Login successful!',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'is_under_18' => $user->is_under_18,
                    'parent_email'=>$user->parent_email,
                ],
            ], 200);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials.'], 401);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Login successful!',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'is_under_18' => $user->is_under_18,
            ],
        ], 200);
    }
 
     public function logout(Request $request)
     {
         $request->user()->tokens()->delete();
 
         return response()->json([
             'message' => 'Logout successful!'
         ]);
     }
    
    public function sendResetCode(Request $request)
   {
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    $email = $request->email;
    $resetCode = rand(100000, 999999); 
    $expiresAt = now()->addMinutes(10); 

    PasswordReset::where('email', $email)->delete();
    PasswordReset::create([
        'email' => $email,
        'code' => $resetCode,
        'expires_at' => $expiresAt,
    ]);

    Mail::raw("Your password reset code is: $resetCode", function ($message) use ($email) {
        $message->to($email)
                ->subject('Password Reset Code');
    });

      return response()->json(['message' => 'Password reset code sent successfully.',
    ], 200);
   }

   public function verifyResetCode(Request $request)
   {
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email',
        'code' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $resetEntry = PasswordReset::where('email', $request->email)
                               ->where('code', $request->code)
                               ->first();

    if (!$resetEntry || now()->gt($resetEntry->expires_at)) {
        return response()->json(['error' => 'Invalid or expired verification code.'], 400);
    }

    return response()->json(['message' => 'Verification successful.'], 200);
}

public function resetPassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email',
        'code' => 'required|string',
        'password' => 'required|string|min:8',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $resetEntry = PasswordReset::where('email', $request->email)
                               ->where('code', $request->code)
                               ->first();

    if (!$resetEntry || now()->gt($resetEntry->expires_at)) {
        return response()->json(['error' => 'Invalid or expired verification code.'], 400);
    }

    $user = User::where('email', $request->email)->first();
    $user->password = Hash::make($request->password);
    $user->save();

    $resetEntry->delete();

    return response()->json(['message' => 'Password has been reset successfully.'], 200);
}

    public function deleteAccount(Request $request)
   {
    try {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $user->dailyReports()->delete();
        $user->streaks()->delete();
        $user->app_limit_requests()->delete();
        $user->screenDistanceAlerts()->delete();
        $user->screen_times()->delete();
        $user->ai_insights()->delete();
        $user->delete();

        return response()->json(['message' => 'Account deleted successfully.'], 200);
       } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500); 
      }
   }

 public function updateAccount(Request $request)
   {
    $request->validate([
        //'first_name' => 'string|max:255',
        //'last_name' => 'string|max:255',
        //'email' => 'email|unique:users,email,' . $request->user()->id,
        'password' => 'nullable|string|min:8|confirmed'
    ]);
    try {
        $user = $request->user();
        
        //$user->first_name = $request->first_name ?? $user->first_name;
        //$user->last_name = $request->last_name ?? $user->last_name;
        //$user->email = $request->email ?? $user->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return response()->json(['message' => 'Account updated successfully.'], 200);
    }catch (\Exception $e) {
        return response()->json(['error' => 'Failed to update account.'], 500);
    }
   }


}
