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
                <!-- Sidebar with Filters -->
                <div class="col-md-3">
                    <div class="shop-sidebar">
                        <form action="{{ url('/shop') }}" method="GET">
                            <div class="sidebar-widget mb-4">
                                <h4 class="widget-title">Search</h4>
                                <input type="text" name="q" class="form-control" placeholder="Search products..." value="{{ request('q') }}">
                            </div>

                            <div class="sidebar-widget mb-4">
                                <h4 class="widget-title">Brand</h4>
                                <select name="brand" class="form-control select2">
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand['id'] }}" {{ request('brand') == $brand['id'] ? 'selected' : '' }}>
                                            {{ $brand['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="sidebar-widget mb-4">
                                <h4 class="widget-title">Pattern</h4>
                                <select name="pattern" class="form-control select2">
                                    <option value="">Select Pattern</option>
                                    @foreach($patterns as $pattern)
                                        <option value="{{ $pattern['id'] }}" {{ request('pattern') == $pattern['id'] ? 'selected' : '' }}>
                                            {{ $pattern['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="sidebar-widget mb-4">
                                <h4 class="widget-title">OEM</h4>
                                <select name="oem" class="form-control select2">
                                    <option value="">Select OEM</option>
                                    @foreach($oems as $oem)
                                        <option value="{{ $oem['id'] }}" {{ request('oem') == $oem['id'] ? 'selected' : '' }}>
                                            {{ $oem['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="sidebar-widget mb-4">
                                <h4 class="widget-title">Specifications</h4>
                                <div class="mb-3">
                                    <input type="text" name="width" class="form-control" placeholder="Width" value="{{ request('width') }}">
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="height" class="form-control" placeholder="Height" value="{{ request('height') }}">
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="rim" class="form-control" placeholder="Rim" value="{{ request('rim') }}">
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="runflat" class="form-control" placeholder="RunFlat" value="{{ request('runflat') }}">
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="load_speed" class="form-control" placeholder="Load/Speed" value="{{ request('load_speed') }}">
                                </div>
                            </div>

                            <div class="sidebar-widget mb-4">
                                <h4 class="widget-title">Origin</h4>
                                <select name="origin" class="form-control select2">
                                    <option value="">Select Origin</option>
                                    @foreach($origins as $origin)
                                        <option value="{{ $origin['id'] }}" {{ request('origin') == $origin['id'] ? 'selected' : '' }}>
                                            {{ $origin['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="sidebar-widget mb-4">
                                <h4 class="widget-title">Year</h4>
                                <select name="year" class="form-control select2">
                                    <option value="">Select Year</option>
                                    @php
                                        $currentYear = date('Y');
                                        $startYear = 2000;
                                    @endphp
                                    @for($year = $currentYear; $year >= $startYear; $year--)
                                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="sidebar-widget mb-4">
                                <button type="submit" class="btn btn-primary w-100 mb-2">Apply Filters</button>
                                <a href="{{ url('/shop') }}" class="btn btn-secondary w-100">Reset Filters</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="col-md-9">
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
                                            <div class="price">
                                                @if($row->product_old_price != '')
                                                <del>${{ $row->product_old_price }}</del>
                                                @endif
                                                ${{ $row->product_current_price }}
                                            </div>
                                            <div class="cart-button">
                                                @if($row->product_stock == 0)
                                                <a href="javascript:void(0);" class="stock-empty w-100-p text-center">{{ STOCK_EMPTY }}</a>
                                                @else
                                                <form action="{{ route('front.add_to_cart') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $row->id }}">
                                                    <input type="hidden" name="product_qty" value="1">
                                                    <button type="submit">{{ ADD_TO_CART }}</button>
                                                </form>
                                                @endif
                                            </div>
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
