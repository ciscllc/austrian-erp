<?php

namespace App\Controllers;

use App\Models\Customer;

class CustomerController extends BaseController
{
    public function index()
    {
        $customers = Customer::getAll();
        $this->render('customers/index', ['customers' => $customers]);
    }
    
    public function create()
    {
        $this->render('customers/create');
    }
    
    public function store()
    {
        $data = [
            'customer_number' => $_POST['customer_number'],
            'company_name' => $_POST['company_name'] ?? '',
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'email' => $_POST['email'],
            'phone' => $_POST['phone'] ?? '',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        Customer::create($data);
        $this->redirect('/customers');
    }
}