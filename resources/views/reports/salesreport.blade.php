@extends('layouts.main')

@section('title', 'Sales Report')

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

    <!-- <h3 class="mb-3" style="margin-bottom: 30px">Sales Report <a href="/client/registraion" class="btn btn-info mt-o" style="float: right;">New Client</a></h3> -->

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Sales Report</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <a class="btn btn-info mr-1 mb-1" href="{{ url()->previous() }}">Back</a>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
            <div class="row">
                <div class="col-sm-8"></div>
                <div class="col-sm-4">
                    <div class="btn-group float-right" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-secondary dateFilter" value="week">Week</button>
                        <button type="button" class="btn btn-secondary dateFilter" value="month">Month</button>
                        <button type="button" class="btn btn-secondary dateFilter" value="year">Year</button>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>

    <div class="container-fluid" style="overflow-x:auto;">
        <table id="salesReportTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>Order No.</th>
                    <th>Quantity</th>
                    <th>Customer</th>
                    <th>Total Amount</th>
                    <th>Order Date</th>
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
    $(function() {
        $('.dateFilter:first').addClass('active');
        var date = $('.dateFilter').val();
        datatable(date);
    });

    $(document).on('click','.dateFilter',function(){
        $('.dateFilter').removeClass('active');
        var date = this.value;
        datatable(date);
    });

    function datatable(date) {
        var salesReportTable = $('#salesReportTable').DataTable({
            destroy: true,
            scrollY: "70vh",
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "{{ url('admin/reports/sales') }}",
                type: "GET",
                data: function (d) {
                    d.date = date;
                    d._token = '{{csrf_token()}}';
                }
            },
            columns: [{
                    data: 'order_no',
                    name: 'order_no'
                },
                {
                    data: 'qty',
                    name: 'qty'
                },
                {
                    data: 'customer',
                    name: 'customer'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                }
            ]
        });
    }
</script>
@endsection