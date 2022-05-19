<?php
include 'credentials.php';

var_dump(\Tigron\Cp\Util::domain_available('example', 'com'));
var_dump(\Tigron\Cp\Util::domain_sellable('example', 'com'));
var_dump(\Tigron\Cp\Util::domain_available_and_sellable('example', 'com'));
