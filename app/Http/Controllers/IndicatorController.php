<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\Unit;
use App\Models\OrganizationalUnit;
use App\Models\Indicator;
use Illuminate\Http\Request;
use DB;
use Crypt;

class IndicatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $indicators = Indicator::select('indicators.id', 'indicator_name', 'institution_name', 'ou_name', 'unit_name', 'strategic_indicator')
        ->join('institutions as inst', 'indicators.institution_id', '=', 'inst.id')
        ->join('units as uni', 'indicators.unit_id', '=', 'uni.id')
        ->join('organizational_units as ou', 'indicators.organizational_unit_id', '=', 'ou.id')
        ->get();

        $indicators = EncryptController::encryptArray($indicators, ['id', 'institution_id', 'unit_id', 'organizational_unit_id']);

        return response()->json(['message' => 'success', 'indicators'=>$indicators]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except(['institution_name', 'unit_name', 'ou_name']);

        $institution = Institution::where('institution_name', $request->institution_name)->first();
        $unit = Unit::where('unit_name', $request->unit_name)->first();
        $ou = OrganizationalUnit::where('ou_name', $request->ou_name)->first();

        $data['institution_id'] = $institution->id;
        $data['unit_id'] = $unit->id;
        $data['organizational_unit_id'] = $ou->id;
        $data['strategic_indicator'] = ($data['strategic_indicator'])?"SI":"NO";

        Indicator::insert($data);

        return response()->json(['message'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Indicator  $indicator
     * @return \Illuminate\Http\Response
     */
    public function show(Indicator $indicator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Indicator  $indicator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
         // dd($request->all());
        $data = $request->except(['institution_name', 'unit_name', 'ou_name']);
        // dd($data);
        $institution = Institution::where('institution_name', $request->institution_name)->first();
        $unit = Unit::where('unit_name', $request->unit_name)->first();
        $ou = OrganizationalUnit::where('ou_name', $request->ou_name)->first();
        $data = EncryptController::decryptModel($request->except(['institution_name', 'unit_name', 'ou_name']), 'id');

        $data['institution_id'] = $institution->id;
        $data['unit_id'] = $unit->id;
        $data['organizational_unit_id'] = $ou->id;
        // dd($data);
        $data['strategic_indicator'] = ($data['strategic_indicator'])?"SI":"NO";

        Indicator::where('id', $data['id'])->update($data);
        return response()->json(["message"=>"success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Indicator  $indicator
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $id = EncryptController::decryptValue($id);

        Indicator::where('id', $id)->delete();
        return response()->json(["message"=>"success"]);
    }
}
