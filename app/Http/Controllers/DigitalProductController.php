<?php

namespace App\Http\Controllers;

use App\Models\DigitalProduct;
use Illuminate\Http\Request;

class DigitalProductController extends Controller
{
    /**
     * Display a listing of the digital products.
     */
    public function index()
    {
        // Remove inventory_count check - PDFs don't have inventory
        $digitalProducts = DigitalProduct::where('is_featured', true)
            ->orWhere('is_featured', false) // Get all products
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('digital-products', compact('digitalProducts'));
    }
    
    /**
     * Display the specified digital product.
     */
    public function show(DigitalProduct $digitalProduct)
    {
        return view('digital-product-detail', compact('digitalProduct'));
    }
}