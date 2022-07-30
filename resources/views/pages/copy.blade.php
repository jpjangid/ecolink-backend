@extends('layouts.main')

@section('title', 'Add Page')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Add Page</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/pages') }}" class="btn btn-info mt-o" style="float: right;">Back</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div id="loader"></div>

    <div class="card">
        <div class="card-body">
            <form action="{{ url('admin/pages/store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <img src="{{ asset('default.jpg') }}" class="img-rounded" width="150" height="150" id="blah"><br>
                            </div>
                        </div>
                    </div>

                    <!-- Page Title -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="title"><span style="color: red;">* </span>Title</label>
                            <input type="text" class="form-control form-control-solid @error('title') is-invalid @enderror" name="title" id="title" placeholder="Please Enter Page title" value="{{ $page->title }}">
                            @error('title')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- slug -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="slug"><span style="color: red;">* </span>Slug</label>
                            <input type="text" class="form-control form-control-solid @error('slug') is-invalid @enderror" name="slug" value="{{ $page->slug }}" placeholder="Please Enter Slug" />
                            @error('slug')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- page category -->
                    <!-- <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="category">Page Category</label>
                            <select class="form-control form-control-solid @error('category') is-invalid @enderror" name="category">
                                <option value="">Select Page Category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $page->category == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> -->

                    <!-- Parent Page -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="parent_id">Parent Page</label>
                            <select class="form-control form-control-solid @error('parent_id') is-invalid @enderror select2bs4" name="parent_id">
                                <option value="">Select Parent Page</option>
                                @foreach($parentpages as $parentpage)
                                <option value="{{ $parentpage->id }}" {{ $page->parent_id == $parentpage->id ? 'selected' : '' }}>{{ $parentpage->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Page image -->
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="image">Featured Image:</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" name="image" onchange="readURL(this);">
                                <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                            </div>
                            @error('image')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- alt title-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="alt_title">Alt Title</label>
                            <input type="text" class="form-control form-control-solid @error('alt_title') is-invalid @enderror" name="alt" id="alt_title" placeholder="Please Enter Alt Title" value="{{ $page->alt }}">
                            @error('meta_description')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="status"><span style="color: red;">* </span>Publish Page</label>
                            <select class="form-control @error('status') is-invalid @enderror" name="status">
                                <option value="">Select Status</option>
                                <option value="1" {{ $page->status == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ $page->status == '0' ? 'selected' : '' }}>No</option>
                            </select>
                            @error('status')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Page Description -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="required form-label" for="description"><span style="color: red;">* </span>Detail Description</label>
                            <textarea id="summernote" class="form-control form-control-solid @error('description') is-invalid @enderror" name="description"><?php echo $page->description; ?></textarea>
                            @error('description')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Head Schema -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="required form-label" for="head_schema">Head Schema</label>
                            <textarea class="form-control @error('head_schema') is-invalid @enderror" name="head_schema" placeholder="Please Enter Schema"><?php echo $page->head_schema; ?></textarea>
                            @error('head_schema')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Body Schema -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="required form-label" for="body_schema">Body Schema</label>
                            <textarea class="form-control @error('body_schema') is-invalid @enderror" name="body_schema" placeholder="Please Enter Schema"><?php echo $page->body_schema; ?></textarea>
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
                            <input type="text" class="form-control form-control-solid @error('meta_title') is-invalid @enderror" name="meta_title" value="{{ $page->meta_title }}" placeholder="Please Enter Meta Title" />
                            @error('meta_title')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- keywords -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="keywords">Keywords</label>
                            <input type="text" class="form-control form-control-solid @error('keywords') is-invalid @enderror" name="keywords" id="keywords" value="{{ $page->keywords }}" placeholder="Please Enter Keywords" />
                            @error('keywords')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- meta_description -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label" for="meta_description">Meta Description</label>
                            <textarea rows="4" cols="" class="form-control form-control-solid @error('meta_description') is-invalid @enderror" name="meta_description" placeholder="Please enter Meta Description">{{ $page->meta_description }}</textarea>
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
                            <input type="text" class="form-control form-control-solid @error('og_title') is-invalid @enderror" name="og_title" value="{{ $page->og_title }}" placeholder="Please Enter OG Title" />
                            @error('og_title')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Page image -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="og_image"><span style="color: red;">* </span>OG Image:</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" name="og_image">
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
                            <textarea rows="4" cols="" class="form-control form-control-solid @error('og_description') is-invalid @enderror" name="og_description" placeholder="Please enter OG Description">{{ $page->og_description }}</textarea>
                            @error('og_description')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- Links For Page -->
                    <div class="col-md-12 mt-2">
                        <div class="form-group">
                            <label class="required form-label" for="pagelinks">Links For Page</label>
                            <select class="form-control form-control-solid @error('pagelinks') is-invalid @enderror select2bs4" multiple="multiple" name="pagelinks[]">
                                <option value="">Select Links</option>
                                @foreach($links as $link)
                                <option value="{{ $link->id }}" {{ in_array($link->id, $pagelinks) == true ? 'selected' : '' }}>{{ $link->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
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
<script src="{{ asset('js/validations/pages/addpagerules.js') }}"></script>
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