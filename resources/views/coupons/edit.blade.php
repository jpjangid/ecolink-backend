@extends('layouts.main')

@section('title', 'Edit Coupon')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Edit Coupon</h1>
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
            <form action="{{ url('admin/coupons/update', $id) }}" id="addData" method="post" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="required form-label" for="name"><span style="color: red;">* </span>Coupon Name</label>
                            <input type="text" class="form-control form-control-solid @error('name') is-invalid @enderror" name="name" id="name" placeholder="Please Enter Coupon Name" value="{{ $coupon->name }}">
                            @error('name')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="required form-label" for="code"><span style="color: red;">* </span>Coupon Code</label>
                            <input type="text" class="form-control form-control-solid @error('code') is-invalid @enderror" name="code" id="code" placeholder="Please Enter Coupon Code" value="{{ $coupon->code }}">
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
                                <option value="cart_value_discount" {{ $coupon->type == 'cart_value_discount' ? 'selected' : '' }}>Cart Value Discount</option>
                                <!-- <option value="referral_discount" {{ $coupon->type == 'referral_discount' ? 'selected' : '' }}>Referral Discount</option> -->
                                <!-- <option value="personal_code" {{ $coupon->type == 'personal_code' ? 'selected' : '' }}>Personal Code Discount</option> -->
                                <option value="customer_based" {{ $coupon->type == 'customer_based' ? 'selected' : '' }}>Customer Based Discount</option>
                                <option value="global" {{ $coupon->type == 'global' ? 'selected' : '' }}>Global Discount</option>
                                <!-- <option value="merchandise" {{ $coupon->type == 'merchandise' ? 'selected' : '' }}>Merchandise Discount</option> -->
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
                                <option value="{{ $category->id }}" {{ $coupon->cat_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
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
                                <option value="{{ $product->id }}" {{ $coupon->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
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
                                <option value="{{ $user->id }}" {{ $coupon->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label" for="min_order_amount">Min Order Amount</label>
                            <input type="number" step=".01" class="form-control form-control-solid @error('min_order_amount') is-invalid @enderror" name="min_order_amount" id="min_order_amount" placeholder="Please Enter Min Order Amount" value="{{ $coupon->min_order_amount }}">
                            @error('min_order_amount')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label" for="max_order_amount">Max Order Amount</label>
                            <input type="number" step=".01" class="form-control form-control-solid @error('max_order_amount') is-invalid @enderror" name="max_order_amount" id="max_order_amount" placeholder="Please Enter Max Order Amount" value="{{ $coupon->max_order_amount }}">
                            @error('max_order_amount')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label" for="offer_start">Offer Start</label>
                            <input type="datetime-local" class="form-control form-control-solid @error('offer_start') is-invalid @enderror" name="offer_start" id="offer_start" placeholder="Please Enter Offer Start" value="{{ date('Y-m-d\TH:i:s', strtotime($coupon->offer_start)) }}">
                            @error('offer_start')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label" for="offer_end">Offer End</label>
                            <input type="datetime-local" class="form-control form-control-solid @error('offer_end') is-invalid @enderror" name="offer_end" id="offer_end" placeholder="Please Enter Offer End" value="{{ date('Y-m-d\TH:i:s', strtotime($coupon->offer_end)) }}">
                            @error('offer_end')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- @php $days = explode(',',$coupon->days); @endphp
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label" for="days"><span style="color: red;">* </span>Week Days</label>
                            <select name="days[]" id="days" class="form-control select2bs4" data-placeholder="Select Days" multiple>
                                <option value="Sunday" {{ in_array('Sunday', $days) == true ? 'selected' : '' }}>Sunday</option>
                                <option value="Monday" {{ in_array('Monday', $days) == true ? 'selected' : '' }}>Monday</option>
                                <option value="Tuesday" {{ in_array('Tuesday', $days) == true ? 'selected' : '' }}>Tuesday</option>
                                <option value="Wednesday" {{ in_array('Wednesday', $days) == true ? 'selected' : '' }}>Wednesday</option>
                                <option value="Thursday" {{ in_array('Thursday', $days) == true ? 'selected' : '' }}>Thursday</option>
                                <option value="Friday" {{ in_array('Friday', $days) == true ? 'selected' : '' }}>Friday</option>
                                <option value="Saturday" {{ in_array('Saturday', $days) == true ? 'selected' : '' }}>Saturday</option>
                            </select>
                        </div>
                    </div> -->

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label" for="coupon_limit">Coupon Limit</label>
                            <input type="text" class="form-control form-control-solid" name="coupon_limit" id="coupon_limit" placeholder="Please Enter Coupon Limit" value="{{ $coupon->coupon_limit }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label" for="disc_type">Discount Type</label>
                            <select name="disc_type" id="disc_type" class="form-control">
                                <option value="">Select Type</option>
                                <option value="percent" {{ $coupon->disc_type == 'percent' ? 'selected' : '' }}>In Percent</option>
                                <option value="amount" {{ $coupon->disc_type == 'amount' ? 'selected' : '' }}>In Amount</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label" for="discount">Discount</label>
                            <input type="number" step=".01" class="form-control form-control-solid" name="discount" id="discount" placeholder="Please Enter Discount" value="{{ $coupon->discount }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" for="show_in_front"><span style="color: red;">* </span>Status</label>
                        <select class="form-control @error('show_in_front') is-invalid @enderror" name="show_in_front">
                            <option value="">Select Status</option>
                            <option value="1" {{ $coupon->show_in_front == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $coupon->show_in_front == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('show_in_front')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
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
<script src="{{ asset('js/validations/coupons/editcouponrules.js') }}"></script>
<script>
    $(document).on('change', '#disc_type', function() {
        var type = $('#disc_type').val();
        var discount = $('#discount').val();
        if (type == 'percent') {
            $('#discount').val('');
        }
    });
</script>
<script>
    $(document).ready(function() {
        var type = $('#type').val();;
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