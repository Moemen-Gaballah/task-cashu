<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image; 
use Illuminate\Validation\Rule;
use Auth;


class UserController extends Controller
{

    public function __construct()
    {
        // create read update delete
        $this->middleware(['permission:read_users'])->only('index');
        $this->middleware(['permission:create_users'])->only('create');
        $this->middleware(['permission:update_users'])->only('edit');
        $this->middleware(['permission:delete_users'])->only('destroy');
    } // end of construct

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = (Auth::user()->user_id != 0) ? Auth::user()->user_id : Auth::user()->id;  

        if(Auth::user()->role == 0){
            $users = User::whereRoleIs('user')->where('user_id', $user_id)
            ->latest()->paginate(15);
        }else {
            $users = User::latest()->paginate(15);
        }

        return view('dashboard.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.users.create');
    } // end of create

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:150',
            'email' => 'required|email|max:150|unique:users',
            'website' => 'min:3|max:200',
            'password' => 'required|confirmed',
            'permissions' => 'required|min:1',
        ]);


        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->website = $request->website;
        $user->password = bcrypt($request->password);
        $user->user_id = Auth::user()->user_id == 0 ? Auth::user()->id : Auth::user()->user_id;
        $user->email_verified_at = now();
        $user->save();

        $user->attachRole('user');
        $user->syncPermissions($request->permissions);

        session()->flash('success', __('site.added_successfully'));

        return redirect()->route('dashboard.users.index');
    } //end of store

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    // public function show(User $user)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if(Auth::user()->id == $user->user_id || Auth::user()->user_id == $user->user_id || Auth::user()->role == 1){
            return view('dashboard.users.edit', compact('user'));
        }else {
            abort(404);
        }
    } // end of edit

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
         $request->validate([
            'name' => 'required|min:3|max:150',
            'email' => ['required', Rule::unique('users')->ignore($user->id),],
            'website' => 'min:3|max:200',
            'permissions' => 'required|min:1',
        ]);

        $request_data = $request->except(['permissions']);

        
        $user->update($request_data);
        $user->syncPermissions($request->permissions);

        session()->flash('success', __('site.updated_successfully'));

        return redirect()->route('dashboard.users.index');
    } // end of update

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if(Auth::user()->id == $user->user_id || Auth::user()->user_id == $user->user_id || Auth::user()->role == 1){
            $user->delete();
            session()->flash('success', __('site.deleted_successfully'));
            return redirect()->route('dashboard.users.index');
        }else {
            abort(404);
        }
    }
}
