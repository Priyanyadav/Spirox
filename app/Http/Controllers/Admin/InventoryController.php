<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\SalesExecutive;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Inventory::with('get_product')->get();
        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="' . route('admin.inventory.edit', ['inventory' => $row->id]) . '" data-title="Edit" class="btn btn-icon btn-light btn-hover-primary btn-xs mx-1 modal-link" title="Edit"><i class="far fa-edit icon-sm text-primary"></i></a>';
                    $btn .= '<a href="' . route('admin.inventory.destroy', ['inventory' => $row->id]) . '" data-title="Delete" class="btn btn-icon btn-light btn-hover-danger btn-xs mx-1 delete-data" title="Delete"><i class="far fa-trash-alt icon-sm text-danger"></i></a>';
                    return $btn;
                })

                ->addColumn('get_product', function ($row) {
                    return $row->get_product->name ?? '-';
                })

                ->rawColumns(['action', 'get_product'])
                ->make(true);
        }
        $title = "Store List";
        return view('admin.Inventory.index', compact('title', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('status', '1')->get();
        return view('admin.Inventory.create', compact('products'));
    }

    /**F
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'batch_no' => ['required'],
                'quantity' => ['required'],
                'manufacture_date' => ['required'],
                'expiry_date' => ['required'],
                'product_fk' => ['required']
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
                DB::transaction(function () use ($request) {
                    $data = [
                        'batch_no' => $request->input('batch_no'),
                        'quantity' => $request->input('quantity'),
                        'manufacture_date' => $request->input('manufacture_date'),
                        'expiry_date' => $request->input('expiry_date'),
                        'product_fk' => $request->input('product_fk'),
                    ];
                    Inventory::create($data);
                });
            }

            return Response::json(
                array(
                    'error' => false,
                    'errors' => null,
                    'success' => true,
                    'msg' => "Inventory created successfully.",
                )
            );
        } catch (Exception $e) {
            Log::error("Admin/InventoryController.php:- store() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        $products = Product::where('status', '1')->get();
        return view('admin.Inventory.create', compact('products', 'inventory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        try {
            $rules = [
                'batch_no' => ['required'],
                'quantity' => ['required'],
                'manufacture_date' => ['required'],
                'expiry_date' => ['required'],
                'product_fk' => ['required']
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
                DB::transaction(function () use ($request, $inventory) {
                    $data = [];
                    $data['batch_no'] = $request->batch_no;
                    $data['quantity'] = $request->quantity;
                    $data['manufacture_date'] = $request->manufacture_date;
                    $data['expiry_date'] = $request->expiry_date;
                    $data['product_fk'] = $request->product_fk;
                    $inventory->update($data);
                });
            }

            return Response::json(
                array(
                    'error' => false,
                    'errors' => null,
                    'success' => true,
                    'msg' => "Inventory Update successfully.",
                )
            );
        } catch (Exception $e) {
            Log::error("Admin/InventoryController.php:- update() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        try {
            $inventory->delete();
            return Response::json(
                array(
                    'error' => false,
                    'errors' => NULL,
                    'success' => true,
                    'msg' => "Inventory Deleted successfully.",
                )
            );
        } catch (Exception $e) {
            Log::error("Admin/InventoryController.php:- destroy() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return catchReponse($e);
        }
    }
}
