<?php

namespace App\Http\Controllers;

use App\ServiceList;
use Illuminate\Http\Request;
use App\Http\Requests;
use Response;
use App\Http\Controllers\Controller;

class ServiceLists extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($id = null) {
        if ($id == null) {
            return ServiceList::orderBy('id', 'asc')->get();
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
        $service = new ServiceList;
        $service->name = $request->input('name');
        $service->description = $request->input('description');
        $service->category = $request->input('category');
        $service->status = $request->input('status');
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
        return ServiceList::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {
        $service = ServiceList::find($id);

        $service->name = $request->input('name');
        $service->description = $request->input('description');
        $service->category = $request->input('category');
        $service->status = $request->input('status');
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
		 $service = ServiceList::find($id);

        $service->delete();
		
        return "Service record successfully deleted #" . $request->input('id');
    }

	public function ServicePagination()
    {  
        $services = ServiceList::paginate(5); // 5 is the number of items to show per page
        return Response::json($services);
    }

}
