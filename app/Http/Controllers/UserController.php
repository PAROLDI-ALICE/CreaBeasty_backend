<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Firebase\JWT\JWT;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validate pour vérification des datas récupérées
        $validator = Validator::make($request->all(), [
            //On applique les restrictions pour chaque input
            'email' => 'required|string|email|max:100|unique:users,email',
            //REGEX pour le password (minimum 8 caractères et comportant une lettre, un chiffre et un symbole)
            'password' => [
                'string',
                'required',
                'min:8',
                'regex:^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$^'
            ],
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'username' => 'required|string|max:50',
            'address1' => 'required|string|max:100',
            'address2' => 'nullable|string|max:50',
            'phone' => 'required|string|max:20',
            'zipcode' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'country' => 'nullable|string|max:100'
        ]);

        //Vérification de la validation - FAIL
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 422);
        } else {
            $user = [
                'is_admin' => false, // set is_admin to false for regular users
                'email' => $request->input('email'),
                //Password Hashing
                'password' => bcrypt($request->input('password')),
                'firstname' => $request->input('firstname'),
                'lastname' => $request->input('lastname'),
                'username' => $request->input('username'),
                'address1' => $request->input('address1'),
                'address2' => $request->input('address2'),
                'phone' => $request->input('phone'),
                'zipcode' => $request->input('zipcode'),
                'city' => $request->input('city'),
                'country' => $request->input('country'),
            ];

            $topSecret = env('JWT_SECRET');
            $encode = JWT::encode($token, new Key($topSecret, 'HS256'));
            $token = $request->bearerToken();

            // try {
            //     $decoded = JWT::decode($token, new Key($topSecret, 'HS256'));
            //     $role = $decoded->role;

            //     // Do something based on the role value
            //     if ($role === 'admin') {
            //         // Handle admin role
            //     } else {
            //         // Handle other roles
            //     }
            // } catch (ExpiredException $e) {
            //     return response()->json(['error' => 'Token has expired'], 401);
            // } catch (Exception $e) {
            //     return response()->json(['error' => 'Invalid token'], 401);
            // }
            User::create($user);
            //on renvoie un code 200 et un message de confirmation de création.
            return response()->json([
                'success' => true,
                'message' => 'Votre profil a bien été créé',
                'token' => $token // Include the token in the response
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
