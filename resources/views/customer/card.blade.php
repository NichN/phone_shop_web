@extends('Layout.headerfooter')

@section('title', 'Credit Card Payment')

@section('content')
    <style>
        .payment-container {
            display: flex;
            justify-content: center;
            margin-top: 50px;
            padding-bottom: 50px;
            /* Added bottom padding for space */
        }

        /* Boxed Form Style */
        .payment-box {
            width: 60%;
            padding: 30px;
            border-radius: 10px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;

        }

        .payment-box h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 4px;
            margin-bottom: 15px;
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }

        .btn-pay {
            width: 100%;
            background-color: #2c7be5;
            color: white;
            padding: 15px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-pay:hover {
            background-color: #1a6cb0;
        }

        .card-icons {
            display: flex;
            justify-content: flex-start;
            /* Align icons to the left */
            margin-bottom: 15px;
        }

        .card-icons img {
            width: 5%;
        }

        /* Transaction Summary Box */
        .transaction-summary {
            width: 35%;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .transaction-summary h4 {
            margin-bottom: 20px;
        }
    </style>

    <div class="container payment-container">
        <!-- Payment Box -->
        <div class="payment-box">
            <h3>Debit/Credit Card</h3>
            <form action="{{ route('payment.process') }}" method="POST">
                @csrf
                <div class="card-icons">
                    <img src="{{ asset('image/visa.png') }}" alt="Visa">
                    <img src="{{ asset('image/master.png') }}" alt="MasterCard">
                </div>

                <!-- Card Number -->
                <label for="card_number">Card Number*</label>
                <input type="text" name="card_number" id="card_number" class="form-control"
                    placeholder="0000-0000-0000-0000" required>

                <!-- Card Holder Name -->
                <label for="cardholder_name">Card Holder Name*</label>
                <input type="text" name="cardholder_name" id="cardholder_name" class="form-control"
                    placeholder="Your Name" required>

                <!-- Expiry Date -->
                <label for="expiry_date">Expiration Date*</label>
                <input type="month" name="expiry_date" id="expiry_date" class="form-control" required>

                <!-- CVV -->
                <label for="cvv">CVV Number*</label>
                <input type="text" name="cvv" id="cvv" class="form-control" placeholder="CVV" required>

                <button type="submit" class="btn-pay">Confirm and Pay</button>
            </form>
        </div>
    </div>
@endsection
