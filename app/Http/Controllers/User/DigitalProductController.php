<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ProductKey;
use App\Models\DigitalProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DigitalProductController extends Controller
{
    /**
     * Display a listing of the user's purchased digital products.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get only purchased products (no subscription logic)
        $productKeys = User::find(Auth::id())->productKeys()
            ->with('digitalProduct')
            ->get();
        
        return view('user.digital-products.index', compact('productKeys'));
    }
    
    /**
     * Display the specified digital product details.
     */
    public function show(ProductKey $productKey)
    {
        $user = Auth::user();
        
        // Check if user owns this product key
        if ($productKey->used_by !== $user->id) {
            abort(403, 'You do not have access to this digital product.');
        }
        
        return view('user.digital-products.show', compact('productKey'));
    }
}