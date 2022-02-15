<?php

namespace App\Http\Controllers;

use App\Models\Financing;
use Illuminate\Http\Request;
use DB;
use Crypt;


class FinancingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $financings = Financing::all();
        $financings = EncryptController::encryptArray($financings, ['id']);

        return response()->json(['message' => 'success', 'financings' => $financings]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Financing::insert($request->all());

        return response()->json(['message'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Financing  $financing
     * @return \Illuminate\Http\Response
     */
    public function show(Financing $financing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Financing  $financing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = EncryptController::decryptModel($request->all(), 'id');

        Financing::where('id', $data['id'])->update($data);
        return response()->json(["message"=>"success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Financing  $financing
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $id = EncryptController::decryptValue($id);

        Financing::where('id', $id)->delete();
        return response()->json(["message"=>"success"]);
    }
}
