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
            
            <form action="{{ url('admin/settings/update',['category_title' => $category_title->id, 'category_des' => $category_des->id]) }}" method="post" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <!-- Category Name -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="required form-label" for="name"><span style="color: red;">* </span>Name/Slug</label>
                        <input type="text" class="form-control form-control-solid @error('name') is-invalid @enderror" name="value1"  placeholder="Please Enter Category Name" value="{{ $category_title->value }}">
                        @error('name')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
    
              <!-- Category Value -->
              <div class="col-md-6">
                    <div class="form-group">
                        <label class="required form-label" for="value">Category Value</label>
                        <textarea class="form-control form-control-solid @error('value') is-invalid @enderror" name="value2" placeholder="Please Enter Category Value"><?php echo $category_des->value ?></textarea>
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



