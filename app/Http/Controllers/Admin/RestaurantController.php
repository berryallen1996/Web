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
        $data['page_title'] = $data['title'] = 'Add Restaurant';
        $data['countries'] = DB::table("country")->pluck("country_name","id_country");
        return view('backend.restaurant.add')->with($data);
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
                'name'          => 'required|max:50',
                'address'       => 'required|max:50',
                'contact_no'    => 'required|numeric|digits_between:8,12',
                'country'       => 'required',
                'state'         => 'required',
                'city'          => 'required',
                'locality'      => 'required',
                'pincode'       => 'required',
                'image'          => 'required'
                ],
                [
                    'name.required' => 'Please enter Restaurant Name.',
                    'address.required' => 'Please enter Restaurant Address.',
                    'contact_no.required' => 'Please enter Mobile Number.',
                    'contact_no.numeric' => 'Mobile Number must be numeric.',
                    'contact_no.digits_between' => 'Mobile Number must be between 8 to 12 digits.',
                    'country.required' => 'Please Choose Country.',
                    'state.required' => 'Please Choose State.',
                    'city.required' => 'Please Choose City.',
                    'locality.required' => 'Please Choose Locality.',
                    'pincode.required' => 'Please enter Pincode.',
                    'image.required' => 'Please select Image.',
                ]
        );
        
        Restaurant::insert(
            [
                'name'          => $request->name,
                'address'       => $request->address,
                'contact_no'    => $request->contact_no,
                'country_id'    => $request->country,
                'state_id'      => $request->state,
                'city_id'       => $request->city,
                'locality_id'   => $request->locality,
                'pincode'       => $request->pincode,
                'image'         => asset('/uploads/restaurant').'/'.$request->image,
                'created_at'    => date('Y-m-d H:i:s')
            ]
        );

        $request->session()->flash('success', 'Restaurant added successfully.');
        return redirect('admin/restaurant');
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
        $data['page_title'] = $data['title']= 'Edit Restaurant';
        $data['countries'] = DB::table("country")->pluck("country_name","id_country");
        
        $data['restaurant'] = Restaurant::select(['restaurant.id','restaurant.name','restaurant.address','restaurant.contact_no','restaurant.image','restaurant.pincode','restaurant.locality_id','restaurant.city_id','id_country','country_name','locality.name as locality_name','states.id as state_id','states','city.name as city_name'])
            ->leftJoin('city','city.id','city_id')
            ->leftJoin('states','city.state_id','states.id')
            ->leftJoin('country','states.country_id','id_country')
            ->leftJoin('locality','locality.id','locality_id')

            ->where(['restaurant.id'=>$id])->first();
        
        return view('backend.restaurant.edit')->with($data);
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
                'name'          => 'required|max:50',
                'address'       => 'required|max:50',
                'contact_no'    => 'required|numeric|digits_between:8,12',
                'country'       => 'required',
                'state'         => 'required',
                'city'          => 'required',
                'locality'      => 'required',
                'pincode'       => 'required',
                'image'          => 'required'
                ],
                [
                    'name.required' => 'Please enter Restaurant Name.',
                    'address.required' => 'Please enter Restaurant Address.',
                    'contact_no.required' => 'Please enter Mobile Number.',
                    'contact_no.numeric' => 'Mobile Number must be numeric.',
                    'contact_no.digits_between' => 'Mobile Number must be between 8 to 12 digits.',
                    'country.required' => 'Please Choose Country.',
                    'state.required' => 'Please Choose State.',
                    'city.required' => 'Please Choose City.',
                    'locality.required' => 'Please Choose Locality.',
                    'pincode.required' => 'Please enter Pincode.',
                    'image.required' => 'Please select Image.',
                ]
        );
        Restaurant::where('id', $id)
        ->update(
            [
                'name'          => $request->name,
                'address'       => $request->address,
                'contact_no'    => $request->contact_no,
                'country_id'    => $request->country,
                'state_id'      => $request->state,
                'city_id'       => $request->city,
                'locality_id'   => $request->locality,
                'pincode'       => $request->pincode,
                'image'         => asset('/uploads/restaurant').'/'.$request->image,
                'created_at'    => date('Y-m-d H:i:s')
            ]
        );

        $request->session()->flash('success', 'Restaurant updated successfully.');
        return redirect('admin/restaurant');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Restaurant::where('id', $id)->delete();
        $request->session()->flash('success', 'Restaurant deleted successfully.');
        return redirect('admin/restaurant');
    }
}
