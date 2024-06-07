<?php

App::uses('Component', 'Controller');

class FirebaseMessagingComponent extends Component {
    public function send($configuration) {
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $serverKey = "AAAAynwqtV8:APA91bHpzWTpmkPs4GbGhTluyGpgoAovpbDxcbB4pFbYI0TyuPvOKuk3utnQSsemq_WEvJyETg8P9bambFY27FbqGtVoDjMZES2Aq0UE1JMFQpzJVA2CsHqpsjismHadlMLyDpDFjkMb";
        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json'
        ];
        $notification = [
            'title' => 'This is notification title',
            'body' => 'This is notification text',
            //'alert' => '',
            'sound' => 'default',
        ];
        $data = [
            'title' => 'This is notification title',
            'body' => 'This is notification text',
            'priority' => 'high',
            'content_available' => true
        ];
        
        
        $fcmNotification = [
            //'to' => '/topics/alerts',
            'registration_ids' => [],
            'notification' => $notification,
            'data' => $data,
            'priority' => 10
        ];
        
        
        $fcmNotification = array_replace_recursive($fcmNotification, $configuration);
        

        $cRequest = curl_init();
        curl_setopt($cRequest, CURLOPT_URL, $fcmUrl);
        curl_setopt($cRequest, CURLOPT_POST, true);
        curl_setopt($cRequest, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($cRequest, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cRequest, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($cRequest, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($cRequest);
        curl_close($cRequest);
        
        
        
        return $result;
    }

}
