<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function checkactiveuser(Request $request)
    {
        $email_address = $request->input('email');
        $valcheck = User::checkactiveuser($email_address);
        if($email_address==''){
        	echo 'Y';
        }
        else if($valcheck=='Y')
        {
            echo 'Y';
        }
        else if($valcheck=='NY')
        {
            echo 'NY';
        }
        else if($valcheck=='N')
        {
            echo 'N';
        }
    }
}
