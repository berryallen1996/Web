<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use App\Models\DishCategory;

class DishCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['page_title'] = $data['title'] = 'Dish Category';
        if ($request->ajax()){

            $category = DishCategory::all(['id','name','created_at']);
            
            if($category->isEmpty()){
                return Datatables::of($category)->make(true);   
            }else{  
                $data['category'] = array();
                foreach ($category as $key => $value) {
                    $data['category'][$key]['category_id'] = ($value->id);
                    $data['category'][$key]['name'] = ($value->name);
                }
                return Datatables::of($data['category'])->make(true); 
            }  
        }
        return view('backend.category.list')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_title'] = $data['title'] = 'Add Category';
        return view('backend.category.add')->with($data);
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
                'name' => 'required|max:50|unique:dishes_category',
                ],
                [
                    'name.required' => 'Please enter Category.',
                    'name.unique'   => 'Category Already Exists.Please try another.'
                ]
        );
        
        DishCategory::insert(
            [
                'name' => $request->name,
                'created_at' => date('Y-m-d H:i:s')
            ]
        );

        $request->session()->flash('success', 'Category saved successfully.');
        return redirect('admin/category');
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
        $data['page_title'] = $data['title']= 'Edit Category';
        
        $data['category'] = DishCategory::select(['id','name'])->where(['id'=>$id])->first();
        
        return view('backend.category.edit')->with($data);
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
                'name' => 'required|max:50|unique:dishes_category,name,'.$id.'',
                ],
                [
                    'name.required' => 'Please enter Category.',
                    'name.unique'   => 'Category Already Exists.Please try another.'
                ]
        );

        DishCategory::where('id', $id)
            ->update([
                        'name'       => $request->name,
                    ]
        );

        $request->session()->flash('success', 'Category updated successfully.');
        return redirect('admin/category');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        DishCategory::where('id', $id)->delete();
        $request->session()->flash('success', 'Category deleted successfully.');
    }
}
