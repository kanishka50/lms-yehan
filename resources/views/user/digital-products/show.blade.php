@extends('layouts.user')

@section('title', $digitalProduct->name)

@section('content')
<div class="md:ml-64 content-transition min-h-screen bg-dark">
    <div class="container px-6 py-8 mx-auto">
        <!-- Breadcrumb -->
        <div class="mb-6 flex items-center text-sm text-gray-400">
            <a href="{{ route('user.dashboard') }}" class="hover:text-white transition-colors">Dashboard</a>
            <span class="mx-2">/</span>
            <a href="{{ route('user.digital-products.index') }}" class="hover:text-white transition-colors">My Digital Products</a>
            <span class="mx-2">/</span>
            <span class="text-white">{{ $digitalProduct->name }}</span>
        </div>

        <!-- Product Details Card -->
        <div class="glass-effect rounded-lg border border-gray-800 overflow-hidden">
            <div class="p-8">
                <div class="flex items-start justify-between mb-6">
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-white mb-2">{{ $digitalProduct->name }}</h1>
                        <p class="text-gray-400">{{ $digitalProduct->description }}</p>
                    </div>
                    <div class="ml-6 flex-shrink-0">
                        <div class="w-20 h-20 bg-gradient-to-br from-accent-teal/20 to-secondary-400/20 rounded-lg flex items-center justify-center">
                            <svg class="h-10 w-10 text-accent-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Product Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-darker rounded-lg">
                            <span class="text-gray-400">Type:</span>
                            <span class="text-white font-medium">PDF Document</span>
                        </div>
                        @if($digitalProduct->file_size)
                        <div class="flex items-center justify-between p-3 bg-darker rounded-lg">
                            <span class="text-gray-400">File Size:</span>
                            <span class="text-white font-medium">{{ $digitalProduct->formatted_file_size }}</span>
                        </div>
                        @endif
                        @if($digitalProduct->page_count)
                        <div class="flex items-center justify-between p-3 bg-darker rounded-lg">
                            <span class="text-gray-400">Pages:</span>
                            <span class="text-white font-medium">{{ $digitalProduct->page_count }}</span>
                        </div>
                        @endif
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-darker rounded-lg">
                            <span class="text-gray-400">Price:</span>
                            <span class="text-accent-teal font-medium">Rs. {{ number_format($digitalProduct->price, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-darker rounded-lg">
                            <span class="text-gray-400">Purchased On:</span>
                            <span class="text-white font-medium">{{ \Carbon\Carbon::parse($access->granted_at)->format('M d, Y') }}</span>
                        </div>
                        @if($access->order)
                        <div class="flex items-center justify-between p-3 bg-darker rounded-lg">
                            <span class="text-gray-400">Order #:</span>
                            <span class="text-white font-medium">{{ $access->order->order_number }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Action Button -->
                <div class="flex justify-center">
                    <a href="{{ route('user.digital-products.view-pdf', $digitalProduct) }}" 
                       target="_blank"
                       class="inline-flex items-center px-8 py-3 bg-primary-500 text-white font-bold rounded-lg hover:bg-primary-600 transition-all duration-200 glow-effect">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        View PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Access Information -->
        <div class="mt-6 glass-effect rounded-lg border border-gray-800 p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                <svg class="h-5 w-5 mr-2 text-accent-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                Access Information
            </h3>
            
            <div class="bg-darker rounded-lg p-4">
                <p class="text-gray-300 mb-2">
                    <span class="text-green-400">✓</span> You have permanent access to this PDF
                </p>
                <p class="text-gray-300 mb-2">
                    <span class="text-green-400">✓</span> View online anytime from your account
                </p>
                <p class="text-gray-300">
                    <span class="text-green-400">✓</span> Your purchase is securely recorded
                </p>
            </div>
            
            <p class="mt-4 text-sm text-gray-400">
                Note: This PDF is for your personal use only. Please do not share or distribute it as it may violate our terms of service.
            </p>
        </div>
    </div>
</div>
@endsection