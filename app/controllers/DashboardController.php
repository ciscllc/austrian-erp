<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index()
    {
        $this->requireAuth();
        
        return $this->render('dashboard/index', [
            'title' => 'Dashboard',
            'user' => $_SESSION['user'] ?? null
        ]);
    }
}