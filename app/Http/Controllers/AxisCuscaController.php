<?php

namespace App\Http\Controllers;

use DB;
use Crypt;
use App\Models\AxisCusca;
use App\Models\Institution;
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
        'institution_name','user_name')
        ->join('institutions as institution', 'axis_cusca.institution_id', '=', 'institution.id')
        ->join('users as u', 'axis_cusca.user_id', '=', 'u.id') 
        ->get();

        $axisCuscas = EncryptController::encryptArray($axisCuscas, ['id', 'user_id', 'institution_id']);

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
        $data = $request->except(['user_name', 'institution_name']);

        $user = User::where('user_name', $request->user_name)->first();
        $institution = Institution::where('institution_name', $request->institution_name)->first();

        $data['executed'] = ($data['executed'])?"SI":"NO";
        $data['user_id'] = $user->id;
        $data['institution_id'] = $institution->id;

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
         $data = $request->except(['user_name', 'institution_name']);
        // dd($data);
        $user = User::where('user_name', $request->user_name)->first();
        $institution = Institution::where('institution_name', $request->institution_name)->first();
        $data = EncryptController::decryptModel($request->except(['user_name', 'institution_name']), 'id');

        $data['executed'] = ($data['executed'])?"SI":"NO";
        $data['user_id'] = $user->id;
        $data['institution_id'] = $institution->id;
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
