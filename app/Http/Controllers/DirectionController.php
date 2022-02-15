<?php

namespace App\Http\Controllers;

use App\Models\Direction;
use App\Models\Institution;
use Illuminate\Http\Request;
use DB;
use Crypt;

class DirectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $directions = Direction::all();

        foreach ($directions as $direction) {
            $direction->institution_name = $direction->institution->institution_name;
        }
        $directions->makeHidden(['institution']);

        $directions = EncryptController::encryptArray($directions, ['id']);

        return response()->json(['message' => 'success', 'directions'=>$directions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except('institution_name');
        $institution = Institution::where('institution_name', $request->institution_name)->first();
        $data['institution_id'] = $institution->id;
        Direction::insert($data);

        return response()->json(['message'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Direction  $direction
     * @return \Illuminate\Http\Response
     */
    public function show(Direction $direction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Direction  $direction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $institution = Institution::where('institution_name', $request->institution_name)->first();
        $data = EncryptController::decryptModel($request->except(['institution_name']), 'id');
        // dd($data, $institution);

        $data['institution_id'] = $institution->id;

        Direction::where('id', $data['id'])->update($data);
        return response()->json(["message"=>"success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Direction  $direction
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = EncryptController::decryptValue($id);

        Direction::where('id', $id)->delete();
        return response()->json(["message"=>"success"]);
    }
}
