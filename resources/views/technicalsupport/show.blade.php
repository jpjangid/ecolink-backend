@extends('layouts.main')

@section('title', 'View')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">View</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/technicalsupport') }}" class="btn btn-info mt-o" style="float: right;">Back</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required form-label" for="first_name">First Name</label>
                        <input type="text" class="form-control" disabled name="first_name" id="first_name" placeholder="Please Enter First Name" value="{{ $contact->first_name }}">
                        @error('first_name')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required form-label" for="last_name">Last Name</label>
                        <input type="text" class="form-control" disabled name="last_name" id="last_name" placeholder="Please Enter Last Name" value="{{ $contact->last_name }}">
                        @error('last_name')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required form-label" for="phone">Phone No.</label>
                        <input type="text" class="form-control" disabled name="phone" id="phone" placeholder="Please Enter Phone No." value="{{ $contact->phone }}">
                        @error('phone')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required form-label" for="email">Email</label>
                        <input type="text" class="form-control" disabled name="email" id="email" placeholder="Please Enter Email" value="{{ $contact->email }}">
                        @error('email')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required form-label" for="address_1">Address 1</label>
                        <input type="text" class="form-control" disabled name="address_1" id="address_1" placeholder="Please Enter Address 1" value="{{ $contact->address_1 }}">
                        @error('address_1')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required form-label" for="address_2">Address 2</label>
                        <input type="text" class="form-control" disabled name="address_2" id="address_2" placeholder="Please Enter Address 2" value="{{ $contact->address_2 }}">
                        @error('address_2')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required form-label" for="city">City</label>
                        <input type="text" class="form-control" disabled name="city" id="city" placeholder="Please Enter City" value="{{ $contact->city }}">
                        @error('city')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required form-label" for="state">State</label>
                        <input type="text" class="form-control" disabled name="state" id="state" placeholder="Please Enter State" value="{{ $contact->state }}">
                        @error('state')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required form-label" for="zip">Zip</label>
                        <input type="text" class="form-control" disabled name="zip" id="zip" placeholder="Please Enter Zip" value="{{ $contact->zip }}">
                        @error('zip')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required form-label" for="country">Country</label>
                        <input type="text" class="form-control" disabled name="country" id="country" placeholder="Please Enter Country" value="{{ $contact->country }}">
                        @error('country')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="required form-label" for="input_1">What item do you wish to clean & what is the surface material?</label>
                        <input type="text" class="form-control" disabled name="input_1" id="input_1" placeholder="Please Enter" value="{{ $contact->question->input_1 }}">
                        @error('input_1')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="required form-label" for="input_2">What kind of soil, grease or coating do you wish to remove?</label>
                        <input type="text" class="form-control" disabled name="input_2" id="input_2" placeholder="Please Enter" value="{{ $contact->question->input_2 }}">
                        @error('input_2')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="required form-label" for="input_3">How do you currently clean this item?</label>
                        <input type="text" class="form-control" disabled name="input_3" id="input_3" placeholder="Please Enter" value="{{ $contact->question->input_3 }}">
                        @error('input_3')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="required form-label" for="input_4">What chemicals or products do you currently use and in what amounts?</label>
                        <input type="text" class="form-control" disabled name="input_4" id="input_4" placeholder="Please Enter" value="{{ $contact->question->input_4 }}">
                        @error('input_4')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="required form-label" for="input_5">Why are you looking to change?</label>
                        <input type="text" class="form-control" disabled name="input_5" id="input_5" placeholder="Please Enter" value="{{ $contact->question->input_5 }}">
                        @error('input_5')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="required form-label" for="input_6">Other? Please explain</label>
                        <input type="text" class="form-control" disabled name="input_6" id="input_6" placeholder="Please Enter" value="{{ $contact->question->input_6 }}">
                        @error('input_6')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="required form-label" for="input_7">Shipper Acct # (FedEx, UPS):</label>
                        <input type="text" class="form-control" disabled name="input_7" id="input_7" placeholder="Please Enter" value="{{ $contact->question->input_7 }}">
                        @error('input_7')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="required form-label" for="input_8">I'm also interested in learning more about</label>
                        <input type="text" class="form-control" disabled name="input_8" id="input_8" placeholder="Please Enter" value="{{ $contact->question->input_8 }}">
                        @error('input_8')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection