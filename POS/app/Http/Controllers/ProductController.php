<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $categoryProducts = [
        'food-beverage' => ['Biscuit', 'Ice Cream', 'Soft Drink'],
        'beauty-health' => ['Shampoo', 'Soap', 'Toothpaste'],
        'home-care' => ['Detergent', 'Floor Cleaner', 'Tissue'],
        'baby-kid' => ['Diapers', 'Baby Food', 'Toys']
    ];

    public function showProducts($category)
    {
        return view('products.index', [
            'category' => $category,
            'products' => $this->categoryProducts[$category] ?? []
        ]);
    }
}