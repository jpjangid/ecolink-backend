@extends('layouts.main')

@section('title', 'Edit Settings')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Edit Settings</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/categories') }}" class="btn btn-info mt-o" style="float: right;">Back</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div id="loader"></div>
    <div class="card">
        <div class="card-body">
            
            <form action="{{ url('admin/settings/update', $settings->id) }}" method="post" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <!-- Category Title -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="required form-label" for="title"><span style="color: red;">* </span>Title</label>
                        <input type="text" class="form-control form-control-solid @error('title') is-invalid @enderror" name="title" id="title" placeholder="Please Enter Category Title" value="{{ $settings->title }}">
                        @error('name')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
    
              <!-- Category Description -->
              <div class="col-md-6">
                    <div class="form-group">
                        <label class="required form-label" for="description">Category Description</label>
                        <textarea class="form-control form-control-solid @error('description') is-invalid @enderror" name="description" placeholder="Please Enter Category Description"><?php echo $settings->description ?></textarea>
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



