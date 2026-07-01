<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        // If the user is already logged in, redirect to the dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function attemptLogin()
    {
        // Validate the input
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        // Check if username and password are provided
        if (empty($username) || empty($password)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Username dan password wajib diisi.');
        }
        // Check if the user exists and verify the password
        $userModel = new UserModel();
        $user = $userModel->where('username', $username)->first();
        // If the user is not found, redirect back with an error message
        if (!$user) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Username tidak ditemukan.');
        }
        // Verify the password using password_verify
        if (!password_verify($password, $user['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Password salah.');
        }
        // Store important user information in session after successful login.
        // This session data will be used to protect dashboard, product, and sales pages.
        session()->set([
            'user_id'    => $user['id'],
            'name'       => $user['name'],
            'username'   => $user['username'],
            'role'       => $user['role'],
            'isLoggedIn' => true,
        ]);

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')
            ->with('success', 'Anda berhasil logout.');
    }

}
