<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuantityController extends Controller
{
    public function index()
    {
        $data['page_title'] = $data['title'] = 'Company';
		if ($request->ajax()){

            $quantity = Quantity::getQuantityList();
            return Datatables::of($quantity)
            ->editColumn('action',function($company) {
            	$html = '<a href="'.___url('quantity/'.$quantity->id.'/edit','backend','').'" class="badge bg-light-blue">Edit</a>';
            	if($quantity->status === 'active'){
                    $html .= '<a href="javascript:void(0);" data-url="'.___url('quantity/status?id_quantity='.___encrypt($quantity->id).'&status=inactive','backend','').'" data-request="status" data-ask="Do you really want to continue with this action?" class="badge bg-green" >Inactive</a> ';

                }else{
                	$html .= '<a href="javascript:void(0);" data-url="'.___url('quantity/status?id_quantity='.___encrypt($quantity->id).'&status=active','backend','').'" data-request="status" data-ask="Do you really want to continue with this action?" class="badge bg-green" >Active</a> ';
                }

                $html .= '<a href="javascript:void(0);" data-url="'.___url('quantity/status?id_quantity='.___encrypt($quantity->id).'&status=trashed','backend','').'" data-request="status" data-ask="Your all Branches, HQ and Staff User will be permanently delete?" class="badge bg-red" >Delete</a>';
                

                return $html;
            })
            ->make(true);
        }

        $data['html'] = $htmlBuilder->addColumn(['data' => 'row_number', 'name' => 'row_number', 'title' => '#', 'width' => '1', 'searchable' => false, 'orderable' => false])
        ->addColumn(['data' => 'company_name', 'name' => 'company_name', 'title' => 'Company Name'])
        ->addColumn(['data' => 'country_name', 'name' => 'country_name', 'title' => 'Country Name'])
        ->addColumn(['data' => 'address', 'name' => 'address', 'title' => 'Address'])
        ->addColumn(['data' => 'license_number', 'name' => 'license_number', 'title' => 'Lic Code'])
        ->addColumn(['data' => 'pincode', 'name' => 'pincode', 'title' => 'Pincode'])
        ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Date'])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action','searchable' => false, 'orderable' => false]);
       //dd($data['html']->scripts());
		return view('backend.company.list')->with($data);
    }
}
