<?php

App::uses('Component', 'Controller');

class SMSGatewayComponent extends Component {
    
    public function send($configuration) {
        $fcmUrl = 'https://smsgateway.me/api/v4/message/send';
        $serverKey = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTYxMzU5MDg1NywiZXhwIjo0MTAyNDQ0ODAwLCJ1aWQiOjg3MzAzLCJyb2xlcyI6WyJST0xFX1VTRVIiXX0.-qlsIyi7ZDmMkWv3ZvTHIBI377hrvbs8kR3OVSqHLaw";
        $headers = [
            'Authorization: ' . $serverKey,
            'Content-Type: application/json'
        ];
        
        $smsTemplate = [
            'from' => 'Mercadito Naranja',
            'message' => 'This is notification text',
            'phone_number' => '',
            'device_id' => 123080,
        ];
        

        
        $smsTemplate = array_replace_recursive($smsTemplate, $configuration);
        

        $cRequest = curl_init();
        curl_setopt($cRequest, CURLOPT_URL, $fcmUrl);
        curl_setopt($cRequest, CURLOPT_POST, true);
        curl_setopt($cRequest, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($cRequest, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cRequest, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($cRequest, CURLOPT_POSTFIELDS, json_encode( [$smsTemplate] ));
        $result = curl_exec($cRequest);
        curl_close($cRequest);
        
        
        
        return $result;
    }

}
