<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function agents()
    {
        // Get users that have the 'agent' role
        $agents = \App\Models\User::whereHas('roles', function ($q) {
            $q->where('name', 'agent');
        })->get(['id', 'first_name', 'middle_name', 'last_name', 'email']);

        // Optional: format full name
        $agents->transform(function ($user) {
            $user->full_name = trim("{$user->first_name} {$user->middle_name} {$user->last_name}");
            return $user;
        });

        return response()->json(['data' => $agents]);
    }
}
