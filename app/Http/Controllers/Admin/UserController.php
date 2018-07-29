<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\StaticContent;
use Yajra\Datatables\Datatables;
use App\User;

class UserController extends Controller
{
	public function index(Request $request){
		$data['page_title'] = $data['title'] = 'User Management';
        if ($request->ajax()){

            $user = User::select(['id','first_name','last_name','contact_no','email','created_at'])->where('type','user')->get();
            
            if($user->isEmpty()){
                return Datatables::of($user)->make(true);   
            }else{  
                $data['user'] = array();
                foreach ($user as $key => $value) {
                    $data['user'][$key]['user_id'] = ($value->id);
                    $data['user'][$key]['first_name'] = ($value->first_name);
                    $data['user'][$key]['last_name'] = ($value->last_name);
                    $data['user'][$key]['email'] = ($value->email);
                    $data['user'][$key]['contact'] = ($value->contact_no);
                }
                return Datatables::of($data['user'])->make(true); 
            }  
        }
        return view('backend.user.list')->with($data);
	}



	/**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['page_title'] = $data['title']= 'Edit User';
        
        $data['user'] = User::select(['id','first_name','last_name','email','contact_no'])->where(['id'=>$id])->first();
        
        return view('backend.user.edit')->with($data);
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
                'first_name' 	=> 'required',
                'last_name' 	=> 'required',
                'email' 		=> 'required|max:50|unique:users,email,'.$id.'',
                'contact' 		=> 'required|numeric|unique:users,contact_no,'.$id.'',
                ],
                [
                    'first_name.required' => 'Please enter First Name.',
                    'last_name.required' => 'Please enter Last Name.',
                    'email.required' => 'Please enter Email.',
                    'email.unique'   => 'Email Already Exists.Please try another.',
                    'contact.required' => 'Please enter Contact Info.',
                    'contact.unique'   => 'Number Already Exists.Please try another.'

                ]
        );

        User::where('id', $id)
            ->update([
                        'first_name'       => $request->first_name,
                        'last_name'       => $request->last_name,
                        'email'       => $request->email,
                        'contact'       => $request->contact,
                    ]
        );

        $request->session()->flash('success', 'User updated successfully.');
        return redirect('admin/user');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        User::where('id', $id)->delete();
        $request->session()->flash('success', 'User deleted successfully.');
    }
}
?>