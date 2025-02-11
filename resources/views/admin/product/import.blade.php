@extends('admin.admin_layouts')
@section('admin_content')
    <h1 class="h3 mb-3 text-gray-800">Import Product</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.product.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Upload CSV File</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="file">Select CSV File *</label>
                    <input type="file" name="file" class="form-control" required>
                    <small class="form-text text-muted">The CSV file should contain product data.</small>
                </div>
                <button type="submit" class="btn btn-success">Import Products</button>
            </div>
        </div>
    </form>

@endsection
