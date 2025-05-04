@extends('Layout.headerfooter')

@section('title', 'Hisotry')

@section('header')
<link rel="stylesheet" href="{{ asset('css/history.css') }}">
@endsection

@section('content')
<div class="container mt-4 mb-5">
    <h2 class="text-center mb-4">
        My Order
    </h2>

    <form method="GET" action="{{ route('history') }}">
        <div class="row mb-4">
            <div class="col-md-3 mb-2">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Product</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Returned" {{ request('status') == 'Returned' ? 'selected' : '' }}>Returned</option>
                    <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>Processing</option>
                </select>
            </div>
    
            <div class="col-md-3 mb-2">
                <input type="text" class="form-control" name="search" placeholder="Search Order" value="{{ request('search') }}">
            </div>
    
            <div class="col-md-3 mb-2">
                <input type="date" class="form-control" name="date" value="{{ request('date') }}">
            </div>
    
            <div class="col-md-3 mb-2">
                <a href="{{ route('history') }}" class="btn btn-danger w-100">Reset</a>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">
            <thead>
                <tr class="text-white">
                    <th style="background-color: #237804;">Item ID</th>
                    <th style="background-color: #1E50B5;">Order date</th>
                    <th style="background-color: #1E50B5;">Amount</th>
                    <th style="background-color: #1E50B5;">Order Status</th>
                    <th style="background-color: #1E50B5;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr>
                    <td>{{ $order['id'] }}</td>
                    <td>{{ $order['date'] }}</td>
                    <td>{{ $order['amount'] }}</td>
                    <td>
                        @if ($order['status'] === 'Returned')
                            <span class="text-danger fw-bold">{{ $order['status'] }}</span>
                        @elseif ($order['status'] === 'Completed')
                            <span class="text-success fw-bold">{{ $order['status'] }}</span>
                        @else
                            <span class="text-warning fw-bold">{{ $order['status'] }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ url('/invoice/1') }}" class="btn btn-outline-primary btn-sm me-2">View</a>
                        <form action="#" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>
</div>
@endsection