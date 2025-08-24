@extends('layouts.user')

@section('title', 'My Digital Products')

@section('content')
<div class="md:ml-64 content-transition min-h-screen bg-dark">
    <div class="container px-6 py-8 mx-auto">
        <h1 class="text-2xl font-bold text-white mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2 text-accent-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            My Digital Products
        </h1>

        @if($digitalProducts->isEmpty())
            <div class="glass-effect rounded-lg p-10 text-center border border-gray-800">
                <svg class="h-16 w-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-white mb-2">No Digital Products Yet</h3>
                <p class="text-gray-400 mb-6">You haven't purchased any digital products yet.</p>
                <a href="{{ route('digital-products.index') }}" class="inline-flex items-center px-4 py-2 bg-accent-teal text-white rounded-md hover:bg-opacity-80 transition-all duration-300">
                    Browse Digital Products
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        @else
            <div class="grid gap-6 lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1">
                @foreach($digitalProducts as $product)
                    <div class="glass-effect rounded-lg overflow-hidden shadow-lg border border-gray-800 hover:shadow-accent-teal/10 transition-all duration-300 group">
                        <div class="h-48 bg-darker flex items-center justify-center">
                            <svg class="h-16 w-16 text-accent-teal/50 group-hover:text-accent-teal transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        
                        <div class="p-5">
                            <h3 class="text-lg font-semibold text-white group-hover:text-accent-teal transition-colors duration-300">
                                {{ $product->name }}
                            </h3>
                            
                            <p class="mt-2 text-sm text-gray-400 line-clamp-2">
                                {{ $product->description }}
                            </p>
                            
                            <div class="mt-3 space-y-1">
                                <p class="text-sm text-gray-400">
                                    <span class="font-medium text-gray-300">Type:</span> PDF Document
                                </p>
                                @if($product->file_size)
                                <p class="text-sm text-gray-400">
                                    <span class="font-medium text-gray-300">Size:</span> {{ $product->formatted_file_size }}
                                </p>
                                @endif
                                <p class="text-sm text-gray-400">
                                    <span class="font-medium text-gray-300">Purchased:</span> {{ \Carbon\Carbon::parse($product->pivot->granted_at)->format('M d, Y') }}
                                </p>
                            </div>
                            
                            <div class="mt-4">
                                <a href="{{ route('user.digital-products.show', $product->id) }}" 
                                   class="inline-flex items-center justify-center w-full px-3 py-2 bg-card border border-gray-700 rounded-md text-white text-sm hover:bg-accent-teal hover:border-accent-teal hover:text-white transition-all duration-200">
                                    View Product
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection