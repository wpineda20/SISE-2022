<?php

namespace App\Http\Controllers;

use App\Models\ActionsCusca;
use App\Models\TrackingCusca;
use App\Models\User;
use App\Models\Year;
use App\Models\Month;
use App\Models\TrakingStatus;
use App\Models\TrackingObservationCusca;

use Illuminate\Http\Request;

class TrackingCuscaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = RoleController::getAllowedRoles();
        $filters = [];
        if (isset($roles[0])) {
            switch ($roles[0]) {
                case "Enlace":
                    $filters = [
                        'ou.id'=>auth()->user()->organizational_units_id,
                        'inst.id' => auth()->user()->institution_id
                    ];
                    break;
            }
        }

        if (isset($request->filter)) {
            switch ($request->filter) {
                case "Mensuales":
                    $month = "m.id = ".intval(date('n'));
                    break;
                case "Atrasado":
                    $month = "m.id <= ".intval(date('n'));
                    $filters['ts.status_name'] = "Atrasado";
                    $filters['tracking_cusca.executed'] = 'NO';
                    break;
                case "Completado":
                    $month = "m.id <= ".intval(date('n'));
                    $filters['ts.status_name'] = "Completado";

                    break;
            }
        }

        $filters['y.year_name'] = date('Y');

        $trackingsCusca = TrackingCusca::select(
            'tracking_cusca.id as id',
            'tracking_detail',
            'tracking_cusca.executed',
            'monthly_actions',
            'tracking_cusca.budget_executed',
            'action_description',
            'u.user_name',
            'year_name',
            //'month_name',
            'status_name',
            'tracking_cusca.observation',
            'reply'
        )
        ->join('users as u', 'tracking_cusca.user_id', '=', 'u.id')
        ->join('years as y', 'tracking_cusca.year_id', '=', 'y.id')
        //->join('months as m', 'tracking_cusca.month_id', '=', 'm.id')
        ->join('actions_cusca as ac', 'tracking_cusca.actions_cusca_id', '=', 'ac.id')
        ->join('results_cusca as re', 'ac.results_cusca_id', '=', 're.id')
        ->join('organizational_units as ou', 're.organizational_units_id', '=', 'ou.id')
        ->join('directions as di', 'ou.direction_id', '=', 'di.id')
        ->join('institutions as inst', 'di.institution_id', '=', 'inst.id')
        ->join('traking_statuses as ts', 'tracking_cusca.traking_status_id', '=', 'ts.id')
        ->where($filters)
        ->whereRaw($month)
        ->orderBy('id', 'desc')
        ->get();

        $trackingsCusca = EncryptController::encryptArray($trackingsCusca, ['id', 'user_id', 'year_id',
        /*'month_id',*/ 'traking_status_id', 'actions_cusca_id', 'tracking_observation_cusca_id']);

        return response()->json(['message' => 'success', 'trackingsCusca'=>$trackingsCusca]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->getRoleNames()[0] != "Administrador"){
            return response()->json([
                'message'=>'success', 
                "error"=>"El usuario no posee los permisos suficientes para esta acción."
            ]);
        }
        
        $data = $request->except(['user_name', 'action_description', /*'month_name',*/ 
        'year_name', 'status_name','observation']);

        //$month = Month::where('month_name', $request->month_name)->first();
        $action_description = ActionsCusca::where('action_description', $request->action_description)->first();
        $year = Year::where('year_name', $request->year_name)->first();
        $status = TrakingStatus::where('status_name', $request->status_name)->first();

        $data['user_id'] = auth()->user()->id;
        //$data['month_id'] = $month->id;
        $data['actions_cusca_id'] = $action_description->id;
        $data['year_id'] = $year->id;
        $data['traking_status_id'] = $status->id;
        $data['executed'] = ($data['executed'])?"SI":"NO";
        $data['observation'] = $request->observation;
        $data['reply'] = $request->reply;
        $data['tracking_detail'] = $request->tracking_detail;

        TrackingCusca::insert($data);

        return response()->json(['message'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TrackingCusca  $trackingCusca
     * @return \Illuminate\Http\Response
     */
    public function show(TrackingCusca $trackingCusca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TrackingCusca  $trackingCusca
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $request->except(['user_name', 'action_description', /*'month_name',*/ 'year_name', 'status_name',
        'observation']);
        if (auth()->user()->user_name == $request->user_name || auth()->user()->hasRole('Administrador')) {
            $user = User::where('user_name', $request->user_name)->first();

            //$month = Month::where('month_name', $request->month_name)->first();
            $year = Year::where('year_name', $request->year_name)->first();
            $action_description = ActionsCusca::where('action_description', $request->action_description)->first();
            $status = TrakingStatus::where('status_name', $request->status_name)->first();

            $data = EncryptController::decryptModel($request->except(['user_name', /*'month_name',*/ 'action_description',
            'year_name', 'status_name', 'observation']), 'id');

            $model = TrackingCusca::where('id', $data['id'])->first();
            $model->user_id = $user->id;
            $model->year_id = $year->id;
            //$model->month_id = $month->id;
            $model->actions_cusca_id = $action_description->id;
            $model->traking_status_id = $status->id;
            $model->executed = ($data['executed'])?"SI":"NO";
            $model->observation = $request->observation;
            $model->reply = $request->reply;
            $model->tracking_detail = $request->tracking_detail;

            $model->save();

            return response()->json(["message"=>"success"]);
        }

        return response()->json(["message"=>"success", "reason"=>"Únicamente $request->user_name o el administrador puede modificar el registro."]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TrackingCusca  $trackingCusca
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = EncryptController::decryptValue($id);
        $tracking = TrackingCusca::where('id', $id)->first();
        $user = User::where('id', $tracking->user_id)->first();

        if (auth()->user()->id == $tracking->user_id || auth()->user()->hasRole('Administrador')) {
            TrackingCusca::where('id', $id)->delete();
            return response()->json(["message"=>"success"]);
        }

        return response()->json(["message"=>"success", "reason"=>"Únicamente $user->user_name o el administrador puede eliminar el registro."]);
    }
}
