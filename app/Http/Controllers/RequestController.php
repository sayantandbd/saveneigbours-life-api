<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'body' => 'required'
        ]);
        
        return Auth::user()->requests()->create($request->all());
    }

    public function list()
    {
        $user = Auth::user();

        $radius = 25;
        $coordinates['lat'] = $user->lat;
        $coordinates['lng'] = $user->lng;
        $lists =  Models\Request::where('status', '1')
        ->where(function($q) use ($radius, $coordinates) { 
            $q->isWithinMaxDistance($coordinates, $radius);
        })->get();

        $i = 0;
        foreach($lists as $list) {
            $lists[$i]['user'] = $list->user()->first();
            $lists[$i]['distance'] = $this->distance($user->lat,$user->lng,$list->lat,$list->lng,'K');
            $i++;
        }

        return $lists;
    }

    function distance($lat1, $lon1, $lat2, $lon2, $unit) {
    if (($lat1 == $lat2) && ($lon1 == $lon2)) {
        return 0;
    }
    else {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);
    
        if ($unit == "K") {
        return ($miles * 1.609344);
        } else if ($unit == "N") {
        return ($miles * 0.8684);
        } else {
        return $miles;
        }
    }
    }


    
}
