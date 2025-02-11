@extends('layouts.app')

@section('content')
    <div class="page-banner" style="background-image: url({{ asset('uploads/'.$g_setting->banner_product_detail) }})">
        <div class="bg-page"></div>
        <div class="text">
            <h1>{{ $product_detail->product_name }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ HOME }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('front.shop') }}">{{ PRODUCTS }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $product_detail->product_name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="page-content">
        <div class="container">
            <div class="row product-detail pt_30 pb_40">
                <div class="col-md-5">
                    <div class="photo"><img src="{{ asset('uploads/'.$product_detail->product_featured_photo)  }}"></div>
                </div>
                <div class="col-md-7">
                    @csrf
                    <h2>{{ $product_detail->product_name }}</h2>
                    <p>
                        {!! nl2br(e($product_detail->product_content_short)) !!}
                    </p>
                </div>
            </div>

            <div class="row product-detail pt_30 pb_40">
                <div class="col-md-12">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">{{ DESCRIPTION }}</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                            {!! $product_detail->product_content !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
