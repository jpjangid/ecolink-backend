@extends('layouts.main')

@section('title', 'Add Product')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Add Product</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/products') }}" class="btn btn-info mt-o" style="float: right;">Back</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ url('admin/products/store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <!-- Product Title -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="name"><span style="color: red;">* </span>Product Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" placeholder="Please Enter Product Name" value="{{ old('name') }}">
                            @error('name')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- slug -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="slug"><span style="color: red;">* </span>Slug</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" name="slug" value="{{ old('slug')}}" placeholder="Please Enter Slug" />
                            @error('slug')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- main category -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="category_id"><span style="color: red;">* </span>Category</label>
                            <select name="category_id" class="form-control select2bs4">
                                <option value="">Select Category</option>
                                @foreach($cats as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Product HSN -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="hsn">HSN</label>
                            <input type="text" class="form-control @error('hsn') is-invalid @enderror" name="hsn" id="hsn" placeholder="Please Enter Product HSN" value="{{ old('hsn') }}">
                            @error('hsn')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Product SKU -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="sku">SKU</label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror" name="sku" id="sku" placeholder="Please Enter Product SKU" value="{{ old('sku') }}">
                            @error('sku')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Product Regular Price -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="regular_price"><span style="color: red;">* </span>Product Regular Price</label>
                            <input type="number" class="form-control" name="regular_price" id="regular_price" placeholder="Please Enter Product Regular Price" value="{{ old('regular_price') }}">
                        </div>
                    </div>

                    <!-- GST % -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="gst"><span style="color: red;">* </span>GST %</label>
                            <input type="number" class="form-control" name="gst" id="gst" placeholder="Please Enter GST %" value="{{ old('gst') }}">
                        </div>
                    </div>

                    <!-- Product Discount Type -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="discount_type"><span style="color: red;">* </span>Product Discount Type</label>
                            <select name="discount_type" class="form-control" id="dis_type">
                                <option value="">Select Type</option>
                                <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                <option value="flat" {{ old('discount_type') == 'flat' ? 'selected' : '' }}>Flat</option>
                            </select>
                        </div>
                    </div>

                    <!-- Product Discount -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="discount"><span style="color: red;">* </span>Product Discount</label>
                            <input type="number" class="form-control" name="discount" id="discount" placeholder="Please Enter Product Discount" value="{{ old('discount') }}">
                        </div>
                    </div>

                    <!-- Product Sale Price -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="sale_price"><span style="color: red;">* </span>Product Sale Price</label>
                            <input type="number" class="form-control" name="sale_price" id="sale_price" placeholder="Please Enter Product Sale Price" value="{{ old('sale_price') }}" readonly>
                        </div>
                    </div>

                    <!-- Product Image -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="image"><span style="color: red;">* </span>Featured Image:</label>
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="alt_title"><span style="color: red;">* </span>Alt Title</label>
                            <input type="text" class="form-control @error('alt_title') is-invalid @enderror" name="alt" id="alt_title" placeholder="Please Enter Alt Title" value="{{ old('alt') }}">
                            @error('meta_description')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label class="required form-label" for="status"><span style="color: red;">* </span>Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" name="status">
                                <option value="">Select Status</option>
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>No</option>
                            </select>
                            @error('status')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Product Description -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="required form-label" for="description"><span style="color: red;">* </span>Detail Description</label>
                            <textarea id="summernote" class="form-control @error('description') is-invalid @enderror" name="description"><?php echo old('description'); ?></textarea>
                            @error('description')
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
                <div class="row" style="border: 1px solid gray;border-radius: 10px;">

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

                    <!-- Product image -->
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
                            <textarea rows="4" cols="" class="form-control @error('og_description') is-invalid @enderror" name="og_description" placeholder="Please Enter OG Description">{{ old('og_description')}}</textarea>
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
<script>
    $('#summernote').summernote({
        placeholder: 'Please Enter Description',
        tabsize: 2,
        height: 120,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    $(document).on('change', '#regular_price', function() {
        var dis_type = $('#dis_type').val();
        var dis = $('#discount').val();
        var price = $('#regular_price').val();
        discount_amt(dis_type, dis, price);
    });

    $(document).on('change', '#dis_type', function() {
        var dis_type = $('#dis_type').val();
        var dis = $('#discount').val();
        var price = $('#regular_price').val();
        discount_amt(dis_type, dis, price);
    });

    $(document).on('change', '#discount', function() {
        var dis_type = $('#dis_type').val();
        var dis = $('#discount').val();
        var price = $('#regular_price').val();
        discount_amt(dis_type, dis, price);
    });

    $(document).on('change', '#gst', function() {
        var dis_type = $('#dis_type').val();
        var dis = $('#discount').val();
        var price = $('#regular_price').val();
        discount_amt(dis_type, dis, price);
    });

    function discount_amt(dis_type, dis, price) {
        var amt = 0;
        var gst = $('#gst').val();
        var gst_amt = ((price * gst) / 100)
        price = parseFloat(price) + parseFloat(gst_amt);

        if (dis_type != '' && dis != '' && price != '') {
            if (dis_type == 'percentage') {
                amt = parseFloat(price) - parseFloat((price * dis) / 100);
                $('#sale_price').val(amt);
            } else {
                if (price !== null) {
                    if (dis < price) {
                        amt = parseFloat(price) - parseFloat(dis);
                        $('#sale_price').val(amt);
                    } else {
                        swal("Danger!", 'Please Enter Correct Discount', "error");
                        $('#discount').val('');
                    }
                }
            }
        } else {
            $('#sale_price').val(price);
        }
    }
</script>

@endsection