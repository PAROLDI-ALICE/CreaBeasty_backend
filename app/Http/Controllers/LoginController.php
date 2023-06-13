<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
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
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        if (Auth::guard('user')->attempt($request->only('email', 'password'))) {
            $user = User::where('email', $request->input('email'))->first();
            if ($user->is_admin) {
                Auth::guard('user')->login($user);
                $token = auth()->login($user);
                return response()->json([
                    'authorization' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ],
                    'message' => "Vous êtes connecté en tant qu'Admin.",
                ]);
            } else {
                Auth::guard('user')->login($user);
                $token = auth()->login($user);
                return response()->json([
                    'authorization' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ],
                    'message' => "Vous êtes connecté en tant que User.",
                ]);
            }
        }

        return response()->json([
            'message' => "Les informations d'identification sont incorrectes.",
        ], 401);
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
