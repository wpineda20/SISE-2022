<?php

namespace App\Http\Controllers;

use App\Models\Year;
use App\Models\Month;
use App\Models\MonthlyClosing;
use Illuminate\Http\Request;
use DB;
use Crypt;

class MonthlyClosingController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $monthlyClosings = MonthlyClosing::select('monthly_closings.id', 'month_name', 'value', 'active')
        ->join('years as y', 'monthly_closings.year_id', '=', 'y.id')
        ->join('months as m', 'monthly_closings.month_id', '=', 'm.id')
        ->get();

        $monthlyClosings = EncryptController::encryptArray($monthlyClosings, ['id', 'year_id', 'month_id']);

        return response()->json(['message' => 'success', 'monthlyClosings'=>$monthlyClosings]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except(['value', 'month_name']);

        $year = Year::where('value', $request->value)->first();
        $month = Month::where('month_name', $request->month_name)->first();

        $data['year_id'] = $year->id;
        $data['month_id'] = $month->id;
        $data['active'] = ($data['active'])?"SI":"NO";

        MonthlyClosing::insert($data);

        return response()->json(['message'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Municipality  $monthly
     * @return \Illuminate\Http\Response
     */
    public function show(Municipality $monthly)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Municipality  $monthly
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // dd($request->all());
        $data = $request->except(['value', 'month_name']);
        // dd($data);
        $year = Year::where('value', $request->value)->first();
        $month = Month::where('month_name', $request->month_name)->first();
        $data = EncryptController::decryptModel($request->except(['value', 'month_name']), 'id');

        $data['year_id'] = $year->id;
        $data['month_id'] = $month->id;
        $data['active'] = ($data['active'])?"SI":"NO";
        // dd($data);

        MonthlyClosing::where('id', $data['id'])->update($data);
        return response()->json(["message"=>"success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Municipality  $monthly
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = EncryptController::decryptValue($id);

        MonthlyClosing::where('id', $id)->delete();
        return response()->json(["message"=>"success"]);
    }
}
