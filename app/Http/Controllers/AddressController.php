<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Institution;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $addresses = Address::all();

        foreach ($addresses as $address) {
            $address->institution_name = $address->institution->institution_name;
        }
        $addresses->makeHidden(['institution']);

        $addresses = EncryptController::encryptArray($addresses, ['id']);

        return response()->json(['message' => 'success', 'addresses'=>$addresses]);
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
        Address::insert($data);

        return response()->json(['message'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show(Address $address)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $institution = Institution::where('institution_name', $request->institution_name)->first();
        $data = EncryptController::decryptModel($request->except(['institution_name']), 'id');
        // dd($data, $institution);

        $data['institution_id'] = $institution->id;

        Address::where('id', $data['id'])->update($data);
        return response()->json(["message"=>"success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $id = EncryptController::decryptValue($id);

        Address::where('id', $id)->delete();
        return response()->json(["message"=>"success"]);
    }
}
