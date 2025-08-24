@extends('layouts.admin')

@section('title', 'Edit Digital Product')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Edit Digital Product</h1>
        <a href="{{ route('admin.digital-products.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
            Back to Products
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.digital-products.update', $digitalProduct) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Product Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $digitalProduct->name) }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4" 
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ old('description', $digitalProduct->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (Rs.)</label>
                    <input type="number" name="price" id="price" value="{{ old('price', $digitalProduct->price) }}" 
                           step="0.01" min="0"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                           required>
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current PDF Info -->
                @if($digitalProduct->pdf_file_path)
                <div class="bg-gray-50 p-4 rounded-md">
                    <p class="text-sm font-medium text-gray-700 mb-2">Current PDF File</p>
                    <p class="text-sm text-gray-600">File size: {{ $digitalProduct->formatted_file_size }}</p>
                    @if($digitalProduct->page_count)
                        <p class="text-sm text-gray-600">Pages: {{ $digitalProduct->page_count }}</p>
                    @endif
                </div>
                @endif

                <!-- PDF File -->
                <div>
                    <label for="pdf_file" class="block text-sm font-medium text-gray-700 mb-2">
                        Replace PDF File (Optional)
                    </label>
                    <input type="file" name="pdf_file" id="pdf_file" accept=".pdf"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <p class="mt-1 text-sm text-gray-500">Leave empty to keep current file. Maximum file size: 50MB.</p>
                    @error('pdf_file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Featured -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" 
                               {{ old('is_featured', $digitalProduct->is_featured) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        <span class="ml-2 text-sm text-gray-700">Featured Product</span>
                    </label>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.digital-products.index') }}" 
                   class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 transition">
                    Update Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection