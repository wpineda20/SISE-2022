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
            'action_description',
            'annual_actions',
            'actions_cusca.executed',
            'responsable_name',
            'user_name',
            'result_description',
            'year_name',
            'financing_name',
        )
        ->join('users as u', 'actions_cusca.user_id', '=', 'u.id')
        ->join('results_cusca as rs', 'actions_cusca.results_cusca_id', '=', 'rs.id')
        ->join('years as y', 'actions_cusca.year_id', '=', 'y.id')
        ->join('financings as f', 'actions_cusca.financings_id', '=', 'f.id')
        ->get();
        
        // $users = User::all();
        // $resultsCusca = ResultsCusca::all();
        // $years = Year::all();
        // $financings = Financing::all();
        
        $actionsCusca = EncryptController::encryptArray($actionsCusca, ['id', 'user_id', 
        'results_cusca_id','year_id','financings_id']);

        return response()->json(['message' => 'success', 'actionsCusca'=>$actionsCusca]);
        
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
        //  dd($request->all());
        $data = $request->except(['user_name', 'result_description', 'year_name', 'financing_name']);

        $users = User::where('user_name', $request->user_name)->first();
        $resultsCusca = ResultsCusca::where('result_description', $request->result_description)->first();
        $years = Year::where('year_name', $request->year_name)->first();
        $financings = Financing::where('financing_name', $request->financing_name)->first();
        // dd($data);
        $data['user_id'] = $users->id;
        $data['results_cusca_id'] = $resultsCusca->id;
        $data['year_id'] = $years->id;
        $data['financings_id'] = $financings->id;
        $data['executed'] = ($data['executed'])?"SI":"NO";

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
        $data = $request->except(['user_name', 'result_description', 'year_name', 'financing_name']);

        $users = User::where('user_name', $request->user_name)->first();
        $resultsCusca = ResultsCusca::where('result_description', $request->result_description)->first();
        $years = Year::where('year_name', $request->year_name)->first();
        $financings = Financing::where('financing_name', $request->financing_name)->first();

        $data = EncryptController::decryptModel($request->except(['user_name', 'result_description', 'year_name',
        'financing_name']), 'id');

        $data['user_id'] = $users->id;
        $data['results_cusca_id'] = $resultsCusca->id;
        $data['year_id'] = $years->id;
        $data['financings_id'] = $financings->id;
        $data['executed'] = ($data['executed'])?"SI":"NO";


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
