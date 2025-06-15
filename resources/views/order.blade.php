@extends('layouts.app')

@section('title', 'Place an Order')

@section('content')
    <h1 class="text-xl font-bold mb-4">Place an Order</h1>

    {{-- Show success message if available --}}
    @if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
    @endif

    {{-- Show error messages if available --}}
    @if ($errors->any())
        <div>
            @foreach ($errors->all() as $error)
                <p style="color: red;">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('order.store') }}" method="POST" class="order-form">
        {{-- Include CSRF token for security --}}
        @csrf {{-- CSRF token for security --}}

        {{-- Customer name --}}
        <div class="form-group">
            <label for="customer_name">Customer Name:</label>
            <input type="text" name="customer_name" id="customer_name" required>
        </div>

        {{-- Need-by date --}}
        @php
            $minDate = \Carbon\Carbon::now()->addDays(7)->format('Y-m-d');
        @endphp
        <div class="form-group">
            <label for="need_by_date">Need-by Date:</label>
            <input
                type="date"
                name="need_by_date"
                id="need_by_date"
                min="{{ $minDate }}"
                required>
        </div>

        {{-- Product type --}}
        <div class="form-group">
            <label for="type">Product Type:</label>
            <select name="type" id="type" required>
                <option value="">-- Select a type --</option>
                <option value="1">Type 1</option>
                <option value="2">Type 2</option>
                <option value="3">Type 3</option>
            </select>
        </div>

        {{-- Products container (populated dynamically) --}}
        <div class="form-group">
            <div id="products-container">
                <!-- Dynamic product inputs will be inserted here -->
            </div>
        </div>

        <button id="submit-button" type="submit">Submit Order</button>
    </form>

    <script>
    </script>
@endsection
