@extends('layouts.main')

@section('title', 'Edit News Letter')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Edit News Letter</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/newsletters') }}" class="btn btn-info mt-o" style="float: right;">Back</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div id="loader"></div>

    <div class="card">
        <div class="card-body">
            <form action="{{ url('admin/newsletters/update', $id) }}" method="post">
                @method('PUT')
                @csrf
                <div class="row">

                    <!-- Email -->
                    <div class="col-md-6 mb-4">
                        <div class="form-group">
                            <label class="required form-label" for="email">Email</label>
                            <input type="email" class="form-control form-control-solid @error('email') is-invalid @enderror" name="email" id="email" placeholder="Please Enter Email" value="{{ $letter->email }}">
                            @error('email')
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