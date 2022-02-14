@extends('layouts.main')

@section('title', 'Home')

@section('css')
<style>
    /* .table-striped > tbody > tr:nth-child(2n+1) > td, .table-striped > tbody > tr:nth-child(2n+1) > th {
            background-color: #edfdef;
        } */
    /* th {
            background-color: lightgrey;
        } */
    .info-box-text {
        color: white;
    }

    .info-box-number {
        color: white;
        font-size: 25px;
    }

    @keyframes wobble-vertical-on-hover {
        16.65% {
            -webkit-transform: translateY(8px);
            transform: translateY(8px);
        }

        33.3% {
            -webkit-transform: translateY(-6px);
            transform: translateY(-6px);
        }

        49.95% {
            -webkit-transform: translateY(4px);
            transform: translateY(4px);
        }

        66.6% {
            -webkit-transform: translateY(-2px);
            transform: translateY(-2px);
        }

        83.25% {
            -webkit-transform: translateY(1px);
            transform: translateY(1px);
        }

        100% {
            -webkit-transform: translateY(0);
            transform: translateY(0);
        }
    }

    .wobble-vertical-on-hover {
        display: inline-block;
        vertical-align: middle;
        -webkit-transform: perspective(1px) translateZ(0);
        transform: perspective(1px) translateZ(0);
        box-shadow: 0 0 1px rgba(0, 0, 0, 0);
    }

    .wobble-vertical-on-hover:hover,
    .wobble-vertical-on-hover:focus,
    .wobble-vertical-on-hover:active {
        -webkit-animation-name: wobble-vertical-on-hover;
        animation-name: wobble-vertical-on-hover;
        -webkit-animation-duration: 1s;
        animation-duration: 1s;
        -webkit-animation-timing-function: ease-in-out;
        animation-timing-function: ease-in-out;
        -webkit-animation-iteration-count: 1;
        animation-iteration-count: 1;
    }
</style>
@endsection

@section('content')
<section class="content">
    <!-- Info boxes -->

    <div class="row" style="padding: 5px 20px;">
        <div class="col-md-3 col-sm-6 col-xs-12 wobble-vertical-on-hover">
            <a href="{{ url('clients') }}" style="color: black;">
                <div class="info-box" style="background-color: #ffc107">
                    <span class="info-box-icon"><i class="fas fa-users" style="color: white"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Clients</span>
                        <span class="info-box-number">{{ @$clientcount }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12 wobble-vertical-on-hover">
            <a href="{{ url('supervisors') }}" style="color: black;">
                <div class="info-box" style="background-color: #DC3545">
                    <span class="info-box-icon"><i class="fa fa-hard-hat" style="color: white"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">SuperVisors</span>
                        <span class="info-box-number">{{ @$supervisorcount }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </a>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12 wobble-vertical-on-hover">
            <a href="{{ url('sites') }}" style="color: black;">
                <div class="info-box" style="background-color: #6610f2">
                    <span class="info-box-icon"><i class="nav-icon fas fa-map-marked-alt" style="color: white"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">
                            Total Sites
                        </span>
                        <span class="info-box-number">
                            {{ @$sitecount }}
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </a>
            <!-- /.info-box -->
        </div>

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12 wobble-vertical-on-hover">
            <a href="{{ url('vendors') }}" style="color: black;">
                <div class="info-box" style="background-color: #007bff">
                    <span class="info-box-icon"><i class="nav-icon fas fa-store" style="color: white"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Vendors</span>
                        <span class="info-box-number">{{ @$vendorcount }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </a>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12 wobble-vertical-on-hover">
            <a href="{{ url('purchase/add') }}" style="color: black;">
                <div class="info-box" style="background-color: #4099ff">
                    <span class="info-box-icon"><i class="nav-icon fas fa-shopping-cart" style="color: white"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">
                            New Purchase
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </a>
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12 wobble-vertical-on-hover">
            <a href="{{ url('purchases/return/add') }}" style="color: black;">
                <div class="info-box" style="background-color: #fd7e14">
                    <span class="info-box-icon"><i class="fas fa-undo" style="color: white"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">New Purchase</span>
                        <span class="info-box-text"> Return</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </a>
            <!-- /.info-box -->
        </div>

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12 wobble-vertical-on-hover">
            <a href="{{ url('receiveable/add') }}" style="color: black;">
                <div class="info-box" style="background-color: #006466">
                    <span class="info-box-icon"><i class="nav-icon fas fa-people-arrows" style="color: white"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">New Receivable</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </a>
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12 wobble-vertical-on-hover">
            <a href="{{ url('expense/add') }}" style="color: black;">
                <div class="info-box" style="background-color: #6f42c1">
                    <span class="info-box-icon bg-aqua"><i class="nav-icon fas fa-receipt" style="color: white"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">New Expense</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12 wobble-vertical-on-hover">
            <a href="{{ url('task/add') }}" style="color: black;">
                <div class="info-box" style="background-color: #2ed8b6">
                    <span class="info-box-icon"><i class="fas fa-tasks" style="color: white"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">New Task</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </a>
        </div>
        <!-- /.col -->
    </div>
    <!-- end row -->
    <div class="row" style="padding: 5px 20px;">
        <div class="col-md-4">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">Purchase Chart</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="purchasepieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Expense vs Receivable</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Expense Chart</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="expensepieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>

    <div class="row" style="padding: 5px 20px;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Pending Purchase :</h3>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ url('purchases') }}" class="btn btn-info btn-sm float-right">View All</a>
                        </div>
                    </div>
                    <div style="overflow-x: auto">
                        <table class="table table-striped table-bordered" id="purchase">
                            <thead>
                                <tr class="text-center">
                                    <th>Vendor</th>
                                    <th>Site</th>
                                    <th>Material</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h3>Pending Purchase Return:</h3>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ url('purchase/returns') }}" class="btn btn-info btn-sm float-right">View All</a>
                        </div>
                    </div>
                    <div style="overflow-x: auto">
                        <table class="table table-striped table-bordered" id="purchaseReturn">
                            <thead>
                                <tr class="text-center">
                                    <th>Vendor</th>
                                    <th>Site</th>
                                    <th>Material (Brand) (Quality)</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h3>Pending Receivable: </h3>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ url('receiveables') }}" class="btn btn-info btn-sm float-right">View All</a>
                        </div>
                    </div>
                    <div style="overflow-x: auto">
                        <table class="table table-striped table-bordered" id="receiveable">
                            <thead>
                                <tr class="text-center">
                                    <th>Site Name</th>
                                    <th>Cheque No./Transaction No.</th>
                                    <th>Payment Mode</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h3>Pending Expenses: </h3>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ url('expenses') }}" class="btn btn-info btn-sm float-right">View All</a>
                        </div>
                    </div>
                    <div style="overflow-x: auto">
                        <table class="table table-striped table-bordered" id="expense">
                            <thead>
                                <tr class="text-center">
                                    <th>Vendor</th>
                                    <th>Cheque No./Transaction No.</th>
                                    <th>Payment Mode</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection