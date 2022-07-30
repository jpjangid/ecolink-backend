@extends('layouts.main')

@section('title', 'Edit Product')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Edit Product</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/products') }}" class="btn btn-info mt-o" style="float: right;">Back</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div id="loader"></div>

    <div class="card">
        <div class="card-body">
            <form action="{{ url('admin/products/update', $id) }}" id="addData" method="post" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <img src="{{ $product->image != null ? asset('storage/products/'.$product->image) : asset('default.jpg') }}" class="img-rounded" alt="{{ $product->name }}" width="150" height="150" id="blah"><br>
                            </div>
                        </div>
                    </div>

                    <!-- Product Title -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="name"><span style="color: red;">* </span>Product Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" placeholder="Please Enter Product Name" value="{{ $product->name }}">
                            @error('name')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Product Variant -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="variant"><span style="color: red;">* </span>Product Variant</label>
                            <input type="text" class="form-control @error('variant') is-invalid @enderror" name="variant" id="variant" placeholder="Please Enter Product Variant" value="{{ $product->variant }}">
                            @error('variant')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- slug -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="slug"><span style="color: red;">* </span>Slug</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" name="slug" value="{{ $product->slug }}" placeholder="Please Enter Slug" />
                            @error('slug')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- main category -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="category_id"><span style="color: red;">* </span>Category</label>
                            <select name="category_id" class="form-control select2bs4 @error('category_id') is-invalid @enderror">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->parent_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
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
                            <input type="text" class="form-control @error('hsn') is-invalid @enderror" name="hsn" id="hsn" placeholder="Please Enter Product HSN" value="{{ $product->hsn }}">
                            @error('hsn')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Product SKU -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="sku"><span style="color: red;">* </span>SKU</label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror" name="sku" id="sku" placeholder="Please Enter Product SKU" value="{{ $product->sku }}">
                            @error('sku')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Product Regular Price -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="regular_price"><span style="color: red;">* </span>Product Regular Price</label>
                            <input type="number" step=".01" class="form-control @error('regular_price') is-invalid @enderror" name="regular_price" id="regular_price" min="1" oninput="validity.valid||(value='');" placeholder="Please Enter Product Regular Price" value="{{ $product->regular_price }}">
                            @error('regular_price')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Product Discount Type -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="discount_type">Product Discount Type</label>
                            <select name="discount_type" class="form-control" id="dis_type">
                                <option value="">Select Type</option>
                                <option value="percentage" {{ $product->discount_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                <option value="flat" {{ $product->discount_type == 'flat' ? 'selected' : '' }}>Flat</option>
                            </select>
                        </div>
                    </div>

                    <!-- Product Discount -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="discount">Product Discount</label>
                            <input type="number" step=".01" class="form-control" name="discount" id="discount" min="0" oninput="validity.valid||(value='');" placeholder="Please Enter Product Discount" value="{{ $product->discount }}">
                        </div>
                    </div>

                    <!-- Product Sale Price -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="sale_price"><span style="color: red;">* </span>Product Sale Price</label>
                            <input type="number" step=".01" class="form-control @error('sale_price') is-invalid @enderror" name="sale_price" id="sale_price" placeholder="Please Enter Product Sale Price" value="{{ $product->sale_price }}" readonly>
                            @error('sale_price')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Product Image -->
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="image">Featured Image:</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" name="image" onchange="readURL(this);" accept="image/x-png,image/gif,image/jpeg">
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
                            <label class="required form-label" for="alt"><span style="color: red;">* </span>Alt Title</label>
                            <input type="text" class="form-control @error('alt') is-invalid @enderror" name="alt" id="alt" placeholder="Please Enter Alt Title" value="{{ $product->alt }}">
                            @error('alt')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="status"><span style="color: red;">* </span>Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" name="status">
                                <option value="">Select Status</option>
                                <option value="1" {{ $product->status == '1' ? 'selected' : '' }}>Active </option>
                                <option value="0" {{ $product->status == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Product Tag-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="tag">Product Tag</label>
                            <input type="text" class="form-control @error('tag') is-invalid @enderror" name="tag" id="tag" placeholder="Please Enter Product Tag" value="{{ $product->tag }}">
                        </div>
                    </div>

                    <!-- Product In Stock Status -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="in_stock">In Stock</label>
                            <select class="form-control @error('in_stock') is-invalid @enderror" name="in_stock">
                                <option value="1" {{ $product->in_stock == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ $product->in_stock == '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>

                    <!-- Product Stock-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="stock"><span style="color: red;">* </span>Product Stock</label>
                            <input type="number" step=".01" class="form-control @error('stock') is-invalid @enderror" name="stock" id="stock" min="1" oninput="validity.valid||(value='');" placeholder="Please Enter Product Stock" value="{{ $product->stock }}">
                            @error('stock')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Product Low Stock Amount-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="low_stock"><span style="color: red;">* </span>Product Low Stock Amount</label>
                            <input type="number" step=".01" class="form-control @error('low_stock') is-invalid @enderror" name="low_stock" id="low_stock" min="1" oninput="validity.valid||(value='');" placeholder="Please Enter Product Low Stock Amount" value="{{ $product->low_stock }}">
                            @error('low_stock')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Tax Status-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="tax_status">Tax Status</label>
                            <input type="text" class="form-control @error('tax_status') is-invalid @enderror" name="tax_status" id="tax_status" placeholder="Please Enter Tax Status" value="{{ $product->tax_status }}">
                        </div>
                    </div>

                    <!-- Tax Class-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="tax_class">Tax Class</label>
                            <input type="text" class="form-control @error('tax_class') is-invalid @enderror" name="tax_class" id="tax_class" placeholder="Please Enter Tax Class" value="{{ $product->tax_class }}">
                        </div>
                    </div>

                    <!-- Sold Individually-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="sold_individually">Sold Individually</label>
                            <input type="number" step=".01" class="form-control @error('sold_individually') is-invalid @enderror" name="sold_individually" id="sold_individually" placeholder="Please Enter Sold Individually" value="{{ $product->sold_individually }}">
                        </div>
                    </div>

                    <!-- Weight-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="weight">Weight</label>
                            <input type="number" step=".01" class="form-control @error('weight') is-invalid @enderror" name="weight" id="weight" min="1" oninput="validity.valid||(value='');" placeholder="Please Enter Weight" value="{{ $product->weight }}">
                            @error('weight')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- length-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="length">Length</label>
                            <input type="number" step=".01" class="form-control @error('length') is-invalid @enderror" name="length" id="length" min="1" oninput="validity.valid||(value='');" placeholder="Please Enter Length" value="{{ $product->length }}">
                            @error('length')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Width-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="width">Width</label>
                            <input type="number" step=".01" class="form-control @error('width') is-invalid @enderror" name="width" id="width" min="1" oninput="validity.valid||(value='');" placeholder="Please Enter Width" value="{{ $product->width }}">
                            @error('width')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Height-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="height">Height</label>
                            <input type="number" step=".01" class="form-control @error('height') is-invalid @enderror" name="height" id="height" min="1" oninput="validity.valid||(value='');" placeholder="Please Enter Height" value="{{ $product->height }}">
                            @error('height')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Shipping Class-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="shipping_class">Shipping Class</label>
                            <input type="text" step=".01" class="form-control @error('Shipping Class') is-invalid @enderror" name="shipping_class" id="shipping_class" placeholder="Please Enter Shipping Class" value="{{ $product->shipping_class }}">
                        </div>
                    </div>

                    <!-- Dropship Location -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="dropship"><span style="color: red;">* </span>Dropship Location</label>
                            <select name="dropship" class="form-control select2bs4 @error('dropship') is-invalid @enderror">
                                <option value="">Select Dropship Location</option>
                                @foreach($dropships as $dropship)
                                <option value="{{ $dropship->id }}" {{ $product->dropship == $dropship->id ? 'selected' : '' }}>{{ $dropship->name }}, {{$dropship->city}}, {{$dropship->state}}, {{$dropship->country}}, {{ $dropship->zip }}</option>
                                @endforeach
                            </select>
                            @error('dropship')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Product is Hazardous -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="hazardous">Hazardous</label>
                            <select class="form-control @error('hazardous') is-invalid @enderror" name="hazardous">
                                <option value="0" {{ $product->hazardous == '0' ? 'selected' : '' }}>No</option>
                                <option value="1" {{ $product->hazardous == '1' ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>
                    </div>

                    <!-- Product has Insurance -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="insurance">Insurance</label>
                            <select class="form-control @error('insurance') is-invalid @enderror" name="insurance">
                                <option value="0" {{ $product->insurance == '0' ? 'selected' : '' }}>No</option>
                                <option value="1" {{ $product->insurance == '1' ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>
                    </div>

                    <!-- Product Minimum Quantity-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="minimum_qty">Product Minimum Quantity</label>
                            <input type="number" step=".01" class="form-control @error('minimum_qty') is-invalid @enderror" name="minimum_qty" id="minimum_qty" min="1" oninput="validity.valid||(value='');" placeholder="Please Enter Product Minimum Quantity" value="{{ $product->minimum_qty }}">
                        </div>
                    </div>

                    <!-- Product Short Description -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="required form-label" for="short_desc"><span style="color: red;">* </span>Short Description</label>
                            <textarea class="form-control @error('short_desc') is-invalid @enderror" name="short_desc"><?php echo $product->short_desc; ?></textarea>
                            @error('short_desc')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Product Description -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="required form-label" for="description"><span style="color: red;">* </span>Detail Description</label>
                            <textarea id="summernote" class="form-control @error('description') is-invalid @enderror" name="description"><?php echo $product->description; ?></textarea>
                            @error('description')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Head Schema -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="required form-label" for="head_schema">Head Schema</label>
                            <textarea class="form-control @error('head_schema') is-invalid @enderror" name="head_schema" placeholder="Please Enter Schema"><?php echo $product->head_schema; ?></textarea>
                            @error('head_schema')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Body Schema -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="required form-label" for="body_schema">Body Schema</label>
                            <textarea class="form-control @error('body_schema') is-invalid @enderror" name="body_schema" placeholder="Please Enter Schema"><?php echo $product->body_schema; ?></textarea>
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
                            <input type="text" class="form-control @error('meta_title') is-invalid @enderror" name="meta_title" value="{{ $product->meta_title }}" placeholder="Please Enter Meta Title" />
                            @error('meta_title')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- keywords -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="meta_keyword">Meta Keywords</label>
                            <input type="text" class="form-control @error('meta_keyword') is-invalid @enderror" name="meta_keyword" id="meta_keyword" value="{{ $product->meta_keyword }}" placeholder="Please Enter Meta Keywords" />
                            @error('meta_keyword')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- meta_description -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label" for="meta_description">Meta Description</label>
                            <textarea rows="4" cols="" class="form-control @error('meta_description') is-invalid @enderror" name="meta_description" placeholder="Please Enter Meta Description">{{ $product->meta_description }}</textarea>
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
                            <input type="text" class="form-control @error('og_title') is-invalid @enderror" name="og_title" value="{{ $product->og_title }}" placeholder="Please Enter OG Title" />
                            @error('og_title')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Product og image -->
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
                            <textarea rows="4" cols="" class="form-control @error('og_description') is-invalid @enderror" name="og_description" placeholder="Please Enter OG Description">{{ $product->og_description }}</textarea>
                            @error('og_description')
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
@section('js')
<script src="{{ asset('js/validations/products/editproductrules.js') }}"></script>
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
<script>
    $(document).ready(function() {
        var dis_type = $('#dis_type').val();
        var dis = $('#discount').val();
        var price = $('#regular_price').val();
        discount_amt(dis_type, dis, price);
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

    function discount_amt(dis_type, dis, price) {
        var amt = 0;
        price = parseFloat(price);

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

    $(document).on('keyup', '#discount', function() {
        var type = $('#dis_type').val();
        var discount = $('#discount').val();

        if (type == 'percentage') {
            discount = discount > 100 ? '' : discount;
            if (discount == '') {
                alert('Discount in percentage should be lower or equal to 100%');
            }
        }
        $('#discount').val(discount);
    });
</script>
@endsection