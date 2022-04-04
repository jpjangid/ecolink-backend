@extends('layouts.main')

@section('title', 'Orders')

@section('content')
<div class="content">
    <!-- alert for success -->
    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Success! </strong>{{ $message }}
    </div>
    @endif

    @if ($message = Session::get('danger'))
    <div class="alert alert-danger alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Danger! </strong>{{ $message }}
    </div>
    @endif

    <!-- <h3 class="mb-3" style="margin-bottom: 30px">Orders <a href="/client/registraion" class="btn btn-info mt-o" style="float: right;">New Client</a></h3> -->

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Orders</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <a class="btn btn-info mr-1 mb-1" href="{{ url()->previous() }}">Back</a>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="container-fluid" style="overflow-x:auto;">
        <table id="orderTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>Order No</th>
                    <th>Customer Name</th>
                    <th>Order Status</th>
                    <th>Payment Status</th>
                    <th>Total Amount</th>
                    <th>Order Date</th>
                    <th>Special Requirement</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript">
    var orderTable = $('#orderTable').DataTable({
        processing: true,
        serverSide: true,
        url: "{{ url('admin/orders') }}",
        columns: [{
                data: 'orderno',
                name: 'orderno'
            },
            {
                data: 'client',
                name: 'client'
            },
            {
                data: 'order_status',
                name: 'order_status'
            },
            {
                data: 'payment_status',
                name: 'payment_status'
            },
            {
                data: 'total',
                name: 'total'
            },
            {
                data: 'date',
                name: 'date'
            },
            {
                data: 'order_comments',
                name: 'order_comments'
            },
        ]
    });
</script>
@endsection