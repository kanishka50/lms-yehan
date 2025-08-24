<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalProduct;
use App\Http\Requests\Admin\DigitalProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DigitalProductController extends Controller
{
    /**
     * Display a listing of the digital products.
     */
    public function index()
    {
        $digitalProducts = DigitalProduct::latest()->paginate(10);
        return view('admin.digital-products.index', compact('digitalProducts'));
    }

    /**
     * Show the form for creating a new digital product.
     */
    public function create()
    {
        return view('admin.digital-products.create');
    }

    /**
     * Store a newly created digital product in storage.
     */
    public function store(DigitalProductRequest $request)
    {
        $data = $request->validated();
        
        // Handle PDF file upload
        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('pdfs', $fileName, 'private');
            
            $data['pdf_file_path'] = $filePath;
            $data['file_size'] = $file->getSize();
            $data['type'] = 'pdf';
            
            // You can add logic to count PDF pages here if needed
            // $data['page_count'] = $this->countPdfPages($file);
        }
        
        DigitalProduct::create($data);
        
        return redirect()->route('admin.digital-products.index')
            ->with('success', 'Digital product created successfully!');
    }

    /**
     * Display the specified digital product.
     */
    public function show(DigitalProduct $digitalProduct)
    {
        $users = $digitalProduct->users()->withPivot('granted_at')->get();
        return view('admin.digital-products.show', compact('digitalProduct', 'users'));
    }

    /**
     * Show the form for editing the specified digital product.
     */
    public function edit(DigitalProduct $digitalProduct)
    {
        return view('admin.digital-products.edit', compact('digitalProduct'));
    }

    /**
     * Update the specified digital product in storage.
     */
    public function update(DigitalProductRequest $request, DigitalProduct $digitalProduct)
    {
        $data = $request->validated();
        
        // Handle PDF file upload
        if ($request->hasFile('pdf_file')) {
            // Delete old file if exists
            if ($digitalProduct->pdf_file_path) {
                Storage::disk('private')->delete($digitalProduct->pdf_file_path);
            }
            
            $file = $request->file('pdf_file');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('pdfs', $fileName, 'private');
            
            $data['pdf_file_path'] = $filePath;
            $data['file_size'] = $file->getSize();
            $data['type'] = 'pdf';
        }
        
        $digitalProduct->update($data);
        
        return redirect()->route('admin.digital-products.index')
            ->with('success', 'Digital product updated successfully!');
    }

    /**
     * Remove the specified digital product from storage.
     */
    public function destroy(DigitalProduct $digitalProduct)
    {
        // Check if product has been purchased
        $hasPurchases = $digitalProduct->userAccess()->exists();
        if ($hasPurchases) {
            return redirect()->route('admin.digital-products.index')
                ->with('error', 'Cannot delete digital product with active purchases!');
        }
        
        // Delete PDF file if exists
        if ($digitalProduct->pdf_file_path) {
            Storage::disk('private')->delete($digitalProduct->pdf_file_path);
        }
        
        $digitalProduct->delete();
        
        return redirect()->route('admin.digital-products.index')
            ->with('success', 'Digital product deleted successfully!');
    }
}