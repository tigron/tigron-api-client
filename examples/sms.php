<?php
include 'credentials.php';

/**
 * Send an SMS
 *
 * Phone numbers should be in the format +00.000000000
 */

$sms_client = new Tigron\Cp\Sms();
$sms_client->send_sms('+32.478000000', '+32.479000000', 'Hello world');
