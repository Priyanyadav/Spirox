<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LiveLocationTracking;
use Illuminate\Http\Request;
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

class LiveLocationTrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = LiveLocationTracking::with(['get_sales_exe'])->get();
        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="' . route('admin.livelocationtracking.edit', ['livelocationtracking' => $row->id]) . '" data-title="Edit" class="btn btn-icon btn-light btn-hover-primary btn-xs mx-1 modal-link" title="Edit"><i class="far fa-edit icon-sm text-primary"></i></a>';
                    $btn .= '<a href="' . route('admin.livelocationtracking.destroy', ['livelocationtracking' => $row->id]) . '" data-title="Delete" class="btn btn-icon btn-light btn-hover-danger btn-xs mx-1 delete-data" title="Delete"><i class="far fa-trash-alt icon-sm text-danger"></i></a>';
                    return $btn;
                })

                ->addColumn('sales_exec_id', function ($row) {
                    return $row->get_sales_exe->name ?? '-';
                })

                ->rawColumns(['action', 'sales_exec_id'])
                ->make(true);
        }
        $title = "Live Location Tracking List";
        return view('admin.LiveLocationTracking.index', compact('title', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $salesexecutives = SalesExecutive::where('status', '1')->get();
        return view('admin.LiveLocationTracking.create', compact('salesexecutives'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'latitude' => ['required'],
                'longitude' => ['required'],
                'location_time' => ['required'],
                'sales_exec_id' => ['required'],
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
                        'latitude' => $request->input('latitude'),
                        'longitude' => $request->input('longitude'),
                        'location_time' => $request->input('location_time'),
                        'sales_exec_id' => $request->input('sales_exec_id'),
                    ];
                    LiveLocationTracking::create($data);
                });
            }

            return Response::json(
                array(
                    'error' => false,
                    'errors' => null,
                    'success' => true,
                    'msg' => "Live Location Tracking created successfully.",
                )
            );
        } catch (Exception $e) {
            Log::error("Admin/LiveLocationTrackingController.php:- store() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(LiveLocationTracking $liveLocationTracking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LiveLocationTracking $livelocationtracking)
    {
        $salesexecutives = SalesExecutive::where('status', '1')->get();
        return view('admin.LiveLocationTracking.create', compact('salesexecutives', 'livelocationtracking'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LiveLocationTracking $livelocationtracking)
    {
        try {
            $rules = [
                'latitude' => ['required'],
                'longitude' => ['required'],
                'location_time' => ['required'],
                'sales_exec_id' => ['required'],
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
                DB::transaction(function () use ($request, $livelocationtracking) {
                    $data = [];
                    $data['latitude'] = $request->latitude;
                    $data['longitude'] = $request->longitude;
                    $data['location_time'] = $request->location_time;
                    $data['sales_exec_id'] = $request->sales_exec_id;
                    $livelocationtracking->update($data);
                });
            }

            return Response::json(
                array(
                    'error' => false,
                    'errors' => null,
                    'success' => true,
                    'msg' => "Live Location Tracking Update successfully.",
                )
            );
        } catch (Exception $e) {
            Log::error("Admin/LiveLocationTrackingController.php:- update() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LiveLocationTracking $livelocationtracking)
    {
        try {
            $livelocationtracking->delete();
            return Response::json(
                array(
                    'error' => false,
                    'errors' => NULL,
                    'success' => true,
                    'msg' => "Live Location Tracking Deleted successfully.",
                )
            );
        } catch (Exception $e) {
            Log::error("Admin/LiveLocationTrackingController.php:- destroy() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return catchReponse($e);
        }
    }
}
