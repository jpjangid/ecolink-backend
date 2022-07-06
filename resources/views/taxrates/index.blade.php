@extends('layouts.main')

@section('title', 'Tax Rates')

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

    <!-- <h3 class="mb-3" style="margin-bottom: 30px">Tax Rates <a href="/client/registraion" class="btn btn-info mt-o" style="float: right;">New Client</a></h3> -->

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Tax Rates</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <a class="btn btn-info mr-1 mb-1" href="{{ url()->previous() }}">Back</a>
                        <li class="breadcrumb-item"><a href="{{ url('admin/taxrates/create') }}" class="btn btn-info mt-o" style="float: right;">New Tax Rate</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="container-fluid" style="overflow-x:auto;">
        <table id="taxrateTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>Country</th>
                    <th>State</th>
                    <th>City</th>
                    <th>Zip</th>
                    <th>Rate</th>
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
        $(document).on('click', '.tax_confirm', function(event) {
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
    var taxrateTable = $('#taxrateTable').DataTable({
        scrollY: "70vh",
        processing: true,
        serverSide: true,
            order: [],
        url: "{{ url('admin/taxrates') }}",
        columns: [{
                data: 'country',
                name: 'country'
            },
            {
                data: 'state',
                name: 'state'
            },
            {
                data: 'city',
                name: 'city'
            },
            {
                data: 'zip',
                name: 'zip'
            },
            {
                data: 'rate',
                name: 'rate'
            },
            {
                data: 'action',
                name: 'action'
            },
        ]
    });
</script>
@endsection