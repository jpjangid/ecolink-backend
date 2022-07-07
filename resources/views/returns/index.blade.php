@extends('layouts.main')

@section('title', 'Returns')

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

    <!-- <h3 class="mb-3" style="margin-bottom: 30px">Returns <a href="/client/registraion" class="btn btn-info mt-o" style="float: right;">New Client</a></h3> -->

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Returns</h1>
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
        <table id="returnTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>Return Order No</th>
                    <th>Order No</th>
                    <th>SKU</th>
                    <th>Customer Name</th>
                    <th>Reason</th>
                    <th>Return Status</th>
                    <th>Return Initiation Date</th>
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
    var returnTable = $('#returnTable').DataTable({
        scrollY: "70vh",
        processing: true,
        serverSide: true,
            order: [],
        url: "{{ url('admin/returns') }}",
        columns: [{
                data: 'returnno',
                name: 'returnno'
            },
            {
                data: 'order_no',
                name: 'order_no'
            },
            {
                data: 'sku',
                name: 'sku'
            },
            {
                data: 'client',
                name: 'client'
            },
            {
                data: 'reason',
                name: 'reason'
            },
            {
                data: 'return_status',
                name: 'return_status'
            },
            {
                data: 'date',
                name: 'date'
            },
        ]
    });
</script>
@endsection