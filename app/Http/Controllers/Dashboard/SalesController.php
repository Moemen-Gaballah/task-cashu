<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Sales;
use App\Models\User;

class SalesController extends Controller
{
    public function __construct()
    {
        // create read update delete
        $this->middleware(['permission:read_sales'])->only('index');
        $this->middleware(['permission:create_sales'])->only('create');
        $this->middleware(['permission:update_sales'])->only(['edit', 'update']);
        $this->middleware(['permission:delete_sales'])->only('destroy');
    } // end of construct


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        if(Auth::user()->role == 0){
            $user_id = Auth::user()->user_id == 0 ? Auth::user()->id : Auth::user()->user_id;
            $sales = Sales::where('owner', $user_id)->with('user')->latest()->paginate(15);
        }else {
            $sales = Sales::with('user')->latest()->paginate(15);
        }

        return view('dashboard.sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        #TODO For suberAdmin
        if(Auth::user()->role == 0){
            $user_id = Auth::user()->user_id == 0 ? Auth::user()->id : Auth::user()->user_id;
            $users = User::where('user_id', $user_id)->get();
            return view('dashboard.sales.create', compact('users'));
        }else {
            return "Sorry Admin I will ToDo It later";
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'user' => 'required', // ToDo Must become in this customer
            'payment' => 'required|min:1', // ToDo validation decimal
        ]);

        $sales = new Sales();
        $sales->owner = Auth::user()->user_id == 0 ? Auth::user()->id : Auth::user()->user_id;
        $sales->user_id = $request->user;
        $sales->payment = $request->payment;
        $sales->save();

        session()->flash('success', __('site.added_successfully'));

        return redirect()->route('dashboard.sales.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Sales $sale)
    {

        #TODO For suberAdmin
         if(Auth::user()->id == $sale->owner || Auth::user()->user_id == $sale->user_id || Auth::user()->role == 1){
            $users = User::where('user_id', $sale->owner)->get();
            return view('dashboard.sales.edit', compact('users', 'sale'));
        }else{
            abort(404);
        }
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
        $sales = Sales::findOrFail($id);
        $request->validate([
            'user' => 'required', // ToDo Must become in this customer
            'payment' => 'required|min:1', // ToDo validation decimal
        ]);

        $sales->user_id = $request->user;
        $sales->payment = $request->payment;
        $sales->save();

        session()->flash('success', __('site.updated_successfully'));

        return redirect()->route('dashboard.sales.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sales = Sales::findOrFail($id);
        if(Auth::user()->id == $sales->owner || Auth::user()->user_id == $sales->owner || Auth::user()->role == 1)
        
        $sales->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.sales.index');
    }
}
