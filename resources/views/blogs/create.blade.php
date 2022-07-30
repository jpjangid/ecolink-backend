@extends('layouts.main')

@section('title', 'Add Blog')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Add Blog</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/blogs') }}" class="btn btn-info mt-o" style="float: right;">Back</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div id="loader"></div>

    <div class="card">
        <div class="card-body">
            <form action="{{ url('admin/blogs/store') }}" id="addData" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <img src="{{ asset('default.jpg') }}" class="img-rounded" width="150" height="150" id="blah"><br>
                            </div>
                        </div>
                    </div>

                    <!-- Blog Title -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="title"><span style="color: red;">* </span>Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="title" placeholder="Please Enter Blog title" value="{{ old('title') }}">
                            @error('title')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- slug -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="slug"><span style="color: red;">* </span>Slug</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" name="slug" value="{{ old('slug')}}" placeholder="Please Enter Slug Of Blog" />
                            @error('slug')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- alt title-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="alt"><span style="color: red;">* </span>Alt Title</label>
                            <input type="text" class="form-control @error('alt') is-invalid @enderror" name="alt" id="alt" placeholder="Please Enter Alt Title" value="{{ old('alt') }}">
                            @error('alt')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- published date -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="category"><span style="color: red;">* </span>Category</label>
                            <select class="form-control select2bs4 @error('category') is-invalid @enderror" name="category">
                                <option value="">Select Blog Category</option>
                                @foreach( $blogcategories as $blogcategory)
                                <option value="{{ $blogcategory->blog_category}}" {{ old('category') == $blogcategory->blog_category ? 'selected' : '' }}> {{ $blogcategory->blog_category}}</option>
                                @endforeach
                            </select>
                            @error('category')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- published date -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="publish_date"><span style="color: red;">* </span>Date/Time</label>
                            <input type="datetime-local" class="form-control @error('publish_date') is-invalid @enderror" name="publish_date" id="publish_date" value="{{ date('Y-m-d\TH:i') }}" placeholder="Please Enter Meta Tag" />
                            @error('publish_date')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="status"><span style="color: red;">* </span>Publish Blog</label>
                            <select class="form-control @error('status') is-invalid @enderror" name="status">
                                <option value="">Select Status</option>
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Publish</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Draft</option>
                                <option value="2" {{ old('status') == '2' ? 'selected' : '' }}>Schedule</option>
                            </select>
                            @error('status')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- blog image -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="image"><span style="color: red;">* </span>Featured Image:</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('image') is-invalid @enderror" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" name="image" onchange="readURL(this);" accept="image/x-png,image/gif,image/jpeg">
                                <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                            </div>
                            @error('image')
                            <span class="error invalid-feedback" style="display: block !important;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Blog Short Description -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="required form-label" for="short_desc">Short Description</label>
                            <textarea class="form-control form-control-solid @error('short_desc') is-invalid @enderror" name="short_desc" placeholder="Please Enter Short Description"><?php echo old('short_desc'); ?></textarea>
                        </div>
                    </div>

                    <!-- Blog Description -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="required form-label" for="description"><span style="color: red;">* </span>Detail Description</label>
                            <textarea id="summernote" class="form-control @error('description') is-invalid @enderror" name="description"><?php echo old('description'); ?></textarea>
                            @error('description')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Head Schema -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="required form-label" for="head_schema">Head Schema</label>
                            <textarea class="form-control @error('head_schema') is-invalid @enderror" name="head_schema" placeholder="Please Enter Schema"><?php echo old('head_schema'); ?></textarea>
                            @error('head_schema')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Body Schema -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="required form-label" for="body_schema">Body Schema</label>
                            <textarea class="form-control @error('body_schema') is-invalid @enderror" name="body_schema" placeholder="Please Enter Schema"><?php echo old('body_schema'); ?></textarea>
                            @error('body_schema')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row" style="border: 1px solid gray;border-radius: 10px;">

                    <!-- Meta Title -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="meta_title">Meta Title</label>
                            <input type="text" class="form-control @error('meta_title') is-invalid @enderror" name="meta_title" value="{{ old('meta_title')}}" placeholder="Please Enter Meta Title" />
                            @error('meta_title')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- keywords -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="keywords">Meta Keywords</label>
                            <input type="text" class="form-control @error('keywords') is-invalid @enderror" name="keywords" id="keywords" value="{{ old('keywords')}}" placeholder="Please Enter Meta Keywords" />
                            @error('keywords')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- meta_description -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label" for="meta_description">Meta Description</label>
                            <textarea rows="4" cols="" class="form-control @error('meta_description') is-invalid @enderror" name="meta_description" placeholder="Please Enter Meta Description">{{ old('meta_description')}}</textarea>
                            @error('meta_description')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row mt-2" style="border: 1px solid gray;border-radius: 10px;">

                    <!-- OG Title -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="og_title">OG Title</label>
                            <input type="text" class="form-control @error('og_title') is-invalid @enderror" name="og_title" value="{{ old('og_title')}}" placeholder="Please Enter OG Title" />
                            @error('og_title')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- blog og image -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="og_image">OG Image:</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" name="og_image" accept="image/x-png,image/gif,image/jpeg">
                                <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                            </div>
                            @error('og_image')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- OG description -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label" for="og_description">OG Description</label>
                            <textarea rows="4" cols="" class="form-control @error('og_description') is-invalid @enderror" name="og_description" placeholder="Please enter OG Description">{{ old('og_description')}}</textarea>
                            @error('og_description')
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
@section('js')
<script src="{{ asset('js/validations/blogs/addblogrules.js') }}"></script>
<script type=text/javascript>
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
@endsection