@extends('layouts.main')

@section('title', 'Edit User Permissions')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Edit User Permissions</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/userpermissions') }}" class="btn btn-info mt-o" style="float: right;">Back</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card">
        <div class="card-body">
            <form class="form" action="{{ url('userpermissions/update',$id) }}" method="post">
                @method('PUT')
                @csrf
                <div class="card-body">
                    <table class="table table-separate" style="border: 0px !important;background-color:#2f2f2f;">
                        <thead>
                            <tr>
                                <td style="color:white;" colspan="3">Select All Permissions</td>
                                <input type="hidden" id="user_id" value="{{$id}}">
                                <input type="hidden" id="role_id" value="{{$role_id}}">
                                <td style="border-bottom: 0px !important"><input type="checkbox" class="permission_id form-control-input" value="all"></label></td>
                                <td style="color:white;" colspan="3">Delete All Permissions</td>
                                <td style="border-bottom: 0px !important"><input type="checkbox" class="permission_id form-control-input" value="unselectall"></label></td>
                            </tr>
                        </thead>
                    </table>
                    <table class="table table-separate">
                        <thead>
                            <tr>
                                <th>Operations</th>
                                <th>View</th>
                                <th>Create</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Select All Permissions</td>
                                <input type="hidden" id="user_id" value="{{$id}}">
                                <input type="hidden" id="role_id" value="{{$role_id}}">
                                <td><input type="checkbox" class="permission_id form-control-input" value="all"></label></td>
                                <td>Delete All Permissions</td>
                                <td colspan="2"><input type="checkbox" class="permission_id form-control-input" value="unselectall"></label></td>
                            </tr>
                            @foreach($names as $name)
                            <tr>
                                @if($name->title != 'Role' && $name->title != 'Permission')
                                <td style="border-bottom: 0px !important">{{$name->title}}</td>
                                @endif
                                @foreach($permissions as $permission)
                                @if($permission->title == $name->title)
                                @if($permission->title != 'Role' && $permission->title != 'Permission')
                                @if(strpos($permission->name, 'index') !== false)
                                <td style="border-bottom: 0px !important">
                                    <input type="hidden" id="user_id" value="{{$id}}">
                                    <input type="hidden" id="role_id" value="{{$role_id}}">
                                    <input type="checkbox" class="permission_id form-control-input" value="{{$permission->id}}" {{in_array($permission->id,$data) == '1' ? 'checked' : ''}}>
                                </td>
                                @endif
                                @if(strpos($permission->name, 'create') !== false)
                                <td style="border-bottom: 0px !important">
                                    <input type="hidden" id="user_id" value="{{$id}}">
                                    <input type="hidden" id="role_id" value="{{$role_id}}">
                                    <input type="checkbox" class="permission_id form-control-input" value="{{$permission->id}}" {{in_array($permission->id,$data) == '1' ? 'checked' : ''}}>
                                </td>
                                @endif
                                @if(strpos($permission->name, 'edit') !== false)
                                <td style="border-bottom: 0px !important">
                                    <input type="hidden" id="user_id" value="{{$id}}">
                                    <input type="hidden" id="role_id" value="{{$role_id}}">
                                    <input type="checkbox" class="permission_id form-control-input" value="{{$permission->id}}" {{in_array($permission->id,$data) == '1' ? 'checked' : ''}}>
                                </td>
                                @endif
                                <!-- @if(strpos($permission->name, 'show') !== false)
                                <td style="border-bottom: 0px !important">
                                    <input type="hidden" id="user_id" value="{{$id}}">
                                    <input type="hidden" id="role_id" value="{{$role_id}}">
                                    <input type="checkbox" class="permission_id form-control-input" value="{{$permission->id}}" {{in_array($permission->id,$data) == '1' ? 'checked' : ''}}>
                                </td>
                                @endif -->
                                @if(strpos($permission->name, 'destroy') !== false)
                                <td style="border-bottom: 0px !important">
                                    <input type="hidden" id="user_id" value="{{$id}}">
                                    <input type="hidden" id="role_id" value="{{$role_id}}">
                                    <input type="checkbox" class="permission_id form-control-input" value="{{$permission->id}}" {{in_array($permission->id,$data) == '1' ? 'checked' : ''}}>
                                </td>
                                @endif
                                @endif
                                @endif
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    $('.permission_id').on('change', function() {
        var permission_id = this.value;
        var user_id = $('#user_id').val();
        var role_id = $('#role_id').val();
        $.ajax({
            url: "{{url('admin/userpermissions/store')}}",
            type: "POST",
            data: {
                permission_id: permission_id,
                user_id: user_id,
                role_id: role_id,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(data) {
                if (data['msg'] == 'success') {
                    swal("Success!", "Permission Added!", "success").then((value) => {
                        location.reload();
                    });
                } else {
                    swal("Danger!", "Permission Deleted!", "error").then((value) => {
                        location.reload();
                    });
                }
            }
        });
    });
</script>
@endsection