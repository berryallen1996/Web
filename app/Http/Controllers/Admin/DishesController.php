<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use App\Models\Restaurant;
use App\Models\Dish;


class DishesController extends Controller
{
   public function dishList(Request $request,$id){
        $data['page_title'] = $data['title'] = 'Dishes Management';
        $data['restaurant_name'] = Restaurant::select('name')->where('id',$id)->first();
        $data['restaurant_id'] = $id;
        if ($request->ajax()){
            $dishes = Dish::select('dishes.id','dishes.name as dish_name','category_id','price','restaurant.name as restaurant_name','dishes_category.name as category')->leftJoin('dish_restaurant','dish_id','id')
                ->leftJoin('restaurant','restaurant.id','dish_restaurant.restaurant_id')
                ->leftJoin('dishes_category','dishes_category.id','dishes.category_id')
                ->where('dish_restaurant.restaurant_id',$request->restaurant_id)
                ->get();
            if($dishes->isEmpty()){
                return Datatables::of($dishes)->make(true);   
            }else{  
                $data['dishes'] = array();
                foreach ($dishes as $key => $value) {
                    $data['dishes'][$key]['dishes_id'] = ($value->id);
                    $data['dishes'][$key]['dish_name'] = ($value->dish_name);
                    $data['dishes'][$key]['restaurant_name'] = ($value->restaurant_name);
                    $data['dishes'][$key]['category'] = ($value->category);
                    $data['dishes'][$key]['price'] = ($value->price);
                }
                return Datatables::of($data['dishes'])->make(true); 
            }  
        }
        return view('backend.dishes.list')->with($data);
    }

    public function addDish(Request $request, $id){
    	// dd($id);
    	$data['page_title'] = $data['title'] = 'Add Dish';
    	$data['countries'] = DB::table("country")->pluck("country_name","id_country");

    	return view('backend.dishes.add')->with($data);

    }
}
?>