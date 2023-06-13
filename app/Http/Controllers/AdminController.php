<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;
use Firebase\JWT\ExpiredException;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
        $topSecret = env('JWT_SECRET');
        $token = $request->bearerToken();
        try {
            $decoded = JWT::decode($token, new Key($topSecret, 'HS256'));
            $role = $decoded->role;
        } catch (ExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], 401);
        } catch (Exception $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        //  if ($role === 'admin') { 

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * 
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
