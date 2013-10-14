<?php

// Simple demo of World Text Helper classes...
//
// NB...
// You will need to replace
// yourID with your World Text account ID
// yourSecretAPIKey with your secret API key
// both can be viewed within your World Text account at
//      http://www.world-text.com/account/
// Mobile numbers will need replacing with valid numbers to send live SMS

require_once("WorldText.php");
require_once("wtException.php");

function my_var_dump($data) {
	echo "<pre>";
	var_dump($data);
	echo "</pre>";
}

print "Create group object...<br>";
try {
	// Create New Group...
	$group = WorldTextGroup::CreateNewGroupInstance(yourID, 'yourSecretAPIKey', "UNITest", "SMSAlert", "0000");
} catch (Exception $e) {
	// Group exists, open existing...
	if ($e->getError() == 1300) {
		try {
			$group = WorldTextGroup::CreateExistingGroupInstance(yourID, 'yourSecretAPIKey', "UNITest");

			print "Existing group opened... <br />";
			print my_var_dump($group->details());
		} catch (wtException $e) {
			// Can't create existing either...
			echo "The world has ended, can't create or open existing: <pre>", $e->getMessage(), "<br />";
			echo $e->getCode() . "<br />";
			echo $e->getError() . "<br />";
			echo $e->getDesc() . "<br />";

			exit;
		}
	} else {
		echo 'Caught exception: ', $e->getMessage(), "<br />";
		exit;
	}
}

// To send *actual* SMS texts to group, simply remove the call to setSimulated here.

print "<br />Set simulated so we're not actually sending texts... <br />";
$group->setSimulated(TRUE);

print "<br />group/find (fails, not found)...<br />";
try {
	$info = $group->find("UNI-Typo");
	if ($info == NULL) {
		print "Not found <br />";
	} else {
		// Shouldn't happen unless the group pre-existed...
		print "Group ID: " . $info . "<br />";
	}
} catch (Exception $e) {
	echo 'Caught exception: ', $e->getMessage(), "<br />";
}


print "<br />group/create (should throw)...<br />";
try {
	// Attempts to create a group with a source address not allocated to account...
	$info = $group->create("UNI-Test", "Gibberish", "1111");
} catch (Exception $e) {
	echo 'Caught exception: ', $e->getMessage(), "<br />";
}


print "<br />group/entry add entries to group individually...<br />";
try {
	// Re-adding is not an error to the API...
	// Numbers must be unique, Names for existing numbers will be ignored...
	$info = $group->entry("Boris", "447980000000");
	$info = $group->entry("D", "447598000000");
	$info = $group->entry("Dave", "447598000000");
} catch (Exception $e) {
	echo 'Caught exception: ', $e->getMessage(), "<br />";
}

print "<br />group/details...<br>";
try {
	$info = $group->details();
	// Output each group entry...
	print "Group Contents: <br />";
	foreach ($info as $key => $value) {
		print "name : " . $value . " # : " . $value . "<br />";
	}
} catch (Exception $e) {
	echo 'Caught exception: ', $e->getMessage(), "<br />";
}

print "<br />group/send default sender ID...<br>";
try {
	$info = $group->send("hello");
} catch (wtException $ex) {
	echo "<pre>";
	echo $ex->getCode() . "\n";
	echo $ex->getMessage() . "\n";
	echo $ex->getError() . "\n";
	echo $ex->getDesc() . "\n";
}


print "<br />group/send Override sender ID...<br>";
try {
	// Must be allocated to the account in use, or the default sender ID will be used...
	$info = $group->send("hello", "World-Text");
} catch (wtException $ex) {
	echo "<pre>";
	echo $ex->getCode() . "\n";
	echo $ex->getMessage() . "\n";
	echo $ex->getError() . "\n";
	echo $ex->getDesc() . "\n";
}

print "<br />group/send Multipart, but default sender...<br>";
try {
	// Must be allocated to the account in use, or the default sender ID will be used...
	$info = $group->send(" 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789", NULL, 2);
} catch (wtException $ex) {
	echo "<pre>";
	echo $ex->getCode() . "\n";
	echo $ex->getMessage() . "\n";
	echo $ex->getError() . "\n";
	echo $ex->getDesc() . "\n";
}

print "<br />group/send UTF8: <br />";
try {
	$info = $group->send("Hey 汉语/漢語  华语/華語 中文");

	foreach ($info['data']['message'] as $k => $v) {
		print "MSG ID : " . $v['msgid'] . "<br />";
	}
} catch (wtException $ex) {
	echo "<pre>";
	echo $ex->getCode() . "\n";
	echo $ex->getMessage() . "\n";
	echo $ex->getError() . "\n";
	echo $ex->getDesc() . "\n";
}
?>
