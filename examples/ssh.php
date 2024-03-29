<?php
include 'credentials.php';

$user = \Tigron\Cp\User::Get();

/**
 * $ssh_keys can contain multiple keys.
 * Each line in the text input should contain 1 key
 */
$ssh_keys = 'MY_SSH_KEYS';

if ($user->is_reseller) {
	$reseller = \Tigron\Cp\Reseller::get_by_id($user->reseller_id);
	$users = \Tigron\Cp\User::get_by_reseller($reseller);
} else {
	$users = [ $user ];
}


foreach ($users as $user) {
	$ssh = \Tigron\Cp\Ssh::get_by_user($user);
	$ssh->ssh_keys = $ssh_keys;
	$ssh->save();
}
