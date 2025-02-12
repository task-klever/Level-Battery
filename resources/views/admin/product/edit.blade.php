@extends('admin.admin_layouts')
@section('admin_content')
    <h1 class="h3 mb-3 text-gray-800">Edit Product</h1>

    <form action="{{ url('admin/product/update/'.$product->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 mt-2 font-weight-bold text-primary">Edit Product</h6>
                <div class="float-right d-inline">
                    <a href="{{ route('admin.product.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> View All</a>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="">Name *</label>
                    <input type="text" name="product_name" class="form-control" value="{{ $product->product_name }}" autofocus>
                </div>
                <div class="form-group">
                    <label for="">Slug</label>
                    <input type="text" name="product_slug" class="form-control" value="{{ $product->product_slug }}">
                </div>
                <div class="form-group">
                    <label for="">Content *</label>
                    <textarea name="product_content" class="form-control editor" cols="30" rows="10">{{ $product->product_content }}</textarea>
                </div>
                <div class="form-group">
                    <label for="">Short Content *</label>
                    <textarea name="product_content_short" class="form-control h_100" cols="30" rows="10">{{ $product->product_content_short }}</textarea>
                </div>
                <div class="form-group">
                    <label for="">Existing Photo</label>
                    <div>
                        <img src="{{ asset('uploads/'.$product->product_featured_photo) }}" alt="" class="w_200">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Change Photo</label>
                    <div>
                        <input type="file" name="product_featured_photo">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Order</label>
                    <input type="text" name="product_order" class="form-control" value="{{ $product->product_order }}">
                </div>
                <div class="form-group">
                    <label for="">Status *</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="product_status" id="rr1" value="Show" @if($product->product_status == 'Show') checked @endif>
                            <label class="form-check-label font-weight-normal" for="rr1">Show</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="product_status" id="rr2" value="Hide" @if($product->product_status == 'Hide') checked @endif>
                            <label class="form-check-label font-weight-normal" for="rr2">Hide</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">SEO Information</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="">Title</label>
                    <input type="text" name="seo_title" class="form-control" value="{{ $product->seo_title }}">
                </div>
                <div class="form-group">
                    <label for="">Meta Description</label>
                    <textarea name="seo_meta_description" class="form-control h_100" cols="30" rows="10">{{ $product->seo_meta_description }}</textarea>
                </div>
                <button type="submit" class="btn btn-success">Update</button>
            </div>
        </div>
    </form>

@endsection
