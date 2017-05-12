<?php
include dirname(__FILE__) . '/../../../autoload.php';
include 'credentials.php';

$user = \Tigron\CP\User::Get();

/**
 * $ssh_keys can contain multiple keys.
 * Each line in the text input should contain 1 key
 */
$ssh_keys = 'MY_SSH_KEYS';

if ($user->is_reseller) {
	$reseller = \Tigron\CP\Reseller::get_by_id($user->reseller_id);
	$users = \Tigron\CP\User::get_by_reseller($reseller);
} else {
	$users = [ $user ];
}


foreach ($users as $user) {
	$ssh = \Tigron\CP\Ssh::get_by_user($user);
	$ssh->ssh_keys = $ssh_keys;
	$ssh->save();
}
