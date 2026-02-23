<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalesOrder;
use App\Models\Product;
use App\Models\SalesExecutive;
use App\Models\Store;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

use App\Models\SalesOrderItems;
use Illuminate\Http\Request;

class SalesOrderItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = SalesOrderItems::with(['get_sales_order', 'get_product'])->get();
        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="' . route('admin.salesOrderItem.edit', ['salesOrderItem' => $row->id]) . '" data-title="Edit" class="btn btn-icon btn-light btn-hover-primary btn-xs mx-1 modal-link" title="Edit"><i class="far fa-edit icon-sm text-primary"></i></a>';
                    $btn .= '<a href="' . route('admin.salesOrderItem.destroy', ['salesOrderItem' => $row->id]) . '" data-title="Delete" class="btn btn-icon btn-light btn-hover-danger btn-xs mx-1 delete-data" title="Delete"><i class="far fa-trash-alt icon-sm text-danger"></i></a>';
                    return $btn;
                })

                ->addColumn('sales_order_fk', function ($row) {
                    return $row->get_sales_order->id ?? '-';
                })

                ->addColumn('product_fk', function ($row) {
                    return $row->get_product->name ?? '-';
                })

                ->rawColumns(['action', 'get_sales_excutive', 'get_store'])
                ->make(true);
        }
        $title = "Sales Order Item List";
        return view('admin.salesOrderItem.index', compact('title', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $salesorders = SalesOrder::where('payment_status', '1')->get();
        $products = Product::all();
        return view('admin.salesOrderItem.create', compact('salesorders', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'quantity' => ['required'],
                'price' => ['required'],
                'total' => ['required'],
                'gst' => ['required'],
                'sales_order_fk' => ['required'],
                'product_fk' => ['required'],
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
                        'quantity' => $request->input('quantity'),
                        'price' => $request->input('price'),
                        'total' => $request->input('total'),
                        'gst' => $request->input('gst'),
                        'sales_order_fk' => $request->input('sales_order_fk'),
                        'product_fk' => $request->input('product_fk'),
                    ];
                    SalesOrderItems::create($data);
                });
            }

            return Response::json(
                array(
                    'error' => false,
                    'errors' => null,
                    'success' => true,
                    'msg' => "Sales Order Items created successfully.",
                )
            );
        } catch (Exception $e) {
            Log::error("Admin/SalesOrderItemsController.php:- store() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SalesOrderItems $salesOrderItems)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalesOrderItems $salesOrderItem)
    {
        $salesorders = SalesOrder::where('payment_status', '1')->get();
        $products = Product::all();
        return view('admin.salesOrderItem.create', compact('salesorders', 'products', 'salesOrderItem'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalesOrderItems $salesOrderItem)
    {
        try {
            $rules = [
                'quantity' => ['required'],
                'price' => ['required'],
                'total' => ['required'],
                'gst' => ['required'],
                'sales_order_fk' => ['required'],
                'product_fk' => ['required'],
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
                DB::transaction(function () use ($request, $salesOrderItem) {
                    $data = [];
                    $data['quantity'] = $request->quantity;
                    $data['price'] = $request->price;
                    $data['total'] = $request->total;
                    $data['gst'] = $request->gst;
                    $data['sales_order_fk'] = $request->sales_order_fk;
                    $data['product_fk'] = $request->product_fk;
                    $salesOrderItem->update($data);
                });
            }

            return Response::json(
                array(
                    'error' => false,
                    'errors' => null,
                    'success' => true,
                    'msg' => "Sales Order Item Update successfully.",
                )
            );
        } catch (Exception $e) {
            Log::error("Admin/SalesOrderItemsController.php:- update() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesOrderItems $salesOrderItem)
    {
        try {
            $salesOrderItem->delete();
            return Response::json(
                array(
                    'error' => false,
                    'errors' => NULL,
                    'success' => true,
                    'msg' => "Sales Order Items Deleted successfully.",
                )
            );
        } catch (Exception $e) {
            Log::error("Admin/SalesOrderItemsController.php:- destroy() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return catchReponse($e);
        }
    }
}
