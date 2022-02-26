<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $skip = $request->skip;
        $limit = $request->take - $skip; // the limit

        $users = User::skip($skip)->take($limit)
        ->get();

        $users->makeVisible(['password']);

        foreach ($users as $user) {
            $user->rol = $user->getRoleNames()[0];
        }

        $users = EncryptController::encryptArray($users, ['id']);

        $total = User::count();

        return response()->json([
            'message' => 'success',
            'users' => $users,
            'total' => $total,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $verifyEmail = User::where(['email'=>$request->email])->count();

        if ($verifyEmail > 0) {
            return response()->json(['message' => 'Este correo ya existe.']);
        }

        // $verifyDui = User::where(['dui'=>$request->dui])->count();

        // if ($verifyDui > 0) {
        //     return response()->json(['message' => 'Este dui ya existe.']);
        // }

        $password = Hash::make($request->password);

        $user = new User;
        $user->name = $request->name;
        $user->user_name = $request->user_name;
        $user->email = $request->email;
        $user->job_title = $request->job_title;
        $user->phone = $request->phone;
        $user->password = $password;
        $user->email_verified_at = now();

        $user->save();

        // Getting the model with the id
        $user = User::where(['email'=>$request->email])->first();

        $role = Role::where('name', $request->rol)->first();
        $user->assignRole($role);

        return response()->json(['message' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = EncryptController::decryptValue($request->id);
        $user = User::find($id);

        $password = Hash::make($request->password);

        $data = [
            'name' => $request->name,
            'user_name' => $request->user_name,
            'job_title' => $request->job_title,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => $password,
        ];

        if (isset($request->rol)) {
            $oldRole = DB::table('model_has_roles')->where('model_id', $user->id)->delete();

            $role = Role::where('name', $request->rol)->first();
            $user->assignRole($role);
        }

        $user->update($data);

        return response()->json(["message"=>"success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user->delete();
        return response()->json(["message" => "success"]);
    }

    /**
     * Gets the specified user resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualUser(Request $request)
    {
        $user = User::find(auth()->user()->id);

        return response()->json(['message'=> 'success', 'user'=> $user]);
    }
}
