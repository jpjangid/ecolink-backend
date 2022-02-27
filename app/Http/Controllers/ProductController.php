<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            /* Getting all records */
            $allproducts = DB::table('products')->select('id', 'name', 'slug', 'status')->where(['status' => 1, 'flag' => 0])->get();

            /* Converting Selected Data into desired format */
            $products = new Collection;
            foreach ($allproducts as $product) {
                $products->push([
                    'id'        => $product->id,
                    'name'      => $product->name,
                    'slug'      => $product->slug,
                    'status'    => $product->status,
                ]);
            }

            /* Sending data through yajra datatable for server side rendering */
            return Datatables::of($products)
                ->addIndexColumn()
                /* Status Active and Deactive Checkbox */
                ->addColumn('active', function ($row) {
                    $checked = $row['status'] == '1' ? 'checked' : '';
                    $active  = '<div class="form-check form-switch form-check-custom form-check-solid">
                                        <input type="hidden" value="' . $row['id'] . '" class="product_id">
                                        <input type="checkbox" class="form-check-input js-switch  h-20px w-30px" id="customSwitch1" name="status" value="' . $row['status'] . '" ' . $checked . '>
                                    </div>';

                    return $active;
                })
                /* Adding Actions like edit, delete and show */
                ->addColumn('action', function ($row) {
                    $delete_url = url('admin/products/delete', $row['id']);
                    $edit_url = url('admin/products/edit', $row['id']);
                    $btn = '<a class="btn btn-primary btn-xs ml-1" href="' . $edit_url . '"><i class="fas fa-edit"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action', 'active'])
                ->make(true);
        }

        return view('products.index');
    }

    public function create()
    {
        /* Loading Create Page with categories data */
        $categories  = DB::table('categories')->where(['flag' => '0'])->get();

        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        /* Validating Input fields */
        $request->validate([
            'name'                  =>  'required',
            'slug'                  =>  'required',
            'description'           =>  'required',
            'sku'                   =>  'required',
            'regular_price'         =>  'required',
            'sale_price'            =>  'required',
            'category_id'           =>  'required',
        ], [
            'name.required'                 =>  'Please Enter Product Name',
            'slug.required'                 =>  'Please Enter Slug',
            'description.required'          =>  'Please Enter Description',
            'sku.required'                  =>  'Please Enter SKU',
            'regular_price.required'        =>  'Please Enter Regular Price',
            'sale_price.required'           =>  'Please Enter Sale Price',
            'category_id.required'          =>  'Please Select Category',
        ]);

        /* Storing OG Image on local disk */
        $og_image = '';
        if ($request->hasFile('og_image')) {
            $extension = $request->file('og_image')->extension();
            $file = $request->file('og_image');
            $fileNameString = (string) Str::uuid();
            $og_image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/products/og_images', $file, $og_image);
        }

        /* Storing Featured Image on local disk */
        $image = '';
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->extension();
            $file = $request->file('image');
            $fileNameString = (string) Str::uuid();
            $image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/products', $file, $image);
        }

        /* Storing Data in Table */
        Product::create([
            'name'                  =>  $request->name,
            'slug'                  =>  $request->slug,
            'parent_id'             =>  $request->category_id,
            'description'           =>  $request->description,
            'meta_title'            =>  $request->meta_title,
            'meta_keyword'          =>  $request->meta_keyword,
            'meta_description'      =>  $request->meta_description,
            'og_title'              =>  $request->og_title,
            'og_description'        =>  $request->og_description,
            'status'                =>  $request->status,
            'og_image'              =>  $og_image,
            'sku'                   =>  $request->sku,
            'discount_type'         =>  $request->discount_type,
            'discount'              =>  $request->discount,
            'regular_price'         =>  $request->regular_price,
            'sale_price'            =>  $request->sale_price,
            'image'                 =>  $image,
            'alt'                   =>  $request->alt,
            'hsn'                   =>  $request->hsn,
            'gst'                   =>  $request->gst,
        ]);

        /* After Successfull insertion of data redirecting to listing page with message */
        return redirect('admin/products')->with('success', 'Product added successfully');
    }

    public function update_status(Request $request)
    {
        /* Updating status of selected entry */
        $product = Product::find($request->product_id);
        $product->status   = $request->status == 1 ? 0 : 1;
        $product->update();

        return response()->json(['message' => 'Product status updated successfully.']);
    }

    public function edit($id)
    {
        /* Getting Product data with categories for edit using Id */
        $categories = DB::table('categories')->where(['flag' => '0'])->get();

        $product = DB::table('products')->find($id);

        return view('products.edit', compact('categories', 'product', 'id'));
    }

    public function update(Request $request, $id)
    {
        /* Validating Input fields */
        $request->validate([
            'name'                  =>  'required',
            'slug'                  =>  'required',
            'description'           =>  'required',
            'sku'                   =>  'required',
            'regular_price'         =>  'required',
            'sale_price'            =>  'required',
            'category_id'           =>  'required',
        ], [
            'name.required'                 =>  'Please Enter Product Name',
            'slug.required'                 =>  'Please Enter Slug',
            'description.required'          =>  'Please Enter Description',
            'sku.required'                  =>  'Please Enter SKU',
            'regular_price.required'        =>  'Please Enter Regular Price',
            'sale_price.required'           =>  'Please Enter Sale Price',
            'category_id.required'          =>  'Please Select Category',
        ]);

        /* Fetching Blog Data using Id */
        $product = Product::find($id);

        /* Storing Featured Image on local disk */
        $image = $product->image;
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->extension();
            $file = $request->file('image');
            $fileNameString = (string) Str::uuid();
            $image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/products', $file, $image);
        }

        /* Storing OG Image on local disk */
        $og_image = $product->og_image;
        if ($request->hasFile('og_image')) {
            $extension = $request->file('og_image')->extension();
            $file = $request->file('og_image');
            $fileNameString = (string) Str::uuid();
            $og_image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/products/og_images', $file, $og_image);
        }

        /* Updating Data fetched by Id */
        $product->name                  =  $request->name;
        $product->slug                  =  $request->slug;
        $product->description           =  $request->description;
        $product->alt                   =  $request->alt;
        $product->hsn                   =  $request->hsn;
        $product->gst                   =  $request->gst;
        $product->meta_title            =  $request->meta_title;
        $product->meta_keyword          =  $request->meta_keyword;
        $product->meta_description      =  $request->meta_description;
        $product->og_title              =  $request->og_title;
        $product->og_description        =  $request->og_description;
        $product->status                =  $request->status;
        $product->parent_id             =  $request->category_id;
        $product->og_image              =  $og_image;
        $product->sku                   =  $request->sku;
        $product->discount_type         =  $request->discount_type;
        $product->discount              =  $request->discount;
        $product->regular_price         =  $request->regular_price;
        $product->sale_price            =  $request->sale_price;
        $product->image                 =  $image;
        $product->update();

        /* After successfull update of data redirecting to index page with message */
        return redirect('admin/products')->with('success', 'Product updated successfully');
    }
}
