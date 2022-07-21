@extends('layouts.main')

@section('title', 'Edit Notice')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Edit Notice</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/notices') }}" class="btn btn-info mt-o" style="float: right;">Back</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div id="loader"></div>

    <div class="card">
        <div class="card-body">
            <form action="{{ url('admin/notices/update', $notice->id) }}" id="addData" method="post" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="row">

                    <!-- Title -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required form-label" for="title"><span style="color: red;">* </span> Title </label>
                            <input type="text" class="form-control form-control-solid @error('title') is-invalid @enderror" name="title" id="title" placeholder="Please Enter Title" value="{{ $notice->title }}">
                            @error('title')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Notice image -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="image">Featured Image:</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" name="image">
                                <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                            </div>
                            @error('image')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- alt title-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required form-label" for="alt_title">Alt Title</label>
                            <input type="text" class="form-control form-control-solid @error('alt_title') is-invalid @enderror" name="alt" id="alt_title" placeholder="Please Enter Alt Title" value="{{ $notice->alt }}">
                        </div>
                    </div>

                     <!-- Redirect To Url-->
                     <div class="col-md-6">
                        <div class="form-group">
                            <label class="required form-label" for="url">Redirect To Url</label>
                            <input type="text" class="form-control form-control-solid @error('url') is-invalid @enderror" name="url" id="url" placeholder="Please Enter Redirect To Url" value="{{ $notice->url }}">
                        </div>
                    </div>

                    <!-- Display Status -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="status"><span style="color: red;">* </span>Display Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" name="status">
                                <option value="">Select Status</option>
                                <option value="1" {{ $notice->status == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ $notice->status == '0' ? 'selected' : '' }}>No</option>
                            </select>
                            @error('status')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="start_date">Notice Start's</label>
                            <input type="datetime-local" class="form-control form-control-solid @error('start_date') is-invalid @enderror" name="start_date" id="start_date" placeholder="Please Enter Notice Start" value="{{ date('Y-m-d\TH:i:s', strtotime($notice->start_date)) }}">
                            @error('start_date')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="end_date">Notice End's</label>
                            <input type="datetime-local" class="form-control form-control-solid @error('end_date') is-invalid @enderror" name="end_date" id="end_date" placeholder="Please Enter Notice End" value="{{ date('Y-m-d\TH:i:s', strtotime($notice->end_date)) }}">
                            @error('end_date')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                   

                    <!-- Notice Message -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="required form-label" for="message"><span style="color: red;">* </span>Message</label>
                            <textarea id="wysiwyg" class="form-control form-control-solid @error('message') is-invalid @enderror" name="message" placeholder="Please Enter Your Text"><?php echo $notice->message; ?></textarea>
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
@section('js')
<script src="{{ asset('js/validations/notices/addnoticerules.js') }}"></script>
@endsection