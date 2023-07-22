<?php

namespace App\Controllers;

use App\Models\Books;
use App\Models\Users;
use App\Core\Controller;
use App\Models\Students;
use App\Models\Circulations;

/**
 * HomeController
 */
class HomeController extends Controller
{
    function __construct()
    {
        parent::__construct();
        auth()->handle();
    }

    public function index($request)
    {
        return view()->setTitle('Home')->show('home');
    }

    public function settings()
    {
        return view()->setTitle('Settings')->show('settings');
    }

    public function update($request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        if ($validated === true) {
            $user = $request->user();
            $payload = [
                'name' => $request->post('name', $user->name),
                'email' => $request->post('email', $user->email),
            ];
            app(Users::class)->update($user->id, $payload);
            $this->addFlash('success', 'Profile Updated Successfully!');
        }
        return $this->back('/settings');
    }

    public function update_password($request)
    {
        $validated = $request->validate([
            'old_password' => 'required',
            'password' => 'required',
        ]);

        if ($validated === true) {
            $user = $request->user();
            if ($user->password === md5($request->post('old_password'))) {
                app(Users::class)->update($user->id, [
                    'password' => md5($request->post('password'))
                ]);
                $this->addFlash('success', 'Password Updated Successfully!');
            } else {
                $this->addFlash('error', 'Old password did not matched!');
            }
        }
        return $this->back('/settings');
    }
}
