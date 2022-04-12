<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Str;

class UserController extends Controller
{

    //Create New User
    public function createUser(Request $request)
    {
        return DB::transaction(function () use ($request) {

            //Data Validation
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users'
            ]);

            if($validator->fails()) {
                return response()->json(array('status'=>false ,'error' => $validator->messages()->first()));
            }
            try{
                $name=$request->name;
                $email=$request->email;
                $phone=$request->phone;
                $age=$request->age;

                //Check if email already exited
                $checkEmail = User::where('email', $email)->first();

                if(!empty($checkEmail)) {

                    return response()->json(array('status'=>false ,'error' => 'Email Already existed'));

                }else{

                    //Check if phone variable different of null and empty
                    if($phone!=null || !empty($phone)) {
                        //Check if phone already exited
                        $checkPhone = User::where('phone', $phone)->first();
                        if(!empty($checkPhone)) {
                            return response()->json(array('status'=>false ,'error' => 'Phone Already existed'));
                        }
                    }

                    $newUser = new User;
                    $newUser->name = $name;
                    $newUser->email = $email;
                    $newUser->phone = $phone;
                    $newUser->age = $age;
                    $newUser->save();

                    return response()->json(array('status'=>true, 'success' => 'successfully created', 'user_id'=>$newUser->id));

                }

            } catch (\Exception $e)
            {
                return response()->json(array('status'=>false ,'error' => 'Something wrong please try late'));

            }

        });
        
    }
    public function updateUser(Request $request)
    {
        return DB::transaction(function () use ($request) {

            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'name' => 'required',
                'email' => 'required|email'
            ]);

            if($validator->fails()) {
                return response()->json(array('status'=>false ,'error' => $validator->messages()->first()));
            }
            try{
                $id=$request->id;
                $name=$request->name;
                $email=$request->email;
                $phone=$request->phone;
                $age=$request->age;

                //Find User by ID
                $checkUserUpdate = User::find($id);

                if($checkUserUpdate) {
                    
                    //Check if this email passed is different with the current email 
                    if($checkUserUpdate->email!=$email) {
                        //Check if email passed already used by another user
                        $checkEmail = User::where('email', $email)->first();
                        if(!empty($checkEmail)) {
                            return response()->json(array('status'=>false ,'error' => 'Email Already existed by another user'));
                        }
                    }

                    if($phone!=null || !empty($phone)) {
                        if($checkUserUpdate->phone!=$phone) {
                            //Check if phone passed already used by another user
                            $checkPhone = User::where('phone', $phone)->first();
                            if(!empty($checkPhone)) {
                                return response()->json(array('status'=>false ,'error' => 'Phone Already existed by another user'));
                            }
                        }
                    }

                    $checkUserUpdate->name = $name;
                    $checkUserUpdate->email = $email;
                    $checkUserUpdate->phone = $phone;
                    $checkUserUpdate->age = $age;
                    $checkUserUpdate->save();

                    return response()->json(array('status'=>true, 'success' => 'successfully updated'));

                }else{
                    return response()->json(array('status'=>false ,'error' => 'User does not exist'));
    
                }

            } catch (\Exception $e)
            {
                return response()->json(array('status'=>false ,'error' => 'Something wrong please try late'));

            }

        });
        
    }
    public function deleteUser(Request $request)
    {
        return DB::transaction(function () use ($request) {

            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(array('status'=>false ,'error' => $validator->messages()->first()));
            }

            try{
                $id=$request->id;
                //Find user by ID
                $deleteUser =  User::find($id);

                if($deleteUser){
                    //Delete if existed 
                    $deleteUser->delete();
                    return response()->json(array('success' => 'User successfully deleted'));
                }else{
                    return response()->json(array('error' => 'Something is wrong, please try again'));
                }

            } catch (\Exception $e)
            {
                return response()->json(array('status'=>false ,'error' => 'lllSomething wrong please try late'));
            }
        });
    }
    public function searchUser(Request $request) {
        
        return DB::transaction(function () use ($request) {

            //Data Validation
            $validator = Validator::make($request->all(), [
                'phone' => 'required',
                'email' => 'required|email'
            ]);
            if($validator->fails()) {
                return response()->json(array('status'=>false ,'error' => $validator->messages()->first()));
            }

            try{
                $email = $request->email;
                $phone =  $request->phone;

        
                $users =  User::where(
                    function($query) use($email, $phone) {
                        $query->where('email', 'LIKE', "%$email%")
                        ->orwhere('email', 'LIKE', "%$email")
                        ->orwhere('email', 'LIKE', "$email%")
                        ->orwhere('phone', 'LIKE', "%$phone%")
                        ->orwhere('phone', 'LIKE', "%$phone")
                        ->orwhere('phone', 'LIKE', "$phone%");
                    }
                )
                ->orderBy('users.email','asc')
                ->orderBy('users.phone','asc')
                ->get();

                return  response()->json($users);

            } catch (\Exception $e)
            {
                return response()->json(array('status'=>false ,'error' => 'Something wrong please try late'));
            }
        });



    }
    public function searchUser0(Request $request) {
        
        return DB::transaction(function () use ($request) {

            //Data Validation
            $validator = Validator::make($request->all(), [
                'phone' => 'required',
                'email' => 'required|email'
            ]);
            if($validator->fails()) {
                return response()->json(array('status'=>false ,'error' => $validator->messages()->first()));
            }

            try{
                $email = $request->email;
                $phone =  $request->phone;

        
                $users =  User::where(
                    function($query) use($email, $phone) {
                        $query->where('email', 'LIKE', "%$email%")
                        ->orwhere('email', 'LIKE', "%$email")
                        ->orwhere('email', 'LIKE', "$email%")
                        ->orwhere('phone', 'LIKE', "%$phone%")
                        ->orwhere('phone', 'LIKE', "%$phone")
                        ->orwhere('phone', 'LIKE', "$phone%");
                    }
                )
                ->orderBy('users.email','asc')
                ->orderBy('users.phone','asc')
                ->get();

                return  response()->json($users);

            } catch (\Exception $e)
            {
                return response()->json(array('status'=>false ,'error' => 'Something wrong please try late'));
            }
        });



    }
}
