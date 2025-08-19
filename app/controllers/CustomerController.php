<?php

namespace App\Controllers;

use App\Models\Customer;
use App\Helpers\DatabaseDebugger;

class CustomerController extends BaseController
{
    public function index()
    {
        $this->requireAuth();
        
        // Debug: Check if table exists
        try {
            $customers = Customer::getAll();
            DatabaseDebugger::log("Customers loaded", ['count' => count($customers)]);
            
            return $this->render('customers/index', [
                'title' => 'Kunden',
                'customers' => $customers
            ]);
            
        } catch (\Exception $e) {
            DatabaseDebugger::log("Error loading customers", ['error' => $e->getMessage()]);
            
            // Create demo data on first load
            Customer::createDemoData();
            $customers = Customer::getAll();
            
            return $this->render('customers/index', [
                'title' => 'Kunden',
                'customers' => $customers,
                'message' => 'Demo-Daten wurden erstellt'
            ]);
        }
    }
    
    public function store()
    {
        $this->requireAuth();
        
        DatabaseDebugger::log("Creating customer", ['POST' => $_POST]);
        
        $data = [
            'customer_number' => $_POST['customer_number'],
            'company_name' => $_POST['company_name'] ?? '',
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'email' => $_POST['email'],
            'phone' => $_POST['phone'] ?? '',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        try {
            Customer::create($data);
            DatabaseDebugger::log("Customer created", ['data' => $data]);
            return $this->redirect('/customers');
            
        } catch (\Exception $e) {
            DatabaseDebugger::log("Error creating customer", ['error' => $e->getMessage()]);
            $this->flash('error', 'Fehler beim Speichern: ' . $e->getMessage());
            return $this->redirect('/customers/create');
        }
    }
}