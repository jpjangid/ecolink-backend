@extends('layouts.main')

@section('title', 'Products')

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
                    <h1 class="m-0 text-dark">Products</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <a class="btn btn-info mr-1 mb-1" href="{{ url()->previous() }}">Back</a>
                        <li class="breadcrumb-item"><a href="{{ url('admin/products/create') }}" class="btn btn-info mt-o" style="float: right;">New Product</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="container-fluid" style="overflow-x:auto;">
        <table id="productTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>Product</th>
                    <th>Variant</th>
                    <th>Slug</th>
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
        $(document).ready(function() {
            var materialTable = $('#productTable').DataTable({
                scrollY: "55vh",
                processing: true,
                serverSide: true,
                url: "{{ url('admin/products') }}",
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'variant',
                        name: 'variant'
                    },
                    {
                        data: 'slug',
                        name: 'slug'
                    },
                    {
                        data: 'active',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ]
            });
        });

    });

    $(document).on('change', '.js-switch', function() {
        var row = $(this).closest('tr');
        let status = row.find('.js-switch').val();
        let productId = row.find('.product_id').val();
        $.ajax({
            url: "{{ url('admin/products/update_status') }}",
            type: "POST",
            dataType: "json",
            data: {
                status: status,
                product_id: productId,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                if (data['msg'] == 'success') {
                    swal({
                        title: 'Active!',
                        text: "Product status updated successfully.",
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
                        text: "Product status updated successfully.",
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