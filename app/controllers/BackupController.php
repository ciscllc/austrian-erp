<?php

namespace App\Controllers;

use App\Helpers\BackupHelper;
use App\Models\Settings;

class BackupController extends BaseController
{
    public function index()
    {
        $this->requirePermission('manage_settings');
        
        $backups = BackupHelper::getAll();
        $settings = Settings::getBackupSettings();
        
        return $this->render('admin/backups', [
            'backups' => $backups,
            'settings' => $settings,
            'title' => 'Backups'
        ]);
    }
    
    public function create()
    {
        $this->requirePermission('manage_settings');
        
        try {
            $backup = BackupHelper::create();
            $this->flash('success', 'Backup erfolgreich erstellt');
        } catch (\Exception $e) {
            $this->flash('error', 'Backup fehlgeschlagen: ' . $e->getMessage());
        }
        
        return $this->redirect('/admin/backups');
    }
    
    public function restore(int $id)
    {
        $this->requirePermission('manage_settings');
        
        try {
            BackupHelper::restore($id);
            $this->flash('success', 'Backup erfolgreich wiederhergestellt');
        } catch (\Exception $e) {
            $this->flash('error', 'Wiederherstellung fehlgeschlagen: ' . $e->getMessage());
        }
        
        return $this->redirect('/admin/backups');
    }
}