<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
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
use App\Models\Store;
use Illuminate\Http\Request;


class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Store::with('get_sales')->get();
        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="' . route('admin.store.edit', ['store' => $row->id]) . '" data-title="Edit" class="btn btn-icon btn-light btn-hover-primary btn-xs mx-1 modal-link" title="Edit"><i class="far fa-edit icon-sm text-primary"></i></a>';
                    $btn .= '<a href="' . route('admin.store.destroy', ['store' => $row->id]) . '" data-title="Delete" class="btn btn-icon btn-light btn-hover-danger btn-xs mx-1 delete-data" title="Delete"><i class="far fa-trash-alt icon-sm text-danger"></i></a>';
                    return $btn;
                })

                ->addColumn('sales_fk', function ($row) {
                    return $row->get_sales->name ?? '-';
                })

                ->rawColumns(['action', 'sales_fk'])
                ->make(true);
        }
        $title = "Store List";
        return view('admin.Store.index', compact('title', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sales = SalesExecutive::where('status', '1')->get();
        return view('admin.store.create', compact('sales'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => [
                    'required',
                    'string',
                    'min:5',
                    'max:100'
                ],

                'mobile' => [
                    'required',
                    'digits_between:10,15'
                ],

                'address' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'city' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'latitude' => [
                    'required'
                ],

                'longitude' => [
                    'required'
                ],

                'sales_fk' => [
                    'required',
                    'string'
                ]
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
                        'name' => $request->input('name'),
                        'mobile' => $request->input('mobile'),
                        'address' => $request->input('address'),
                        'city' => $request->input('city'),
                        'latitude' => $request->input('latitude'),
                        'longitude' => $request->input('longitude'),
                        'sales_fk' => $request->input('sales_fk'),
                    ];
                    Store::create($data);
                });
            }

            return Response::json(
                array(
                    'error' => false,
                    'errors' => null,
                    'success' => true,
                    'msg' => "Store created successfully.",
                )
            );
        } catch (Exception $e) {
            dd($request->all());
            die();
            Log::error("Admin/StoreController.php:- store() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Store $store)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Store $store)
    {
        $sales = SalesExecutive::where('status', '1')->get();
        return view('admin.store.create', compact('sales', 'store'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Store $store)
    {

        try {
            $rules = [
                'name' => [
                    'required',
                    'string',
                    'min:5',
                    'max:100'
                ],

                'mobile' => [
                    'required',
                    'digits_between:10,15'
                ],

                'address' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'city' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'latitude' => [
                    'required'
                ],

                'longitude' => [
                    'required'
                ],

                'sales_fk' => [
                    'nullable',
                    'string'
                ]
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
                DB::transaction(function () use ($request, $store) {
                    $data = [];
                    $data['name'] = $request->name;
                    $data['mobile'] = $request->mobile;
                    $data['address'] = $request->address;
                    $data['city'] = $request->city;
                    $data['latitude'] = $request->latitude;
                    $data['longitude'] = $request->longitude;
                    $data['sales_fk'] = $request->sales_fk;
                    $store->update($data);
                });
            }

            return Response::json(
                array(
                    'error' => false,
                    'errors' => null,
                    'success' => true,
                    'msg' => "Store Update successfully.",
                )
            );
        } catch (Exception $e) {
            Log::error("Admin/StoreController.php:- update() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Store $store)
    {
        try {
            $store->delete();
            return Response::json(
                array(
                    'error' => false,
                    'errors' => NULL,
                    'success' => true,
                    'msg' => "Store Deleted successfully.",
                )
            );
        } catch (Exception $e) {
            Log::error("Admin/StoreController.php:- destroy() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return catchReponse($e);
        }
    }
}
