<?php 
namespace App\Controllers;

use App\Models\Users;
use App\Core\Controller;

/**
 * UserController
 */
class UserController extends Controller
{
    function __construct()
    {
        parent::__construct();
        auth()->handle();
    }
    
    public function index($request)
    {
        $users = (new Users())->getAll();
        return view()->setTitle('Users')->show('users.list', compact('users'));
    }

    public function store($request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required',
        ]);
        if( $validated === true ){
            $student = app(Users::class)->insert([
                'name' => $request->post('name'),
                'email' => $request->post('email'),
                'password' => md5($request->post('password')),
                'role' => $request->post('role', 'user'),
            ]);
            $this->addFlash('success', "User added successfully!");
        }else {
            $this->addFlash('error', "Something went wrong!");
        }
        
        return $this->back('/users');
    }

    public function delete($request, $id)
    {
        $delete = app(Users::class)->delete($id);
        if($delete){
            $this->addFlash('success', "User deleted successfully!");
        }else {
            $this->addFlash('error', "Failed to delete the student!");
        }
        return $this->back('/users');
    }

}