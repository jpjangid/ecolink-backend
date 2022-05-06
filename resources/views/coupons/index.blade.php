@extends('layouts.main')

@section('title', 'Coupons')

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

    <!-- <h3 class="mb-3" style="margin-bottom: 30px">Coupons <a href="/client/registraion" class="btn btn-info mt-o" style="float: right;">New Client</a></h3> -->

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Coupons</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <a class="btn btn-info mr-1 mb-1" href="{{ url()->previous() }}">Back</a>
                        <li class="breadcrumb-item"><a href="{{ url('admin/coupons/create') }}" class="btn btn-info mt-o" style="float: right;">New Coupon</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="container-fluid" style="overflow-x:auto;">
        <table id="couponTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>Coupon Name</th>
                    <th>Coupon Code</th>
                    <th>Coupon Type</th>
                    <th>Coupon Start Time</th>
                    <th>Coupon End Time</th>
                    <th>Days</th>
                    <th>Created On</th>
                    <th>Show In Front</th>
                    <th class="no-sort">Action</th>
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
    var couponTable = $('#couponTable').DataTable({
        scrollY: "55vh",
        processing: true,
        serverSide: true,
        url: "{{ url('admin/coupons') }}",
        columns: [{
                data: 'name',
                name: 'name'
            },
            {
                data: 'code',
                name: 'code'
            },
            {
                data: 'type',
                name: 'type'
            },
            {
                data: 'offer_start',
                name: 'offer_start'
            },
            {
                data: 'offer_end',
                name: 'offer_end'
            },
            {
                data: 'days',
                name: 'days'
            },
            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'active',
                name: 'active'
            },
            {
                data: 'action',
                name: 'action'
            },
        ]
    });
</script>
<script>
    $(document).on('change', '.js-switch', function() {
        var row = $(this).closest('tr');
        let show_in_front = row.find('.show_in_front').val();
        let couponId = row.find('.coupon_id').val();
        $.ajax({
            url: "{{ url('admin/coupons/update_status') }}",
            type: "POST",
            dataType: "json",
            data: {
                show_in_front: show_in_front,
                coupon_id: couponId,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                swal("Good job!", data.message, "success");
            }
        });
    });
</script>
@endsection