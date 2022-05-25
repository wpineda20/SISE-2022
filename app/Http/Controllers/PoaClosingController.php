<?php

namespace App\Http\Controllers;

use App\Models\PoaClosing;
use App\Models\Year;
use Illuminate\Http\Request;
use DB;
use Crypt;

class PoaClosingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $poaClosings = PoaClosing::all();

        foreach ($poaClosings as $poaClosing) {
            $poaClosing->year_name = $poaClosing->year->year_name;
        }
        $poaClosings->makeHidden(['year']);

        $poaClosings = EncryptController::encryptArray($poaClosings, ['id']);

        return response()->json(['message' => 'success', 'poaClosings'=>$poaClosings]);
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
        $data = $request->except('year_name');
        $year = Year::where('year_name', $request->year_name)->first();
        $data['year_id'] = $year->id;
        PoaClosing::insert($data);

        return response()->json(['message'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PoaClosing  $poaClosing
     * @return \Illuminate\Http\Response
     */
    public function show(PoaClosing $poaClosing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PoaClosing  $poaClosing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $year = Year::where('year_name', $request->year_name)->first();
        $data = EncryptController::decryptModel($request->except(['year_name']), 'id');
        // dd($data, $year);

        $data['year_id'] = $year->id;

        PoaClosing::where('id', $data['id'])->update($data);
        return response()->json(["message"=>"success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PoaClosing  $poaClosing
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = EncryptController::decryptValue($id);

        PoaClosing::where('id', $id)->delete();
        return response()->json(["message"=>"success"]);
    }
}
