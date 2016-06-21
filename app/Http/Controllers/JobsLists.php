<?php

namespace App\Http\Controllers;

use App\JobsList;
use App\ServiceList;
use Illuminate\Http\Request;
use App\Http\Requests;
use Response;
use App\Http\Controllers\Controller;
use DB;
use Carbon;

class JobsLists extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($id = null) {
        if ($id == null) {
            return JobsList::orderBy('id', 'asc')->get();
        } else {
            return $this->show($id);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request) {
		$workDateArr = explode(' ',$request->input('work_date'));
		$work_date = date('Y-m-d', strtotime($workDateArr[1].' '.$workDateArr[2].' '.$workDateArr[3]));
		
		$service = new JobsList;
        $service->address_2 = $request->input('address_2');
        $service->address_1 = $request->input('address_1');
        $service->city = $request->input('city');
        $service->state = $request->input('state');
		$service->zip = $request->input('zip');
		$service->billto = $request->input('billto');
		$service->orders = $request->input('orders');
		$service->service_id = $request->input('service_id');
		$service->technician_id = $request->input('technician_id');
		$service->service_description = $request->input('service_description');
		$service->detail = $request->input('detail');
		$service->work_date = $work_date;
		$service->time_range = $request->input('time_range');
		$service->order_time = $request->input('order_time');
		$service->order_instruction = $request->input('order_instruction');
		$service->service_instructions = $request->input('service_instructions');
        $service->save();
		echo json_encode($service);
        //return 'Service record successfully created with id ' . $service->id;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        return JobsList::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {
        $service = JobsList::find($id);
        $service->address_2 = $request->input('address_2');
        $service->address_1 = $request->input('address_1');
        $service->city = $request->input('city');
        $service->state = $request->input('state');
		$service->zip = $request->input('zip');
		$service->billto = $request->input('billto');
		$service->orders = $request->input('orders');
		$service->service_id = $request->input('service_id');
		$service->technician_id = $request->input('technician_id');
		$service->service_description = $request->input('service_description');
		$service->detail = $request->input('detail');
		$service->work_date = $request->input('work_date');
		$service->time_range = $request->input('time_range');
		$service->order_time = $request->input('order_time');
		$service->order_instruction = $request->input('order_instruction');
		$service->service_instructions = $request->input('service_instructions');
        $service->save();
        echo json_encode($service);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $id) {
		 $service = JobsList::find($id);

        $service->delete();
		
        return "Service record successfully deleted #" . $request->input('id');
    }

	public function allservices()
    {  
		$services = DB::table("service_lists")->where('status', 'Active')->get();
        return Response::json($services);
    }
	
	public function alltechnicinas()
    {  
		$technicinas = DB::table('users')
            ->select('users.*', 'roles.slug as role')
            ->where('users.status', 'active')
            ->where('role_user.role_id', 4)
            ->join('role_user','users.id','=','role_user.user_id' )
            ->join('roles','role_user.role_id','=','roles.id' )
            ->get();
			
        return Response::json($technicinas);
    }

}
