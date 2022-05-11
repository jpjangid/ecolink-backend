@extends('layouts.main')

@section('title', 'Pages')

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

    <!-- <h3 class="mb-3" style="margin-bottom: 30px">Pages <a href="/client/registraion" class="btn btn-info mt-o" style="float: right;">New Client</a></h3> -->

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Pages</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <a class="btn btn-info mr-1 mb-1" href="{{ url()->previous() }}">Back</a>
                        <li class="breadcrumb-item"><a href="{{ url('admin/pages/create') }}" class="btn btn-info mt-o" style="float: right;">New Page</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="container-fluid" style="overflow-x:auto;">
        <table id="pageTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Active/Inactive</th>
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
    var pageTable = $('#pageTable').DataTable({
        scrollY: "55vh",
        processing: true,
        serverSide: true,
        url: "{{ url('admin/pages') }}",
        columns: [{
                data: 'title',
                name: 'title'
            },
            {
                data: 'slug',
                name: 'slug'
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
        let status = row.find('.js-switch').val();
        let pageId = row.find('.page_id').val();
        $.ajax({
            url: "{{ url('admin/pages/update_status') }}",
            type: "POST",
            dataType: "json",
            data: {
                status: status,
                page_id: pageId,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                if (data['msg'] == 'success') {
                    swal({
                        title: 'Active!',
                        text: "Page status updated successfully.",
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result) {
                            location.reload();
                        }
                    })
                } else {
                    swal({
                        title: 'Inactive',
                        text: "Page status updated successfully.",
                        icon: 'error',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'OK'
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