<?php

namespace Tests\Unit;

use App\Models\Product;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function testProductCreation()
    {
        $product = Product::create([
            'name' => 'Test Product',
            'base_price' => 100.00,
            'tax_rate' => 20.00,
            'unit_id' => 1
        ]);
        
        $this->assertNotNull($product->id);
        $this->assertEquals('Test Product', $product->name);
    }
    
    public function testSubProductCalculation()
    {
        $product = Product::find(1);
        $subProductTotal = Product::calculateSubProductTotal($product->id);
        
        $this->assertIsFloat($subProductTotal);
        $this->assertGreaterThanOrEqual(0, $subProductTotal);
    }
    
    public function testProductWithSubProducts()
    {
        $product = Product::create([
            'name' => 'Product with Sub-products',
            'base_price' => 0,
            'tax_rate' => 20.00,
            'unit_id' => 1
        ]);
        
        // Add sub-products
        $subProduct1 = SubProduct::create(['name' => 'Sub 1', 'cost_price' => 10.00]);
        $subProduct2 = SubProduct::create(['name' => 'Sub 2', 'cost_price' => 15.00]);
        
        $product->addSubProduct($subProduct1->id, 2);
        $product->addSubProduct($subProduct2->id, 1);
        
        $total = Product::calculateSubProductTotal($product->id);
        $this->assertEquals(35.00, $total); // (10 * 2) + (15 * 1)
    }
}