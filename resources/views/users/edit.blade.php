@extends('layouts.main')

@section('title', 'Edit User')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Edit User</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/users') }}" class="btn btn-info mt-o" style="float: right;">Back</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card">
        <div class="card-body">
            <form method="post" action="{{ url('admin/users/update', $id) }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <label for="name"><span style="color: red;">* </span> Full Name:</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter Full Name" value="{{ $user->name }}" />
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="email"><span style="color: red;">* </span>Email:</label>
                        <input type="email" class="form-control" name="email" placeholder="Enter Email" value="{{ $user->email }}" />
                        @error('email')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="mobile"><span style="color: red;">* </span>Mobile No:</label>
                        <input type="number" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" class="form-control" name="mobile" placeholder="Enter Mobile No." value="{{ $user->mobile }}" />
                        @error('mobile')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="address"><span style="color: red;">* </span>Address:</label>
                        <input type="text" class="form-control" name="address" placeholder="Enter Address" value="{{ $user->address }}" />
                        @error('address')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="country"><span style="color: red;">* </span>Country:</label>
                        <input type="text" class="form-control" name="country" placeholder="Enter Country" value="{{ $user->country }}" />
                        @error('country')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="state"><span style="color: red;">* </span>State:</label>
                        <input type="text" class="form-control" name="state" placeholder="Enter State" value="{{ $user->state }}" />
                        @error('state')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="city"><span style="color: red;">* </span>City:</label>
                        <input type="text" class="form-control" name="city" placeholder="Enter City" value="{{ $user->city }}" />
                        @error('city')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="pincode"><span style="color: red;">* </span>Postal:</label>
                        <input type="text" class="form-control" name="pincode" placeholder="Enter Pincode" value="{{ $user->pincode }}" />
                        @error('pincode')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="role_id"><span style="color: red;">* </span>Role:</label>
                        <select class="form-control select2bs4" name="role_id" id="role_id">
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                            <option value="{{$role->id}}" {{$user->role_id == $role->id ? 'selected' : ''}}>{{$role->name}}</option>
                            @endforeach
                        </select>
                        @error('role_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="profile_image">Profile Image:</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" name="profile_image">
                            <label class="custom-file-label" for="inputGroupFile01">Choose File</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="password">Password:</label>
                        <input type="text" class="form-control" name="password" placeholder="Enter Password" value="{{ old('password') }}" />
                        @error('password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-12 mt-2">
                        <button type="submit" class="btn btn-info">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script type=text/javascript>
    if ($("#state > option:selected").val() != "") {
        var state = $("#state > option:selected").val();
        getCity(state);
    }

    $("body").on("change", "#state", function() {
        var state = $(this).val();
        getCity(state);

    });

    $("body").on("change", "#city", function() {
        var city = $(this).val();
        getPincode(city);

    });

    function getCity(state) {
        var state = state;
        var old_city = "{{ $user->city }}";
        $("#city").html('');
        $("#pincode").html('');
        $("#pincode").html("<option value=''>Select Pincode</option>");
        $.ajax({
            url: "{{url('citylist')}}",
            type: "POST",
            data: {
                state: state,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(result) {
                $("#city").html("<option value=''>Select City</option>");
                $.each(result.cities, function(key, value) {
                    if (old_city == value.city) {
                        $("#city").append('<option selected value="' + value.city + '">' + value.city + '</option>');
                        getPincode(value.city);
                    } else {
                        $("#city").append('<option value="' + value.city + '">' + value.city + '</option>');
                    }
                });
            }
        });
    }

    function getPincode(city) {
        var city = city;
        var old_pincode = "{{ $user->pincode }}";
        $("#pincode").html('');
        $.ajax({
            url: "{{url('pincodelist')}}",
            type: "POST",
            data: {
                city: city,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(result) {
                $("#pincode").html("<option value=''>Select Pincode</option>");
                $.each(result.pincodes, function(key, value) {
                    if (old_pincode == value.pincode) {
                        $("#pincode").append('<option selected value="' + value.pincode + '">' + value.pincode + '</option>');
                    } else {
                        $("#pincode").append('<option value="' + value.pincode + '">' + value.pincode + '</option>');
                    }
                });
            }
        });
    }
</script>
@endsection