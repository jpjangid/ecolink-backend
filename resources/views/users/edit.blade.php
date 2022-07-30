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
    <div id="loader"></div>
    <div class="card">
        <div class="card-body">
            <form method="post" id="addData" action="{{ url('admin/users/update', $id) }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <img src="{{ $user->profile_image != null ? asset('storage/profile_image/'.$user->profile_image) :asset('default.jpg') }}" class="img-rounded" alt="{{ $user->name }}" width="150" height="150" id="blah"><br>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="email"><span style="color: red;">* </span>Email:</label>
                        <input type="email" class="form-control" name="email" placeholder="Enter Email" value="{{ $user->email }}" readonly />
                        @error('email')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="name"><span style="color: red;">* </span> Full Name:</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Enter Full Name" value="{{ $user->name }}" />
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="company_name"><span style="color: red;">* </span> Company Name:</label>
                        <input type="text" class="form-control" name="company_name" id="company_name" placeholder=" Enter Company Name" value="{{ $user->company_name }}" />
                        @error('company_name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="mobile"><span style="color: red;">* </span>Mobile No:</label>
                        <input type="number" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" class="form-control" name="mobile" id="mobile" placeholder="Enter Mobile No." value="{{ $user->mobile }}" />
                        @error('mobile')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="address"><span style="color: red;">* </span>Address:</label>
                        <input type="text" class="form-control" name="address" id="address" placeholder="Enter Address" value="{{ $user->address }}" />
                        @error('address')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- <div class="col-md-4 mt-2">
                        <label for="landmark">Landmark:</label>
                        <input type="text" class="form-control" name="landmark" id="landmark" placeholder="Enter Landmark" value="{{ $user->landmark }}" />
                        @error('landmark')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div> --}}
                    <div class="col-md-4 mt-2">
                        <label for="country"><span style="color: red;">* </span>Country:</label>
                        <input type="text" class="form-control" name="country" id="country" placeholder="Enter Country" value="{{ $user->country }}" />
                        @error('country')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="state"><span style="color: red;">* </span>State:</label>
                        <input type="text" class="form-control" name="state" id="state" placeholder="Enter State" value="{{ $user->state }}" />
                        @error('state')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="city"><span style="color: red;">* </span>City:</label>
                        <input type="text" class="form-control" name="city" id="city" placeholder="Enter City" value="{{ $user->city }}" />
                        @error('city')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="pincode"><span style="color: red;">* </span>Zip Code:</label>
                        <input type="text" class="form-control" name="pincode" id="pincode" placeholder="Enter Zip Code" value="{{ $user->pincode }}" />
                        @error('pincode')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="role_id"><span style="color: red;">* </span>Role:</label>
                        <select class="form-control select2bs4" name="role_id" id="role_id">
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                            <option value="{{$role->id}}" {{$user->role_id == $role->id ? 'selected' : ''}}>{{$role->title}}</option>
                            @endforeach
                        </select>
                        @error('role_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="password">Password:</label>
                        <input type="text" class="form-control" name="password" placeholder="Enter Password" value="{{ old('password') }}" />
                        @error('password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="flag">Active:</label>
                        <select class="form-control select2bs4" name="flag" id="flag">
                            <option value="">Select</option>
                            <option value="0" {{$user->flag == 0 ? 'selected' : ''}}>Yes</option>
                            <option value="1" {{$user->flag == 1 ? 'selected' : ''}}>No</option>
                        </select>
                        @error('flag')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="tax_exempt">Tax Exempt:</label>
                        <select class="form-control select2bs4" name="tax_exempt" id="tax_exempt">
                            <option value="">Select</option>
                            <option value="1" {{$user->tax_exempt == 1 ? 'selected' : ''}}>Yes</option>
                            <option value="0" {{$user->tax_exempt == 0 ? 'selected' : ''}}>No</option>
                        </select>
                        @error('tax_exempt')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label class="form-label" for="validity_date">Tax Exempt Validity Date</label>
                            <input type="date" class="form-control form-control-solid @error('validity_date') is-invalid @enderror" name="validity_date" id="validity_date" placeholder="Please Enter Tax Exempt Validity Date" value="{{ $user->validity_date }}">
                            @error('validity_date')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <label for="profile_image">Profile Image:</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" name="profile_image" onchange="readURL(this);" accept="image/x-png,image/gif,image/jpeg">
                            <label class="custom-file-label" for="inputGroupFile01">Choose File</label>
                            @error('profile_image')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12 mt-2 document" hidden="hidden">
                        <label for="files">Upload Tax Exempt Documents:</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" aria-describedby="inputGroupFileAddon01" name="files[]" multiple>
                            <label class="custom-file-label" for="inputGroupFile01">Choose File</label>
                            @error('files')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    @if($documents->isNotEmpty())
                    <div class="col-md-12 mt-2 document" hidden="hidden">
                        <label>Uploaded Documents:</label>
                        <div class="row">
                            @foreach($documents as $document)
                            @php $str = substr($document->file_name, 0, 15) . '...'; @endphp
                            @if($document->file_type != 'pdf')
                            <div class="col-md-2">
                                <img src="{{ asset('storage/documents/'.$user->id.'/'.$document->file_name) }}" class="img-rounded" alt="{{ $document->file_name }}" width="150" height="150">
                                <br>
                                <a href="{{ asset('storage/documents/'.$user->id.'/'.$document->file_name) }}" target="_blank">{{$str}}</a>
                            </div>
                            @else
                            <div class="col-md-2">
                                <object data="{{ asset('storage/documents/'.$user->id.'/'.$document->file_name) }}" type="application/pdf" width="150" height="150"></object>
                                <br>
                                <a href="{{ asset('storage/documents/'.$user->id.'/'.$document->file_name) }}" target="_blank">{{$str}}</a>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
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
<script src="{{ asset('js/validations/users/edituserrules.js') }}"></script>
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
        var check = $('#tax_exempt').val();
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