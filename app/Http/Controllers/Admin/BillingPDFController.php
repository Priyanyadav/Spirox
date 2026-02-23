<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillingPDF;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\SalesExecutive;
use App\Models\SalesOrder;
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

class BillingPDFController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = BillingPDF::with(['get_sales_order'])->get();
        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="' . route('admin.BillingPDF.edit', ['BillingPDF' => $row->id]) . '" data-title="Edit" class="btn btn-icon btn-light btn-hover-primary btn-xs mx-1 modal-link" title="Edit"><i class="far fa-edit icon-sm text-primary"></i></a>';
                    $btn .= '<a href="' . route('admin.BillingPDF.destroy', ['BillingPDF' => $row->id]) . '" data-title="Delete" class="btn btn-icon btn-light btn-hover-danger btn-xs mx-1 delete-data" title="Delete"><i class="far fa-trash-alt icon-sm text-danger"></i></a>';
                    return $btn;
                })

                ->addColumn('sales_order_fk', function ($row) {
                    return $row->get_sales_order->id ?? '-';
                })

                ->editColumn('shared_via', function ($row) {
                    if ($row->shared_via == '0') {
                        $shared_via = '<a href="' . route('admin.BillingPDF.status', ['id' => $row->id]) . '" class="cursor-pointer label label-md font-weight-bold label-light-danger label-inline status-change">Email</a>';
                    } else {
                        $shared_via = '<a href="' . route('admin.BillingPDF.status', ['id' => $row->id]) . '" class="cursor-pointer label label-md font-weight-bold label-light-success label-inline status-change">WhatsApp</a>';
                    }
                    return $shared_via;
                })

                ->rawColumns(['action', 'sales_order_fk', 'shared_via'])
                ->make(true);
        }
        $title = "Billing PDF List";
        return view('admin.BillingPDF.index', compact('title', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $salesorders = SalesOrder::where('payment_status', '1')->get();
        return view('admin.BillingPDF.create', compact('salesorders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'pdf_url' => ['required'],
                'shared_at' => ['required'],
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
                        'pdf_url' => $request->input('pdf_url'),
                        'shared_at' => $request->input('shared_at'),
                        'sales_order_fk' => $request->input('sales_order_fk'),
                        'shared_via' => 0,
                    ];
                    BillingPDF::create($data);
                });
            }

            return Response::json(
                array(
                    'error' => false,
                    'errors' => null,
                    'success' => true,
                    'msg' => "Billing PDF created successfully.",
                )
            );
        } catch (Exception $e) {
            Log::error("Admin/BillingPDFController.php:- store() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BillingPDF $billingPDF)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BillingPDF $BillingPDF)
    {
        $salesorders = SalesOrder::where('payment_status', '1')->get();
        return view('admin.BillingPDF.create', compact('salesorders', 'BillingPDF'));
    }   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BillingPDF $BillingPDF)
    {

        try {
            $rules = [
                'pdf_url' => ['required'],
                'shared_at' => ['required'],
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
                DB::transaction(function () use ($request, $BillingPDF) {
                    $data = [];
                    $data['pdf_url'] = $request->pdf_url;
                    $data['shared_at'] = $request->shared_at;
                    $data['sales_order_fk'] = $request->sales_order_fk;
                    $BillingPDF->update($data);
                });
            }

            return Response::json(
                array(
                    'error' => false,
                    'errors' => null,
                    'success' => true,
                    'msg' => "Billing PDF Update successfully.",
                )
            );
        } catch (Exception $e) {
            Log::error("Admin/BillingPDFController.php:- update() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BillingPDF $BillingPDF)
    {
        try {
            $BillingPDF->delete();
            return Response::json(
                array(
                    'error' => false,
                    'errors' => NULL,
                    'success' => true,
                    'msg' => "Billing PDF Deleted successfully.",
                )
            );
        } catch (Exception $e) {
            Log::error("Admin/BillingPDFController.php:- destroy() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return catchReponse($e);
        }
    }

    public function status($id)
    {
        try {
            $data = BillingPDF::where(['id' => $id])->first();
            if ($data->shared_via) {
                $shared_via = "0";
            } else {
                $shared_via = "1";
            }
            BillingPDF::where('id', $data->id)
                ->update([
                    'shared_via' => $shared_via
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
            Log::error("Admin/BillingPDFController.php.php:- status() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return catchReponse($e);
        }
    }
}
