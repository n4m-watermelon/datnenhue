<?php

namespace App\Services;


use App\Models\Estate;
use App\Services\Abstracts\StoreEstateServiceAbstract;
use Illuminate\Http\Request;

class StoreEstateService extends StoreEstateServiceAbstract
{

    /**
     * @param Request $request
     * @param Estate $estate
     * @return mixed|void
     */
    public function execute(Request $request, Estate $estate)
    {
        if ($estate->address) {
//            $data = [];
//            $url  = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($estate->address) . '&key=AIzaSyDqAHaMV9ZVcSX992nMQOgZ_Vy80GUZ_8I&sensor=true';
//            $json = @file_get_contents($url);
//            $position = json_decode($json);
//            if ($position->status == "OK"){
//                if ($position->results[0]->geometry->location->lat && $position->results[0]->geometry->location->lng){
//                    $estate->lat = $position->results[0]->geometry->location->lat;
//                    $estate->lng = $position->results[0]->geometry->location->lng;
//                    $estate->save();
//                }
//            };
        }
    }

    public function uploadImage(Request $request, Estate $estate)
    {
        // TODO: Implement uploadImage() method.
    }
}
