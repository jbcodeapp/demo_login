<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserController extends Controller
{

    public function index(): Response
    {
        $users = User::all();
        return view('dashboard', compact('users'));
        
    }
    public function store(Request $request): Response
    {
        //
    }
    public function show(User $user): Response
    {
        //
    }
    public function update(Request $request, User $user): Response
    {
        //
    }
    public function destroy( User $user): Response
    {
        //
    }

}
