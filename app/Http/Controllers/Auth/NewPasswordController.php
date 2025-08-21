<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class NewPasswordController extends Controller
{
    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        try {
            // Log the incoming request data
            \Log::info('Password reset request received', [
                'email' => $request->email,
                'token' => $request->token,
                'has_password' => !empty($request->password),
                'has_password_confirmation' => !empty($request->password_confirmation),
                'all_request_data' => $request->only(['email', 'token', 'password', 'password_confirmation'])
            ]);
            
            $request->validate([
                'token' => ['required'],
                'email' => ['required', 'email'],
                'password' => ['required', 'confirmed', 'min:8'],
            ]);

            // Here we will attempt to reset the user's password. If it is successful we
            // will update the password on an actual user model and persist it to the
            // database. Otherwise we will parse the error and return the response.
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    event(new PasswordReset($user));
                }
            );

            // Log the status for debugging
            \Log::info('Password reset attempt', [
                'email' => $request->email,
                'status' => $status,
                'token_exists' => \DB::table('password_reset_tokens')->where('email', $request->email)->exists()
            ]);

            if ($status != Password::PASSWORD_RESET) {
                $errorMessage = '';
                switch ($status) {
                    case Password::INVALID_TOKEN:
                        $errorMessage = 'The password reset token is invalid or has expired.';
                        break;
                    case Password::INVALID_USER:
                        $errorMessage = 'We cannot find a user with that email address.';
                        break;
                    default:
                        $errorMessage = __($status);
                }
                
                if ($request->expectsJson()) {
                    return response()->json(['error' => $errorMessage], 422);
                }
                
                return back()->withErrors(['email' => $errorMessage]);
            }

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Password has been reset successfully.']);
            }

            return redirect()->route('login')->with('status', 'Password has been reset successfully.');
            
        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Validation failed', 'errors' => $e->errors()], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Password reset error', [
                'email' => $request->email ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json(['error' => 'An error occurred while resetting your password.'], 500);
            }
            
            return back()->withErrors(['email' => 'An error occurred while resetting your password.']);
        }
    }
}
