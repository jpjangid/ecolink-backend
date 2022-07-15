@extends('layouts.main')

@section('title', 'Home')

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

    <!-- <h3 class="mb-3" style="margin-bottom: 30px">Products <a href="/client/registraion" class="btn btn-info mt-o" style="float: right;">New Client</a></h3> -->

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Dashboard</h1>
                </div><!-- /.col -->
                <!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Total Order/Enquire Chart</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="roomChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Ecolink</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="guestChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h6 class="m-0 text-dark">Enquire Contact</h6>
                                </div><!-- /.col -->
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="{{ url('admin/contact') }}" class="btn btn-info btn-sm" style="float: right;">View All</a></li>
                                    </ol>
                                </div>
                                <!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- /.container-fluid -->
                    </div>
                    <div class="container-fluid" style="overflow-x:auto;">
                        <table id="productTable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>Enquire Type</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach( $contacts as $contact)
                                <tr>
                                    <td>{{ $contact->type}}</td>
                                    <td>
                                        <span><b>Name: </b></span>
                                        <small>{{ $contact->first_name }}</small><br />
                                        <span><b>Email: </b></span>
                                        <small>{{ $contact->email }}</small><br />
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h6 class="m-0 text-dark">Enquire Askchemist</h6>
                                </div><!-- /.col -->
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="{{ url('admin/askchemist') }}" class="btn btn-info btn-sm" style="float: right;">View All</a></li>
                                    </ol>
                                </div>
                                <!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- /.container-fluid -->
                    </div>
                    <div class="container-fluid" style="overflow-x:auto;">
                        <table id="productTable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>Enquire Type</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach( $askchemists as $askchemist)
                                <tr>
                                    <td>{{ $askchemist->type}}</td>
                                    <td>
                                        <span><b>Name: </b></span>
                                        <small>{{ $askchemist->first_name }}</small><br />
                                        <span><b>Email: </b></span>
                                        <small>{{ $askchemist->email }}</small><br />
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h6 class="m-0 text-dark">Enquire Product Request</h6>
                                </div><!-- /.col -->
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="{{ url('admin/requestproduct') }}" class="btn btn-info btn-sm" style="float: right;">View All</a></li>
                                    </ol>
                                </div>
                                <!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- /.container-fluid -->
                    </div>
                    <div class="container-fluid" style="overflow-x:auto;">
                        <table id="productTable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>Enquire Type</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach( $productrequests as $productrequest)
                                <tr>
                                    <td>{{ $productrequest->type}}</td>
                                    <td>
                                        <span><b>Name: </b></span>
                                        <small>{{ $productrequest->first_name }}</small><br />
                                        <span><b>Email: </b></span>
                                        <small>{{ $productrequest->email }}</small><br />
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h6 class="m-0 text-dark">Enquire Technicals Support</h6>
                                </div><!-- /.col -->
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="{{ url('admin/technicalsupport') }}" class="btn btn-info btn-sm" style="float: right;">View All</a></li>
                                    </ol>
                                </div>
                                <!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- /.container-fluid -->
                    </div>
                    <div class="container-fluid" style="overflow-x:auto;">
                        <table id="productTable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>Enquire Type</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach( $technicals as $technical)
                                <tr>
                                    <td>{{ $technical->type}}</td>
                                    <td>
                                        <span><b>Name: </b></span>
                                        <small>{{ $technical->first_name }}</small><br />
                                        <span><b>Email: </b></span>
                                        <small>{{ $technical->email }}</small><br />
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h6 class="m-0 text-dark">Enquire Bulkpricings</h6>
                                </div><!-- /.col -->
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="{{ url('admin/bulkpricing') }}" class="btn btn-info btn-sm" style="float: right;">View All</a></li>
                                    </ol>
                                </div>
                                <!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- /.container-fluid -->
                    </div>
                    <div class="container-fluid" style="overflow-x:auto;">
                        <table id="productTable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>Enquire Type</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach( $bulkpricings as $bulkpricing)
                                <tr>
                                    <td>{{ $bulkpricing->type}}</td>
                                    <td>
                                        <span><b>Name: </b></span>
                                        <small>{{ $bulkpricing->first_name }}</small><br />
                                        <span><b>Email: </b></span>
                                        <small>{{ $bulkpricing->email }}</small><br />
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h6 class="text-dark">Order</h6>
                                </div><!-- /.col -->
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="{{ url('admin/orders') }}" class="btn btn-info btn-sm" style="float: right;">View All</a></li>
                                    </ol>
                                </div>
                                <!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- /.container-fluid -->
                    </div>
                    <div class="container-fluid" style="overflow-x:auto;">
                        <table id="productTable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>Product</th>
                                    <th>Order#</th>
                                    <th>Customer</th>
                                    <th>Payment Status</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach( $orders as $order)
                                <tr>
                                    <td>{{ !empty($order->items[0]) ? $order->items[0]->product->name : '' }}</td>
                                    <td><a href="{{ url('admin/orders/order_detail',$order->id)}}"><span>#</span>{{ $order->order_no}}</a></td>
                                    <td> <span><b>Name: </b></span>
                                        <small>{{ $order->user->name }}</small><br />
                                        <span><b>Number: </b></span>
                                        <small>{{ $order->mobile }}</small><br />
                                    </td>
                                    <td>{{  ucfirst(strtolower($order->payment_status))}}</td>
                                    <td>${{ number_format((float)$order->total_amount, 2, '.', ','), }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{asset('../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<!-- AdminLTE App -->
<script>
    $(document).ready(function() {
        const bookedroom = '<?php echo $orderCount->count(); ?>';
        const avilableroom = '<?php echo $enquireCount->count(); ?>';
        roomDetails(bookedroom, avilableroom);

        const totalProduct = '<?php echo $productCount->count(); ?>';
        const expensespending = '<?php echo $blogCount->count(); ?>';
        const expensespending1 = '<?php echo $CartCount->count(); ?>';
        guestDetails(totalProduct, expensespending, expensespending1);

        var months = <?php echo json_encode('test'); ?>;
        var expense = <?php echo json_encode('test'); ?>;
        var receive = <?php echo json_encode('test'); ?>;
        barChart(months, expense, receive)
    });
</script>
<script>
    function roomDetails(data1, data2) {
        var donutData = {
            labels: [
                'Order',
                'Enquire',
            ],
            datasets: [{
                data: [data1, data2],
                backgroundColor: ['#00a65a', '#dc3545'],
            }]
        }
        var pieChartCanvas = $('#roomChart').get(0).getContext('2d')
        var pieData = donutData;
        var pieOptions = {
            maintainAspectRatio: false,
            responsive: true,
        }
        var pieChart = new Chart(pieChartCanvas, {
            type: 'pie',
            data: pieData,
            options: pieOptions
        })
    }

    function guestDetails(data1, data2, data3) {
        var donutData = {
            labels: [
                'Total Product',
                'Total Blog',
                'Cart in Product',
            ],
            datasets: [{
                data: [data1, data2, data3],
                backgroundColor: ['#f39c12', '#007bff', '#37793a'],
            }]
        }
        var pieChartCanvas = $('#guestChart').get(0).getContext('2d')
        var pieData = donutData;
        var pieOptions = {
            maintainAspectRatio: false,
            responsive: true,
        }
        var pieChart = new Chart(pieChartCanvas, {
            type: 'pie',
            data: pieData,
            options: pieOptions
        })
    }
</script>

@endsection