<?php

namespace App\Http\Controllers;

use DB;
use Crypt;
use App\Models\Institution;
use Illuminate\Http\Request;
use App\Models\Programmatic_Objective;
use App\Models\User;

class ProgrammaticObjectiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programmaticObjectives = Programmatic_Objective::select('programmatic_objectives.id', 'programmatic_objectives.description', 'strategy_objective', 
        'create_date', 'percentage', 'institution_name','user_name')

        ->join('institutions as inst', 'programmatic_objectives.institution_id', '=', 'inst.id')
        ->join('users as user', 'programmatic_objectives.user_id', '=', 'user.id')
        ->get();

        $programmaticObjectives = EncryptController::encryptArray($programmaticObjectives, ['id', 'institution_id', 'user_id']);

        return response()->json(['message' => 'success', 'programmatic_objectives'=>$programmaticObjectives]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except(['institution_name', 'user_name']);

        $institution = Institution::where('institution_name', $request->institution_name)->first();
        $user = User::where('user_name', $request->user_name)->first();
        
        $data['institution_id'] = $institution->id;
        $data['user_id'] = $user->id;
        $data['strategy_objective'] = ($data['strategy_objective'])?"SI":"NO";

        Programmatic_Objective::insert($data);

        return response()->json(['message'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Programmatic_Objective  $programmatic_Objective
     * @return \Illuminate\Http\Response
     */
    public function show(Programmatic_Objective $programmatic_Objective)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Programmatic_Objective  $programmatic_Objective
     * @return \Illuminate\Http\Response
     */
    public function edit(Programmatic_Objective $programmatic_Objective)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Programmatic_Objective  $programmatic_Objective
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // dd($request->all());
        $data = $request->except(['institution_name', 'user_name']);
        // dd($data);
        $institution = Institution::where('institution_name', $request->institution_name)->first();
        $user = User::where('user_name', $request->user_name)->first();
        $data = EncryptController::decryptModel($request->except(['institution_name', 'user_name']), 'id');

        $data['institution_id'] = $institution->id;
        $data['user_id'] = $user->id;
        $data['strategy_objective'] = ($data['strategy_objective'])?"SI":"NO";

        Programmatic_Objective::where('id', $data['id'])->update($data);
        return response()->json(["message"=>"success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Programmatic_Objective  $programmatic_Objective
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = EncryptController::decryptValue($id);

        Programmatic_Objective::where('id', $id)->delete();
        return response()->json(["message"=>"success"]);
    }
}
