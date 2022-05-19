<?php

namespace App\Http\Controllers;

use App\Services\CarsService;
use Illuminate\Http\Request;
use App\Models\Car;
use Exception;

class CarsController extends Controller
{
    public function store(Request $request, CarsService $service)
    {
        $message = json_decode($request['json'], true);
        $responses['Message']['Head'] = ['Source' => 'AT', 'MessageId' => '881e1e3a-309b-11eb-969d-00505695dcc5'];
        
        foreach ($message['Message']['Body']['Objects'] as $car) {
            if ($car['Is_deleted']) {
                Car::where('external_id', $car['Key']['ExternalID'])->delete();
            } else {
                Car::withTrashed()
                    ->where('external_id', $car['Key']['ExternalID'])
                    ->restore();
                $response = [];
                if ($car['Key']['Type'] == 'vehicles') {
                    $data = $service->prepareData($car);
                    try {
                        Car::upsert($data, ['external_id']);
                        $response['errorCode'] = 1;
                        $response['errorText'] = '';
                    } catch (Exception $e) {
                        $response['errorCode'] = 2;
                        $response['errorText'] = $e->getMessage();
                    }

                    $responses['Message']['Body']['Reply'][] = $service->prepareReply($response, $data);
                }
            }
        }
        dd($responses);
    }
}
