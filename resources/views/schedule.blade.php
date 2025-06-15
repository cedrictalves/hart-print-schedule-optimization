@extends('layouts.app')

@section('title', 'Schedule')

@section('content')

    <h1 class="text-xl font-bold mb-4">Schedule</h1>

    {{-- Show success message if available --}}
    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if (count($planning) > 0)
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3 border border-gray-200">Order ID</th>
                    <th scope="col" class="px-6 py-3 border border-gray-200">Customer</th>
                    <th scope="col" class="px-6 py-3 border border-gray-200">Products</th>
                    <th scope="col" class="px-6 py-3 border border-gray-200">Type</th>
                    <th scope="col" class="px-6 py-3 border border-gray-200">Production Time</th>
                    <th scope="col" class="px-6 py-3 border border-gray-200">Due Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($planning as $line)
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                        <td class="px-6 py-4 border border-gray-200">{{ $line['order_id'] }}</td>
                        <td class="px-6 py-4 border border-gray-200">{{ $line['customer_name'] }}</td>
                        <td class="px-6 py-4 border border-gray-200">
                            @foreach($line['items'] as $item)
                                @if ($loop->first && $line['setup'] > 0)
                                    <div class="setup-time"><strong>Setup Time</strong> - {{ $line['setup'] }} minutes</div>
                                    <hr>
                                @elseif ($loop->first)
                                    <div class="setup-time"><strong>No Setup Time</strong></div>
                                    <hr>
                                @endif
                                <div class="name-units"><strong>{{ $item['product_name'] }}</strong> - {{ $item['quantity'] }} units</div>
                                <div class="start-time"><strong>Production Time</strong> - {{ $item['production_time'] }} minutes</div>
                                @if (!$loop->last)
                                    <hr>
                                @endif

                            @endforeach
                        </td>
                        <td class="px-6 py-4 border border-gray-200">{{ $line['type'] }}</td>
                        <td class="px-6 py-4 border border-gray-200">{{ $line['total_production_time'] }} minutes</td>
                        <td class="px-6 py-4 border border-gray-200">{{ $line['due_date'] }}</td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <p>No orders scheduled yet.</p>
        <p><a href="{{ route('order.form') }}" class="text-blue-500 hover:underline">Place an order</a> to start scheduling.</p>
    @endif

@endsection

