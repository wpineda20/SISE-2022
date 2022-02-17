<?php

namespace App\Http\Controllers;

use App\Models\TrakingStatus;
use Illuminate\Http\Request;
use DB;
use Crypt;

class TrakingStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trakingStatuses = TrakingStatus::all();
        $trakingStatuses = EncryptController::encryptArray($trakingStatuses, ['id']);

        return response()->json(['message' => 'success', 'trakingStatuses' => $trakingStatuses]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        TrakingStatus::insert($request->all());

        return response()->json(['message'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TrakingStatus  $trakingStatus
     * @return \Illuminate\Http\Response
     */
    public function show(TrakingStatus $trakingStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TrakingStatus  $trakingStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = EncryptController::decryptModel($request->all(), 'id');

        TrakingStatus::where('id', $data['id'])->update($data);
        return response()->json(["message"=>"success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TrakingStatus  $trakingStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = EncryptController::decryptValue($id);

        TrakingStatus::where('id', $id)->delete();
        return response()->json(["message"=>"success"]);
    }
}
