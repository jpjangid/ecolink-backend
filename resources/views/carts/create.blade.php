@extends('layouts.main')

@section('title', 'Add New Cart Entry')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Add New Cart Entry</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/carts') }}" class="btn btn-info mt-o" style="float: right;">Back</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div id="loader"></div>

    <div class="card">
        <div class="card-body">
            <form action="{{ url('admin/carts/store') }}" method="post">
                @csrf
                <div class="row">

                    <!-- Customer -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required form-label" for="user_id"><span style="color: red;">* </span>Customer</label>
                            <select class="form-control form-control-solid @error('user_id') is-invalid @enderror select2bs4" name="user_id">
                                <option value="">Select Customer</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old("user_id") == $user->id ? "selected":"" }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Product -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required form-label" for="product_id"><span style="color: red;">* </span>Product</label>
                            <select class="form-control form-control-solid @error('product_id') is-invalid @enderror select2bs4" name="product_id">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old("product_id") == $product->id ? "selected":"" }}>{{ $product->name }}</option>
                                @endforeach
                            </select>
                            @error('product_id')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required form-label" for="quantity"><span style="color: red;">* </span>Quantity</label>
                            <input type="number" class="form-control form-control-solid @error('quantity') is-invalid @enderror" name="quantity" id="quantity" placeholder="Please Enter quantity" value="{{ old('quantity') }}">
                            @error('quantity')
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