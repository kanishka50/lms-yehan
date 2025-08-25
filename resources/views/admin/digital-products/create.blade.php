@extends('layouts.admin')

@section('title', 'Create Digital Product')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-100">Create Digital Product</h1>
        <a href="{{ route('admin.digital-products.index') }}" class="bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
            Back to Products
        </a>
    </div>

    <div class="bg-card rounded-lg shadow-md p-6 border border-gray-800">
        <form action="{{ route('admin.digital-products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Product Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                           class="w-full rounded-md bg-darker border-gray-700 text-gray-100 shadow-sm focus:border-accent-teal focus:ring-accent-teal"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4" 
                              class="w-full rounded-md bg-darker border-gray-700 text-gray-100 shadow-sm focus:border-accent-teal focus:ring-accent-teal">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-300 mb-2">Price (Rs.)</label>
                    <input type="number" name="price" id="price" value="{{ old('price') }}" 
                           step="0.01" min="0"
                           class="w-full rounded-md bg-darker border-gray-700 text-gray-100 shadow-sm focus:border-accent-teal focus:ring-accent-teal"
                           required>
                    @error('price')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- PDF File -->
                <div>
                    <label for="pdf_file" class="block text-sm font-medium text-gray-300 mb-2">PDF File</label>
                    <input type="file" name="pdf_file" id="pdf_file" accept=".pdf"
                           class="w-full rounded-md bg-darker border-gray-700 text-gray-100 shadow-sm focus:border-accent-teal focus:ring-accent-teal file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-accent-teal file:text-white hover:file:bg-opacity-80"
                           required>
                    <p class="mt-1 text-sm text-gray-400">Maximum file size: 50MB. Only PDF files are accepted.</p>
                    @error('pdf_file')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Featured -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                               class="rounded border-gray-700 bg-darker text-accent-teal shadow-sm focus:border-accent-teal focus:ring-accent-teal">
                        <span class="ml-2 text-sm text-gray-300">Featured Product</span>
                    </label>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.digital-products.index') }}" 
                   class="bg-gray-700 text-gray-300 px-4 py-2 rounded-md hover:bg-gray-600 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-accent-teal text-white px-4 py-2 rounded-md hover:bg-opacity-80 transition">
                    Create Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection