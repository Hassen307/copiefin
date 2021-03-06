<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use App\Permission;
use Image;
use DB;
use Hash;
use Auth;
use File;



class UserController extends Controller
{
    
    
        public function profile(){
    	return view('profile', array('user' => Auth::user()) );
    }

    public function update_avatar(Request $request){

    	// Handle the user upload of avatar
    	if($request->hasFile('avatar')){
    		$avatar = $request->file('avatar');
                $user = Auth::user();
    		$filename = time() . '.' . $avatar->getClientOriginalExtension();
    		Image::make($avatar)->resize(300, 300)->save( public_path('/uploads/avatars/' . $filename ) );

    		
    		$user->avatar = $filename;
    		$user->save();
    	}

    	return view('profile', array('user' => Auth::user()) );

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = User::orderBy('id','DESC')->paginate(5);
        
        return view('users.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $roles = Role::pluck('display_name','id');
        return view('users.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] =bcrypt($input['password']);
      //  $input['password'] = Hash::make($input['password']);
         
       $idrole=$request->input('roles');
       $role_id = head($idrole); 
       array_pop($input);
       $input['role_id'] = $role_id;
       $input['verified']=true;
     
         
        $user = User::create($input);
        return redirect()->route('users.index')
                        ->with('success','User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        
        $Role_id=$user->role_id;
        $roles = Role::find($Role_id);
       // $userRole = $user->roles->pluck('id','id')->toArray();
       // $userRole=$user->role_id;
        
        $permissions=Permission::pluck('name','id');
        $userPermission = $roles->permissions->pluck('id','id')->toArray();
        foreach ($userPermission as $key => $value) {
         $user_perm[]=$permissions[$value];
         }
        
        return view('users.show',compact('user','user_perm'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('display_name','id');
       // $userRole = $user->roles->pluck('id','id')->toArray();
        $userRole=$user->role_id;
        //dd($role_id);

        return view('users.edit',compact('user','roles','userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);
     //   $role= roles()->id;
     //   dd($role);

        $input = $request->all();
        $idrole=$request->input('roles');
        $role_id = head($idrole); 
         array_pop($input);
         $input['role_id'] = $role_id;
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = array_except($input,array('password'));    
        }

        $user = User::find($id);
        $user->update($input);
       

        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       // User::find($id)->delete();
     //  $user = User::find($id);    
      //  $user->delete();
        DB::table('users')->where('id', $id)->delete();
        //DB::delete('DELETE FROM users WHERE id =id');
        return redirect()->route('users.index')
                        ->with('successdel','User deleted successfully');
    }
}
