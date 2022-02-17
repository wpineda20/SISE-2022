<?php

namespace App\Http\Controllers;

use App\Models\Year;
use App\Models\Period;
use Illuminate\Http\Request;
use DB;
use Crypt;

class YearController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $years = Year::all();

        foreach ($years as $year) {
            $year->period_name = $year->period->period_name;
        }
        $years->makeHidden(['period']);

        $years = EncryptController::encryptArray($years, ['id']);

        return response()->json(['message' => 'success', 'years'=>$years]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except('period_name');
        $period = Period::where('period_name', $request->period_name)->first();
        $data['period_id'] = $period->id;
        Year::insert($data);

        return response()->json(['message'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Year  $year
     * @return \Illuminate\Http\Response
     */
    public function show(Year $year)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Year  $year
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $period = Period::where('period_name', $request->period_name)->first();
        $data = EncryptController::decryptModel($request->except(['period_name']), 'id');
        // dd($data, $period);

        $data['period_id'] = $period->id;

        Year::where('id', $data['id'])->update($data);
        return response()->json(["message"=>"success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Year  $year
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = EncryptController::decryptValue($id);

        Year::where('id', $id)->delete();
        return response()->json(["message"=>"success"]);
    }
}
