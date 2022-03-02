<?php

namespace App\Http\Controllers;


use DB;
use Crypt;
use App\Models\ActionsCusca;
use App\Models\TrackingCusca;
use App\Models\User;
use App\Models\Year;
use App\Models\Month;
use App\Models\TrakingStatus;
use App\Models\TrackingObservationCusca;

use Illuminate\Http\Request;

class TrackingCuscaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $trackingsCusca = TrackingCusca::select('tracking_cusca.id', 'tracking_detail', 'tracking_cusca.executed', 
        'monthly_actions', 'budget_executed', 'action_description', 
        'user_name', 'value', 'month_name', 'status_name', 'observation')

       
        ->join('users as u', 'tracking_cusca.user_id', '=', 'u.id')
        ->join('years as y', 'tracking_cusca.year_id', '=', 'y.id')
        ->join('months as m', 'tracking_cusca.month_id', '=', 'm.id')
        ->join('actions_cusca as ac', 'tracking_cusca.actions_cusca_id', '=', 'ac.id')
        ->join('traking_statuses as ts', 'tracking_cusca.traking_status_id', '=', 'ts.id')
        ->join('tracking_observation_cusca as toc', 'tracking_cusca.tracking_observation_cusca_id', '=', 'toc.id')
        ->get();

        $user = User::all();
        $month = Month::all();
        $year = Year::all();
        $status = TrakingStatus::all();
        $observation = TrackingObservationCusca::all();
        $action_description = ActionsCusca::all();
        
        $trackingsCusca = EncryptController::encryptArray($trackingsCusca, ['id', 'user_id', 'year_id', 
        'month_id', 'traking_status_id', 'actions_cusca_id', 'tracking_observation_cusca_id']);

        return response()->json(['message' => 'success', 'trackingsCusca'=>$trackingsCusca]);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $data = $request->except(['user_name', 'action_description', 'month_name', 'value', 'status_name', 
        'observation']);

        $user = User::where('user_name', $request->user_name)->first();
        $month = Month::where('month_name', $request->month_name)->first();
        $action_description = ActionsCusca::where('action_description', $request->action_description)->first();
        $year = Year::where('value', $request->value)->first();
        $status = TrakingStatus::where('status_name', $request->status_name)->first();
        $observation = TrackingObservationCusca::where('observation', $request->observation)->first();
        // dd($data);
        
        $data['user_id'] = $user->id;
        $data['month_id'] = $month->id;
        $data['actions_cusca_id'] = $action_description->id;
        $data['year_id'] = $year->id;
        $data['traking_status_id'] = $status->id;
        $data['tracking_observation_cusca_id'] = $observation->id;
        $data['executed'] = ($data['executed'])?"SI":"NO";

        TrackingCusca::insert($data);

        return response()->json(['message'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TrackingCusca  $trackingCusca
     * @return \Illuminate\Http\Response
     */
    public function show(TrackingCusca $trackingCusca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TrackingCusca  $trackingCusca
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
         //  dd($request->all());
        $data = $request->except(['user_name', 'action_description', 'month_name', 'value', 'status_name', 
        'observation']);
        // dd($data);
        $user = User::where('user_name', $request->user_name)->first();
        $month = Month::where('month_name', $request->month_name)->first();
        $year = Year::where('value', $request->value)->first();
        $action_description = ActionsCusca::where('action_description', $request->action_description)->first();
        $status = TrakingStatus::where('status_name', $request->status_name)->first();
        $observation = TrackingObservationCusca::where('observation', $request->observation)->first();

        $data = EncryptController::decryptModel($request->except(['user_name', 'month_name', 'action_description', 
        'value', 'status_name', 'observation']), 'id');

        $data['user_id'] = $user->id;
        $data['year_id'] = $year->id;
        $data['month_id'] = $month->id;
        $data['actions_cusca_id'] = $action_description->id;
        $data['traking_status_id'] = $status->id;
        $data['tracking_observation_cusca_id'] = $observation->id;
        $data['executed'] = ($data['executed'])?"SI":"NO";
        // dd($data);

        TrackingCusca::where('id', $data['id'])->update($data);
        return response()->json(["message"=>"success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TrackingCusca  $trackingCusca
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $id = EncryptController::decryptValue($id);

        TrackingCusca::where('id', $id)->delete();
        return response()->json(["message"=>"success"]);
    }
}
