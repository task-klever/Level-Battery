@extends('layouts.app')

@section('content')
    <div class="page-banner" style="background-image: url({{ asset('uploads/'.$g_setting->banner_product) }})">
        <div class="bg-page"></div>
        <div class="text">
            <h1>{{ $shop->name }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ HOME }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $shop->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="page-content pt_60">
        <div class="container">
            <div class="row">
                <!-- Main Content Area -->
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            {!! $shop->detail !!}
                        </div>
                    </div>
                    <div class="row">
                        @if($products->isEmpty())
                            <div class="col-md-12 text-center">
                                <p>No products found.</p>
                                <a href="{{ url('/shop') }}" class="btn btn-secondary">Go back to Shop</a>
                            </div>
                        @else
                            @foreach($products as $row)
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="product-item">
                                        <div class="photo">
                                            <a href="{{ url('product/'.$row->product_slug) }}">
                                                <img src="{{ asset('uploads/'.$row->product_featured_photo) }}">
                                            </a>
                                        </div>
                                        <div class="text">
                                            <h3><a href="{{ url('product/'.$row->product_slug) }}">{{ $row->product_name }}</a></h3>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="col-md-12">
                                {{ $products->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .shop-sidebar {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 5px;
    }
    .widget-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #ddd;
    }
    .sidebar-widget {
        margin-bottom: 20px;
    }
    .select2-container .select2-selection--single {
        height: 38px;
        line-height: 38px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });
    });
</script>
@endsection
