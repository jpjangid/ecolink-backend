@extends('layouts.main')

@section('title', 'Add Static Value')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Add Static Value</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/staticvalues') }}" class="btn btn-info mt-o" style="float: right;">Back</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div id="loader"></div>

    <div class="card">
        <div class="card-body">
            <form action="{{ url('admin/staticvalues/store') }}" method="post">
                @csrf
                <div class="row">

                    <!-- Name -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="name"><span style="color: red;">* </span>Name</label>
                            <input type="text" class="form-control form-control-solid @error('name') is-invalid @enderror" name="name" id="name" placeholder="Please Enter Name" value="{{ old('name') }}">
                            @error('name')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Type -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="type"><span style="color: red;">* </span>Type</label>
                            <input type="text" class="form-control form-control-solid @error('type') is-invalid @enderror" name="type" id="type" placeholder="Please Enter Type" value="{{ old('type') }}">
                            @error('type')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Value -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="value"><span style="color: red;">* </span>Value</label>
                            <input type="text" class="form-control form-control-solid @error('value') is-invalid @enderror" name="value" id="value" placeholder="Please Enter Value" value="{{ old('value') }}">
                            @error('value')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mt-2">
                        <button type="submit" class="btn btn-info">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection