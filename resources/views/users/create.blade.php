@extends('layouts.main')

@section('title', 'Add User')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Add User</h1>
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
            <form method="post" action="{{ url('admin/users/store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <label for="name"><span style="color: red;">* </span> Full Name:</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter full name" value="{{ old('name') }}" />
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="email"><span style="color: red;">* </span>Email:</label>
                        <input type="email" class="form-control" name="email" placeholder="Enter email" value="{{ old('email') }}" />
                        @error('email')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="mobile"><span style="color: red;">* </span>Mobile No:</label>
                        <input type="number" class="form-control" name="mobile" placeholder="Enter mobile number" value="{{ old('mobile') }}" />
                        @error('mobile')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="address"><span style="color: red;">* </span>Address:</label>
                        <input type="text" class="form-control" name="address" placeholder="Enter address" value="{{ old('address') }}" />
                        @error('address')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="country"><span style="color: red;">* </span>Country:</label>
                        <input readonly type="text" class="form-control" name="country" placeholder="Enter country" value="India" />
                        @error('country')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="state"><span style="color: red;">* </span>State:</label>
                        <select class="form-control select2bs4" name="state" id="state">
                            <option value="">Select State</option>
                            @foreach($locations as $location)
                            <option value="{{ $location->state }}" {{ old('state') == $location->state ? "selected" : "" }}>{{ $location->state }}</option>
                            @endforeach
                        </select>
                        @error('state')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="city"><span style="color: red;">* </span>City:</label>
                        <select class="form-control select2bs4" name="city" id="city">
                            <option value="">Select City</option>
                        </select>
                        @error('city')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="pincode"><span style="color: red;">* </span>Postal:</label>
                        <select class="form-control select2bs4" name="pincode" id="pincode">
                            <option value="">Select Postal</option>
                        </select>
                        @error('pincode')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="role_id"><span style="color: red;">* </span>Role:</label>
                        <select class="form-control select2bs4" name="role_id" id="role_id">
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                            <option value="{{$role->id}}" {{old('role_id') == $role->id ? 'selected' : ''}}>{{$role->name}}</option>
                            @endforeach
                        </select>
                        @error('role_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="profile_image"><span style="color: red;">* </span>Profile Image:</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" name="profile_image">
                            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="password"><span style="color: red;">* </span>Password:</label>
                        <input type="text" class="form-control" name="password" placeholder="Enter Password" value="{{ old('password') }}" />
                        @error('password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-12 mt-2">
                        <button type="submit" class="btn btn-info">Register</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script type=text/javascript>
    $('#state').on('change', function() {
        var state = this.value;
        $("#city").html('');
        $.ajax({
            url: "{{url('citylist')}}",
            type: "POST",
            data: {
                state: state,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(result) {
                $('#city').html('<option value="">Select City</option>');
                $.each(result.cities, function(key, value) {
                    $("#city").append('<option value="' + value.city + '">' + value.city + '</option>');
                });
            }
        });
    });

    $('#city').on('change', function() {
        var city = this.value;
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
                $('#pincode').html('<option value="">Select Pincode</option>');
                $.each(result.pincodes, function(key, value) {
                    $("#pincode").append('<option value="' + value.pincode + '">' + value.pincode + '</option>');
                });
            }
        });
    });

    if ($("#state > option:selected").val() != "") {
        var state = $("#state > option:selected").val();
        getCity(state);
    }

    function getCity(state) {
        var state = state;
        var old_city = "{{ old('city') }}";
        $("#city").html('');
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
        var old_pincode = "{{ old('pincode') }}";
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