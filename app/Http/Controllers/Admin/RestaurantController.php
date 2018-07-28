<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['page_title'] = $data['title'] = 'Restaurant Management';
        if ($request->ajax()){

            $restaurant = Restaurant::all(['id','name','address','contact_no','created_at']);
            
            if($restaurant->isEmpty()){
                return Datatables::of($restaurant)->make(true);   
            }else{  
                $data['restaurant'] = array();
                foreach ($restaurant as $key => $value) {
                    $data['restaurant'][$key]['restaurant_id'] = ($value->id);
                    $data['restaurant'][$key]['name'] = ($value->name);
                    $data['restaurant'][$key]['address'] = ($value->address);
                    $data['restaurant'][$key]['contact_no'] = ($value->contact_no);
                }
                return Datatables::of($data['restaurant'])->make(true); 
            }  
        }
        return view('backend.restaurant.list')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
