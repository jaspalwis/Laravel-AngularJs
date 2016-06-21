<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Hash;
use DB;

class UsersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
		//$users = User::all();
		$users = DB::table('users')
            ->select('users.*', 'roles.slug as role')
            ->join('role_user','users.id','=','role_user.user_id' )
            ->join('roles','role_user.role_id','=','roles.id' )
            ->get();
		
		return json_encode($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
		$member = self::CreateOrUpdate(null, $request);
		
		$users = DB::table('users')
            ->select('users.*', 'roles.slug as role')
            ->where('users.id', $member->id)
            ->join('role_user','users.id','=','role_user.user_id' )
            ->join('roles','role_user.role_id','=','roles.id' )
            ->first();
			
        echo json_encode($users);
    }
	
	/*function to check user group*/
	public function checkusergroup(Request $request, $id)
    {
		$users = DB::table('users')
            ->select('roles.id as role')
            ->where('users.id', $id)
            ->join('role_user','users.id','=','role_user.user_id' )
            ->join('roles','role_user.role_id','=','roles.id' )
            ->get();
			
        echo json_encode($users);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $User = User::find($id);
        return json_encode($User);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request,$id)
    {
    	$member = self::CreateOrUpdate($id, $request);
		$users = DB::table('users')
            ->select('users.*', 'roles.slug as role')
            ->where('users.id', $member->id)
            ->join('role_user','users.id','=','role_user.user_id' )
            ->join('roles','role_user.role_id','=','roles.id' )
            ->first();
		echo json_encode($users);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        User::where('id',$id)->delete();
		return $id;
    }
	
	public function CreateOrUpdate($id = null, $request)
	{
		$user = is_null($id) ? new User : User::findOrFail($id);
		$user->name = trim($request['name']," ");
        $user->email = trim($request['email']," ");
		$user->password = Hash::make($request['password']);
		$user->status = $request['status'];
		$user->pestpacname = isset($request['pestpacname']) ? $request['pestpacname'] : NULL;
		$user->license_number = isset($request['license_number']) ? $request['license_number'] : NULL;
		$user->dropbox_images = isset($request['dropbox_images']) ? $request['dropbox_images'] : NULL;
		$user->dropbox_image_name = isset($request['dropbox_image_name']) ? $request['dropbox_image_name'] : NULL;
        $user->save();
		
		if(!is_null($id)){
			DB::table('role_user')->where('user_id', $user->id)->delete();
			//$user->detachRole($request['userrole']);
		}
		$user->attachRole($request['userrole']);
		
		return $user ? $user : Null;
		
	}
	
}
