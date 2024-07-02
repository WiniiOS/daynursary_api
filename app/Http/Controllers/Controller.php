<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public function randomPassword($len = 10) {
        if($len < 8)
            $len = 8;

        $sets = array();
        $sets[] = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        $sets[] = '0123456789';
        $sets[]  = '%!&*$#@|+';

        $password = '';
        
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
        }

        while(strlen($password) < $len) {
            $randomSet = $sets[array_rand($sets)];
            
            $password .= $randomSet[array_rand(str_split($randomSet))]; 
        }
        
        return str_shuffle($password);
    }
    
}
