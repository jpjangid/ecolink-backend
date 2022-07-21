@extends('layouts.main')

@section('title', 'Add Dropship')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Add Dropship</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dropship') }}" class="btn btn-info mt-o" style="float: right;">Back</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div id="loader"></div>

    <div class="card">
        <div class="card-body">
            <form method="post" action="{{ url('admin/dropship/store') }}">
                @csrf
                <div class="row">
                
                    <div class="col-md-4">
                        <label for="name"><span style="color: red;">* </span>Name:</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder=" Enter Name" value="{{ old('name') }}" />
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                
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
                        <label for="zip"><span style="color: red;">* </span>Zip Code:</label>
                        <input type="text" class="form-control" name="zip" id="zip" placeholder="Enter Zip Code" value="{{ old('zip') }}" />
                        @error('zip')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
    
                    <div class="col-md-12 mt-2">
                        <button type="submit" class="btn btn-info">ADD</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
