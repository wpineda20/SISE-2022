<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActionsCusca;
use App\Models\Financing;
use App\Models\ResultsCusca;
use App\Models\Year;
use Illuminate\Http\Request;

class ActionsCuscaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $actionsCusca = ActionsCusca::select(
            'actions_cusca.id',
            'description_action',
            'actions_cusca.create_date',
            'actionNumberYear',
            'actions_cusca.percentage',
            'responsable_name',
            'user_name',
            'result_description',
            'value',
            'financing_name',
        )
        ->join('users as user', 'actions_cusca.user_id', '=', 'user.id')
        ->join('results_cusca as rs', 'actions_cusca.results_cusca_id', '=', 'rs.id')
        ->join('years as y', 'actions_cusca.year_id', '=', 'y.id')
        ->join('financings as f', 'actions_cusca.financings_id', '=', 'f.id')
        ->get();
            
        
        $actionsCusca = EncryptController::encryptArray($actionsCusca, ['id', 'user_id', 
        'results_cusca_id','year_id','financings_id']);

        return response()->json(['message' => 'success', 'actions_cusca'=>$actionsCusca]);
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
         dd($request->all());
        $data = $request->except(['user_name', 'result_description', 'value', 'financing_name']);

        $user = User::where('user_name', $request->user_name)->first();
        $result = ResultsCusca::where('result_description', $request->result_description)->first();
        $year = Year::where('value', $request->value)->first();
        $financing = Financing::where('financing_name', $request->financing_name)->first();

        $data['user_id'] = $user->id;
        $data['results_cusca_id'] = $result->id;
        $data['year_id'] = $year->id;
        $data['financings_id'] = $financing->id;

        ActionsCusca::insert($data);

        return response()->json(['message'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ActionsCusca  $actionsCusca
     * @return \Illuminate\Http\Response
     */
    public function show(ActionsCusca $actionsCusca)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ActionsCusca  $actionsCusca
     * @return \Illuminate\Http\Response
     */
    public function edit(ActionsCusca $actionsCusca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ActionsCusca  $actionsCusca
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $request->except(['user_name', 'result_description', 'value', 'financing_name']);
        $user = User::where('user_name', $request->user_name)->first();
        $result = ResultsCusca::where('result_description', $request->result_description)->first();
        $year = Year::where('value', $request->value)->first();
        $financing = Financing::where('financing_name', $request->financing_name)->first();

        $data = EncryptController::decryptModel($request->except(['user_name', 'result_description', 'value',
        'financing_name']), 'id');

        $data['user_id'] = $user->id;
        $data['results_cusca_id'] = $result->id;
        $data['year_id'] = $year->id;
        $data['financings_id'] = $financing->id;


        ActionsCusca::where('id', $data['id'])->update($data);
        return response()->json(["message"=>"success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ActionsCusca  $actionsCusca
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = EncryptController::decryptValue($id);

        ActionsCusca::where('id', $id)->delete();
        return response()->json(["message"=>"success"]);
    }
}
