<?php

namespace App\Controllers;

class SettingsController extends BaseController
{
    public function index()
    {
        return $this->render('settings/index', [
            'title' => 'Einstellungen'
        ]);
    }
    
    public function update()
    {
        return $this->redirect('/settings');
    }
    
    public function updateBackupSettings()
    {
        return $this->redirect('/admin/backups');
    }
}