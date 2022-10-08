<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estate;
use Illuminate\Support\Facades\Auth;

class StrategySellController extends Controller
{
    //
    function strategySellShow(Request $request){


        return view("estate.strategy_sell",[
            "myEstates" => $myEstates ?? [],
        ]);
    }

}