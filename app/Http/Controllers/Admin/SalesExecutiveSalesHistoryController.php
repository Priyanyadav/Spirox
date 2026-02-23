<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalesExecutiveSalesHistory;
use Illuminate\Http\Request;
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

class SalesExecutiveSalesHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = SalesExecutiveSalesHistory::with(['get_sales_order', 'get_sales_exe'])->get();
        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="' . route('admin.salesExe.edit', ['salesExe' => $row->id]) . '" data-title="Edit" class="btn btn-icon btn-light btn-hover-primary btn-xs mx-1 modal-link" title="Edit"><i class="far fa-edit icon-sm text-primary"></i></a>';
                    $btn .= '<a href="' . route('admin.salesExe.destroy', ['salesExe' => $row->id]) . '" data-title="Delete" class="btn btn-icon btn-light btn-hover-danger btn-xs mx-1 delete-data" title="Delete"><i class="far fa-trash-alt icon-sm text-danger"></i></a>';
                    return $btn;
                })

                ->addColumn('sales_order_fk', function ($row) {
                    return $row->get_sales_order->id ?? '-';
                })

                ->addColumn('get_sales_exe', function ($row) {
                    return $row->get_sales_exe->name ?? '-';
                })

                ->rawColumns(['action', 'get_sales_excutive', 'get_store'])
                ->make(true);
        }
        $title = "Sales Order History List";
        return view('admin.salesOrderHistory.index', compact('title', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $salesexecutives = SalesExecutive::where('status', '1')->get();
        $salesorders = SalesOrder::where('payment_status', '1')->get();
        return view('admin.salesOrderHistory.create', compact('salesexecutives', 'salesorders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'sale_amount' => ['required'],
                'sale_date' => ['required'],
                'sales_exec_id' => ['required'],
                'sales_order_fk' => ['required'],
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
                        'sale_amount' => $request->input('sale_amount'),
                        'sale_date' => $request->input('sale_date'),
                        'sales_exec_id' => $request->input('sales_exec_id'),
                        'sales_order_fk' => $request->input('sales_order_fk'),
                    ];
                    SalesExecutiveSalesHistory::create($data);
                });
            }

            return Response::json(
                array(
                    'error' => false,
                    'errors' => null,
                    'success' => true,
                    'msg' => "Sales Executive History created successfully.",
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
    public function show(SalesExecutiveSalesHistory $salesExecutiveSalesHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalesExecutiveSalesHistory $salesExe)
    {
        $salesexecutives = SalesExecutive::where('status', '1')->get();
        $salesorders = SalesOrder::where('payment_status', '1')->get();
        return view('admin.salesOrderHistory.create', compact('salesexecutives', 'salesorders', 'salesExe'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalesExecutiveSalesHistory $salesExe)
    {
        try {
            $rules = [
                'sale_amount' => ['required'],
                'sale_date' => ['required'],
                'sales_exec_id' => ['required'],
                'sales_order_fk' => ['required'],
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
                DB::transaction(function () use ($request, $salesExe) {
                    $data = [];
                    $data['sale_amount'] = $request->sale_amount;
                    $data['sale_date'] = $request->sale_date;
                    $data['sales_exec_id'] = $request->sales_exec_id;
                    $data['sales_order_fk'] = $request->sales_order_fk;
                    $salesExe->update($data);
                });
            }

            return Response::json(
                array(
                    'error' => false,
                    'errors' => null,
                    'success' => true,
                    'msg' => "Sales Executive Histroy Update successfully.",
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
    public function destroy(SalesExecutiveSalesHistory $salesExe)
    {
        try {
            $salesExe->delete();
            return Response::json(
                array(
                    'error' => false,
                    'errors' => NULL,
                    'success' => true,
                    'msg' => "Sales Executive History Deleted successfully.",
                )
            );
        } catch (Exception $e) {
            Log::error("Admin/salesExecutiveSalesHistoryController.php:- destroy() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return catchReponse($e);
        }
    }
}
