<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

use App\Classes\CropAvatar;

class CropController extends Controller
{

    public function cropper()
    {
        
        $crop = new CropAvatar($_POST['avatar_src'], $_POST['avatar_data'], $_FILES['avatar_file'] , $_POST['image_width'], $_POST['image_height']);

        $response = array(
            'state'  => 200,
            'message' => $crop -> getMsg(),
            'result' => $crop -> getResult(),
            'path'=> $crop -> upload_path,
            'file_name'=> $crop -> return_file_name,
            'original_name'=> $crop -> src,
        );

        if($crop -> getMsg())
        {

        }
        else
        {
            if(!is_dir(url('uploads/'.$_POST['upload_folder'])))
            {
                @mkdir(url('uploads/'.$_POST['upload_folder']),0777);
            }
            
            unlink($crop -> src);
            rename($crop -> getResult(), '../public/uploads/'.$_POST['upload_folder'].'/'.$crop -> return_file_name);

            
            

        }

        echo json_encode($response);
    }
}
