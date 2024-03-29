<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Hash;
use Illuminate\Support\Facades\Input;
use File;
use Session;

class SettingsController extends Controller
{
    //
   /* public function editProfile() {
    	$live = array('menu'=>'12','parent'=>'2');
    	return view('profile_edit', compact('live'));
    }*/
    public function updateProfile( Request $request)
    {
		$data_id    = Auth::user()->id;
		$data_fname = $request->first_name;
		$data_lname = $request->last_name;
		$data_email = $request->email;
    	$userUpdate = User::where('id', $data_id)->update([
    		'fname' => $data_fname,
    		'lname' => $data_lname,
    		'email' => $data_email,
    		]);
    	if (Input::hasFile('images')) {
    		$files = Input::file('images');
    		File::Delete(Auth::user()->image_path);
    		foreach ($files as $file) {
				$fileName      = 'UID'.$data_id.rand().'.jpg';
				$target        = 'storage/user_images/';
				$uploadSuccess = $file->move($target, $fileName);
				$profileImage  = User::where('id', $data_id)->update([
	    			'user_image' => $fileName,
	    			'image_path' => $target.$fileName,
	    			]);
    		}
    	}
    	Session::flash('message', 'Profile update successfull.');
    	return back();
    }
    public function passwordChange() {
    	$live = array('menu'=>'34','parent'=>'9');
    	return view('common.passwordChange', compact('live'));
    }
    public function updatePassword(Request $request)
    {
        $newPassword = $request->newPassword;
        $currentPassword = Auth::User()->password;
        if(Hash::check($request->currentPassword, $currentPassword)){
            $user_id = Auth::User()->id;                       
            $obj_user = User::find($user_id);
            $obj_user->password = Hash::make($newPassword);
            $obj_user->save();
            Session::flash('message', 'Password Changes Successfully');
        } else {
            Session::flash('message', 'Current Password does not matched');
        }
        return back();        
    }
    /*public function email_template()
    {
        $live = array('menu'=>'15','parent'=>'2');
        return view('settingsEmailTemplate', compact('live'));
        
    }*/

    public function menuManagement()
    {
        # code...
        $live = array('menu'=>'40','parent'=>'9');
        return view('admin.menuManagement', compact('live'));

    }
}
