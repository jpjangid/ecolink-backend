@extends('layouts.main')

@section('title', 'Add Category')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Add Category</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/categories') }}" class="btn btn-info mt-o" style="float: right;">Back</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ url('admin/categories/store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <!-- Category Title -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="name"><span style="color: red;">* </span>Name</label>
                            <input type="text" class="form-control form-control-solid @error('name') is-invalid @enderror" name="name" id="name" placeholder="Please Enter Category Name" value="{{ old('name') }}">
                            @error('name')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- slug -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="slug"><span style="color: red;">* </span>Slug</label>
                            <input type="text" class="form-control form-control-solid @error('slug') is-invalid @enderror" name="slug" value="{{ old('slug')}}" placeholder="Please Enter Slug" />
                            @error('slug')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Category image -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="image"><span style="color: red;">* </span>Featured Image:</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('image') is-invalid @enderror" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" name="image">
                                <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                            </div>
                            @error('image')
                            <span class="error invalid-feedback" style="display: block;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- alt title-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="alt"><span style="color: red;">* </span>Alt Title</label>
                            <input type="text" class="form-control form-control-solid @error('alt') is-invalid @enderror" name="alt" id="alt" placeholder="Please Enter Alt Title" value="{{ old('alt') }}">
                            @error('alt')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="status"><span style="color: red;">* </span>Publish Category</label>
                            <select class="form-control @error('status') is-invalid @enderror" name="status">
                                <option value="">Select Status</option>
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Category Short Description -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="required form-label" for="short_desc">Short Description</label>
                            <textarea class="form-control form-control-solid @error('short_desc') is-invalid @enderror" name="short_desc" placeholder="Please Enter Short Description"><?php echo old('short_desc'); ?></textarea>
                        </div>
                    </div>

                    <!-- Category Description -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="required form-label" for="description"><span style="color: red;">* </span>Detail Description</label>
                            <textarea id="wysiwyg" class="form-control form-control-solid @error('description') is-invalid @enderror" name="description"><?php echo old('description'); ?></textarea>
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
                            <input type="text" class="form-control form-control-solid @error('meta_title') is-invalid @enderror" name="meta_title" value="{{ old('meta_title')}}" placeholder="Please Enter Meta Title" />
                            @error('meta_title')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- keywords -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="keywords">Keywords</label>
                            <input type="text" class="form-control form-control-solid @error('keywords') is-invalid @enderror" name="keywords" id="keywords" value="{{ old('keywords')}}" placeholder="Please Enter keywords" />
                            @error('keywords')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- meta_description -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label" for="meta_description">Meta Description</label>
                            <textarea rows="4" cols="" class="form-control form-control-solid @error('meta_description') is-invalid @enderror" name="meta_description" placeholder="Please Enter Meta Description">{{ old('meta_description')}}</textarea>
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
                            <input type="text" class="form-control form-control-solid @error('og_title') is-invalid @enderror" name="og_title" value="{{ old('og_title')}}" placeholder="Please Enter OG Title" />
                            @error('og_title')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Category image -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="og_image">OG Image:</label>
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
                            <textarea rows="4" cols="" class="form-control form-control-solid @error('og_description') is-invalid @enderror" name="og_description" placeholder="Please Enter OG Description">{{ old('og_description')}}</textarea>
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

<!-- Page specific script -->
<!-- <script type="text/javascript">
    $(document).on('keydown', '#keywords', function() {
        if ($('#keywords').val() != "") {
            var keywords = $('#keywords').val();
            keywords = keywords.replace(/\s/g, ",");
            $('#keywords').val(keywords);
        }
    });

    $(document).on('keydown', '#tags', function() {
        if ($('#tags').val() != "") {
            var tags = $('#tags').val();
            tags = tags.replace(/\s/g, ",");
            $('#tags').val(tags);
        }
    });
</script> -->

@endsection