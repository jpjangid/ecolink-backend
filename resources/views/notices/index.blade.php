@extends('layouts.main')

@section('title', 'Notice')

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

    <!-- <h3 class="mb-3" style="margin-bottom: 30px">Notice <a href="/client/registraion" class="btn btn-info mt-o" style="float: right;">New Client</a></h3> -->

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Notice</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <a class="btn btn-info mr-1 mb-1" href="{{ url()->previous() }}">Back</a>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="container-fluid" style="overflow-x:auto;">
        <table id="noticeTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>Title</th>
                    <th>Active</th>
                    <th class="no-sort">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notices as $notice)
                <tr>
                    <td>{{ $notice->title }}</td>
                    <td>
                        <div class="form-check form-switch form-check-custom form-check-solid">
                            <input type="hidden" value="{{ $notice->id }}" class="notice_id">
                            <input type="checkbox" class="form-check-input js-switch  h-20px w-30px" id="customSwitch1" name="status" value="{{ $notice->status }}" {{ $notice->status == '1' ? 'checked' : '' }}>
                        </div>
                    </td>
                    <td><a class="btn btn-primary btn-xs ml-1" href="{{url('admin/notices/edit', $notice->id)}}"><i class="fas fa-edit"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript">
    $(document).on('change', '.js-switch', function() {
        var row = $(this).closest('tr');
        let status = row.find('.js-switch').val();
        let noticeId = row.find('.notice_id').val();
        $.ajax({
            url: "{{ url('admin/notices/update_status') }}",
            type: "POST",
            dataType: "json",
            data: {
                status: status,
                notice_id: noticeId,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                swal("Good job!", data.message, "success");
            }
        });
    });
</script>
@endsection