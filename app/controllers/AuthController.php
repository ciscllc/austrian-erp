<?php

namespace App\Controllers;

class AuthController extends BaseController
{
    public function showLogin()
    {
        $this->requireAuthForPage('login');
        return $this->render('auth/login');
    }
    
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // Simple Login für Demo
            if ($email === 'admin@example.com' && $password === 'admin') {
                $_SESSION['user'] = [
                    'id' => 1,
                    'email' => $email,
                    'name' => 'Administrator'
                ];
                return $this->redirect('/dashboard');
            }
            
            $this->flash('error', 'Ungültige Anmeldedaten');
            return $this->redirect('/login');
        }
        
        return $this->redirect('/login');
    }
    
    public function logout()
    {
        session_destroy();
        return $this->redirect('/login');
    }
}