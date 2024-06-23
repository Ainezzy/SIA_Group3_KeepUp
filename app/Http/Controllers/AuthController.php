<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    /*The register function validates the incoming request data, creates a new user with hashed password, saves it to the database, and then redirects to index.html.*/
    public function register(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        // Create a new user record in the database
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password')); // Hash the password
        $user->save();

        // Optionally, you can log in the user here

        // Redirect to dashboard.html
        return redirect('/index.html');
    }
/*The login function validates the request data, attempts to authenticate the user with provided credentials, and redirects to index.html upon success, or throws a validation exception if authentication fails.*/
    public function login(Request $request)
    {
        // Validate the incoming request data
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            // Authentication was successful
            return redirect('/index.html');
        } else {
            // Authentication failed
            throw ValidationException::withMessages(['email' => ['Invalid credentials']]);
        }
    }
}
