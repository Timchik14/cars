<?php

namespace App\Services;

use App\Models\Car;
use App\Models\Company;
use Exception;
use Illuminate\Http\JsonResponse;

class CarsService
{
    public function getCarType($car)
    {
        $carType = null;

        if ($car['Vehicle_type']['ExternalID'] == '0f61a8a4-9c6e-11ea-9699-00505695dcc5') {
            $carType = 1;
        } elseif ($car['Vehicle_type']['ExternalID'] == '0f61a8c7-9c6e-11ea-9699-00505695dcc5') {
            $carType = 2;
        }
        return $carType;
    }

    public function prepareData($car)
    {
        $carType = $this->getCarType($car);
        $company = Company::where('id', 1)->get()->first();
        $data = [
            'number' => $car['Number'],
            'mark' => $car['Model']['ExternalID'],
            'model' => $car['Model']['ExternalID'],
            'color' => '',
            'vin' => $car['Key']['VIN'],
            'load_capacity' => $car['Load_capacity'],
            'holding_capacity' => 0,
            'glonass' => '',
            'pts' => null,
            'sts' => null,
            'lease' => '{}',
            'created_by' => 1,
            'type' => $carType,
            'company_id' => 1,
            'updated_by' => 1,
            'report_uid' => '',
            'unloaded_weight' => $car['Curb_weight'],
            'dd_id' => 1,
            'external_id' => $car['Key']['ExternalID'],
            'temperature_recorder' => '',
            'tracking_number' => '',
            'sts_number' => $car['Current_documents'][0]['Key']['ExternalID'],
            'fines_monitoring_id' => 0,
            'owner_confirmation_status' => 4,
            'platform_type' => 0,
            'owner_id' => $company->id ?? null,
            'owner_inn' => $company->inn ?? null,
            'owner_entity_type' => $company->entity_type ?? null,
        ];

        return $data;

    }

    public function prepareReply($response, $data)
    {
        $reply['MessageId'] = 125;
        $reply['Key'] = ['Type' => $data['type'], 'Vin' => $data['vin'], 'ExternalID' => $data['external_id']];
        $reply['Date'] = 123;
        $reply['Status'] = 2;
        $reply['Error_code'] = $response['errorCode'];
        $reply['Error_text'] = $response['errorText'];

        return $reply;
    }

    public function insert($car, $data)
    {
        if ($car['Key']['Type'] == 'vehicles') {
            try {
                Car::upsert($data, ['external_id']);
                $response['errorCode'] = 1;
                $response['errorText'] = '';
            } catch (Exception $e) {
                $response['errorCode'] = 2;
                $response['errorText'] = $e->getMessage();
            }
        }
        return $response;
    }

    public function insertData($request)
    {
        $message = json_decode($request['json'], true);
        $responses['Head'] = ['Source' => 'AT', 'MessageId' => '881e1e3a-309b-11eb-969d-00505695dcc5'];

        foreach ($message['Message']['Body']['Objects'] as $car) {

            if ($car['Is_deleted']) {
                Car::where('external_id', $car['Key']['ExternalID'])->delete();
            } else {
                Car::withTrashed()
                    ->where('external_id', $car['Key']['ExternalID'])
                    ->restore();

                $data = $this->prepareData($car);
                $response = $this->insert($car, $data);
                $responses['Body']['Reply'][] = $this->prepareReply($response, $data);
            }
        }

        return new JsonResponse(['Message' => $responses], 200);
    }
}
