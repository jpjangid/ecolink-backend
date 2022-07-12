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
                    <div class="row">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6">
                            <select id="active" class="form-control">
                                <option value="all">All</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
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
                    {{-- <th>Coupon Type</th> --}}
                    <th>Coupon Start Time</th>
                    <th>Coupon End Time</th>
                    {{-- <th>Days</th> --}}
                    <th>Created On</th>
                    <th>Active</th>
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
    $(function() {
        datatable();
    });

    $(document).on('change', '#active', function() {
        datatable();
    });

    function datatable() {
        var couponTable = $('#couponTable').DataTable({
            destroy: true,
            scrollY: "70vh",
            scrollX: true,
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "{{ url('admin/coupons') }}",
                type: "get",
                data: function(d) {
                    d.active = $('#active').val();
                },
            },
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'code',
                    name: 'code'
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
    }

    $(document).ready(function() {
        $(document).on('click', '.coupan_confirm', function(event) {
            var form = $(this).closest("form");
            var action = $(this).data("action");
            event.preventDefault();
            swal({
                    title: `Are you sure you want to delete this record?`,
                    text: "If you delete this, it will be gone forever.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
        });
    });


    $(document).on('change', '.show_in_front', function() {
        var row = $(this).closest('tr');
        let show_in_front = row.find('.show_in_front').val();
        let couponId = row.find('.coupon_id').val();
        console.log(couponId);
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
                if (data['msg'] == 'success') {
                    swal({
                        title: 'Active!',
                        text: "Coupon status updated successfully.",
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'OK',
                        timer: 3000
                    }).then((result) => {
                        if (result) {
                            location.reload();
                        }
                    })
                } else {
                    swal({
                        title: 'Inactive',
                        text: "Coupon status updated successfully.",
                        icon: 'error',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'OK',
                        timer: 3000
                    }).then((result) => {
                        if (result) {
                            location.reload();
                        }
                    })
                }
            }
        });
    });
</script>
@endsection