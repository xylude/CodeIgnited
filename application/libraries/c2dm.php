<?php

/* 
    Document   : c2dm
    Created on : Apr 25, 2012, 11:24:56 AM
    Author     : Jason Crider
    Description:
        
*/
class c2dm {
    /**
     * Obtain the server Auth Token for the app. Requires a Google email (gmail
     * or Google apps).
     *
     * @param type $email
     * @param type $password
     * @param type $source
     * @return array|boolean
     */
    function c2dm_register($email, $password, $source = false) {
	$curl = curl_init();

	if (is_array($source)) {
	    $sourceArr = $source;
	    $source = '';
	    if (isset($sourceArr['company'])) {
		$source = "{$sourceArr['company']}-";
	    } else {
		$source = 'eProximiti-';
	    }

	    if (isset($sourceArr['app'])) {
		$source += "{$sourceArr['app']}-";
	    } else {
		$source += "{$this->ci->site->short_name}-";
	    }

	    if (isset($sourceArr['version'])) {
		$source += $sourceArr['version'];
	    } else {
		$source += '1.0';
	    }
	}

	$data = array(
	    'Email' => $email,
	    'Passwd' => $password,
	    'accountType' => 'HOSTED_OR_GOOGLE',
	    'source' => $source,
	    'service' => 'ac2dm'
	);

	curl_setopt($curl, CURLOPT_URL, 'https://www.google.com/accounts/ClientLogin');
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //Don't verify the SSL cert.
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

	$response = curl_exec($curl);
	curl_close($curl);

	$errors = false;
	preg_match("/Error=([a-z0-9_\-]+)/i", $response, $errors);

	if (!$errors) {
	    // Get the auth string
	    $matches = array();
	    preg_match("/Auth=([a-z0-9_\-]+)/i", $response, $matches);
            $a = new Appattr();
            $a->setAttr('c2dm_token',(string)$matches[1]);
	    return true;
	}

	return $errors[1];
    }
    
    function send($token, $srvtoken, $message, $collapse_key = false) {
	/* collapse_key collapses groups of like messages to avoid spamming the device.
	 * However, we've found that it keeps the device from getting subsequent messages
	 * if it's a constant. Adding a timestamp ensures the key is unique.
	 */
	if(!$collapse_key) {
	    $collapse_key = 'message' . time();
	}

	$headers = array('Authorization: GoogleLogin auth=' . $srvtoken);
	$data = array(
	    'registration_id' => $token,
	    'collapse_key' => $collapse_key,
	    'data.message' => $message
	);

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_URL, "https://android.apis.google.com/c2dm/send");
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //Don't verify the SSL cert. The api one is invalid anyway

	$response = curl_exec($curl);
        
	curl_close($curl);

	$errors = false;
	preg_match("/Error=([a-z0-9_\-]+)/i", $response, $errors);
	if (!$errors) {
	    return array('success' => $response);
	}

	return array('error' => $errors[1]);
    }
}