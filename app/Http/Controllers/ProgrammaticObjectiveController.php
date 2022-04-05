<?php

namespace App\Http\Controllers;

use App\Models\AxisCusca;
use DB;
use Crypt;
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

        $programmaticObjectives = Programmatic_Objective::select(
            'programmatic_objectives.id',
            'programmatic_objectives.description',
            'programmatic_objectives.executed',
            'axis_description',
            'user_name'
        )
        ->join('axis_cusca as axis', 'programmatic_objectives.axis_cusca_id', '=', 'axis.id')
        ->join('users as user', 'programmatic_objectives.user_id', '=', 'user.id')
        ->get();

        $programmaticObjectives = EncryptController::encryptArray($programmaticObjectives, ['id', 'axis_cusca_id', 'user_id']);

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
        $data = $request->except(['axis_description', 'user_name']);

        $axis = AxisCusca::where('axis_description', $request->axis_description)->first();
        $user = User::where('user_name', $request->user_name)->first();

        $data['axis_cusca_id'] = $axis->id;
        $data['user_id'] = auth()->user()->id;
        $data['executed'] = ($data['executed'])?"SI":"NO";

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
        $data = $request->except(['axis_description', 'user_name']);

        $axis = AxisCusca::where('axis_description', $request->axis_description)->first();
        $user = User::where('user_name', $request->user_name)->first();
        $data = EncryptController::decryptModel($request->except(['axis_description', 'user_name']), 'id');

        $data['axis_cusca_id'] = $axis->id;
        $data['user_id'] = auth()->user()->id;
        $data['executed'] = ($data['executed'])?"SI":"NO";

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
