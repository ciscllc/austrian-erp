<?php

namespace App\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Helpers\PDFGenerator;
use App\Helpers\EmailHelper;

class ApiController extends BaseController
{
    public function getProducts()
    {
        $search = $_GET['search'] ?? '';
        $category = $_GET['category'] ?? null;
        
        $products = Product::search($search, $category);
        
        return $this->json($products);
    }
    
    public function getSignature(int $orderId)
    {
        $order = Order::find($orderId);
        if (!$order) {
            return $this->json(['error' => 'Order not found'], 404);
        }
        
        return $this->json([
            'has_signature' => !empty($order->signature_data),
            'signature_data' => $order->signature_data
        ]);
    }
    
    public function saveSignature(int $orderId)
    {
        $order = Order::find($orderId);
        if (!$order) {
            return $this->json(['error' => 'Order not found'], 404);
        }
        
        $signatureData = $_POST['signature'] ?? null;
        if (!$signatureData) {
            return $this->json(['error' => 'Signature data required'], 400);
        }
        
        $order->update(['signature_data' => $signatureData]);
        
        return $this->json(['success' => true]);
    }
    
    public function generatePDF(int $orderId, string $type)
    {
        $order = Order::getByIdWithRelations($orderId);
        if (!$order) {
            return $this->json(['error' => 'Order not found'], 404);
        }
        
        $pdf = PDFGenerator::generate($order, $type);
        
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $type . '_' . $order->order_number . '.pdf"');
        echo $pdf;
    }
    
    public function sendEmail(int $orderId)
    {
        $order = Order::getByIdWithRelations($orderId);
        if (!$order) {
            return $this->json(['error' => 'Order not found'], 404);
        }
        
        $type = $_POST['type'] ?? 'invoice';
        $email = $_POST['email'] ?? $order->customer->email;
        
        $pdf = PDFGenerator::generate($order, $type);
        
        $sent = EmailHelper::sendWithAttachment(
            $email,
            "Ihr $type #{$order->order_number}",
            "Sehr geehrte Damen und Herren,\n\nanbei finden Sie Ihr $type.\n\nMit freundlichen Grüßen",
            $pdf,
            $type . '_' . $order->order_number . '.pdf'
        );
        
        if ($sent) {
            $order->update(['sent_at' => date('Y-m-d H:i:s')]);
            return $this->json(['success' => true]);
        }
        
        return $this->json(['error' => 'Email could not be sent'], 500);
    }
}