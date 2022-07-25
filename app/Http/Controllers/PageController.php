<?php

namespace App\Http\Controllers;

use App\Http\Classes\Upload;

use App\Models\LinkCategory;
use App\Models\LinksOnPage;
use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class PageController extends Controller
{
    public function index(Request $request)
    {
        if (checkpermission('PageController@index')) {
            if (request()->ajax()) {
                /* Getting all records */
                $active = $request->active == 'all' ? array('1', '2', '0') : array($request->active);
                $allpages = DB::table('pages')->select('id', 'title', 'slug', 'status')->where(['flag' => '0'])->whereIn('status', $active)->orderby('created_at', 'desc')->get();

                /* Converting Selected Data into desired format */
                $pages = new Collection;
                foreach ($allpages as $page) {
                    $pages->push([
                        'id'        => $page->id,
                        'title'     => $page->title,
                        'slug'      => $page->slug,
                        'status'    => $page->status
                    ]);
                }

                /* Sending data through yajra datatable for server side rendering */
                return Datatables::of($pages)
                    ->addIndexColumn()
                    /* Status Active and Deactivated Checkbox */
                    ->addColumn('active', function ($row) {
                        $checked = $row['status'] == '1' ? 'checked' : '';
                        $active  = '<div class="form-check form-switch form-check-custom form-check-solid">
                                        <input type="hidden" value="' . $row['id'] . '" class="page_id">
                                        <input type="checkbox" class="form-check-input js-switch  h-20px w-30px" id="customSwitch1" name="status" value="' . $row['status'] . '" ' . $checked . '>
                                    </div>';

                        return $active;
                    })
                    /* Adding Actions like edit, delete and show */
                    ->addColumn('action', function ($row) {
                        $delete_url = url('admin/pages/delete', $row['id']);
                        $edit_url = url('admin/pages/edit', $row['id']);
                        $copy_url = url('admin/pages/copy', $row['id']);
                        $btn = '<div style="display:flex;">
                                    <a class="btn btn-primary btn-xs" href="' . $edit_url . '" style="margin-right: 2px;"><i class="fas fa-edit"></i></a>
                                    <a class="btn btn-info btn-xs ml-1" href="' . $copy_url . '"  style="margin-right: 2px;"><i class="fa fa-clone"></i></a>
                                    <form action="' . $delete_url . '" method="post">
                                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="delete btn btn-danger btn-xs page_confirm"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'active'])
                    ->make(true);
            }

            return view('pages.index');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function create()
    {
        if (checkpermission('PageController@create')) {
            /* Getting All Link Categories */
            $categories = LinkCategory::all();
            /* Getting Parent Pages Data */
            $parentpages = DB::table('pages')->select('id', 'title')->where(['flag' => '0', 'status' => 1])->get();
            /* Getting All Pages */
            $links = DB::table('pages')->select('id', 'title')->where(['flag' => '0', 'status' => 1])->get();

            /* Loading Create Page */
            return view('pages.create', compact('categories', 'links', 'parentpages'));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function copy($id)
    {
        if (checkpermission('PageController@create')) {
            /* Getting All Link Categories */
            $categories = LinkCategory::all();
            /* Getting Page data for edit using Id */
            $page = DB::table('pages')->find($id);
            /* Getting Parent Pages Data */
            $parentpages = DB::table('pages')->select('id', 'title')->where(['flag' => '0', 'status' => 1])->where('id', '!=', $id)->get();
            /* Getting All Pages */
            $links = DB::table('pages')->select('id', 'title')->where(['flag' => '0', 'status' => 1])->get();
            /* Getting All Saved Page Links */
            $pagelinksobject = DB::table('links_on_pages')->select('id', 'link_id')->where('page_id', $id)->get();

            $pagelinks = array();
            foreach ($pagelinksobject as $value) {
                array_push($pagelinks, $value->link_id);
            }

            return view('pages.copy', compact('page', 'id', 'categories', 'links', 'parentpages', 'pagelinks'));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function store(Request $request)
    {
        /* Validating Input fields */
        $request->validate([
            'title'                 =>  'required',
            'description'           =>  'required',
            'slug'                  =>  'required',
            'status'                =>  'required',
        ], [
            'title.required'                =>  'Page Title is required',
            'description.required'          =>  'Page Description is required',
            'slug.required'                 =>  'Page Slug is required',
            'status.required'               =>  'Page Status is required',
        ]);

        /* Storing Featured Image on local disk */
        $image = "";
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->extension();
            $file = $request->file('image');
            $fileNameString = (string) Str::uuid();
            $image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/pages/', $file, $image);
        }

        /* Storing OG Image on local disk */
        $og_image = "";
        if ($request->hasFile('og_image')) {
            $extension = $request->file('og_image')->extension();
            $file = $request->file('og_image');
            $fileNameString = (string) Str::uuid();
            $og_image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/pages/og_images', $file, $og_image);
        }

        /* Storing Data in Table */
        $page = Page::create([
            'slug'                      =>  $request->slug,
            'title'                     =>  $request->title,
            'description'               =>  $request->description,
            'image'                     =>  $image,
            'meta_title'                =>  $request->meta_title,
            'meta_description'          =>  $request->meta_description,
            'keywords'                  =>  $request->keywords,
            'tags'                      =>  $request->tags,
            'alt'                       =>  $request->alt,
            'status'                    =>  $request->status,
            'og_title'                  =>  $request->og_title,
            'og_description'            =>  $request->og_description,
            'og_image'                  =>  $og_image,
            'category'                  =>  $request->category,
            'parent_id'                 =>  $request->parent_id,
            'head_schema'               =>  $request->head_schema,
            'body_schema'               =>  $request->body_schema,
        ]);

        /* Storing links related to this Page in Table */
        if (!empty($request->pagelinks)) {
            foreach ($request->pagelinks as $pagelink) {
                LinksOnPage::create([
                    'page_id'   =>  $page->id,
                    'link_id'   =>  $pagelink,
                ]);
            }
        }

        /* After Successfull insertion of data redirecting to listing page with message */
        return redirect('admin/pages')->with('success', 'Page Created Successfully');
    }

    public function update_status(Request $request)
    {
        /* Updating status of selected entry */
        $page = Page::find($request->page_id);
        $page->status   = $request->status == 1 ? 0 : 1;
        $page->update();
        if ($page->status == 1) {
            $data['msg'] = 'success';
            return response()->json($data);
        } else {
            $data['msg'] = 'danger';
            return response()->json($data);
        }
    }

    public function edit($id)
    {
        if (checkpermission('PageController@edit')) {
            /* Getting All Link Categories */
            $categories = LinkCategory::all();
            /* Getting Page data for edit using Id */
            $page = DB::table('pages')->find($id);
            /* Getting Parent Pages Data */
            $parentpages = DB::table('pages')->select('id', 'title')->where(['flag' => '0', 'status' => 1])->where('id', '!=', $id)->get();
            /* Getting All Pages */
            $links = DB::table('pages')->select('id', 'title')->where(['flag' => '0', 'status' => 1])->where('id', '!=', $id)->get();
            /* Getting All Saved Page Links */
            $pagelinksobject = DB::table('links_on_pages')->select('id', 'link_id')->where('page_id', $id)->get();
            $pagelinks = array();
            foreach ($pagelinksobject as $value) {
                array_push($pagelinks, $value->link_id);
            }

            return view('pages.edit', compact('page', 'id', 'categories', 'links', 'parentpages', 'pagelinks'));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function update(Request $request, $id)
    {
        /* Validating Input fields */
        $request->validate([
            'title'                 =>  'required',
            'description'           =>  'required',
            'slug'                  =>  'required',
            'status'                =>  'required',
        ], [
            'title.required'                =>  'Page Title is required',
            'description.required'          =>  'Page Description is required',
            'slug.required'                 =>  'Page Slug is required',
            'status.required'               =>  'Page Status is required',
        ]);

        /* Fetching Blog Data using Id */
        $page = Page::find($id);

        /* Storing Featured Image on local disk */
        $image = $page->image;
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->extension();
            $file = $request->file('image');
            $fileNameString = (string) Str::uuid();
            $image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/pages/', $file, $image);
        }

        /* Storing OG Image on local disk */
        $og_image = $page->og_image;
        if ($request->hasFile('og_image')) {
            $extension = $request->file('og_image')->extension();
            $file = $request->file('og_image');
            $fileNameString = (string) Str::uuid();
            $og_image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/pages/og_images', $file, $og_image);
        }

        /* Updating Data fetched by Id */
        $page->slug                     =  $request->slug;
        $page->title                    =  $request->title;
        $page->description              =  $request->description;
        $page->image                    =  $image;
        $page->meta_title               =  $request->meta_title;
        $page->meta_description         =  $request->meta_description;
        $page->keywords                 =  $request->keywords;
        $page->tags                     =  $request->tags;
        $page->alt                      =  $request->alt;
        $page->status                   =  $request->status;
        $page->og_title                 =  $request->og_title;
        $page->og_description           =  $request->og_description;
        $page->og_image                 =  $og_image;
        $page->category                 =  $request->category;
        $page->parent_id                =  $request->parent_id;
        $page->head_schema              =  $request->head_schema;
        $page->body_schema              =  $request->body_schema;
        $page->update();

        if (!empty($request->pagelinks)) {
            /* Find All Links related to page stored in table */
            $oldpagelinks = LinksOnPage::where('page_id', $id)->get();
            foreach ($oldpagelinks as $link) {
                /* Deleting Old links */
                $link->delete();
            }

            /* Storing New links related to this Page in Table */
            foreach ($request->pagelinks as $pagelink) {
                LinksOnPage::create([
                    'page_id'   =>  $id,
                    'link_id'   =>  $pagelink,
                ]);
            }
        }

        /* After successfull update of data redirecting to index page with message */
        return redirect('admin/pages')->with('success', 'Page Updated Successfully');
    }

    public function destroy($id)
    {
        if (checkpermission('PageController@destroy')) {
            /* Updating selected entry Flag to 1 for soft delete */
            Page::where('id', $id)->update(['flag' => 1, 'status' => 0]);

            return redirect('admin/pages')->with('danger', 'Page Deleted');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function summernote(Request $request)
    {
        $URL = '//' . $_SERVER['HTTP_HOST'];
        define('URL', $URL);

        $resp_mes = '';
        $resp = '';

        $root_url = env('APP_URL');
        $currentURL = url()->previous();
        $url_find = explode("/", $currentURL);
        // dd($url_find[4]);


        // If a file is present...
        if (isset($_FILES["file"]["type"])) {

            $photo = $_FILES["file"];

            if (!empty($photo) && $photo["size"] > 0) {

                // Generate a unique name for the directory
                $unique_photo_name = sha1(time() . mt_rand(0, 99999));

                $handle = new Upload($photo);
                $photo_mime_type = $handle->file_src_mime;

                if ($handle->uploaded) {
                    $complete_dir_name = 'storage/' . $url_find[4] . '/';

                    // Parameters before uploading the photo
                    $handle->image_resize = false;
                    $handle->png_compression = 8;
                    $handle->webp_quality = 80;
                    $handle->jpeg_quality = 80;
                    $handle->file_new_name_body = $unique_photo_name;

                    $handle->Process($complete_dir_name);

                    if ($handle->processed) {

                        $handle->clean();

                        $photo_url = $complete_dir_name . $handle->file_dst_name;
                        $photo_url_path = str_replace("../", "", $photo_url);

                        $resp = URL . "/" . $photo_url_path; // gives back image name for editor
                        $resp_mes = 'ok';
                    } else {
                        $resp_mes = 'Error: Could not upload the file...';
                    }
                } else {
                    $resp_mes = 'Error: Could not upload the file...';
                }
            }
        } else {
            $resp_mes = 'Error: Please pick a file!';
        }


        // Send response back to javascript
        echo json_encode(array(
            'response' => $resp,
            'message' => $resp_mes
        ));
    }
}
