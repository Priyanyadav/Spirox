<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\SalesExecutive;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class SalesExecutiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = SalesExecutive::get();
        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="' . route('admin.sales_executive.edit', ['sales_executive' => $row->id]) . '" data-title="Edit" class="btn btn-icon btn-light btn-hover-primary btn-xs mx-1 modal-link" title="Edit"><i class="far fa-edit icon-sm text-primary"></i></a>';
                    $btn .= '<a href="' . route('admin.sales_executive.destroy', ['sales_executive' => $row->id]) . '" data-title="Delete" class="btn btn-icon btn-light btn-hover-danger btn-xs mx-1 delete-data" title="Delete"><i class="far fa-trash-alt icon-sm text-danger"></i></a>';
                    return $btn;
                })

                ->editColumn('joining_date', function ($row) {
                    return $row->joining_date
                        ? Carbon::parse($row->joining_date)->format('d-m-Y')
                        : '-';
                })

                ->editColumn('status', function ($row) {
                    if ($row->status == '1') {
                        $status = '<a href="' . route('admin.sales_executive.status', ['id' => $row->id]) . '" class="cursor-pointer label label-md font-weight-bold label-light-success label-inline status-change">Active</a>';
                    } else {
                        $status = '<a href="' . route('admin.sales_executive.status', ['id' => $row->id]) . '" class="cursor-pointer label label-md font-weight-bold label-light-danger label-inline status-change">InActive</a>';
                    }
                    return $status;
                })
                ->rawColumns(['action', 'status', 'dateformate'])
                ->make(true);
        }
        $title = "Sales Executive List";
        return view('admin.sales_executive.index', compact('title', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $password = "Password";
        return view('admin.sales_executive.create', compact('password'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u', 'min:5', 'max:100'],
                'mobile' => ['required', 'digits_between:10,15', 'unique:sales_executives,mobile',],
                'email' => ['required', 'regex:/(.+)@(.+)\.(.+)/i', 'max:255', 'unique:sales_executives,email'],
                'assigned_area' => ['required', 'string', 'max:255',],
                'joining_date' => ['required', 'date'],
                'password' => ['required'],
                'password_confirmation' => ['required', 'same:password']
            ];

            $validator = Validator::make($request->all(), $rules, [
                'required' => 'Required',
            ]);

            $supabaseUrl = env('SUPABASE_URL');
            $serviceKey  = env('SUPABASE_SERVICE_ROLE_KEY');

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
                DB::transaction(function () use ($request, $supabaseUrl, $serviceKey) {

                    $uuid = (string) Str::uuid();

                    $data = [
                        'uuid' => $uuid,
                        'name' => $request->input('name'),
                        'mobile' => $request->input('mobile'),
                        'email' => $request->input('email'),
                        'assigned_area' => $request->input('assigned_area'),
                        'joining_date' => $request->input('joining_date'),
                        'password' => bcrypt($request->password),
                        'status' => 1,
                    ];

                    // ✅ Step 2 — Create Supabase auth user with same id
                    $response = Http::withHeaders([
                        'apikey' => $serviceKey,
                        'Authorization' => 'Bearer ' . $serviceKey,
                        'Content-Type' => 'application/json',
                    ])->post($supabaseUrl . '/auth/v1/admin/users', [
                        'id' => $uuid, // IMPORTANT
                        'email' => $request->email,
                        'role' => 'authenticated',
                        'encrypted_password' => $request->password,
                        'email_confirm' => true
                    ]);

                    if (!$response->successful()) {
                        throw new Exception($response->body());
                    }

                    SalesExecutive::create($data);
                });
            }

            return Response::json(
                array(
                    'error' => false,
                    'errors' => null,
                    'success' => true,
                    'msg' => "Sales Executive created successfully.",
                )
            );
        } catch (Exception $e) {
            dd($e);
            Log::error("Admin/SalesExecutiveController.php:- store() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SalesExecutive $salesExecutive)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalesExecutive $sales_executive)
    {
        try {
            $password = 'Change Password';
            return view('admin.sales_executive.create', compact('sales_executive', 'password'));
        } catch (Exception $e) {
            Log::error("Admin/AdminController.php:- edit() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalesExecutive $sales_executive)
    {
        try {
            $rules = [
                'name' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u', 'min:5', 'max:100'],
                'mobile' => ['nullable', 'digits_between:10,15'],
                'email' => ['nullable', 'regex:/(.+)@(.+)\.(.+)/i', 'max:255'],
                'assigned_area' => ['required', 'string', 'max:255',],
                'joining_date' => ['required', 'date'],
                'password' => [
                    'nullable',
                    'string',
                    'regex:/[a-z]/',          // At least one lowercase letter
                    'regex:/[A-Z]/',          // At least one uppercase letter
                    'regex:/[0-9]/',          // At least one digit
                    'regex:/[@$!%*?&]/',      // At least one special character
                ],
                'password_confirmation' => ['nullable', 'same:password']
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

                $sales_executive = SalesExecutive::findOrFail($sales_executive->id);
                if ($request->password) {
                    $password = bcrypt($request->password);
                } else {
                    $password = $sales_executive->password;
                }

                DB::transaction(function () use ($request, $sales_executive, $password) {
                    $data = [];
                    $data['name'] = $request->name;
                    $data['mobile'] = $request->mobile;
                    $data['email'] = $request->email;
                    $data['assigned_area'] = $request->assigned_area;
                    $data['joining_date'] = $request->joining_date;
                    $data['password'] = bcrypt($request->password);
                    $sales_executive->update($data);
                });

                return Response::json(
                    array(
                        'error' => false,
                        'errors' => null,
                        'success' => true,
                        'msg' => "Sales Executive updated successfully."

                    )
                );
            }
        } catch (Exception $e) {
            dd($e);
            Log::error("Admin/SalesExecutiveController.php:- update() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            // return catchReponse($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesExecutive $sales_executive)
    {
        try {
            $admin = SalesExecutive::findOrFail($sales_executive);
            $admin->delete();
            return Response::json(
                array(
                    'error' => false,
                    'errors' => NULL,
                    'success' => true,
                    'msg' => "Sales Executive Deleted successfully.",
                )
            );
        } catch (Exception $e) {
            Log::error("Admin/SalesExecutive.php:- destroy() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return catchReponse($e);
        }
    }


    public function status($id)
    {
        try {
            $data = SalesExecutive::where(['id' => $id])->first();
            if ($data->status) {
                $status = "1";
            } else {
                $status = "0";
            }
            SalesExecutive::where('id', $data->id)
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
            Log::error("Admin/SalesExecutive.php:- status() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return catchReponse($e);
        }
    }
}
