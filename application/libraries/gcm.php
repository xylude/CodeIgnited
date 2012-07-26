<?php

/*
  Document   : gcm
  Created on : Jul 25, 2012, 1:16:29 PM
  Author     : Jason Crider
  Description:

 */

class Gcm {

    public function send($token, $srvtoken, $message) {
        $headers = array("Content-Type: application/json", "Authorization: key=" . $srvtoken);
        $data = array(
            'data' => array("message" => $message),
            'registration_ids' => array($token)
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, "https://android.googleapis.com/gcm/send");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        error_log(json_encode($data));
        $response = curl_exec($ch);
        curl_close($ch);

        //still need to add response handling
        $res = json_decode($response);
        if (isset($res->successs) > 0) {
            return true;
        }
        return false;
    }
}