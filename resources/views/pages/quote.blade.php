@extends('layouts.app')

@section('content')
    <div class="page-banner" style="background-image: url({{ asset('uploads/'.$g_setting->banner_project) }})">
        <div class="bg-page"></div>
        <div class="text">
            <h1>Quote</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ HOME }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Quote</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @if(session('success'))
                        <div style="color: green;">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('quote.store') }}" method="POST">
                        @csrf
                        <label>Full Name:</label>
                        <input type="text" name="full_name" required>

                        <label>Email:</label>
                        <input type="email" name="email" required>

                        <label>Phone:</label>
                        <input type="text" name="phone" required>

                        <label>Country:</label>
                        <input type="text" name="country" required>

                        <label>Business Type:</label>
                        <select name="business_type" required>
                            <option value="Business">Business</option>
                            <option value="Individual">Individual</option>
                        </select>

                        <label>What Are You Looking For:</label>
                        <select name="looking_for[]" multiple required>
                            <option value="Batteries">Batteries</option>
                            <option value="Spare Parts">Spare Parts</option>
                            <option value="Lubricants">Lubricants</option>
                            <option value="Others">Others</option>
                        </select>

                        <label>Message:</label>
                        <textarea name="message"></textarea>

                        <button type="submit">Submit Quote</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
