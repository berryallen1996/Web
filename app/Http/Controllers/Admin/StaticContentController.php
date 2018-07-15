<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\StaticContent;
use Yajra\Datatables\Datatables;

class StaticContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['page_title'] = $data['title'] = 'Static Content';
        if ($request->ajax()){
            $getStatic = StaticContent::all('object',['title','description','alias']);
            $data['getStatic'] = array();
            foreach ($getStatic as $key => $value) {
                $data['getStatic'][$key]['description'] = ($value->description);
                $data['getStatic'][$key]['title'] = ($value->title);
                $data['getStatic'][$key]['alias'] = strtoupper($value->alias);
                $data['getStatic'][$key]['id'] = $value->alias;
            }
            return Datatables::of($data['getStatic'])->make(true);
        }
        return view('backend.static.list')->with($data);
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

    public function getStatic(Request $request)
    {
        $getStatic = StaticContent::all('object',['title','description','alias']);
        $data['getStatic'] = array();
        foreach ($getStatic as $key => $value) {
            $data['getStatic'][$key]['description'] = ($value->description);
            $data['getStatic'][$key]['title'] = ($value->title);
            $data['getStatic'][$key]['alias'] = strtoupper($value->alias);
            $data['getStatic'][$key]['id'] = $value->alias;
        }
        return Datatables::of($data['getStatic'])->make(true);        
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
        $data['page_title'] = $data['title'] = 'Edit Static Content';
        
        $data['static'] = StaticContent::select(['title','description','alias'])->where(['alias'=>$id])->first();
        
        return view('backend.static.edit')->with($data);
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
                'title' => 'required',
                'description' => 'required',
                ],
                [
                    'title.required' => 'Please enter Title.',
                    'description.required' => 'Please enter Description.',
                ]
        );
        
        StaticContent::where('alias', $id)
            ->update([
                        'title' => $request->title,
                        'description'=> $request->description,
                    ]
                );
        $request->session()->flash('success', 'Static Content updated successfully.');
        return redirect('admin/static');
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
