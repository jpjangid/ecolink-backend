<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if (checkpermission('ProductController@index')) {
            if (request()->ajax()) {
                /* Getting all records */
                $active = $request->active == 'all' ? array('1', '0') : array($request->active);
                $allproducts = DB::table('products')->select('id', 'name', 'slug', 'status', 'variant')->where(['flag' => 0])->whereIn('status', $active)->orderby('created_at','desc')->get();

                /* Converting Selected Data into desired format */
                $products = new Collection;
                foreach ($allproducts as $product) {
                    $products->push([
                        'id'        => $product->id,
                        'name'      => $product->name,
                        'variant'   => $product->variant,
                        'slug'      => $product->slug,
                        'status'    => $product->status,
                    ]);
                }

                /* Sending data through yajra datatable for server side rendering */
                return Datatables::of($products)
                    ->addIndexColumn()
                    /* Status Active and Deactivated Checkbox */
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
                        $btn = '<div style="display:flex;">
                        <a class="btn btn-primary btn-xs" href="' . $edit_url . '" style="margin-right: 2px;"><i class="fas fa-edit"></i></a>
                                        <form action="' . $delete_url . '" method="post">
                                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="delete btn btn-danger btn-xs product_confirm"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'active'])
                    ->make(true);
            }
            return view('products.index');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function create()
    {
        if (checkpermission('ProductController@create')) {
            /* Loading Create Page with categories data */
            $categories  = DB::table('categories')->where(['flag' => '0'])->get();

            $dropships = DB::table('dropships')->get();

            return view('products.create', compact('categories', 'dropships'));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function store(Request $request)
    {
        // dd($request->all());
        /* Validating Input fields */
        $request->validate([
            'name'                  =>  'required',
            'variant'               =>  'required',
            'slug'                  =>  'required|unique:products,slug',
            'description'           =>  'required',
            'sku'                   =>  'required',
            'stock'                 =>  'required',
            'low_stock'             =>  'required',
            'regular_price'         =>  'required',
            'sale_price'            =>  'required',
            'category_id'           =>  'required',
            'alt'                   =>  'required',
            'image'                 =>  'required',
            'status'                =>  'required',
            'weight'                =>  'required',
            'height'                =>  'required',
            'width'                 =>  'required',
            'length'                =>  'required',
            'dropship'              =>  'required',
        ], [
            'name.required'                 =>  'Please Enter Product Name',
            'variant.required'              =>  'Please Enter Product Variant Name',
            'slug.required'                 =>  'Please Enter Slug',
            'description.required'          =>  'Please Enter Description',
            'sku.required'                  =>  'Please Enter SKU',
            'stock.required'                =>  'Please Enter Stock',
            'low_stock.required'            =>  'Please Enter Low Stock',
            'regular_price.required'        =>  'Please Enter Regular Price',
            'sale_price.required'           =>  'Please Enter Sale Price',
            'category_id.required'          =>  'Please Select Category',
            'alt.required'                  =>  'Please Enter Alt',
            'image.required'                =>  'Please Select Image',
            'status.required'               =>  'Please Select Status',
            'weight.required'               =>  'Please Enter Weight',
            'height.required'               =>  'Please Enter Height',
            'width.required'                =>  'Please Enter Width',
            'length.required'               =>  'Please Enter length',
            'dropship.required'             =>  'Please Enter Dropship Location'
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
            'variant'               =>  $request->variant,
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
            'regular_price'         =>  number_format((float)$request->regular_price, 2, '.', ''),
            'sale_price'            =>  number_format((float)$request->sale_price, 2, '.', ''),
            'image'                 =>  $image,
            'alt'                   =>  $request->alt,
            'hsn'                   =>  $request->hsn,
            'tag'                   =>  $request->tag,
            'short_desc'            =>  $request->short_desc,
            'tax_status'            =>  $request->tax_status,
            'tax_class'             =>  $request->tax_class,
            'in_stock'              =>  $request->in_stock,
            'stock'                 =>  $request->stock,
            'low_stock'             =>  $request->low_stock,
            'sold_individually'     =>  $request->sold_individually,
            'minimum_qty'           =>  $request->minimum_qty,
            'weight'                =>  $request->weight,
            'length'                =>  $request->length,
            'width'                 =>  $request->width,
            'height'                =>  $request->height,
            'shipping_class'        =>  $request->shipping_class,
            'dropship'              =>  $request->dropship,
            'insurance'             =>  $request->insurance,
            'hazardous'             =>  $request->hazardous,
            'head_schema'           =>  $request->head_schema,
            'body_schema'           =>  $request->body_schema,
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
        if ($product->status == 1) {
            $data['msg'] = 'success';
            return response()->json($data);
        } else {
            $data['msg'] = 'danger';
            return response()->json($data);
        }
    }

    public function edit($id)
    {
        if (checkpermission('ProductController@edit')) {
            /* Getting Product data with categories for edit using Id */
            $categories = DB::table('categories')->where(['flag' => '0'])->get();

            $product = DB::table('products')->find($id);

            $dropships = DB::table('dropships')->get();

            return view('products.edit', compact('categories', 'product', 'dropships', 'id'));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function update(Request $request, $id)
    {
        /* Validating Input fields */
        $request->validate([
            'name'                  =>  'required',
            'variant'               =>  'required',
            'slug'                  =>  'required|unique:products,slug,'.$id,
            'description'           =>  'required',
            'sku'                   =>  'required',
            'stock'                 =>  'required',
            'low_stock'             =>  'required',
            'regular_price'         =>  'required',
            'sale_price'            =>  'required',
            'category_id'           =>  'required',
            'alt'                   =>  'required',
            'status'                =>  'required',
            'weight'                =>  'required',
            'height'                =>  'required',
            'width'                 =>  'required',
            'length'                =>  'required',
            'dropship'              =>  'required',
        ], [
            'name.required'                 =>  'Please Enter Product Name',
            'variant.required'              =>  'Please Enter Product Variant Name',
            'slug.required'                 =>  'Please Enter Slug',
            'description.required'          =>  'Please Enter Description',
            'sku.required'                  =>  'Please Enter SKU',
            'stock.required'                =>  'Please Enter Stock',
            'low_stock.required'            =>  'Please Enter Low Stock',
            'regular_price.required'        =>  'Please Enter Regular Price',
            'sale_price.required'           =>  'Please Enter Sale Price',
            'category_id.required'          =>  'Please Select Category',
            'alt.required'                  =>  'Please Enter Alt',
            'status.required'               =>  'Please Select Status',
            'weight.required'               =>  'Please Enter Weight',
            'height.required'               =>  'Please Enter Height',
            'width.required'                =>  'Please Enter Width',
            'length.required'               =>  'Please Enter length',
            'dropship.required'             =>  'Pleae Select Dropship Location'
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
        $product->variant               =  $request->variant;
        $product->slug                  =  $request->slug;
        $product->description           =  $request->description;
        $product->alt                   =  $request->alt;
        $product->hsn                   =  $request->hsn;
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
        $product->regular_price         =  number_format((float)$request->regular_price, 2, '.', '');
        $product->sale_price            =  number_format((float)$request->sale_price, 2, '.', '');
        $product->image                 =  $image;
        $product->tag                   =  $request->tag;
        $product->short_desc            =  $request->short_desc;
        $product->tax_status            =  $request->tax_status;
        $product->tax_class             =  $request->tax_class;
        $product->in_stock              =  $request->in_stock;
        $product->stock                 =  $request->stock;
        $product->low_stock             =  $request->low_stock;
        $product->sold_individually     =  $request->sold_individually;
        $product->minimum_qty           =  $request->minimum_qty;
        $product->weight                =  $request->weight;
        $product->length                =  $request->length;
        $product->width                 =  $request->width;
        $product->height                =  $request->height;
        $product->shipping_class        =  $request->shipping_class;
        $product->dropship              =  $request->dropship;
        $product->insurance             =  $request->insurance;
        $product->hazardous             =  $request->hazardous;
        $product->head_schema           =  $request->head_schema;
        $product->body_schema           =  $request->body_schema;
        $product->update();

        /* After successfull update of data redirecting to index page with message */
        return redirect('admin/products')->with('success', 'Product updated successfully');
    }

    public function destroy($id)
    {
        if (checkpermission('ProductController@destroy')) {
            $carts = Cart::where('product_id', $id)->get();

            if ($carts->isNotEmpty()) {
                return redirect('admin/products')->with('danger', 'Product is present in cart.');
            }

            $product = Product::find($id);
            $product->status = 0;
            $product->flag = 1;
            $product->update();
            return redirect('admin/products')->with('danger', 'Product deleted successfully');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }
}
