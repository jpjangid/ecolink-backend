@extends('layouts.main')

@section('title', 'Dropship Addresses')

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

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Dropship Addresses</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <a class="btn btn-info mr-1 mb-1" href="{{ url()->previous() }}">Back</a>
                        <li class="breadcrumb-item"><a href="{{ url('admin/dropship/create') }}" class="btn btn-info mt-o" style="float: right;">ADD Dropship</a></li> 
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="container-fluid" style="overflow-x:auto;">
        <table id="dropshipTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>Name</th>
                    <th>Country</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Zip</th>
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
        $(document).on('click', '.dropship', function(event) {
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
</script>

<script type="text/javascript">
    $(function() {
        var table = $('#dropshipTable').DataTable({
            scrollY: "70vh",
            processing: true,
            serverSide: true,
            order: [],
            ajax: "{{ url('admin/dropship') }}",
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'country',
                    name: 'country'
                },
                {
                    data: 'city',
                    name: 'city'
                },
                {
                    data: 'state',
                    name: 'state'
                },
                {
                    data: 'zip',
                    name: 'zip'
                },
                {
                    data: 'action',
                    name: 'action',

                    orderable: false,
                    searchable: false
                },
            ]
        });

    });
</script>
@endsection