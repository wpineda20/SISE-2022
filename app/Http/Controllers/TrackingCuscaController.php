<?php

namespace App\Http\Controllers;


use DB;
use Crypt;
use App\Models\TrackingCusca;
use App\Models\User;
use App\Models\Year;
use App\Models\Month;
use App\Models\TrakingStatus;

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
         $trackingsCusca = TrackingCusca::select('tracking_cusca.id', 'tracking_detail', 'executed', 
        'monthly_actions', 'tracking_cusca.percentage', 'budget_executed', 'tracking_cusca.create_date', 
        'user_name', 'value', 'month_name', 'status_name')

       
        ->join('users as u', 'tracking_cusca.user_id', '=', 'u.id')
        ->join('years as y', 'tracking_cusca.year_id', '=', 'y.id')
        ->join('months as m', 'tracking_cusca.month_id', '=', 'm.id')
        ->join('traking_statuses as ts', 'tracking_cusca.traking_status_id', '=', 'ts.id')
        ->get();
        
        $trackingsCusca = EncryptController::encryptArray($trackingsCusca, ['id', 'user_id', 'year_id', 
        'month_id', 'traking_status_id']);

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
        $data = $request->except(['user_name', 'month_name', 'value', 'status_name']);

        $user = User::where('user_name', $request->user_name)->first();
        $month = Month::where('month_name', $request->month_name)->first();
        $year = Year::where('value', $request->value)->first();
        $status = TrakingStatus::where('status_name', $request->status_name)->first();
        
        // dd($data);
        $data['user_id'] = $user->id;
        $data['month_id'] = $month->id;
        $data['year_id'] = $year->id;
        $data['traking_status_id'] = $status->id;
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
        $data = $request->except(['user_name', 'month_name', 'value', 'status_name']);
        // dd($data);
        $user = User::where('user_name', $request->user_name)->first();
        $month = Month::where('month_name', $request->month_name)->first();
        $year = Year::where('value', $request->value)->first();
        $status = TrakingStatus::where('status_name', $request->status_name)->first();

        $data = EncryptController::decryptModel($request->except(['user_name', 'month_name', 'value',
        'status_name']), 'id');

        $data['user_id'] = $user->id;
        $data['year_id'] = $year->id;
        $data['month_id'] = $month->id;
        $data['traking_status_id'] = $status->id;
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
