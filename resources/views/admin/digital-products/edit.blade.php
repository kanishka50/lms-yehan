@extends('layouts.admin')

@section('title', 'Edit Digital Product')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-100">Edit Digital Product</h1>
        <a href="{{ route('admin.digital-products.index') }}" class="bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
            Back to Products
        </a>
    </div>

    <div class="bg-card rounded-lg shadow-md p-6 border border-gray-800">
        <form action="{{ route('admin.digital-products.update', $digitalProduct) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Product Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $digitalProduct->name) }}" 
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
                              class="w-full rounded-md bg-darker border-gray-700 text-gray-100 shadow-sm focus:border-accent-teal focus:ring-accent-teal">{{ old('description', $digitalProduct->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-300 mb-2">Price (Rs.)</label>
                    <input type="number" name="price" id="price" value="{{ old('price', $digitalProduct->price) }}" 
                           step="0.01" min="0"
                           class="w-full rounded-md bg-darker border-gray-700 text-gray-100 shadow-sm focus:border-accent-teal focus:ring-accent-teal"
                           required>
                    @error('price')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current PDF Info -->
                @if($digitalProduct->pdf_file_path)
                <div class="bg-darker p-4 rounded-md border border-gray-700">
                    <p class="text-sm font-medium text-gray-300 mb-2">Current PDF File</p>
                    <p class="text-sm text-gray-400">File size: {{ $digitalProduct->formatted_file_size }}</p>
                    @if($digitalProduct->page_count)
                        <p class="text-sm text-gray-400">Pages: {{ $digitalProduct->page_count }}</p>
                    @endif
                </div>
                @endif

                <!-- PDF File -->
                <div>
                    <label for="pdf_file" class="block text-sm font-medium text-gray-300 mb-2">
                        Replace PDF File (Optional)
                    </label>
                    <input type="file" name="pdf_file" id="pdf_file" accept=".pdf"
                           class="w-full rounded-md bg-darker border-gray-700 text-gray-100 shadow-sm focus:border-accent-teal focus:ring-accent-teal file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-accent-teal file:text-white hover:file:bg-opacity-80">
                    <p class="mt-1 text-sm text-gray-400">Leave empty to keep current file. Maximum file size: 50MB.</p>
                    @error('pdf_file')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Featured -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" 
                               {{ old('is_featured', $digitalProduct->is_featured) ? 'checked' : '' }}
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
                    Update Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection