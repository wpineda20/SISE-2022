<?php

namespace App\Http\Controllers;


use DB;
use Crypt;
use App\Models\User;
use App\Models\Indicator;
use App\Models\Year;
use App\Models\Period;
use App\Models\OrganizationalUnit;
use App\Models\ResultsCusca;
use App\Models\AxisCusca;
use Illuminate\Http\Request;

class ResultsCuscaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resultsCusca = ResultsCusca::select('results_cusca.id', 'result_description', 'responsible_name', 
        'results_cusca.executed', 'user_name', 'axis_description', 'indicator_name', 'ou_name', 'value', 'period_name')

       
        ->join('users as u', 'results_cusca.user_id', '=', 'u.id')
        ->join('axis_cusca as a', 'results_cusca.axis_cusca_id', '=', 'a.id')
        ->join('indicators as i', 'results_cusca.indicator_id', '=', 'i.id')
        ->join('organizational_units as ou', 'results_cusca.organizational_units_id', '=', 'ou.id')
        ->join('years as y', 'results_cusca.year_id', '=', 'y.id')
        ->join('periods as p', 'results_cusca.period_id', '=', 'p.id')
        ->get();
        
        $resultsCusca = EncryptController::encryptArray($resultsCusca, ['id', 'user_id', 'axis_cusca_id', 
        'indicator_id', 'organizational_units_id', 'year_id', 'period_id']);

        return response()->json(['message' => 'success', 'resultsCusca'=>$resultsCusca]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except(['user_name', 'axis_description', 'indicator_name', 'ou_name', 'value', 
        'period_name']);

        $user = User::where('user_name', $request->user_name)->first();
        $indicator = Indicator::where('indicator_name', $request->indicator_name)->first();
        $ou = OrganizationalUnit::where('ou_name', $request->ou_name)->first();
        $axis_description = AxisCusca::where('axis_description', $request->axis_description)->first();
        $year = Year::where('value', $request->value)->first();
        $period = Period::where('period_name', $request->period_name)->first();

        $data['user_id'] = $user->id;
        $data['indicator_id'] = $indicator->id;
        $data['organizational_units_id'] = $ou->id;
        $data['axis_cusca_id'] = $axis_description->id;
        $data['year_id'] = $year->id;
        $data['period_id'] = $period->id;
        $data['executed'] = ($data['executed'])?"SI":"NO";

        ResultsCusca::insert($data);

        return response()->json(['message'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ResultsCusca  $resultsCusca
     * @return \Illuminate\Http\Response
     */
    public function show(ResultsCusca $resultsCusca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ResultsCusca  $resultsCusca
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //  dd($request->all());
        $data = $request->except(['user_name', 'axis_description', 'indicator_name', 'ou_name', 'value', 'period_name']);
        // dd($data);
        $user = User::where('user_name', $request->user_name)->first();
        $indicator = Indicator::where('indicator_name', $request->indicator_name)->first();
        $ou = OrganizationalUnit::where('ou_name', $request->ou_name)->first();
        $axis_description = AxisCusca::where('axis_description', $request->axis_description)->first();
        $year = Year::where('value', $request->value)->first();
        $period = Period::where('period_name', $request->period_name)->first();

        $data = EncryptController::decryptModel($request->except(['user_name', 'axis_description', 'indicator_name',
        'ou_name', 'value', 'period_name']), 'id');

        $data['user_id'] = $user->id;
        $data['axis_cusca_id'] = $axis_description->id;
        $data['indicator_id'] = $indicator->id;
        $data['organizational_units_id'] = $ou->id;
        $data['year_id'] = $year->id;
        $data['period_id'] = $period->id;
        $data['executed'] = ($data['executed'])?"SI":"NO";
        // dd($data);

        ResultsCusca::where('id', $data['id'])->update($data);
        return response()->json(["message"=>"success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ResultsCusca  $resultsCusca
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = EncryptController::decryptValue($id);

        ResultsCusca::where('id', $id)->delete();
        return response()->json(["message"=>"success"]);
    }
}
