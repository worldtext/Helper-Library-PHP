<?php
namespace WorldText;

// Simple demo of World Text Helper classes...
//
// NB...
// You will need to replace
// yourID with your World Text account ID
// yourSecretAPIKey with your secret API key
// both can be viewed within your World Text account at
//      http://www.world-text.com/account/

// Mobile numbers will need replacing with valid numbers

require_once("WorldText.php");
require_once("wtException.php");

$sms = WorldTextSms::CreateSmsContext(yourID, 'yourSecretAPIKey');
$admin = WorldTextAdmin::CreateAdminContext(yourID, 'yourSecretAPIKey');

// To send *actual* SMS texts to group, simply remove the call to setSimulated here.

print "Set simulated sends.<br>";
$sms->setSimulated(true);


print "admin/ping:<br />";
try {
	$info = $admin->ping();
	print "alive<br />";
} catch (wtException $e) {
	echo 'Caught exception: ', $e->getMessage(), "<br />";
}

print "admin/credits:<br />";
try {
	$info = $admin->credits();
	print "Credits on account: " . $info['data']['credits'] . "<br />";
} catch (wtException $e) {
	echo 'Caught exception: ', $e->getMessage(), "<br />";
}

print "sms/cost: <br />";
try {
	$info = $sms->cost("447989000000");
	print "Credits to send: " . $info['data']['credits'] . "<br />";
} catch (wtException $e) {
	echo 'Caught exception: ', $e->getMessage(), "<br />";
}

print "sms/send: <br />";
try {
	$info = $sms->send("447989000000", "Example message");
	foreach ($info[0] as $k => $v) {
		print "MSG ID : " . $k . " Value : " . $v . "<br />";
	}
} catch (wtException $e) {
	echo 'Caught exception: ', $e->getMessage(), "<br />";
}

print "sms/query: <br />";
try {
	$info = $sms->query('validMsgID');
	print var_dump($info);
} catch (wtException $e) {
	echo 'Caught exception: ', $e->getMessage(), "<br />";
}

print "sms/send...<br>";
try {
	$info = $sms->send("447989000000", "Example message");
	print "dst a: " . $info[0]['dstaddr'] . "<br />";
} catch (wtException $ex) {
	echo "<pre>";
	echo $ex->getCode() . "<br />";
	echo $ex->getMessage() . "<br />";
	echo $ex->getError() . "<br />";
	echo $ex->getDesc() . "<br />";
	echo "</pre>";
}

print "sms/send - multipart...<br>";
try {
	$info = $sms->send("447989000000", " 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789", 2);
	print "First .." . $info[0]['dstaddr'] . "<br />";
	print "First .." . $info[0]['msgid'] . "<br />";
	print "Second .." . $info[1]['dstaddr'] . "<br />";
	print "Second .." . $info[1]['msgid'] . "<br />";
} catch (wtException $ex) {
	echo "<pre>";
	echo $ex->getCode() . "<br />";
	echo $ex->getMessage() . "<br />";
	echo $ex->getError() . "<br />";
	echo $ex->getDesc() . "<br />";
	echo "</pre>";
}
?>