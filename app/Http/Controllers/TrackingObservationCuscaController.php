<?php

namespace App\Http\Controllers;

use DB;
use Crypt;
use App\Models\TrackingObservationCusca;
use App\Models\ActionsCusca;
use App\Models\Year;
use App\Models\Month;
use Illuminate\Http\Request;

class TrackingObservationCuscaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trackingObservationsCusca = TrackingObservationCusca::select('tracking_observation_cusca.id', 
        'observation', 'observation_reply', 'reply_date', 'value', 'month_name', 'action_description')

       
        ->join('years as y', 'tracking_observation_cusca.year_id', '=', 'y.id')
        ->join('months as m', 'tracking_observation_cusca.month_id', '=', 'm.id')
        ->join('actions_cusca as ac', 'tracking_observation_cusca.actions_cusca_id', '=', 'ac.id')
        ->get();
        
        $trackingObservationsCusca = EncryptController::encryptArray($trackingObservationsCusca, ['id', 'year_id', 
        'month_id', 'actions_cusca_id']);

        return response()->json(['message' => 'success', 'trackingObservationsCusca'=>$trackingObservationsCusca]);
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
        $data = $request->except(['value', 'month_name', 'action_description']);

        $year = Year::where('value', $request->value)->first();
        $month = Month::where('month_name', $request->month_name)->first();
        $actions = ActionsCusca::where('action_description', $request->action_description)->first();
        // dd($data);
        
        $data['year_id'] = $year->id;
        $data['month_id'] = $month->id;
        $data['actions_cusca_id'] = $actions->id;

        TrackingObservationCusca::insert($data);

        return response()->json(['message'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TrackingObservationCusca  $trackingObservationCusca
     * @return \Illuminate\Http\Response
     */
    public function show(TrackingObservationCusca $trackingObservationCusca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TrackingObservationCusca  $trackingObservationCusca
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //  dd($request->all());
        $data = $request->except(['value', 'month_name', 'action_description']);
        // dd($data);
        $year = Year::where('value', $request->value)->first();
        $month = Month::where('month_name', $request->month_name)->first();
        $actions = ActionsCusca::where('action_description', $request->action_description)->first();

        $data = EncryptController::decryptModel($request->except(['value', 'month_name', 'action_description']), 'id');

        $data['year_id'] = $year->id;
        $data['month_id'] = $month->id;
        $data['actions_cusca_id'] = $actions->id;
        // dd($data);

        TrackingObservationCusca::where('id', $data['id'])->update($data);
        return response()->json(["message"=>"success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TrackingObservationCusca  $trackingObservationCusca
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $id = EncryptController::decryptValue($id);

        TrackingObservationCusca::where('id', $id)->delete();
        return response()->json(["message"=>"success"]);
    }
}