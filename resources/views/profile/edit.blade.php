@extends('layouts.main')

@section('title', 'Edit Profile')

@section('content')
<div class="content">
    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ $message }}
    </div>
    @endif

    @if ($message = Session::get('danger'))
    <div class="alert alert-danger alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ $message }}
    </div>
    @endif
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Edit Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/')}}" class="btn btn-info mt-o" style="float: right;">Home</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div id="loader"></div>
    <div class="card">
        <div class="card-body">
            <form method="post" action="{{ url('admin/profile/update',auth()->user()->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <img src="{{ auth()->user()->profile_image != null ? asset('storage/profile_image/'.auth()->user()->profile_image) :asset('default.jpg') }}" class="img-rounded" alt="{{ auth()->user()->name }}" width="304" height="236"><br>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="name"><span style="color: red;">* </span> Email : </label>
                        <input type="email" class="form-control" name="email" placeholder="Enter your email" value="{{ auth()->user()->email }}" />
                        @error('email')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="" class="col-md-12 text-left">Profile Picture :</label>
                        <div class="custom-file col-md-12">
                            <input type="file" class="custom-file-input" name="profile_image" id="exampleInputFile">
                            <label class="custom-file-label" for="exampleInputFile">Profile Picture</label>
                        </div>
                        @error('profile_image')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="name">Password : </label>
                        <input type="password" class="form-control" name="password" placeholder="Enter your passowrd" value="" />
                        @error('password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="name">Confirm Password : </label>
                        <input type="password" class="form-control" name="password_confirmation" placeholder="Enter your confirm password" value="" />
                        @error('password_confirmation')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-12 mt-2">
                        <button type="submit" class="btn btn-info" style="float: right;">Update Profile</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection