<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use App\Models\Plate;

class QuantityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['page_title'] = $data['title'] = 'Plate';
        if ($request->ajax()){

            $quantity = Plate::all(['id','name','created_at']);
            
            if($quantity->isEmpty()){
                return Datatables::of($quantity)->make(true);   
            }else{  
                $data['quantity'] = array();
                foreach ($quantity as $key => $value) {
                    $data['quantity'][$key]['quantity_id'] = ($value->id);
                    $data['quantity'][$key]['name'] = ($value->name);
                }
                return Datatables::of($data['quantity'])->make(true); 
            }  
        }
        return view('backend.quantity.list')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_title'] = $data['title'] = 'Add Plate';
        return view('backend.quantity.add')->with($data);
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
                'name' => 'required|max:50|unique:plates',
                ],
                [
                    'name.required' => 'Please enter Plate.',
                    'name.unique'   => 'Plate Already Exists.Please try another.'
                ]
        );
        
        Plate::insert(
            [
                'name' => $request->name,
                'created_at' => date('Y-m-d H:i:s')
            ]
        );

        $request->session()->flash('success', 'Plate saved successfully.');
        return redirect('admin/quantity');
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
        $data['page_title'] = $data['title']= 'Edit Plate';
        
        $data['quantity'] = Plate::select(['id','name'])->where(['id'=>$id])->first();
        
        return view('backend.quantity.edit')->with($data);
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
                'name' => 'required|max:50|unique:plates,name,'.$id.'',
                ],
                [
                    'name.required' => 'Please enter Country.',
                    'name.unique'   => 'Plate Already Exists.Please try another.'
                ]
        );

        Plate::where('id', $id)
            ->update([
                        'name'       => $request->name,
                    ]
        );

        $request->session()->flash('success', 'Plate updated successfully.');
        return redirect('admin/quantity');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        Plate::where('id', $id)->delete();
        $request->session()->flash('success', 'Plate deleted successfully.');
    }
}
