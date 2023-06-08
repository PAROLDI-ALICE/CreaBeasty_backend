<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        //Vérification des conditions de validation
        if (Auth::guard('user')->attempt($credentials)) {
            $user = User::where('email', $credentials['email'])->first();
            //Génération et stockage du TOKEN
            $token = Auth::attempt($credentials);
            //Récupération du booléen de la table 'Users' en BDD
            if ($user->is_admin) {
                Auth::guard('user')->login($user);
                return response()->json([
                    'authorization' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ],
                    'message' => "Vous êtes connecté en tant qu'Admin.",
                ]);
            } else if ($user) {
                Auth::guard('user')->login($user);
                return response()->json([
                    'authorization' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ],
                    'message' => "Vous êtes connecté en tant que User.",
                ]);
            }
        }
        // else if (Auth::guard('user')->attempt($credentials)) {
        //     $user = User::where('email', $credentials['email'])->first();
        //     if ($user) {
        //         Auth::guard('user')->login($user);
        //         return response()->json([
        //             'message' => "Vous êtes connecté en tant que User.",
        //         ]);
        //     }
        // } 
        else {
            //Informations de connexion invalides -step back vers l'authentification
            return redirect()->back()->withErrors(
                [
                    'message' => 'Adresse email ou mot de passe incorrect.'
                ]
            );
        }
    }

    /**
     * LOGOUT
     */
    public function logout()
    {
        Auth::logout(); //Fonction interne de logout pour tous Users
        return response()->json(
            ['message' => 'Vous êtes déconnecté.']
        );
    }

    /**
     * REFRESH
     */
    public function refresh()
    {
        return response()->json([
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
