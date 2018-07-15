<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use App\Models\Quantity;
use App\Models\Locality;
use App\Models\Country;
use App\Models\State;
use App\Models\City;



class LocalityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['page_title'] = $data['title'] = 'Locality';
        if ($request->ajax()){

            $locality = Locality::getAllLocality();
            // dd($locality);
            
            if($locality->isEmpty()){
                return Datatables::of($locality)->make(true);   
            }else{  
                $data['locality'] = array();
                foreach ($locality as $key => $value) {
                    $data['locality'][$key]['locality_id'] = ($value->id);
                    $data['locality'][$key]['name'] = ($value->name);
                    $data['locality'][$key]['city'] = ($value->city_name);
                }
                return Datatables::of($data['locality'])->make(true); 
            }  
        }
        return view('backend.locality.list')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_title'] = $data['title'] = 'Add Locality';
        $data['countries'] = DB::table("country")->pluck("country_name","id_country");
        return view('backend.locality.add')->with($data);
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
                'name'      => 'required|max:50',
                'country'   => 'required',
                'state'     => 'required',
                'city'      => 'required'
                ],
                [
                    'name.required' => 'Please enter Locality.',
                    'country'       => 'Please Choose Country.',
                    'state'         => 'Please Choose State.',
                    'city'          => 'Please Choose City.'
                ]
        );
        
        Locality::insert(
            [
                'name'          => $request->name,
                'city_id'       => $request->city,
                'created_at'    => date('Y-m-d H:i:s')
            ]
        );

        $request->session()->flash('success', 'Locality saved successfully.');
        return redirect('admin/locality');
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
        $data['page_title'] = $data['title']= 'Edit Locality';
        $data['countries'] = DB::table("country")->pluck("country_name","id_country");
        
        $data['locality'] = Locality::select(['locality.id','locality.name','city_id','id_country','country_name','states.id as state_id','states','city.name as city_name'])
                                    ->leftJoin('city','city.id','city_id')
                                    ->leftJoin('states','city.state_id','states.id')
                                    ->leftJoin('country','states.country_id','id_country')

                                    ->where(['locality.id'=>$id])->first();
        // dd($data);
        
        return view('backend.locality.edit')->with($data);
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
                'name' => 'required|max:50|unique:locality,name,'.$id.'',
                'country'   => 'required',
                'state'     => 'required',
                'city'      => 'required'

                ],
                [
                    'name.required' => 'Please enter Locality.',
                    'country'       => 'Please Choose Country.',
                    'state'         => 'Please Choose State.',
                    'city'          => 'Please Choose City.'
                ]
        );

        Locality::where('id', $id)
            ->update([
                        'name'       => $request->name,
                        'city_id'       => $request->city,
                    ]
        );

        $request->session()->flash('success', 'Locality updated successfully.');
        return redirect('admin/locality');
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
