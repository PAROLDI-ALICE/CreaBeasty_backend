<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;
use Firebase\JWT\ExpiredException;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'logout', 'refresh']]);
    }

    /**
     * LOGIN
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Adresse email ou mot de passe incorrect.'], 422);
        }

        $credentials = $validator->validated();

        if (Auth::guard('user')->attempt($credentials)) {
            $user = User::where('email', $request->input('email'))->first();
            if ($user->is_admin == 1) {
                Auth::guard('user')->login($user);
                $token = $this->generateToken($user);
                return response()->json([
                    'authorization' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ],
                    'message' => "Vous êtes connecté en tant qu'Admin.",
                ]);
            } else {
                Auth::guard('user')->login($user);
                $token = $this->generateToken($user);
                return response()->json([
                    'authorization' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ],
                    'message' => "Vous êtes connecté en tant que User.",
                ]);
            }
        } else {
            return response()->json(['message' => 'Adresse email ou mot de passe incorrect.'], 422);
        }
    }

    private function generateToken($user)
    {
        $key = env('JWT_SECRET');
        $payload = [
            'iss' => config('app.name'),
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + (60 * 60), //Set du temps imparti 
        ];

        try {
            $token = JWT::encode($payload, $key, 'RS256');
            return $token;
        } catch (ExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], 401);
        } catch (Exception $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
    }

    /**
     * LOGOUT
     */
    public function logout()
    {
        Auth::logout(); // Fonction interne de logout pour tous les Users
        return response()->json(['message' => 'Vous êtes déconnecté.']);
    }

    /**
     * REFRESH
     */
    public function refresh()
    {
        return response()->json([
            'user' => Auth::user(),
            'authorization' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
