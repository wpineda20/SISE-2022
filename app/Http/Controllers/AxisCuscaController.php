<?php

namespace App\Http\Controllers;

use DB;
use Crypt;
use App\Models\AxisCusca;
use App\Models\User;
use App\Models\Programmatic_Objective;
use Illuminate\Http\Request;

class AxisCuscaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $axisCuscas = AxisCusca::select('axis_cusca.id', 'axis_description', 'axis_cusca.executed', 
        'user_name', 'description')

        ->join('users as u', 'axis_cusca.user_id', '=', 'u.id')
        ->join('programmatic_objectives as po', 'axis_cusca.programmatic_objectives_id', '=', 'po.id')
        ->get();

        $axisCuscas = EncryptController::encryptArray($axisCuscas, ['id', 'user_id', 'programmatic_objectives_id']);

        return response()->json(['message' => 'success', 'axisCuscas'=>$axisCuscas]);
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
        $description = Programmatic_Objective::where('description', $request->description)->first();

        $data['executed'] = ($data['executed'])?"SI":"NO";
        $data['user_id'] = $user->id;
        $data['programmatic_objectives_id'] = $description->id;

        AxisCusca::insert($data);

        return response()->json(['message'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AxisCusca  $axisCusca
     * @return \Illuminate\Http\Response
     */
    public function show(AxisCusca $axisCusca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AxisCusca  $axisCusca
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
         // dd($request->all());
        $data = $request->except(['user_name', 'description']);
        // dd($data);
        $user = User::where('user_name', $request->user_name)->first();
        $description = Programmatic_Objective::where('description', $request->description)->first();
        $data = EncryptController::decryptModel($request->except(['user_name', 'description']), 'id');

        $data['executed'] = ($data['executed'])?"SI":"NO";
        $data['user_id'] = $user->id;
        $data['programmatic_objectives_id'] = $description->id;
        // dd($data);

        AxisCusca::where('id', $data['id'])->update($data);
        return response()->json(["message"=>"success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AxisCusca  $axisCusca
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = EncryptController::decryptValue($id);

        AxisCusca::where('id', $id)->delete();
        return response()->json(["message"=>"success"]);
    }
}
