@extends('layouts.admin')

@section('title', 'View Digital Product')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Digital Product Details</h1>
        <div class="space-x-3">
            <a href="{{ route('admin.digital-products.edit', $digitalProduct) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                Edit Product
            </a>
            <a href="{{ route('admin.digital-products.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Product Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Product Information</h2>
            
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                    <dd class="text-base text-gray-900">{{ $digitalProduct->name }}</dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="text-base text-gray-900">{{ $digitalProduct->description ?: 'No description' }}</dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Price</dt>
                    <dd class="text-base text-gray-900">Rs. {{ number_format($digitalProduct->price, 2) }}</dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Type</dt>
                    <dd class="text-base text-gray-900">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            PDF
                        </span>
                    </dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Featured</dt>
                    <dd class="text-base text-gray-900">
                        @if($digitalProduct->is_featured)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Yes
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                No
                            </span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        <!-- PDF Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">PDF Information</h2>
            
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">File Status</dt>
                    <dd class="text-base text-gray-900">
                        @if($digitalProduct->pdf_file_path)
                            <span class="text-green-600">PDF Uploaded</span>
                        @else
                            <span class="text-red-600">No PDF Uploaded</span>
                        @endif
                    </dd>
                </div>
                
                @if($digitalProduct->file_size)
                <div>
                    <dt class="text-sm font-medium text-gray-500">File Size</dt>
                    <dd class="text-base text-gray-900">{{ $digitalProduct->formatted_file_size }}</dd>
                </div>
                @endif
                
                @if($digitalProduct->page_count)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Page Count</dt>
                    <dd class="text-base text-gray-900">{{ $digitalProduct->page_count }} pages</dd>
                </div>
                @endif
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Created At</dt>
                    <dd class="text-base text-gray-900">{{ $digitalProduct->created_at->format('M d, Y h:i A') }}</dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                    <dd class="text-base text-gray-900">{{ $digitalProduct->updated_at->format('M d, Y h:i A') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Users with Access -->
    <div class="mt-6 bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Users with Access ({{ $users->count() }})</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            User
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Access Granted
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Order
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $user->name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $user->email }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->pivot->granted_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($user->pivot->order_id)
                                <a href="{{ route('admin.orders.show', $user->pivot->order_id) }}" class="text-indigo-600 hover:text-indigo-900">
                                    View Order
                                </a>
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            No users have access to this product yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection