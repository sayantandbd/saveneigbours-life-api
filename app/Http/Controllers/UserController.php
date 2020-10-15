<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Log;
class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function register(Request $request)
    {
        Log::info('Register: Request hit');
        $this->validate($request, [
            'name' => 'required',
            'dob' => 'required|date',
            'email' => 'required|email|unique:users',
            'phone' => 'required|max:15|min:9|unique:users',
            'password' => 'required|max:25|min:5'
        ]);
        
        $original_pwd = $request->password; // keep it saved
        
        // create
        $request->merge(['password' => Hash::make($request->password)]);
        $create = Models\User::create($request->all());
        
        Log::info($request->all());

        $request->merge(['password' => $original_pwd]);
        $credentials = $request->only(['email', 'password']);
        if (! $token = Auth::guard('user')->attempt($credentials)) {
            return response()->json(['message' => 'Invalid Credentials'], 401);
        }

        return $this->respondWithToken($token);
        // return $create;
    }

    public function login(Request $request)
    {
        Log::info('Login: Request hit');
          //validate incoming request 
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        Log::info($request->all());
        $credentials = $request->only(['email', 'password']);
        if (! $token = Auth::guard('user')->attempt($credentials)) {
            return response()->json(['message' => 'Invalid Credentials'], 401);
        }

        return $this->respondWithToken($token);
    }


    public function profile()
    {
        $user = Auth::user();
        return response()->json($user, 200);
    }

    public function profile_details($id)
    {
        $user = Models\User::findOrFail($id);
        return $user;
    }

    public function profile_update(Request $request)
    {
        $user = Models\User::findOrFail(Auth::id())->update($request->all());

        if($user) return json_encode(array("success"=>"true"));
        else return response()->json(['error' => 'Unauthorized'], 401);
    }



}
