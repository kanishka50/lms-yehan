<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DigitalProduct;
use App\Models\UserDigitalProductAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class DigitalProductController extends Controller
{
    /**
     * Display a listing of the user's purchased digital products.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get digital products user has access to
        $digitalProducts = User::find(Auth::id())->digitalProducts()
            ->withPivot('granted_at', 'order_id')
            ->get();
        
        return view('user.digital-products.index', compact('digitalProducts'));
    }
    
    /**
     * Display the specified digital product details.
     */
    public function show(DigitalProduct $digitalProduct)
    {
        $user = Auth::user();
        
        // Check if user has access to this product
        if (!User::find(Auth::id())->hasAccessToDigitalProduct($digitalProduct)) {
            abort(403, 'You do not have access to this digital product.');
        }
        
        // Get the user's access record
        $access = UserDigitalProductAccess::where('user_id', $user->id)
            ->where('digital_product_id', $digitalProduct->id)
            ->with('order')
            ->first();
        
        return view('user.digital-products.show', compact('digitalProduct', 'access'));
    }

    /**
     * View PDF in browser (protected route).
     */
    public function viewPdf(DigitalProduct $digitalProduct)
    {
        $user = Auth::user();
        
        // Check if user has access to this product
        if (!User::find(Auth::id())->hasAccessToDigitalProduct($digitalProduct)) {
            abort(403, 'You do not have access to this PDF.');
        }

        // Check if PDF file exists
        if (!$digitalProduct->pdf_file_path || !Storage::disk('private')->exists($digitalProduct->pdf_file_path)) {
            abort(404, 'PDF file not found.');
        }

        // Stream the PDF to browser
        return response()->file(
            Storage::disk('private')->path($digitalProduct->pdf_file_path),
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $digitalProduct->name . '.pdf"',
            ]
        );
    }
}