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
        $monthlyClosings = MonthlyClosing::all();

        foreach ($monthlyClosings as $monthlyClosing) {
            $monthlyClosing->value = $monthlyClosing->year->value;
            $monthlyClosing->month_name = $monthlyClosing->month->month_name;
        }
        $monthlyClosings->makeHidden(['year', 'month']);
        

        $monthlyClosings = EncryptController::encryptArray($monthlyClosings, ['id']);

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
        // $data = $request->except('month_name');

        $year = Year::where('value', $request->value)->first();
        $month = Month::where('month_name', $request->month_name)->first();

        $data['year_id'] = $year->id;
        $data['month_id'] = $month->id;

        MonthlyClosing::insert($data);

        return response()->json(['message'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MonthlyClosing  $monthlyClosing
     * @return \Illuminate\Http\Response
     */
    public function show(MonthlyClosing $monthlyClosing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MonthlyClosing  $monthlyClosing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $year = Year::where('value', $request->value)->first();
        $month = Month::where('month_name', $request->month_name)->first();
        
        $data = EncryptController::decryptModel($request->except(['value', 'month_name']), 'id');
        
        // dd($data, $year);

        $data['year_id'] = $year->id;
        $data['month_id'] = $month->id;

        MonthlyClosing::where('id', $data['id'])->update($data);
        return response()->json(["message"=>"success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MonthlyClosing  $monthlyClosing
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = EncryptController::decryptValue($id);

        MonthlyClosing::where('id', $id)->delete();
        return response()->json(["message"=>"success"]);
    }
}
