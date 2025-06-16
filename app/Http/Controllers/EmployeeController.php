<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $roles = Role::all();
        view()->share('roles',$roles);
    }
    public function index()
    {
        $data = Employee::orderBy('id','DESC')->get();
        return view('admin.employee.index', compact('data'));
    }
    public function create()
    {
        return view('admin.employee.create');
    }
    public function store(Request $request)
    {
        try{

            $request->validate([
                'first_name' => 'required', 'string', 'max:255',
                'middle_name' => 'required', 'string', 'max:255',
                'last_name' => 'required', 'string', 'max:255',
                'email' => 'required', 'string', 'email', 'max:255', 'unique:'.User::class,
                'password' => 'required|max:255|min:6',
                'role' => 'required'
            ]);
    
            
    
            $user = User::create([
                'name' => $request->first_name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            $user->assignRole($request->role);
    
            $employee = Employee::create([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'sex' => $request->sex,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'userId' => $user->id,
            ]);
            return redirect()->route('admin.employee.index')->with('success','Employee created successfully.');
        }
        catch(\Illuminate\Database\QueryException $e)
        {
            return redirect()->route('admin.employee.index')->with('error','Error, please try again.');
        }
    }
    public function edit($id)
    {
        $employee = Employee::where('id',decrypt($id))->first();
        return view('admin.employee.edit',compact('employee'));
    }
    public function update(Request $request, Employee $employee)
    {
        try{

            $request->validate([
                'first_name' => 'required', 'string', 'max:255',
                'middle_name' => 'required', 'string', 'max:255',
                'last_name' => 'required', 'string', 'max:255',
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('employees')->ignore($employee->id)],
            ]); 
            $employee = Employee::find($request->id);
            $employee->first_name = $request->first_name;
            $employee->middle_name = $request->middle_name;
            $employee->last_name = $request->last_name;
            $employee->sex = $request->sex;
            $employee->mobile = $request->mobile;
            $employee->email = $request->email;
            $employee->save();
    
            $userId= $employee->userId;
            $user = User::find($userId);
            $user->email = $request->email;
            $user->save();
            return redirect()->route('admin.employee.index')->with('success','Employee updated successfully.');
        }
        catch(\Illuminate\Database\QueryException $e)
        {
            return redirect()->route('admin.employee.index')->with('error','Error, please try again.');
        }
    }
    public function destroy($id)
    {
        $employee = Employee::find(decrypt($id));
        $userId= $employee->userId;
        Employee::where('id',decrypt($id))->delete();
        User::where('id',$userId)->delete();
        return redirect()->back()->with('success','Employee deleted successfully.');
    }
}
