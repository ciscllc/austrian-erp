<?php

namespace App\Controllers;

use App\Models\Order;

class OrderController extends BaseController
{
    public function index()
    {
        $this->requireAuth();
        
        $orders = Order::getAll();
        return $this->render('orders/index', [
            'title' => 'Aufträge',
            'orders' => $orders
        ]);
    }
    
    public function create()
    {
        $this->requireAuth();
        
        return $this->render('orders/create', [
            'title' => 'Neuer Auftrag'
        ]);
    }
    
    public function store()
    {
        $this->requireAuth();
        
        // Order creation logic
        return $this->redirect('/orders');
    }
    
    public function show(int $id)
    {
        $this->requireAuth();
        
        return $this->render('orders/show', [
            'title' => "Auftrag #$id",
            'order_id' => $id
        ]);
    }
}