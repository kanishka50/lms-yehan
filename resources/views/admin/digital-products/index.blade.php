@extends('layouts.admin')

@section('title', 'Digital Products')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-100">Digital Products</h1>
        <a href="{{ route('admin.digital-products.create') }}" class="bg-accent-teal text-white px-4 py-2 rounded-md hover:bg-opacity-80 transition">
            <i class="fas fa-plus mr-2"></i>Add New Product
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-900/20 border-l-4 border-green-500 text-green-400 p-4 mb-6 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-900/20 border-l-4 border-red-500 text-red-400 p-4 mb-6 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-card rounded-lg shadow-md overflow-hidden border border-gray-800">
        <table class="min-w-full divide-y divide-gray-800">
            <thead class="bg-darker">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                        Product Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                        Type
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                        Price
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                        File Size
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                        Featured
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-card divide-y divide-gray-800">
                @forelse($digitalProducts as $product)
                <tr class="hover:bg-darker/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-darker rounded-full flex items-center justify-center">
                                <svg class="h-6 w-6 text-accent-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-100">
                                    {{ $product->name }}
                                </div>
                                <div class="text-sm text-gray-400">
                                    {{ Str::limit($product->description, 50) }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-900/50 text-blue-400 border border-blue-800">
                            PDF
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-100">
                        Rs. {{ number_format($product->price, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                        {{ $product->formatted_file_size ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($product->is_featured)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-900/50 text-green-400 border border-green-800">
                                Yes
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-800 text-gray-400">
                                No
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.digital-products.show', $product) }}" class="text-blue-400 hover:text-blue-300 mr-3">
                            View
                        </a>
                        <a href="{{ route('admin.digital-products.edit', $product) }}" class="text-indigo-400 hover:text-indigo-300 mr-3">
                            Edit
                        </a>
                        <form action="{{ route('admin.digital-products.destroy', $product) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300" onclick="return confirm('Are you sure you want to delete this product?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-400">
                        No digital products found. <a href="{{ route('admin.digital-products.create') }}" class="text-accent-teal hover:text-accent-light">Create one now</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($digitalProducts->hasPages())
        <div class="mt-6">
            {{ $digitalProducts->links() }}
        </div>
    @endif
</div>
@endsection