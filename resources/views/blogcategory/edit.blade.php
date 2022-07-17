@extends('layouts.main')

@section('title', 'Edit Blog Category')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Edit Blog Category</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/blogcategory') }}" class="btn btn-info mt-o" style="float: right;">Back</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div id="loader"></div>
    <div class="card">
        <div class="card-body">
            <form action="{{ url('admin/blogcategory/update',$blogcategory->id) }}" id="addData" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="row">
                    <!-- Blog Category -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required form-label" for="blog_category"><span style="color: red;">* </span>Blog Category</label>
                            <input type="text" class="form-control @error('blog_category') is-invalid @enderror" name="blog_category" id="blog_category" placeholder="Please Enter Blog Category" value="{{ $blogcategory->blog_category }}">
                            @error('blog_category')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mt-2">
                        <button type="submit" class="btn btn-info float-right">Edit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('js/validations/blogCategories/addcategoryrules.js') }}"></script>
@endsection