<?php 
if (!function_exists('greeting')) {
    function greeting($response, $data){
      
        return response()->json([$data,$response]);
    }
}