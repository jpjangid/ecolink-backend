@extends('layouts.main')

@section('title', 'Add Coupon')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Add Coupon</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/coupons') }}" class="btn btn-info mt-o" style="float: right;">Back</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div id="loader"></div>

    <div class="card">
        <div class="card-body">
            <form action="{{ url('admin/coupons/store') }}" id="addData" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="required form-label" for="name"><span style="color: red;">* </span>Coupon Name</label>
                            <input type="text" class="form-control form-control-solid @error('name') is-invalid @enderror" name="name" id="name" placeholder="Please Enter Coupon Name" value="{{ old('name') }}">
                            @error('name')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="required form-label" for="code"><span style="color: red;">* </span>Coupon Code</label>
                            <input type="text" class="form-control form-control-solid @error('code') is-invalid @enderror" name="code" id="code" placeholder="Please Enter Coupon Code" value="{{ old('code') }}">
                            @error('code')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3"> 
                        <div class="form-group">
                            <label class="required form-label" for="type"><span style="color: red;">* </span>Coupon Type</label>
                            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror">
                                <option value="">Select Type</option>
                                <option value="cart_value_discount" {{ old('type') == 'cart_value_discount' ? 'selected' : '' }}>Cart Value Discount</option>
                                <!-- <option value="referral_discount" {{ old('type') == 'referral_discount' ? 'selected' : '' }}>Referral Discount</option>
                                <option value="personal_code" {{ old('type') == 'personal_code' ? 'selected' : '' }}>Personal Code Discount</option> -->
                                <option value="customer_based" {{ old('type') == 'customer_based' ? 'selected' : '' }}>Customer Based Discount</option>
                                <option value="global" {{ old('type') == 'global' ? 'selected' : '' }}>Global Discount</option>
                                <!-- <option value="merchandise" {{ old('type') == 'merchandise' ? 'selected' : '' }}>Merchandise Discount</option> -->
                            </select>
                            @error('type')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3" id="cat_id">
                        <div class="form-group">
                            <label class="form-label" for="cat_id">Category</label>
                            <select name="cat_id" class="form-control">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3" id="product_id">
                        <div class="form-group">
                            <label class="form-label" for="product_id">Product</label>
                            <select name="product_id" class="form-control">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3" id="user_id">
                        <div class="form-group">
                            <label class="form-label" for="user_id">Customer</label>
                            <select name="user_id" class="form-control select2bs4">
                                <option value="">Select Customer</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label" for="min_order_amount">Min Order Amount</label>
                            <input type="number" step=".01" class="form-control form-control-solid @error('min_order_amount') is-invalid @enderror" name="min_order_amount" id="min_order_amount" placeholder="Please Enter Min Order Amount" value="{{ old('min_order_amount') }}">
                            @error('min_order_amount')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label" for="max_order_amount">Max Order Amount</label>
                            <input type="number" step=".01" class="form-control form-control-solid @error('max_order_amount') is-invalid @enderror" name="max_order_amount" id="max_order_amount" placeholder="Please Enter Max Order Amount" value="{{ old('max_order_amount') }}">
                            @error('max_order_amount')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label" for="offer_start">Offer Start</label>
                            <input type="datetime-local" class="form-control form-control-solid @error('offer_start') is-invalid @enderror" name="offer_start" id="offer_start" placeholder="Please Enter Offer Start" value="{{ old('offer_start') }}">
                            @error('offer_start')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label" for="offer_end">Offer End</label>
                            <input type="datetime-local" class="form-control form-control-solid @error('offer_end') is-invalid @enderror" name="offer_end" id="offer_end" placeholder="Please Enter Offer End" value="{{ old('offer_end') }}">
                            @error('offer_end')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label" for="days"><span style="color: red;">* </span>Week Days</label>
                            <select name="days[]" id="days" class="form-control select2bs4" data-placeholder="Select Days" required multiple>
                                <option value="Sunday" {{in_array('Sunday', old("days") ?: []) ? "selected" : ""}}>Sunday</option>
                                <option value="Monday" {{in_array('Monday', old("days") ?: []) ? "selected" : ""}}>Monday</option>
                                <option value="Tuesday" {{in_array('Tuesday', old("days") ?: []) ? "selected" : ""}}>Tuesday</option>
                                <option value="Wednesday" {{in_array('Wednesday', old("days") ?: []) ? "selected" : ""}}>Wednesday</option>
                                <option value="Thursday" {{in_array('Thursday', old("days") ?: []) ? "selected" : ""}}>Thursday</option>
                                <option value="Friday" {{in_array('Friday', old("days") ?: []) ? "selected" : ""}}>Friday</option>
                                <option value="Saturday" {{ in_array('Saturday', old("days") ?: []) ? "selected" : ""}}>Saturday</option>
                            </select>
                        </div>
                    </div> -->

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label" for="coupon_limit">Coupon Limit</label>
                            <input type="text" class="form-control form-control-solid" name="coupon_limit" id="coupon_limit" placeholder="Please Enter Coupon Limit" value="{{ old('coupon_limit') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label" for="disc_type">Discount Type</label>
                            <select name="disc_type" id="disc_type" class="form-control">
                                <option value="">Select Type</option>
                                <option value="percent" {{ old('disc_type') == 'percent' ? 'selected' : '' }}>In Percent</option>
                                <option value="amount" {{ old('disc_type') == 'amount' ? 'selected' : '' }}>In Amount</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label" for="discount">Discount</label>
                            <input type="number" step=".01" class="form-control form-control-solid" name="discount" id="discount" placeholder="Please Enter Discount" value="{{ old('discount') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" for="show_in_front"><span style="color: red;">* </span>Status</label>
                        <select class="form-control @error('show_in_front') is-invalid @enderror" name="show_in_front">
                            <option value="">Select Status</option>
                            <option value="1" {{ old('show_in_front') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('show_in_front') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('show_in_front')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
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
<script src="{{ asset('js/validations/coupons/addcouponrules.js') }}"></script>
<script>
    $(document).on('change', '#disc_type', function() {
        var type = $('#disc_type').val();
        var discount = $('#discount').val();
        if (type == 'percent') {
            $('#discount').val();
        }
    });
</script>
<script>
    $(document).ready(function() {
        var type = '';
        input_show_hide(type);
    });

    $(document).on('change', '#type', function() {
        var type = $(this).val();
        input_show_hide(type);
    });

    $(document).on('change', '#disc_type', function() {
        $('#discount').val('');
    });

    $(document).on('keyup', '#discount', function() {
        var type = $('#disc_type').val();
        var discount = $('#discount').val();

        if (type == 'percent') {
            discount = discount > 100 ? '' : discount;
            if (discount == '') {
                swal("Danger!", "Discount in percent should be lower or equal to 100%", "error");
            }
        }
        $('#discount').val(discount);
    });

    function input_show_hide(type) {
        if (type == 'merchandise') {
            $('#brand_id').show();
            $('#main_cat_id').show();
            $('#cat_id').show();
            $('#sub_cat_id').show();
            $('#product_id').show();
            $('#user_id').hide();
        } else if (type == 'customer_based' || type == 'personal_code') {
            $('#user_id').show();
            $('#brand_id').hide();
            $('#main_cat_id').hide();
            $('#cat_id').hide();
            $('#sub_cat_id').hide();
            $('#product_id').hide();
        } else {
            $('#brand_id').hide();
            $('#main_cat_id').hide();
            $('#cat_id').hide();
            $('#sub_cat_id').hide();
            $('#product_id').hide();
            $('#user_id').hide();
        }
    }
</script>
@endsection