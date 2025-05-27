@extends('Layout.headerfooter')

@section('title', 'Invoice')

@section('content')
<style>
    .receipt {
        font-family: 'Khmer OS';
        max-width: 400px;
        margin: auto;
        border: 1px dashed #000;
        padding: 20px;
    }
    .receipt-header {
        text-align: center;
    }
    .receipt-header h5 {
        font-weight: bold;
    }
    .table td, .table th {
        padding: 0.25rem;
    }
    .border-top-dashed {
        border-top: 1px dashed #000 !important;
    }
</style>
<div class="container my-5">
    <a href="{{ route('history') }}" onclick="history.back()" class="btn btn-secondary mb-4">← Back</a>
    <div class="receipt">
        <div class="receipt-header">
            <h5>TayMeng</h5>
            <small>#78E0, ផ្លូវ13 សង្កាត់ផ្សារកណ្តាល I ខណ្ឌដូនពេញ រាជធានីភ្នំពេញ</small><br>
            <small>096841 2222 / 076 333 3288 / 031 333 3288</small>
        </div>

        <hr class="my-2">

        <div class="text-center">
            <strong>វិក័យបត្រ / RECEIPT</strong>
        </div>

        <div class="mt-2">
            <p><strong>Date:</strong> {{ $date }}</p>
            <p><strong>Invoice Number:</strong> {{ $invoice_number }}</p>
            <p><strong>Customer:</strong> {{ $customer }}</p>
        </div>
    
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th class="text-end">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td class="text-end">${{ number_format($item['price'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    
        <hr class="my-1 border-top-dashed">

        <div class="d-flex justify-content-between">
            <strong>TOTAL:</strong>
            <span>${{ number_format($total_usd, 2) }}</span>
        </div>
        <div class="d-flex justify-content-between">
            <small>Total (KHR):</small>
            <span>{{ $total_khr }}</span>
        </div>
    
        <div class="mt-2">
            <div class="d-flex justify-content-between">
                <small>Receive (Cash):</small>
                <span>${{ $cash }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <strong>Change:</strong>
                <span>${{ $change_usd }} | {{ $change_khr }}</span>
            </div>
        </div>
    
        <hr class="my-2">
    
        <p class="text-center">
            <small>អត្រាប្តូរប្រាក់ប្រើប្រាស់: VAT 10%, អត្រាប្តូរប្រាក់: $1 = ៛4,100</small><br>
            <strong>សូមអរគុណ! សូមអញ្ជើញមកម្តងទៀត</strong><br>
            <small>Thank you, please come back again.</small>
        </p>
    </div>
</div>
@endsection
