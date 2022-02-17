<?php

namespace App\Http\Controllers;

use App\Models\Direction;
use App\Models\OrganizationalUnit;
use Illuminate\Http\Request;
use DB;
use Crypt;


class OrganizationalUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizationalUnits = OrganizationalUnit::all();

        foreach ($organizationalUnits as $organizationalUnit) {
            $organizationalUnit->direction_name = $organizationalUnit->direction->direction_name;
        }
        $organizationalUnits->makeHidden(['direction']);

        $organizationalUnits = EncryptController::encryptArray($organizationalUnits, ['id']);

        return response()->json(['message' => 'success', 'organizationalUnits'=>$organizationalUnits]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except('direction_name');
        $direction = Direction::where('direction_name', $request->direction_name)->first();
        $data['direction_id'] = $direction->id;
        OrganizationalUnit::insert($data);

        return response()->json(['message'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OrganizationalUnit  $organizationalUnit
     * @return \Illuminate\Http\Response
     */
    public function show(OrganizationalUnit $organizationalUnit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrganizationalUnit  $organizationalUnit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
       $direction = Direction::where('direction_name', $request->direction_name)->first();
        $data = EncryptController::decryptModel($request->except(['direction_name']), 'id');
        // dd($data, $direction);

        $data['direction_id'] = $direction->id;

        OrganizationalUnit::where('id', $data['id'])->update($data);
        return response()->json(["message"=>"success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrganizationalUnit  $organizationalUnit
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = EncryptController::decryptValue($id);

        OrganizationalUnit::where('id', $id)->delete();
        return response()->json(["message"=>"success"]);
    }
}
