<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\StrategyCusca;
use App\Models\OrganizationalUnit;
use App\Models\Programmatic_Objective;

class StrategyCuscaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $strategyCusca = StrategyCusca::select(
            'strategy_cusca.id',
            'description_strategy',
            'strategy_cusca.executed',
            'user_name',
            'ou_name',
            'description'
        )
        ->join('users as user', 'strategy_cusca.user_id', '=', 'user.id')
        ->join('organizational_units as ou', 'strategy_cusca.organizational_units_id', '=', 'ou.id')
        ->join('programmatic_objectives as po', 'strategy_cusca.programmatic_objectives_id', '=', 'po.id')
        ->get();
            
        
        $strategyCusca = EncryptController::encryptArray($strategyCusca, ['id', 'user_id','organizational_units_id', 
        'programmatic_objectives_id']);

        return response()->json(['message' => 'success', 'strategy_cusca'=>$strategyCusca]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except(['user_name','ou_name', 'description']);
        $user = User::where('user_name', $request->user_name)->first();
        $ou = OrganizationalUnit::where('ou_name', $request->ou_name)->first();
        $programaticObjectives = Programmatic_Objective::where('description', $request->description)->first();
        $data['user_id'] = $user->id;
        $data['organizational_units_id'] = $ou->id;
        $data['programmatic_objectives_id'] = $programaticObjectives->id;
        $data['executed'] = ($data['executed'])?"SI":"NO";

        StrategyCusca::insert($data);

        return response()->json(['message'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StrategyCusca  $strategyCusca
     * @return \Illuminate\Http\Response
     */
    public function show(StrategyCusca $strategyCusca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StrategyCusca  $strategyCusca
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //  dd($request->all());
        $data = $request->except(['user_name','ou_name', 'description']);
         // dd($data);
         $user = User::where('user_name', $request->user_name)->first();
         $ou = OrganizationalUnit::where('ou_name', $request->ou_name)->first();
         $programaticObjectives = Programmatic_Objective::where('description', $request->description)->first();
         

         $data = EncryptController::decryptModel($request->except(['user_name','ou_name', 'description']), 'id');
         $data['user_id'] = $user->id;
         $data['organizational_units_id'] = $ou->id;
         $data['programmatic_objectives_id'] = $programaticObjectives->id;
         $data['executed'] = ($data['executed'])?"SI":"NO";
         
        StrategyCusca::where('id', $data['id'])->update($data);
        return response()->json(["message"=>"success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StrategyCusca  $strategyCusca
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = EncryptController::decryptValue($id);

        StrategyCusca::where('id', $id)->delete();
        return response()->json(["message"=>"success"]);
    }
}
