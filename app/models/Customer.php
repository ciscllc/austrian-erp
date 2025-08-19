<?php

namespace App\Models;

use App\Config\Database;
use App\Helpers\DatabaseDebugger;

class Customer
{
    public static function getAll()
    {
        $db = Database::getInstance()->getConnection();
        
        // Debug: Check table existence
        try {
            $stmt = $db->query("SELECT COUNT(*) FROM customers");
            $count = $stmt->fetchColumn();
            DatabaseDebugger::log("Customer table exists", ['count' => $count]);
        } catch (\Exception $e) {
            DatabaseDebugger::log("Customer table missing", ['error' => $e->getMessage()]);
            return [];
        }
        
        $stmt = $db->query("SELECT * FROM customers ORDER BY created_at DESC");
        $customers = $stmt->fetchAll();
        DatabaseDebugger::log("Customers fetched", ['count' => count($customers)]);
        
        return $customers;
    }
    
    public static function create(array $data)
    {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("
            INSERT INTO customers (customer_number, company_name, first_name, last_name, email, phone, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        $result = $stmt->execute([
            $data['customer_number'],
            $data['company_name'],
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['phone'],
            $data['created_at']
        ]);
        
        DatabaseDebugger::log("Customer inserted", ['id' => $db->lastInsertId()]);
        return $db->lastInsertId();
    }
    
    public static function createDemoData()
    {
        $demoData = [
            [
                'customer_number' => 'DEMO-001',
                'company_name' => 'Muster GmbH',
                'first_name' => 'Max',
                'last_name' => 'Muster',
                'email' => 'max@muster.at',
                'phone' => '+43 123 456789'
            ],
            [
                'customer_number' => 'DEMO-002',
                'company_name' => 'Test AG',
                'first_name' => 'Anna',
                'last_name' => 'Schmidt',
                'email' => 'anna@test.at',
                'phone' => '+43 987 654321'
            ]
        ];
        
        foreach ($demoData as $customer) {
            $customer['created_at'] = date('Y-m-d H:i:s');
            self::create($customer);
        }
        
        DatabaseDebugger::log("Demo data created", ['count' => count($demoData)]);
    }
}