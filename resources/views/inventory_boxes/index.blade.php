@extends('layouts.storehouse')

@section('content')
<div class="p-6 bg-gray-50">
    <!-- Main Section -->
    <div class="bg-white rounded-xl shadow-lg mb-6">
        <div class="p-6 border-b border-gray-100">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <h2 class="text-xl font-bold text-gray-800">نظام الصناديق الموجودة</h2>
                    <div class="relative">
                        <input type="text" 
                            id="searchInput" 
                            placeholder="بحث..." 
                            class="pr-10 pl-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        <i class="fas fa-search absolute right-3 top-3 text-indigo-500"></i>
                    </div>
                </div>
                <div class="relative">
                    <select id="box_type_filter" 
                        class="pr-10 pl-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        <option value="">كل أنواع الصناديق</option>
                        @foreach($boxTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع الصندوق</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكمية</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ملاحظات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($inventoryBoxes as $box)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $box->boxType->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($box->received_quantity) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $box->notes ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4">
                {{ $inventoryBoxes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
