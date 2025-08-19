
{{-- resources/views/payment-receipt-upload.blade.php --}}
@extends('layouts.app')

@section('title', 'Upload Payment Receipt')

@section('content')
<div class="container px-4 py-8 mx-auto">
    <div class="max-w-4xl mx-auto">
        {{-- Order Details Card --}}
        <div class="bg-card rounded-lg shadow-md p-6 mb-6 border border-gray-800">
            <h1 class="text-2xl font-bold text-gray-100 mb-4">Payment Instructions</h1>
            
            {{-- Order Summary --}}
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-200 mb-3">Order Summary</h2>
                <div class="bg-darker rounded-md p-4 border border-gray-800">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-400">Order Number:</span>
                        <span class="font-medium text-gray-200">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-400">Order Date:</span>
                        <span class="text-gray-200">{{ $order->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Total Amount:</span>
                        <span class="text-xl font-bold text-primary-400">Rs. {{ number_format($order->final_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Bank Details --}}
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-200 mb-3">Bank Transfer Details</h2>
                <div class="bg-blue-900/20 border border-blue-500/30 rounded-md p-4">
                    <p class="text-blue-400 mb-3">Please transfer the amount to the following bank account:</p>
                    <div class="space-y-2">
                        <div class="flex">
                            <span class="text-gray-400 w-32">Bank Name:</span>
                            <span class="text-gray-200 font-medium">{{ env('BANK_NAME', 'Commercial Bank of Ceylon') }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-400 w-32">Account Name:</span>
                            <span class="text-gray-200 font-medium">{{ env('BANK_ACCOUNT_NAME', 'Cash Mind Pvt Ltd') }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-400 w-32">Account Number:</span>
                            <span class="text-gray-200 font-medium">{{ env('BANK_ACCOUNT_NUMBER', '1234567890') }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-400 w-32">Branch:</span>
                            <span class="text-gray-200 font-medium">{{ env('BANK_BRANCH', 'Colombo') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Important Notes --}}
            <div class="mb-6">
                <h3 class="text-md font-semibold text-yellow-400 mb-2">Important Notes:</h3>
                <ul class="list-disc list-inside text-gray-300 space-y-1 text-sm">
                    <li>Please use your Order Number ({{ $order->order_number }}) as the payment reference</li>
                    <li>Upload a clear image or PDF of your payment receipt</li>
                    <li>Payment verification usually takes 1-2 business hours during working days</li>
                    <li>You will receive an email once your payment is verified</li>
                </ul>
            </div>
        </div>

        {{-- Upload Form --}}
        <div class="bg-card rounded-lg shadow-md p-6 border border-gray-800">
            <h2 class="text-xl font-bold text-gray-100 mb-4">Upload Payment Receipt</h2>
            
            @if($order->payment_receipt)
                <div class="mb-4 p-4 bg-green-900/20 border border-green-500/30 rounded-md">
                    <p class="text-green-400">
                        <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Receipt uploaded on {{ $order->payment_receipt_uploaded_at->format('M d, Y h:i A') }}
                    </p>
                    @if($order->payment_status === 'failed')
                        <p class="text-red-400 mt-2">
                            <strong>Rejection Reason:</strong> {{ $order->admin_notes }}
                        </p>
                        <p class="text-gray-300 mt-1">Please upload a new receipt below.</p>
                    @else
                        <p class="text-gray-300 mt-1">Waiting for admin verification...</p>
                    @endif
                </div>
            @endif

            <form action="{{ route('payment.upload.store', $order) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-6">
                    <label for="payment_receipt" class="block text-sm font-medium text-gray-300 mb-2">
                        Payment Receipt <span class="text-red-400">*</span>
                    </label>
                    
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-700 border-dashed rounded-md hover:border-gray-600 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-500" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-400">
                                <label for="payment_receipt" class="relative cursor-pointer rounded-md font-medium text-primary-400 hover:text-primary-300">
                                    <span>Upload a file</span>
                                    <input id="payment_receipt" name="payment_receipt" type="file"  accept="image/*,.pdf" required>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, PDF up to 5MB</p>
                        </div>
                    </div>
                    
                    @error('payment_receipt')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 py-3 px-6 bg-primary-500 text-white font-medium rounded-md hover:bg-primary-600 transition-colors duration-200">
                        Upload Receipt
                    </button>
                    <a href="{{ route('user.orders.index') }}" class="py-3 px-6 bg-gray-700 text-gray-200 font-medium rounded-md hover:bg-gray-600 transition-colors duration-200">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // File upload preview
    document.getElementById('payment_receipt').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        if (fileName) {
            // Update the file name display
            document.getElementById('file-name').textContent = 'Selected: ' + fileName;
            document.getElementById('file-name').classList.add('text-green-400');
        }
    });

    // Optional: Add drag and drop functionality
    const dropZone = document.querySelector('.border-dashed');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight(e) {
        dropZone.classList.add('border-primary-500', 'bg-gray-800');
    }
    
    function unhighlight(e) {
        dropZone.classList.remove('border-primary-500', 'bg-gray-800');
    }
    
    dropZone.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            document.getElementById('payment_receipt').files = files;
            const event = new Event('change', { bubbles: true });
            document.getElementById('payment_receipt').dispatchEvent(event);
        }
    }

</script>
@endpush
@endsection