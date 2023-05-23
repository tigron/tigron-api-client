<?php
include 'credentials.php';


/**
 * Fetch all the domain names from Tigron Control panel and output
 * all information
 */
$domain = \Tigron\Cp\Domain::get_by_name_tld('mydomain', 'com');
$contact = Tigron\Cp\Contact::get_by_id($domain->contact_id);

print_r($domain);
print_r($contact);
