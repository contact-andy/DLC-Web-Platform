<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Customer::orderBy('id','DESC')->get();
        return view('admin.customer.index', compact('data'));
    }
    public function register(Request $request)
    {
        $user = Customer::create([
            'first_name' => $request->firstName,
            'middle_name' => $request->lastName,
            'last_name' => $request->lastName,
            'sex' => $request->firstName,
            'dob' => $request->firstName,
            'mobile' => $request->mobile,
            'city' => $request->city,
            'languageId' => $request->languageId,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json(['user' => $user], 201);
    }
    public function signin(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        $customerCount1=Customer::where('email', $email)->count();
        $customerCount2=Customer::where('mobile', $email)->count();
        if($customerCount1){
            $user=Customer::where('email', $email)->first();
            if ($user && Hash::check($password, $user->password)) {
                 return response()->json(['user' => $user], 201);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'Login error1, please try again.',
                ]);
            }
        }
        else if($customerCount2){
            $user=Customer::where('mobile', $email)->first();
            if ($user && Hash::check($password, $user->password)) {
                 return response()->json(['user' => $user], 201);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'Login error2, please try again.',
                ]);
            }
        }
        else{
            return response()->json([
                'error' => true,
                'message' => 'Login error3, please try again.',
            ]);
        }

        return response()->json(['user' => $user], 201);
    }
    public function updateProfile(Request $request)
    {
        $userId = $request->userId;
        $first_name = $request->firstName;
        $last_name = $request->lastName;
        $mobile = $request->mobile;
        $city = $request->city;
        $languageId = $request->languageId;
        $email = $request->email;
        $password = $request->password;

        //Update
        $userCount = Customer::where('id',$userId)->count();
        if($userCount){
            $user = Customer::find($userId);
            $user->first_name = $first_name;
            $user->last_name = $last_name;
            $user->mobile = $mobile;
            $user->city = $city;
            $user->languageId = $languageId;
            $user->email = $email;
            if(strcmp($password,"")!=0)
                $user->password = bcrypt($password);
            if($user->save()){
                return response()->json(['status'=>'success','message' => "Profile has been updated successfully!"], 201);
            }
            else{
                return response()->json(['status'=>'error','message' => "error, please try again."], 201);
            }

        }
        else
        {
            return response()->json(['status'=>'error','message' => "Error, please try again."], 201);
        }
    }
    
}
