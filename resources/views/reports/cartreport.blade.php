@extends('layouts.main')

@section('title', 'Abandoned Cart Report')

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

    <!-- <h3 class="mb-3" style="margin-bottom: 30px">Abandoned Cart Report <a href="/client/registraion" class="btn btn-info mt-o" style="float: right;">New Client</a></h3> -->

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Abandoned Cart Report</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <a class="btn btn-info mr-1 mb-1" href="{{ url()->previous() }}">Back</a>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
            <div class="row">
                <div class="col-sm-5"></div>
                <div class="col-sm-4 input-group">
                    <select class="form-control select2bs4" data-clear-button="true" id="product">
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}({{$product->variant}})</option>
                        @endforeach
                    </select>
                    <button class="btn bg-transparent" style="margin-left: -40px; z-index: 100;" id="clear">
                        <i class="fa fa-times"></i>
                      </button>
                </div>
                <div class="col-sm-3">
                    <input type="hidden" id="date" />
                    <div class="btn-group float-right" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-secondary dateFilter week" value="week">Week</button>
                        <button type="button" class="btn btn-secondary dateFilter month" value="month">Month</button>
                        <button type="button" class="btn btn-secondary dateFilter year" value="year">Year</button>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>

    <div class="container-fluid" style="overflow-x:auto;">
        <table id="cartTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>Created Date Time</th>
                    <th>User</th>
                    <th>Product</th>
                    <th>Quantity</th>
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
        $('#date').val(date);
        var product = $('#product').val();
        datatable(date,product);
    });

    $(document).on('click','.dateFilter',function(){
        $('.dateFilter').removeClass('active');
        var date = this.value;
        $('.'+date).addClass('active');
        $('#date').val(date);
        var product = $('#product').val();
        datatable(date,product);
    });

    $(document).on('change','#product',function(){
        var date = $('#date').val();
        $('.'+date).addClass('active');
        var product = $('#product').val();
        datatable(date,product);
    });

    $(document).on('click','#clear',function(){
        $("select option").filter(":selected").remove();
        var date = $('#date').val();
        $('.'+date).addClass('active');
        var product = $('#product').val();
        datatable(date,product);
    });

    function datatable(date,product) {
        var cartTable = $('#cartTable').DataTable({
            destroy: true,
            scrollY: "70vh",
            processing: true,
            serverSide: true,
            order: [],
            order: [
                [0, "desc"]
            ],
            ajax: {
                url: "{{ url('admin/reports/carts') }}",
                type: "GET",
                data: function (d) {
                    d.date = date;
                    d.product = product;
                    d._token = '{{csrf_token()}}';
                }
            },
            columns: [{
                    data: 'created_at',
                    name: 'created_at'
                }, {
                    data: 'user',
                    name: 'user'
                },
                {
                    data: 'product',
                    name: 'product'
                },
                {
                    data: 'quantity',
                    name: 'quantity'
                },
            ]
        });
    }
</script>
@endsection