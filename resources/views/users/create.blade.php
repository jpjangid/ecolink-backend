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
    <div id="loader"></div>

    <div class="card">
        <div class="card-body">
            <form method="post" id="addData" action="{{ url('admin/users/store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <img src="{{ asset('default.jpg') }}" class="img-rounded" width="150" height="150" id="blah"><br>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="email"><span style="color: red;">* </span>Email:</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder=" Enter Email" value="{{ old('email') }}" />
                        @error('email')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="name"><span style="color: red;">* </span> Full Name:</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder=" Enter Full Name" value="{{ old('name') }}" />
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="company_name"><span style="color: red;">* </span> Company Name:</label>
                        <input type="text" class="form-control" name="company_name" id="company_name" placeholder=" Enter Company Name" value="{{ old('company_name') }}" />
                        @error('company_name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="mobile"><span style="color: red;">* </span>Mobile No:</label>
                        <input type="number" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" class="form-control" name="mobile" id="mobile" placeholder="Enter Mobile No." value="{{ old('mobile') }}" />
                        @error('mobile')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="address"><span style="color: red;">* </span>Address:</label>
                        <input type="text" class="form-control" name="address" id="address" placeholder="Enter Address" value="{{ old('address') }}" />
                        @error('address')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- <div class="col-md-4 mt-2">
                        <label for="landmark">Landmark:</label>
                        <input type="text" class="form-control" name="landmark" id="landmark" placeholder="Enter Landmark" value="{{ old('landmark') }}" />
                        @error('landmark')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div> --}}
                    <div class="col-md-4 mt-2">
                        <label for="country"><span style="color: red;">* </span>Country:</label>
                        <input type="text" class="form-control" name="country" id="country" placeholder="Enter Country" value="{{ old('country') }}" />
                        @error('country')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="state"><span style="color: red;">* </span>State:</label>
                        <input type="text" class="form-control" name="state" id="state" placeholder="Enter State" value="{{ old('state') }}" />
                        @error('state')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="city"><span style="color: red;">* </span>City:</label>
                        <input type="text" class="form-control" name="city" id="city" placeholder="Enter City" value="{{ old('city') }}" />
                        @error('city')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="pincode"><span style="color: red;">* </span>Zip Code:</label>
                        <input type="text" class="form-control" name="pincode" id="pincode" placeholder="Enter Zip Code" value="{{ old('pincode') }}" />
                        @error('pincode')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="role_id"><span style="color: red;">* </span>Role:</label>
                        <select class="form-control" name="role_id" id="role_id">
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                            <option value="{{$role->id}}" {{ old('role_id')==$role->id ? 'selected' :
                                ''}}>{{$role->title}}</option>
                            @endforeach
                        </select>
                        @error('role_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="password"><span style="color: red;"> </span>Password:</label>
                        <input type="text" class="form-control" name="password" placeholder="Enter Password" value="{{ old('password') }}" />
                        @error('password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="flag"><span style="color: red;">* </span>Active:</label>
                        <select class="form-control" name="flag" id="flag">
                            <option value="">Select</option>
                            <option value="0" <?php old('flag') == 0 ? 'selected' : '' ?>>Yes</option>
                            <option value="1" <?php old('flag') == 1 ? 'selected' : '' ?>>No</option>
                        </select>
                        @error('flag')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="tax_exempt"><span style="color: red;">* </span>Tax Exempt:</label>
                        <select class="form-control" name="tax_exempt" id="tax_exempt">
                            <option value="">Select</option>
                            <option value="1" <?php old('tax_exempt') == 1 ? 'selected' : '' ?>>Yes</option>
                            <option value="0" <?php old('tax_exempt') == 0 ? 'selected' : '' ?>>No</option>
                        </select>
                        @error('tax_exempt')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <input type="hidden" id="oldtax_exempt" value="{{ old('tax_exempt') }}">
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label class="form-label" for="validity_date">Tax Exempt Validity Date</label>
                            <input type="date" class="form-control form-control-solid @error('validity_date') is-invalid @enderror" name="validity_date" id="validity_date" placeholder="Please Enter Tax Exempt Validity Date" value="{{ old('validity_date') }}">
                            @error('validity_date')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <label for="profile_image"><span style="color: red;">* </span>Profile Image:</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" name="profile_image" onchange="readURL(this);" accept="image/x-png,image/gif,image/jpeg">
                            <label class="custom-file-label" for="inputGroupFile01">Choose File</label>
                            @error('profile_image')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12 mt-2 document" hidden="hidden">
                        <label for="files"><span style="color: red;">* </span>Upload Tax Exempt Documents:</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" aria-describedby="inputGroupFileAddon01" name="files[]" multiple>
                            <label class="custom-file-label" for="inputGroupFile01">Choose File</label>
                            @error('files')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <input type="hidden" name="status" id="flag" value="{{ old('flag') }}">
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
<script src="{{ asset('js/validations/users/adduserrules.js') }}"></script>
<script type=text/javascript>
    $('#email').on('change', function() {
        var email = this.value;
        $.ajax({
            url: "{{url('admin/users/userinfo')}}",
            type: "POST",
            data: {
                email: email,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(result) {
                if (result.flag == '1') {
                    document.getElementById("name").value = result.name;
                    document.getElementById("mobile").value = result.mobile;
                    document.getElementById("address").value = result.address;
                    document.getElementById("landmark").value = result.landmark;
                    document.getElementById("country").value = result.country;
                    document.getElementById("state").value = result.state;
                    document.getElementById("city").value = result.city;
                    document.getElementById("pincode").value = result.pincode;
                    document.getElementById("flag").value = result.flag;
                    $('#role_id').select2("trigger", 'select', {
                        data: {
                            id: result.role_id
                        }
                    });
                }
            }
        });
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#blah').attr('src', e.target.result).width(150).height(150);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<script>
    $(document).ready(function() {
        var check = $('#oldtax_exempt').val();
        if (check != "" && check != "0") {
            $('.document').removeAttr('hidden');
        } else {
            $(".document").attr("hidden", "hidden");
        }
    });
    $('body').on('change', '#tax_exempt', function() {
        if ($(this).val() != "" && $(this).val() != "0") {
            $('.document').removeAttr('hidden');
        } else {
            $(".document").attr("hidden", "hidden");
            $("#panno").val("");
        }
    });
</script>
@endsection