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
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = SalesOrder::with(['get_sales_excutive', 'get_store'])->get();
        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if ($row->get_sales_excutive->id == Auth::guard('admin')->user()->id || Auth::guard('admin')->user()->role == 'Salesman') {
                        $btn = '<a href="' . route('admin.salesOrder.destroy', ['salesOrder' => $row->id]) . '" data-title="Delete" class="btn btn-icon btn-light btn-hover-danger btn-xs mx-1 delete-data" title="Delete"><i class="far fa-trash-alt icon-sm text-danger"></i></a>';
                    } else {
                        $btn = '<a href="#" data-title="Edit" class="btn btn-icon btn-light btn-hover-primary btn-xs mx-1 modal-link disabled" title="Edit"><i class="far fa-edit icon-sm text-primary"></i></a>';
                        $btn .= '<a href="#" data-title="Delete" class="btn btn-icon btn-light btn-hover-danger btn-xs mx-1 delete-data disabled" title="Delete"><i class="far fa-trash-alt icon-sm text-danger"></i></a>';
                    }
                    return $btn;
                })

                ->addColumn('get_sales_excutive', function ($row) {
                    return $row->get_sales_excutive->name ?? '-';
                })

                ->addColumn('get_store', function ($row) {
                    return $row->get_store->name ?? '-';
                })

                ->editColumn('payment_status', function ($row) {
                    if ($row->payment_status == '1') {
                        $status = '<a href="' . route('admin.salesOrder.status', ['id' => $row->id]) . '" class="cursor-pointer label label-md font-weight-bold label-light-danger label-inline status-change">Pending</a>';
                    } else {
                        $status = '<a href="' . route('admin.salesOrder.status', ['id' => $row->id]) . '" class="cursor-pointer label label-md font-weight-bold label-light-success label-inline status-change">Paid</a>';
                    }
                    return $status;
                })

                ->rawColumns(['action', 'payment_status', 'get_sales_excutive', 'get_store'])
                ->make(true);
        }
        $title = "Sales Order List";
        return view('admin.Salesorder.index', compact('title', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $salesexecutives = SalesExecutive::where('status', '1')->get();
        $stores = Store::all();
        return view('admin.Salesorder.create', compact('stores', 'salesexecutives'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'order_date' => ['required'],
                'total_amount' => ['required'],
                'sales_executive_fk' => ['required'],
                'store_fk' => ['required'],
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
                        'order_date' => $request->input('order_date'),
                        'total_amount' => $request->input('total_amount'),
                        'sales_executive_fk' => $request->input('sales_executive_fk'),
                        'store_fk' => $request->input('store_fk'),
                        'payment_status' => 1,
                    ];
                    SalesOrder::create($data);
                });
            }

            return Response::json(
                array(
                    'error' => false,
                    'errors' => null,
                    'success' => true,
                    'msg' => "Sales Order created successfully.",
                )
            );
        } catch (Exception $e) {
            Log::error("Admin/SalesOrderController.php:- store() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SalesOrder $salesOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalesOrder $salesOrder)
    {
        $salesexecutives = SalesExecutive::where('status', '1')->get();
        $stores = Store::all();
        return view('admin.Salesorder.create', compact('stores', 'salesexecutives', 'salesOrder'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalesOrder $salesOrder)
    {
        try {
            $rules = [
                'order_date' => ['required'],
                'total_amount' => ['required'],
                'sales_executive_fk' => ['required'],
                'store_fk' => ['required'],
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
                DB::transaction(function () use ($request, $salesOrder) {
                    $data = [];
                    $data['order_date'] = $request->order_date;
                    $data['total_amount'] = $request->total_amount;
                    $data['sales_executive_fk'] = $request->sales_executive_fk;
                    $data['store_fk'] = $request->store_fk;
                    $salesOrder->update($data);
                });
            }

            return Response::json(
                array(
                    'error' => false,
                    'errors' => null,
                    'success' => true,
                    'msg' => "SalesOrder Update successfully.",
                )
            );
        } catch (Exception $e) {
            Log::error("Admin/SalesOrderController.php:- update() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesOrder $salesOrder)
    {
        try {
            $salesOrder->delete();
            return Response::json(
                array(
                    'error' => false,
                    'errors' => NULL,
                    'success' => true,
                    'msg' => "SalesOrder Deleted successfully.",
                )
            );
        } catch (Exception $e) {
            Log::error("Admin/SalesOrderController.php:- destroy() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return catchReponse($e);
        }
    }

    public function status($id)
    {
        try {
            $data = SalesOrder::where(['id' => $id])->first();
            if ($data->payment_status) {
                $payment_status = "1";
            } else {
                $payment_status = "0";
            }
            SalesOrder::where('id', $data->id)
                ->update([
                    'payment_status' => $payment_status
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
            Log::error("Admin/SalesOrderController.php.php:- status() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return catchReponse($e);
        }
    }
}
