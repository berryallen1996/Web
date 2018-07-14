<?php

    use Illuminate\Support\Facades\Mail;
    use Twilio\Rest\Client;

    /* For getting the country data from table */
    function getCountries(){
        $company = DB::table('countries')
        ->get()
        ->toArray();
        return json_decode(json_encode($company), true);
    }

    function ___get_user_menu($menu = array()){
        $option = array(
            'depth' => array(
                'sidebar-menu',
                'treeview-menu',
            )
        );

        ___get_category_list($menu);
        echo sprintf('<ul class="%s"><li class="header">MAIN NAVIGATION</li>%s</ul>',$option['depth'][0],___menu($menu,$option));
    }

        /* MENU */

    function ___get_category_list(&$categoryList,$parent_category = 0){
        $id_admin = Auth::guard('web')->user()->id;
        static $index = 0;$page = "";

        $admin_menus = \App\Models\AdminModel::get_menu_visibility($id_admin);

        $result = DB::table('users_menu')->where(
            [
                'status' => 'active',
                'parent' => $parent_category
            ]
        )->orderBy('menu_order','ASC')->get()->toArray();

        foreach($result as $row){
            
            if(in_array($row->id, $admin_menus)){
                $callback = $row->callback;
                $categoryList[$row->id] = array(
                    'menu_id' => $row->id,
                    'name' => $row->name,
                    'action_url' => $row->action_url,
                    'menu_icon' => $row->menu_icon,
                    'disable_list_view' => $row->disable_list_view,
                    'callback' => (!empty($row->callback) && function_exists($row->callback))?$callback():'',
                    'class' => ($page == $row->action_url)?sprintf('active %s',$row->menu_class):$row->menu_class
                );
            }
            if(check_parent_category_by_id($row->id) > 0){
                ___get_category_list($categoryList[$row->id]['child'],$row->id);
            }
        }
    }

    function check_parent_category_by_id($category_id){
        return DB::table('users_menu')->where(
            array(
                'parent' => $category_id
            )
        )->count();
    }


    function ___menu($menu,$option = array(),$depth = 0){
        static $html = '';
        $html .= add_menu_item($menu,$option,$depth,$html);
        return $html;
    }


    function add_menu_item($menu,$option,$depth,&$html){
        $id_admin = Auth::guard('web')->user()->id;
        $admin_menus = \App\Models\AdminModel::get_menu_visibility($id_admin);

        foreach ($menu as $item) {
            if(!empty($item['menu_id'])){
                if(in_array($item['menu_id'], $admin_menus)){
                    if(empty($item['disable_list_view'])){

                        if(!empty($item['child'])){
                            $classFlag = false;
                            foreach ($item['child'] as $child) {
                                if($child['class'] == 'active ' ){
                                    $classFlag = true;
                                }else if(strpos(Request::url(),___url($child['action_url'],'backend',false)) === 0){
                                    $classFlag = true;
                                }
                            }
                            if($classFlag == true){
                                $html .= '<li class="active treeview" >';
                            }else{
                                $html .= '<li class="treeview" >';
                            }
                        }else{
                            if(!empty($item['class'])){
                                $html .= '<li class="'.$item['class'].'">';
                            }else if(strpos(Request::url(),___url($item['action_url'],'backend',false)) === 0){
                                $html .= '<li class="active">';
                            }else{
                                $html .= '<li>';
                            }
                        }
                    }

                    if(!empty($item['disable_list_view'])){
                        $html .= '<a href="'.___url($item['action_url'],'backend',false).'" class="'.$item['class'].'">'.$item['menu_icon'].'<span>'.$item['name'].'</span>';
                    }else{
                        $html .= '<a href="'.___url($item['action_url'],'backend',false).'">'.$item['menu_icon'].'<span>'.$item['name'].'</span>';
                    }
                    if($depth == 0 && !empty($item['child'])){
                        $html .= '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>';
                    }

                    $html .= '</a>';

                    if(!empty($item['child'])){
                        $depth++;
                        if(!empty($option['depth'][$depth])){
                            $html .= '<ul class="'.$option['depth'][$depth].'">';
                        }else{
                            $html .= '<ul>';
                        }

                        ___menu($item['child'],$option,$depth);
                        $depth--;
                        $html .= '</ul>';
                    }
                    if(empty($item['disable_list_view'])){
                        $html .= '</li>';
                    }
                }
            }
        }
    }


    function ___url($url = "",$folder = "",$echo = true) {
        if($folder == 'backend'){
            
                $url = "admin/$url";
           
        }

        if(preg_match( '/^(http|https):\\/\\/[a-z0-9]+([\\-\\.]{1}[a-z0-9]+)*\\.[a-z]{2,5}'.'((:[0-9]{1,5})?\\/.*)?$/i' ,$url)){
            if($echo == true){
                echo $url;
            }else{
                return $url;
            }
        }else{
            if($echo == true){
                echo URL::to($url);
            }else{
                if($url === '/administrator/#'){
                    return 'javascript:void(0);';
                }else{
                    return URL::to($url);
                }
            }
        }
    }

    function __sendOTP($number,$data){
       //Your Account SID and Auth Token from twilio.com/console
       $sid   = env('TWILIO_ACCOUNT_SID'); // Your Account SID from www.twilio.com/console
       $token = env('TWILIO_AUTH_TOKEN'); // Your Auth Token from www.twilio.com/console

       $client = new Client($sid, $token);
       // Use the client to do fun stuff like send text messages!
       try{
           $response = $client->messages->create(
               // the number you'd like to send the message to
               '+91'.$number,
               array(
                   // A Twilio phone number you purchased at twilio.com/console
                   'from' => env('TWILIO_NUMBER'),
                   // the body of the text message you'd like to send
                   'body' => 'Your OTP is : '.$data
               )
           );
           return true;
       }
       catch(Exception $e){
           return false;   
       }
   }

?>