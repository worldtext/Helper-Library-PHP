# Helper-Library-PHP

PHP Helper Library for World Text SMS Text Messaging

## Installation

You can install **Helper-Library-PHP** by downloading the source, cloning
the github repository or by requiring or via [composer](https://getcomposer.org).
If using composer please ensure you use version **0.2.0** or greater.

Once you have downloaded the library, put the World-Text-PHP folder into
your project hierarchy and then include the library file:

    require __DIR__ . '/worldtext/php-helper/vendor/autoload.php';


## API Documentation

World Text SMS API is documented here: [World Text HTTP REST API](http://www.world-text.com/docs/interfaces/HTTP/)

PHP Helper Library Documentation is here: [World Text PHP Helper Class](http://www.world-text.com/docs/libs/php/)


## Introduction

With the **Helper-Library-PHP** library, we've encapsulated our API into a number of objects.
You don't need to be directly concerned with the API calls, or URLs, or parsing the JSON responses.

The classes are as follows:

* WorldText - The base class, containing the API calls and response processing
* WorldTextAdmin - container for admin methods
* WorldTextSms - Derived from WorldText and the container for sms methods
* WorldTextGroup - Derived from WorldText and the container for group methods.  Pre-populated with
a group from your account on our servers and minimises calls to the API in the process.
* wtException - adds a couple of methods for additional codes to the Exception class

See the [World Text PHP Class Guide](http://www.world-text.com/docs/libs/php/)
to get the details on the classes and methods, and how to get started with
**Helper-Library-PHP**


## Quick Intro

### Send an SMS Text Message

```php
<?php
// You make need to alter the path based on your install location
require __DIR__ . '/worldtext/php-helper/vendor/autoload.php';

// Replace id and apiKey with values from http://www.world-text.com/account/
$account_id = "XXXXXX"; 
$api_key = "XXXXXX"; 

$sms = \WorldText\WorldTextSms::CreateSmsInstance($account_id, $api_key);

$dest_addr = "447980000000";  // Valid international format mobile number
try {
    $info = $sms->send($dest_addr, "Example message");
} catch (\WorldText\wtException $e) {
    echo "Caught exception: ", $e->getMessage(), "\n";
}

?>
```

## Documentation

[Full Documentation](http://www.world-text.com/docs/libs/php/ "World Text PHP Library Documentation") for **Helper-Library-PHP** is on our site, along with detailed API documentation and other libraries and languages.

## Prerequisites

* PHP >= 5.2.3

## Feedback, Ideas and Issues

We would love to hear your ideas and feedback, or if you've added to, or modified this library. Report issues using [Github
Issue Tracker](https://github.com/worldtext/Helper-Library-PHP/issues) or email
[support@world-text.com](mailto:support@world-text.com).
