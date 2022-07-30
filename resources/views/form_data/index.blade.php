@extends('layouts.main')

@section('title', 'Froms List')

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
                    <h1 class="m-0 text-dark">{{ ucwords(str_replace("_"," ",$form_data_id)) }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <a class="btn btn-info mr-1 mb-1" href="{{ url()->previous() }}">Back</a>
                    </ol>
                    <div class="row">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6">
                            <select id="formId" class="form-control" hidden>
                                @foreach($form_ids as $form_id)
                                    <option value="{{ $form_id }}" {{ $form_id == $form_data_id  ? 'selected' : ''}}>{{ ucwords(str_replace("_"," ",$form_id)) }}</option>    
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="container-fluid" style="overflow-x:auto;">
        <table id="formTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>Form Id</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email Address</th>
                    <th>Mobile</th>
                    <th>Date/Time</th>
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

    $(document).on('change', '#formId', function() {
        datatable();
    });

    
    let formTable = null;
    function datatable() {
        formTable = $('#formTable').DataTable({
            destroy: true,
            scrollY: "70vh",
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "{{ url('admin/forms/list') }}",
                type: "get",
                data: function(d) {
                    d.form_id = $('#formId').val();
                },
            },
            columns: [{
                    data: 'form_id',
                    name: 'form_id'
                },
                {
                    data: 'first_name',
                    name: 'first_name'
                },
                {
                    data: 'last_name',
                    name: 'last_name'
                },
                {
                    data: 'email_address',
                    name: 'email_address'
                },
                {
                    data: 'mobile_number',
                    name: 'mobile_number'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ]
        });
    }
</script>
@endsection