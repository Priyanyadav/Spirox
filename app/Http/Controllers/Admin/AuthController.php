<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            if (Auth::guard('admin')->check()) {
                return redirect()->route('admin.dashboard');
            }
            return view('admin.auth.login');
        } catch (Exception $e) {
            Log::error("Admin/AuthController.php:- login() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function loginCheck(Request $request)
    {
        try {
            $rules = [
                'email' => ['required'],
                'password' => ['required']
            ];

            $validator = Validator::make($request->all(), $rules, [
                'required' => 'Required',
                'image.max' => 'The image field must not be greater than 10MB.',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            if (Auth::guard('admin')->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
                return redirect()->route('admin.dashboard');
            } else {
                return back()->with('error', 'Whoops! invalid email and password.');
            }
        } catch (Exception $e) {
            Log::error("Admin/AuthController.php:- loginCheck() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function changePassword()
    {
        try {
            $title = "Change Password";
            return view('admin.change-password', compact('title'));
        } catch (Exception $e) {
            Log::error("Admin/AuthController.php:- changePassword() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function profileView()
    {
        try {
            $Admin = Admin::findOrFail(Auth::guard('admin')->user()->id);
            $title = "Profile View";
            return view('admin.profile-view', compact('title', 'Admin'));
        } catch (Exception $e) {
            Log::error("Admin/AuthController.php:- profileView() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function passwordUpdate(Request $request)
    {
        try {
            $rules = [
                'current_password' =>
                [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (!Hash::check($value, Auth::guard('admin')->user()->password)) {
                            $fail('Old Password didn\'t match');
                        }
                    },
                ],
                'new_password' => ['required', 'different:current_password', 'min:8'],
                'new_confirm_password' => ['required', 'same:new_password', 'min:8'],
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
            }

            Admin::where('id', Auth::guard('admin')->user()->id)->update([
                'password' => Hash::make($request->new_password)
            ]);
            Session::flash('success', 'Password Changed successfully!');
            return Response::json(
                array(
                    'error' => false,
                    'errors' => null,
                    'success' => true,
                    "socket_trigger" => false,
                    'route' => url()->previous(),
                )
            );
        } catch (Exception $e) {
            Log::error("Admin/AuthController.php:- passwordUpdate() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return catchReponse($e);
        }
    }
    public function logout()
    {
        try {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login');
        } catch (Exception $e) {
            Log::error("Admin/AuthController.php:- logout() : ", ["Exception" => $e->getMessage(), "\nTraceAsString" => $e->getTraceAsString()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
