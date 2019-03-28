<?php
include dirname(__FILE__) . '/../../../autoload.php';
include 'credentials.php';

var_dump(\Tigron\CP\Util::domain_available('example', 'com'));
var_dump(\Tigron\CP\Util::domain_sellable('example', 'com'));
var_dump(\Tigron\CP\Util::domain_available_and_sellable('example', 'com'));
