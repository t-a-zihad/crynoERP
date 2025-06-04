<?php

namespace App\Http\Controllers;

use Flasher\Prime\Facades\Flasher;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class EmployeeAuthController extends Controller
{
   // Show Users
    public function showUsers(){
        $employees = $employees = Employee::all();  // 10 per page
        return view('users', compact('employees'));
    }

    // Show registration form
    public function showRegister()
    {
        return view('register');
    }

    // Handle registration
    public function register(Request $request)
    {
        // Validate input first
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:employees',
            'contact_no' => 'required',
            'role' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        // Generate Employee ID: CV-EMP + YYYYMMDD + 3-digit count for today
        $date = date('Ymd');
        $count = Employee::whereDate('created_at', today())->count() + 1;
        $employeeId = 'CV-EMP' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        Employee::create([
            'employee_id' => $employeeId,
            'name' => $request->name,
            'email' => $request->email,
            'contact_no' => $request->contact_no,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 100000)
            ->success("Added employee successfully. Employee ID: {$employeeId}, Name: {$request->name}");

        return redirect()->route('employee.all');
    }

    // Show login form
    public function showLogin()
    {
        return view('employee.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $employee = Employee::where('email', $request->email)->first();

        if (!$employee || !Hash::check($request->password, $employee->password)) {
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 10000)
                ->error('Invalid email or password.');

            return redirect()->back()->withInput();
        }

        // You can store employee info in session
        session([
            'employee_id' => $employee->id,
            'name' => $employee->name
        ]);


        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 10000)
            ->success("Welcome, {$employee->name}!");

        return redirect()->route('orders.index');
    }

    // Logout
    public function logout(Request $request)
    {
        session()->forget('employee_id');
        session()->flush();

        return redirect()->route('login');
    }

    // Dashboard (protected)
    public function dashboard()
    {
        return view('employee.dashboard');
    }

    //edit
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        return view('userUpdate', compact('employee'));
    }

    //update
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'contact_no' => 'required',
            'role' => 'required',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->contact_no = $request->contact_no;
        $employee->role = $request->role;

        if ($request->filled('password')) {
            $employee->password = Hash::make($request->password);
        }

        $employee->save();

        flash()
            ->option('position', 'top-center')
            ->option('timeout', 5000)
            ->success("Employee updated successfully.");

        return redirect()->route('employee.all');
    }

    //delete
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 10000)
            ->warning("Employee {$employee->name} (ID: {$employee->employee_id}) deleted successfully.");

        return redirect()->route('employee.all');
    }


    //Profile
    public function editSelf()
    {
        $employeeId = session('employee_id');
        $employee = Employee::where('employee_id', $employeeId)->firstOrFail();

        return view('profile_edit', compact('employee'));
    }

    public function updateSelf(Request $request)
    {
        $employeeId = session('employee_id');
        $employee = Employee::where('employee_id', $employeeId)->firstOrFail();

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'contact_no' => 'required',
            'password' => 'nullable|min:6|confirmed',
            // no role validation here
        ]);

        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->contact_no = $request->contact_no;

        // Do NOT update role, even if submitted
        if ($request->filled('password')) {
            $employee->password = Hash::make($request->password);
        }

        $employee->save();

        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 5000)
            ->success("Profile updated successfully.");

        return redirect()->route('employee.profile.edit');
    }



}
