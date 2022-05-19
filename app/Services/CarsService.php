<?php

namespace App\Services;

class CarsService
{
    public function getCarType($car)
    {
        $carType = 0;

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
        $data = [
            'number' => $car['Number'],
            'mark' => $car['Model']['ExternalID'],
            'model' => $car['Model']['ExternalID'],
            'color' => '',
            'vin' => $car['Key']['VIN'],
            'load_capacity' => $car['Load_capacity'],
            'holding_capacity' => 0,
            'glonass' => '',
            'pts' => $car['Current_documents'][0]['Key']['Type'],
            'sts' => $car['Current_documents'][0]['Key']['Type'],
            'lease' => '',
            'created_by' => 1,
            'type' => $carType,
            'company_id' => 1,
            'status' => 1,
            'updated_by' => 1,
            'report_uid' => '',
            'unloaded_weight' => $car['Curb_weight'],
            'country_id' => 1,
            'is_non_resident' => false,
            'dd_id' => 1,
            'external_id' => $car['Key']['ExternalID'],
            'temperature_recorder' => '',
            'tracking_number ' => '',
            'sts_number ' => $car['Current_documents'][0]['Key']['ExternalID'],
            'fines_monitoring_id' => 0,
            'owner_confirmation_status ' => 0,
            'platform_type  ' => 0,
            'owner_id' => 1,
            'owner_inn' => '',
            'owner_entity_type' => 1,
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
}
