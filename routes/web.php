<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/flightsearch', 'FlightController@index');

Route::get('/flight', function () {
   $apikey = "AIzaSyChKb0QWxbEteqmdCGc_fNCoEvefBu0iHw";
	$url = "https://www.googleapis.com/qpxExpress/v1/trips/search?key=" . urlencode($apikey);

	$slices = array(array('origin' => 'ABV', 'destination' => 'LOS', 'date' => "2017-10-21"));
 
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

   var_dump($trips);

/*
foreach ($trips as $trip) {
    echo "Flight Cost: " . $trip['saleTotal'] . "<br>";
    foreach ($trip['slice'] as $index => $slice) {
        print "$index: " . $slices[$index]['origin'] . " TO " . $slices[$index]['destination'] . "<br>";
        foreach ($slice['segment'] as $segment) {
            foreach ($segment['leg'] as $leg) {
                print "FROM " . $leg['origin'] . " to " . $leg['destination'] . " (" . $leg['departureTime'] . "-" . $leg['arrivalTime'] . ")" . "<br>";
            }
        }
    }
}
*/
});
