<?php

namespace App\Controllers;

use App\Models\Users;
use App\Core\Input;
use App\Core\Controller;

/**
 * LoginController
 */
class LoginController extends Controller
{
    public function index()
    {
        auth()->handle(true);
        return view()->setTitle('Login')->show('login');
    }

    public function login($request)
    {
        $validated = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validated === true) {
            $email = $request->post('email');
            $password = md5($request->post('password'));

            $user = app(Users::class)->runQuery(
                "SELECT * FROM users WHERE email = '{$email}' AND password = '{$password}'",
                true // to get single item
            );
            if ($user && $user->has()) {
                auth()->login($user->id);
                return response()->redirect('/');
            }
            $this->addFlash('login-error', "Invalid email or password!");
        }

        return $this->back('/login');
    }

    public function logout()
    {
        auth()->logout();
        return response()->redirect('/login');
    }
}
