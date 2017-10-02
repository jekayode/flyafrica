<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FlightController extends Controller
{

    public function index(){

       $apikey = "AIzaSyChKb0QWxbEteqmdCGc_fNCoEvefBu0iHw";
		$url = "https://www.googleapis.com/qpxExpress/v1/trips/search?key=" . urlencode($apikey);

		$slices = array(array('origin' => 'ABV', 'destination' => 'LOS', 'date' => "2017-10-21")
              , array('origin' => 'LOS', 'destination' => 'ABV', 'date' => "2017-10-24"));
 
		$postData = '{
                "request": {
                    "passengers": {
                        "adultCount": 1
                        },
                    "slice": ' . json_encode($slices) . '
                }
            }';


		$curlConnection = curl_init();
	    curl_setopt($curlConnection, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
	    curl_setopt($curlConnection, CURLOPT_URL, $url);
	    curl_setopt($curlConnection, CURLOPT_CUSTOMREQUEST, "POST");
	    curl_setopt($curlConnection, CURLOPT_POSTFIELDS, $postData);
	    curl_setopt($curlConnection, CURLOPT_FOLLOWLOCATION, TRUE);
	    curl_setopt($curlConnection, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($curlConnection, CURLOPT_SSL_VERIFYPEER, FALSE);

	    $results = json_decode(curl_exec($curlConnection), true);

	    if (isset($results['error'])) {
	        var_dump($results);
	        exit();
	    }
	    
	   $trips = array_filter($results['trips']['tripOption'], function($kind) {
	        if (!isset($kind['kind'])) {
	            return false;
	        }
	        if ($kind['kind'] == "qpxexpress#tripOption") {
	            return true;
	        }
	        return false;
	    });

	   return view('flights', compact('trips'));
	   //return view()
    }
}

