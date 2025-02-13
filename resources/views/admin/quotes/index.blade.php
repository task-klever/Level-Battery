@extends('admin.admin_layouts')
@section('admin_content')
    <h1 class="h3 mb-3 text-gray-800">Quote Requests</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 mt-2 font-weight-bold text-primary">View Requests</h6>
            <div class="float-right d-inline">
                
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Country</th>
                            <th>Business/Individual</th>
                            <th>Looking For</th>
                            <th>Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quotes as $quote)
                            <tr>
                                <td>{{ $quote->id }}</td>
                                <td>{{ $quote->full_name }}</td>
                                <td>{{ $quote->email }}</td>
                                <td>{{ $quote->phone }}</td>
                                <td>{{ $quote->country }}</td>
                                <td>{{ $quote->business_type }}</td>
                                <td>{{ implode(', ', is_array($quote->looking_for) ? $quote->looking_for : json_decode($quote->looking_for, true) ?? []) }}</td>
                                <td>{{ $quote->message }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $quotes->links() }} <!-- Pagination -->
            </div>
        </div>
    </div>
@endsection
