<?php

namespace App\Models;

use App\Config\Database;

class Product
{
    protected $table = 'products';
    
    public static function getAllWithSubProducts()
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "
            SELECT p.*, 
                   GROUP_CONCAT(
                       JSON_OBJECT(
                           'id', sp.id,
                           'name', sp.name,
                           'cost_price', COALESCE(psp.price_override, sp.cost_price),
                           'quantity', psp.quantity,
                           'hazard_symbols', sp.hazard_symbols
                       )
                   ) as sub_products,
                   u.symbol as unit_symbol,
                   m.name as manufacturer_name
            FROM products p
            LEFT JOIN product_sub_products psp ON p.id = psp.product_id
            LEFT JOIN sub_products sp ON psp.sub_product_id = sp.id
            LEFT JOIN units u ON p.unit_id = u.id
            LEFT JOIN manufacturers m ON p.manufacturer_id = m.id
            WHERE p.is_active = 1
            GROUP BY p.id
        ";
        
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    }

    public static function calculateSubProductTotal(int $productId): float
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "
            SELECT SUM(COALESCE(psp.price_override, sp.cost_price) * psp.quantity) as total
            FROM product_sub_products psp
            JOIN sub_products sp ON psp.sub_product_id = sp.id
            WHERE psp.product_id = ?
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$productId]);
        $result = $stmt->fetch();
        
        return (float)($result['total'] ?? 0);
    }

    public static function getSubProducts(int $productId): array
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "
            SELECT sp.*, psp.quantity, psp.price_override
            FROM sub_products sp
            JOIN product_sub_products psp ON sp.id = psp.sub_product_id
            WHERE psp.product_id = ?
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }
}