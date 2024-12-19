@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="bg-white rounded-xl shadow-xl border border-gray-200 p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                <span class="bg-indigo-500 bg-opacity-10 p-2 rounded-lg inline-flex items-center justify-center">
                    <i class="fas fa-bell text-indigo-600"></i>
                </span>
                <span class="mr-2">الإشعارات</span>
            </h1>
            <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white px-6 py-2.5 rounded-lg flex items-center transition duration-150 ease-in-out shadow-md">
                    <i class="fas fa-check-double ml-2"></i>
                    تحديد الكل كمقروء
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-lg relative mb-4 flex items-center" role="alert">
                <span class="bg-emerald-100 p-2 rounded-lg inline-flex items-center justify-center ml-2">
                    <i class="fas fa-check text-emerald-600"></i>
                </span>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="space-y-4">
            @forelse($notifications as $notification)
                <div class="border border-gray-200 rounded-xl p-5 {{ !$notification->is_read ? 'bg-indigo-50 border-indigo-200' : 'bg-white' }} transition-all duration-200 hover:shadow-md"
                     data-notification-id="{{ $notification->id }}">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <span class="{{ !$notification->is_read ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-500' }} p-2.5 rounded-lg inline-flex items-center justify-center">
                                <i class="fas fa-info-circle text-2xl"></i>
                            </span>
                        </div>
                        <div class="mr-4 flex-1">
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $notification->title }}
                                </h3>
                                <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full flex items-center">
                                    <i class="far fa-clock ml-1"></i>
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="mt-2 text-gray-600">
                                {{ $notification->message }}
                            </p>
                            @if(!$notification->is_read)
                                <button onclick="markAsRead({{ $notification->id }})" 
                                        class="mt-3 bg-indigo-100 text-indigo-600 hover:bg-indigo-200 px-4 py-2 rounded-lg text-sm transition duration-150 ease-in-out flex items-center w-fit">
                                    <i class="fas fa-check ml-1"></i>
                                    تحديد كمقروء
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                    <span class="bg-gray-100 p-4 rounded-full inline-flex items-center justify-center mb-4">
                        <i class="fas fa-bell-slash text-4xl text-gray-400"></i>
                    </span>
                    <p class="text-gray-500 text-lg">لا توجد إشعارات حالياً</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
function markAsRead(id) {
    fetch(`/notifications/${id}/mark-as-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const notification = document.querySelector(`[data-notification-id="${id}"]`);
            notification.classList.remove('bg-indigo-50', 'border-indigo-200');
            notification.classList.add('bg-white');
            const markAsReadBtn = notification.querySelector('button');
            if (markAsReadBtn) {
                markAsReadBtn.remove();
            }
        }
    });
}
</script>
@endpush
@endsection
