@extends('layouts.admin')

@section('title', __('admin.orders'))

@section('content')
<div class="space-y-6">
    <!-- Header with Date Filter -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <h1 class="text-4xl font-bold text-purple-400">{{ __('admin.orders') }}</h1>
        
        <!-- Date Filter -->
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex items-center gap-3">
            <label for="date" class="text-gray-300 text-sm font-medium whitespace-nowrap">{{ __('admin.filter_by_date') }}:</label>
            <input type="date" 
                   id="date" 
                   name="date" 
                   value="{{ $selectedDate }}" 
                   class="px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 transition"
                   onchange="this.form.submit()">
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2.5 rounded-lg transition font-medium flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <span>{{ __('admin.filter') }}</span>
            </button>
        </form>
    </div>

    @forelse($orders as $date => $dayOrders)
        <!-- Date Header -->
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-gray-300 flex items-center space-x-2">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>{{ \Carbon\Carbon::parse($date)->locale(app()->getLocale())->isoFormat('dddd, D MMMM YYYY') }}</span>
                <span class="text-gray-500 text-sm font-normal">({{ $dayOrders->count() }} {{ __('admin.orders') }})</span>
            </h2>
        </div>

        <!-- Orders for this day -->
        <div class="bg-gray-800 rounded-lg border border-gray-700 overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-900">
                        <tr>
                            <th class="px-6 py-4 text-left text-gray-300 font-semibold text-sm">{{ __('admin.order_number') }}</th>
                            <th class="px-6 py-4 text-left text-gray-300 font-semibold text-sm">{{ __('common.customer_name') }}</th>
                            <th class="px-6 py-4 text-left text-gray-300 font-semibold text-sm">{{ __('common.customer_email') }}</th>
                            <th class="px-6 py-4 text-left text-gray-300 font-semibold text-sm">{{ __('admin.order_status') }}</th>
                            <th class="px-6 py-4 text-left text-gray-300 font-semibold text-sm">{{ __('common.order_total') }}</th>
                            <th class="px-6 py-4 text-center text-gray-300 font-semibold text-sm">{{ __('admin.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dayOrders as $order)
                            <tr class="border-b border-gray-700 hover:bg-gray-750 transition {{ $order->status === 'pending' ? 'bg-yellow-900/10' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-white font-medium">{{ $order->order_number }}</span>
                                        <span class="text-gray-500 text-xs mt-1">{{ $order->created_at->format('H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-300">{{ $order->customer_name }}</td>
                                <td class="px-6 py-4 text-gray-300">{{ $order->customer_email }}</td>
                                <td class="px-6 py-4">
                                    @if($order->status === 'pending')
                                        <span class="inline-block bg-yellow-600 text-white px-3 py-1 rounded-full text-sm font-medium">{{ __('common.pending') }}</span>
                                    @elseif($order->status === 'validated')
                                        <span class="inline-block bg-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">{{ __('common.validated') }}</span>
                                    @else
                                        <span class="inline-block bg-red-600 text-white px-3 py-1 rounded-full text-sm font-medium">{{ __('common.refused') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-right">
                                        <div class="text-purple-400 font-semibold">{{ number_format($order->total_amount, 2) }} MAD</div>
                                        @if($order->delivery_fee > 0)
                                            <div class="text-gray-400 text-xs">+ {{ number_format($order->delivery_fee, 2) }} {{ __('checkout.delivery_fee') }}</div>
                                            <div class="text-purple-400 font-bold mt-1">{{ number_format($order->total_amount + $order->delivery_fee, 2) }} MAD</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2 flex-wrap">
                                        @if($order->status === 'pending')
                                            <!-- Quick Actions for Pending Orders -->
                                            <form action="{{ route('admin.orders.updateStatus', [$order, 'validated']) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('admin.confirm_validate_order', ['number' => $order->order_number]) }}')">
                                                @csrf
                                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg transition text-sm font-medium flex items-center space-x-1" title="{{ __('admin.validate_order') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    <span>{{ __('admin.validate') }}</span>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.orders.updateStatus', [$order, 'refused']) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('admin.confirm_refuse_order', ['number' => $order->order_number]) }}')">
                                                @csrf
                                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg transition text-sm font-medium flex items-center space-x-1" title="{{ __('admin.refuse_order') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                    <span>{{ __('admin.refuse') }}</span>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <a href="{{ route('admin.orders.show', $order) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1.5 rounded-lg transition text-sm font-medium flex items-center space-x-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <span>{{ __('admin.details') }}</span>
                                        </a>
                                        
                                        @if($order->customer_phone && $order->getWhatsAppContactUrl())
                                            <a href="{{ $order->getWhatsAppContactUrl() }}" target="_blank" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg transition text-sm font-medium flex items-center space-x-1" title="WhatsApp">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="bg-gray-800 rounded-lg border border-gray-700 p-12 text-center">
            <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <p class="text-gray-400 text-lg">{{ __('admin.no_items') }}</p>
        </div>
    @endforelse
</div>
@endsection
