<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Product::get();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="' . route('admin.product.edit', ['product' => $row->id]) . '" data-title="Edit" class="btn btn-icon btn-light btn-hover-primary btn-xs mx-1 modal-link" title="Edit"><i class="far fa-edit icon-sm text-primary"></i></a>';
                    $btn .= '<a href="' . route('admin.product.destroy', ['product' => $row->id]) . '" data-title="Delete" class="btn btn-icon btn-light btn-hover-danger btn-xs mx-1 delete-data" title="Delete"><i class="far fa-trash-alt icon-sm text-danger"></i></a>';
                    return $btn;
                })

                ->addColumn('category_fk', function ($row) {
                    return $row->get_category->name ?? '-';
                })

                ->addColumn('image', function ($row) {
                    $imageHtml = '';

                    $images = json_decode($row->image, true);

                    if (!empty($images)) {
                        foreach ($images as $img) {

                            $imageHtml .= "<img src='{$img}' style='width:50px;height:50px;border-radius:5px;margin-right:5px;'>";
                        }
                    }

                    return $imageHtml ?: 'No Image';
                })

                ->editColumn('status', function ($row) {
                    if ($row->status == '1') {
                        $status = '<a href="' . route('admin.product.status', ['id' => $row->id]) . '" class="cursor-pointer label label-md font-weight-bold label-light-success label-inline status-change">Available</a>';
                    } else {
                        $status = '<a href="' . route('admin.product.status', ['id' => $row->id]) . '" class="cursor-pointer label label-md font-weight-bold label-light-danger label-inline status-change">Out of Stock</a>';
                    }
                    return $status;
                })
                ->rawColumns(['action', 'status', 'category_fk', 'image'])
                ->make(true);
        }
        $title = "Product List";
        return view('admin.Product.index', compact('title', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorys = Category::where('status', '1')->get();
        return view('admin.Product.create', compact('categorys'));
    }

    public function upload(Request $request)
    {
        $file = $request->file('image');

        $path = Storage::disk('supabase')->put('assets', $file);

        return response()->json([
            'path' => $path,
            'url' => Storage::disk('supabase')->url($path)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'category_fk' => ['required'],
                'image' => ['required', 'array'],
                'image.*' => ['image', 'mimes:jpg,jpeg,png', 'max:2048'],
                'name' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u', 'min:5', 'max:100'],
                'weight' => ['required', 'max:100'],
                'price' => ['required'],
                'variation_gram' => ['required'],
                'gst' => ['required']
            ];

            $validator = Validator::make($request->all(), $rules, [
                'required' => 'Required',
            ]);

            if ($validator->fails()) {
                return Response::json(
                    array(
                        'error' => true,
                        'errors' => $validator->getMessageBag(),
                        'success' => false,
                        'msg' => "",
                    )
                );
            } else {

                $imageUrls = [];

                if ($request->hasFile('image')) {
                    foreach ($request->file('image') as $image) {
                        if ($image->isValid()) {

                            $imageName = time() . '_' . $image->getClientOriginalName();

                            // Upload to Supabase bucket (assets/product)
                            Http::withHeaders([
                                'Authorization' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Imx6dGxkdmN4aHdsYXl1eXFtaXhuIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc3MTA0MjA0MywiZXhwIjoyMDg2NjE4MDQzfQ.6R0MX0_R0oycf6BsfeA81OA5whSTJrAotP95z4X2JNE',
                            ])->attach(
                                'file',
                                file_get_contents($image->getRealPath()),
                                $imageName
                            )->post('https://lztldvcxhwlayuyqmixn.supabase.co/storage/v1/object/assets/product/' . $imageName);

                            // Save public URL
                            $imageUrl = 'https://lztldvcxhwlayuyqmixn.supabase.co/storage/v1/object/public/assets/product/' . $imageName;

                            $imageNames[] = $imageUrl;
                        }
                    }
                }

                DB::transaction(function () use ($request, $imageNames) {
                    $data = [
                        'image' => json_encode($imageNames),
                        'category_fk' => $request->input('category_fk'),
                        'name' => $request->input('name'),
                        'weight' => $request->input('weight'),
                        'price' => $request->input('price'),
                        'variation_gram' => $request->input('variation_gram'),
                        'gst' => $request->input('gst'),
                        'status' => 1,
                    ];
                    Product::create($data);
                });
            }

            return Response::json(
                array(
                    'error' => false,
                    'errors' => null,
                    'success' => true,
                    'msg' => "Product created successfully.",
                )
            );
        } catch (Exception $e) {
            dd($e);
            Log::error("Admin/ProductController.php:- store() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        try {
            $categorys = Category::where('status', '1')->get();
            return view('admin.product.create', compact('categorys', 'product'));
        } catch (Exception $e) {
            Log::error("Admin/ProductController.php:- edit() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        try {
            $rules = [
                'category_fk' => ['required'],
                'image' => ['required', 'array'],
                'image.*' => ['image', 'mimes:jpg,jpeg,png', 'max:2048'],
                'name' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u', 'min:5', 'max:100'],
                'weight' => ['required', 'max:100'],
                'price' => ['required'],
                'variation_gram' => ['required'],
                'gst' => ['required']
            ];

            $validator = Validator::make($request->all(), $rules, [
                'required' => 'required',
            ]);

            if ($validator->fails()) {
                return Response::json(
                    array(
                        'error' => true,
                        'errors' => $validator->getMessageBag(),
                        'success' => false,
                        'msg' => "",
                    )
                );
            } else {
                $imageNames = [];

                // if ($request->hasFile('image')) {
                //     foreach ($request->file('image') as $image) {
                //         if ($image->isValid()) {
                //             $imageName = time() . '_' . $image->getClientOriginalName();
                //             $image->move(public_path('assets/admin/image/product/'), $imageName);
                //             $imageNames[] = $imageName;
                //         }
                //     }
                // }
                if ($request->hasFile('image')) {
                    foreach ($request->file('image') as $image) {
                        if ($image->isValid()) {

                            $imageName = time() . '_' . $image->getClientOriginalName();

                            // Upload to Supabase bucket (assets/product)
                            Http::withHeaders([
                                'Authorization' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Imx6dGxkdmN4aHdsYXl1eXFtaXhuIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc3MTA0MjA0MywiZXhwIjoyMDg2NjE4MDQzfQ.6R0MX0_R0oycf6BsfeA81OA5whSTJrAotP95z4X2JNE',
                            ])->attach(
                                'file',
                                file_get_contents($image->getRealPath()),
                                $imageName
                            )->post('https://lztldvcxhwlayuyqmixn.supabase.co/storage/v1/object/assets/product/' . $imageName);

                            // Save public URL
                            $imageUrl = 'https://lztldvcxhwlayuyqmixn.supabase.co/storage/v1/object/public/assets/product/' . $imageName;

                            $imageNames[] = $imageUrl;
                        }
                    }
                }

                DB::transaction(function () use ($request, $product, $imageNames) {
                    $data = [];
                    $data['image'] = json_encode($imageNames);
                    $data['category_fk'] = $request->category_fk;
                    $data['name'] = $request->name;
                    $data['weight'] = $request->weight;
                    $data['price'] = $request->price;
                    $data['variation_gram'] = $request->variation_gram;
                    $data['gst'] = $request->gst;
                    $product->update($data);
                });

                return Response::json(
                    array(
                        'error' => false,
                        'errors' => null,
                        'success' => true,
                        'msg' => "Product updated successfully."

                    )
                );
            }
        } catch (Exception $e) {
            dd($e);
            Log::error("Admin/ProductController.php:- update() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            // return catchReponse($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return Response::json(
                array(
                    'error' => false,
                    'errors' => NULL,
                    'success' => true,
                    'msg' => "Product Deleted successfully.",
                )
            );
        } catch (Exception $e) {
            Log::error("Admin/ProductController.php:- destroy() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return catchReponse($e);
        }
    }

    public function status($id)
    {
        try {
            $data = Product::where(['id' => $id])->first();
            if ($data->status) {
                $status = "0";
            } else {
                $status = "1";
            }
            Product::where('id', $data->id)
                ->update([
                    'status' => $status
                ]);
            return Response::json(
                array(
                    'error' => false,
                    'errors' => NULL,
                    'success' => true,
                    'msg' => "Status changed successfully."
                )
            );
        } catch (Exception $e) {
            Log::error("Admin/ProductController.php.php:- status() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return catchReponse($e);
        }
    }
}
