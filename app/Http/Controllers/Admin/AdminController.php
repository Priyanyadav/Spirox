<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Admin::get();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="' . route('admin.admin.edit', ['admin' => $row->id]) . '" data-title="Edit" class="btn btn-icon btn-light btn-hover-primary btn-xs mx-1 modal-link" title="Edit"><i class="far fa-edit icon-sm text-primary"></i></a>';
                    $btn .= '<a href="' . route('admin.admin.destroy', ['admin' => $row->id]) . '" data-title="Delete" class="btn btn-icon btn-light btn-hover-danger btn-xs mx-1 delete-data" title="Delete"><i class="far fa-trash-alt icon-sm text-danger"></i></a>';
                    return $btn;
                })

                ->editColumn('role', function ($row) {
                    if ($row->role == 'Admin') {
                        $role = '<a href="' . route('admin.admin.role', ['id' => $row->id]) . '" class="cursor-pointer label label-md font-weight-bold label-light-danger label-inline role-change">Admin</a>';
                    }
                    if ($row->role == 'Manager') {
                        $role = '<a href="' . route('admin.admin.role', ['id' => $row->id]) . '" class="cursor-pointer label label-md font-weight-bold label-light-danger label-inline role-change">Manager</a>';
                    } else {
                        $role = '<a href="' . route('admin.admin.role', ['id' => $row->id]) . '" class="cursor-pointer label label-md font-weight-bold label-light-success label-inline role-change">Salesman</a>';
                    }
                    return $role;
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == '0') {
                        $status = '<a href="' . route('admin.admin.status', ['id' => $row->id]) . '" class="cursor-pointer label label-md font-weight-bold label-light-danger label-inline status-change">InActive</a>';
                    } else {
                        $status = '<a href="' . route('admin.admin.status', ['id' => $row->id]) . '" class="cursor-pointer label label-md font-weight-bold label-light-success label-inline status-change">Active</a>';
                    }

                    return $status;
                })
                ->rawColumns(['action', 'status', 'role'])
                ->make(true);
        }
        $title = "Admin & Manager List";
        return view('admin.admin.index', compact('title', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $password = "Password";
        return view('admin.admin.create', compact('password'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u', 'min:5', 'max:100'],
                'mobile' => ['required', 'digits_between:10,15'],
                'email' => ['required', 'regex:/(.+)@(.+)\.(.+)/i', 'max:255'],
                'role' => ['required'],
                'joining_date' => ['required'],
                'password' => [
                    'required',
                    'string',
                    'regex:/[a-z]/',          // At least one lowercase letter
                    'regex:/[A-Z]/',          // At least one uppercase letter
                    'regex:/[0-9]/',          // At least one digit
                    'regex:/[@$!%*?&]/',      // At least one special character
                ],
                'password_confirmation' => ['required', 'same:password']
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
                        'email' => $request->input('email'),
                        'role' => $request->input('role'),
                        'joining_date' => $request->input('joining_date'),
                        'password' => bcrypt($request->password),
                        'status' => 1,
                    ];
                    Admin::create($data);
                });
            }

            return Response::json(
                array(
                    'error' => false,
                    'errors' => null,
                    'success' => true,
                    'msg' => "Role created successfully.",
                )
            );
        } catch (Exception $e) {
            return catchReponse($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $admin = Admin::findOrFail($id);
            $password = 'Change Password';
            return view('admin.admin.create', compact('admin', 'password'));
        } catch (Exception $e) {
            Log::error("Admin/AdminController.php:- edit() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $rules = [
                'name' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u', 'min:5', 'max:100'],
                'mobile' => ['required', 'digits_between:10,15'],
                'email' => ['required', 'regex:/(.+)@(.+)\.(.+)/i', 'max:255'],
                'role' => ['required', 'digits_between:0,1'],
                'joining_date' => ['required'],
                'password' => [
                    'required',
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
                $admin = Admin::findOrFail($id);
                if ($request->password) {
                    $password = bcrypt($request->password);
                } else {
                    $password = $admin->password;
                }
                DB::transaction(function () use ($request, $admin, $password) {
                    $data = [];
                    $data['name'] = $request->name;
                    $data['mobile'] = $request->mobile;
                    $data['email'] = $request->email;
                    $data['role'] = $request->role;
                    $data['password'] = $request->password;
                    // dd($data);
                    $admin->update($data);
                });

                return Response::json(
                    array(
                        'error' => false,
                        'errors' => null,
                        'success' => true,
                        'msg' => "Admin updated successfully."

                    )
                );
            }
        } catch (Exception $e) {
            dd($e);
            Log::error("Admin/AdminController.php:- update() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            // return catchReponse($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $admin = Admin::findOrFail($id);
            $admin->delete();
            return Response::json(
                array(
                    'error' => false,
                    'errors' => NULL,
                    'success' => true,
                    'msg' => "Admin Deleted successfully.",
                )
            );
        } catch (Exception $e) {
            Log::error("Admin/AdmiinController.php:- destroy() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return catchReponse($e);
        }
    }

    public function status($id)
    {
        try {
            $data = Admin::where(['id' => $id])->first();
            if ($data->status) {
                $status = "1";
            } else {
                $status = "0";
            }
            Admin::where('id', $data->id)
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
            Log::error("Admin/AdminController.php.php:- status() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return catchReponse($e);
        }
    }

    public function role($id)
    {
        try {
            $data = Admin::where(['id' => $id])->first();
            if ($data->role) {
                $role = "0";
            } else {
                $role = "1";
            }
            Admin::where('id', $data->id)
                ->update([
                    'role' => $role
                ]);
            return Response::json(
                array(
                    'error' => false,
                    'errors' => NULL,
                    'success' => true,
                    'msg' => "Role changed successfully."
                )
            );
        } catch (Exception $e) {
            Log::error("Admin/AdminController.php:- role() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return catchReponse($e);
        }
    }
}
