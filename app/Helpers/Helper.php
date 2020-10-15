<?php
// namespace App\Helpers;

use App\Models;

function sendSMS($to,$body)
{
    $msg = rawurlencode($body);
    $username = 'shopwerbangali';
    $secret = 'werbangali**1';
    $senderid = 'EXTCSH';
    $url = 'https://bulksms.soinit-ts.com/api/api_http.php?username='.$username.'&password='.$secret.'&senderid='.$senderid.'&to='.$to.'&text='.$msg.'&route=Informative&type=text';
    return $json = json_decode(file_get_contents($url), true);
}