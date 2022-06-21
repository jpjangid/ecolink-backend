@extends('layouts.main')

@section('title', 'Sub Categories')

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

    <!-- <h3 class="mb-3" style="margin-bottom: 30px">Sub Categories <a href="/client/registraion" class="btn btn-info mt-o" style="float: right;">New Client</a></h3> -->

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Sub Categories</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <a class="btn btn-info mr-1 mb-1" href="{{ url()->previous() }}">Back</a>
                        <li class="breadcrumb-item"><a href="{{ url('admin/sub/categories/create') }}" class="btn btn-info mt-o" style="float: right;">New Sub Category</a></li>
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
        <table id="categoryTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>Category</th>
                    <th>Sub Category</th>
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
    $(document).ready(function() {
        $(document).on('click', '.sub_category_confirm', function(event) {
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

    $(function() {
        datatable();
    });

    $(document).on('change', '#active', function() {
        var id = $('#active').val();
        datatable();
    });

    function datatable() {
        var materialTable = $('#categoryTable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "{{ url('admin/sub/categories') }}",
                type: "get",
                data: function(d) {
                    d.active = $('#active').val();
                },
            },
            columns: [{
                    data: 'main',
                    name: 'main'
                },
                {
                    data: 'name',
                    name: 'name'
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
    }

    $(document).on('change', '.js-switch', function() {
        var row = $(this).closest('tr');
        let status = row.find('.js-switch').val();
        let categoryId = row.find('.category_id').val();
        $.ajax({
            url: "{{ url('admin/sub/categories/update_status') }}",
            type: "POST",
            dataType: "json",
            data: {
                status: status,
                category_id: categoryId,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                if (data['msg'] == 'success') {
                    swal({
                        title: 'Active!',
                        text: "Sub Category status updated successfully.",
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
                        text: "Sub Category status updated successfully.",
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