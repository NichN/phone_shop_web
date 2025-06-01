@extends('Layout.auth_form')

@section('title', 'Two-Factor Verification')

@section('content')
    <form method="POST" action="{{ route('two_factor.verify') }}">
        @csrf
        <label for="code">Two-Factor Code</label>
        <input type="text" name="code" id="code" required>
        <button type="submit">Verify</button>
    </form>


    @if ($errors->any())
        <div class="text-danger mt-2">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
@endsection
