@extends('layouts.main')

@section('title', 'Edit Tax Rate')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Edit Tax Rate</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/taxrates') }}" class="btn btn-info mt-o" style="float: right;">Back</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div id="loader"></div>

    <div class="card">
        <div class="card-body">
            <form action="{{ url('admin/taxrates/update', $taxrate->id) }}" id="addData" method="post">
                @method('PUT')
                @csrf
                <div class="row">

                    <!-- Country Code -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="country_code"><span style="color: red;">* </span>Country Code</label>
                            <input type="text" class="form-control form-control-solid @error('country_code') is-invalid @enderror" name="country_code" id="country_code" placeholder="Please Enter Country Code" value="{{ $taxrate->country_code }}">
                            @error('country_code')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- State Code -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="state_code"><span style="color: red;">* </span>State Code</label>
                            <input type="text" class="form-control form-control-solid @error('state_code') is-invalid @enderror" name="state_code" id="state_code" placeholder="Please Enter State Code" value="{{ $taxrate->state_code }}">
                            @error('state_code')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- State Name -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="state_name"><span style="color: red;">* </span>State Name</label>
                            <input type="text" class="form-control form-control-solid @error('state_name') is-invalid @enderror" name="state_name" id="state_name" placeholder="Please Enter State Name" value="{{ $taxrate->state_name }}">
                            @error('state_name')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- City -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="city"><span style="color: red;">* </span>City</label>
                            <input type="text" class="form-control form-control-solid @error('city') is-invalid @enderror" name="city" id="city" placeholder="Please Enter City" value="{{ $taxrate->city }}">
                            @error('city')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Zip -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="zip"><span style="color: red;">* </span>Zip</label>
                            <input type="number" class="form-control form-control-solid @error('zip') is-invalid @enderror" name="zip" id="zip" placeholder="Please Enter Zip" value="{{ $taxrate->zip }}">
                            @error('zip')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Rate -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="rate"><span style="color: red;">* </span>Rate</label>
                            <input type="number" step=".01" class="form-control form-control-solid @error('rate') is-invalid @enderror" name="rate" id="rate" placeholder="Please Enter Rate" value="{{ $taxrate->rate }}">
                            @error('rate')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
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
<script src="{{ asset('js/validations/taxRates/addtaxraterules.js') }}"></script>
@endsection