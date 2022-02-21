<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProgrammaticObjective;
use App\Models\AxisCuscatlan;
use Illuminate\Http\Request;
use DB;
use Crypt;

class AxisCuscatlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $axisCuscatlans = AxisCuscatlan::select('axesCuscatlan.id', 'axis_description', 'percentage', 
        'create_date', 'user_name', 'description')
        ->join('users as us', 'axesCuscatlan.user_id', '=', 'us.id')
        ->join('programmatic_objectives as po', 'axesCuscatlan.programmatic_objectives_id', '=', 'po.id')
        ->get();

        $axisCuscatlans = EncryptController::encryptArray($axisCuscatlans, ['id', 'user_id', 'programmatic_objectives_id']);

        return response()->json(['message' => 'success', 'axisCuscatlans'=>$axisCuscatlans]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except(['user_name', 'description']);

        $user = User::where('user_name', $request->user_name)->first();
        $description = ProgrammaticObjective::where('description', $request->description)->first();

        $data['user_id'] = $user->id;
        $data['programmatic_objectives_id'] = $description->id;

        AxisCuscatlan::insert($data);

        return response()->json(['message'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AxisCuscatlan  $axisCuscatlan
     * @return \Illuminate\Http\Response
     */
    public function show(AxisCuscatlan $axisCuscatlan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AxisCuscatlan  $axisCuscatlan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
          // dd($request->all());
        $data = $request->except(['user_name', 'description']);
        // dd($data);
        $user = User::where('user_name', $request->user_name)->first();
        $month = ProgrammaticObjective::where('description', $request->description)->first();
        $data = EncryptController::decryptModel($request->except(['user_name', 'description']), 'id');

        $data['user_id'] = $user->id;
        $data['programmatic_objectives_id'] = $description->id;
        // dd($data);

        AxisCuscatlan::where('id', $data['id'])->update($data);
        return response()->json(["message"=>"success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AxisCuscatlan  $axisCuscatlan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = EncryptController::decryptValue($id);

        AxisCuscatlan::where('id', $id)->delete();
        return response()->json(["message"=>"success"]);
    }
}
