@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <h1 class="text-xl font-bold mb-4">Schedule Optimization</h1>
    <p>Place an order or see the schedule.</p>
    <div class="cards-container">
        <a href="{{ route('order.form') }}" class="card">Place an order</a>
        <a href="{{ route('schedule') }}" class="card">See the schedule</a>
    </div>
@endsection

