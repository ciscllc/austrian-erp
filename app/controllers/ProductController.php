<?php

namespace App\Controllers;

class ProductController extends BaseController
{
    public function index()
    {
        return $this->render('products/index', [
            'title' => 'Produkte',
            'products' => []
        ]);
    }
    
    public function create()
    {
        return $this->render('products/create', [
            'title' => 'Neues Produkt'
        ]);
    }
    
    public function store()
    {
        return $this->redirect('/products');
    }
    
    public function show(int $id)
    {
        return $this->render('products/show', [
            'title' => "Produkt #$id",
            'product_id' => $id
        ]);
    }
    
    public function edit(int $id)
    {
        return $this->render('products/edit', [
            'title' => "Produkt #$id bearbeiten",
            'product_id' => $id
        ]);
    }
    
    public function update(int $id)
    {
        return $this->redirect('/products/' . $id);
    }
    
    public function destroy(int $id)
    {
        return $this->redirect('/products');
    }
}