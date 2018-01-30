# World-Text-PHP

PHP Helper Library for World Text SMS Text Messaging

## Installation

You can install **World-Text-PHP** by downloading the source.

Once you have downloaded the library, put the World-Text-PHP folder into
your project hierarchy and then include the library file:

    require_once('/path/World-Text-PHP/WorldText.php');

[Click here to download a ZIP of the source] (https://github.com/m-r-h/World-Text-PHP/zipball/master) which includes all
dependencies.



## API Documentation

World Text SMS API is documented here: [World Text HTTP REST API](http://www.world-text.com/docs/interfaces/HTTP/)

PHP Helper Library Documentation is here:  [World Text PHP Helper Class](http://www.world-text.com/docs/libs/php/)


## Introduction

With the **World-Text-PHP** library, we've encapsulated our API into a number of objects.
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
**World-Text-PHP**


## Quick Intro

### Send an SMS Text Message

```php
<?php
require_once('/path/World-Text-PHP/WorldText.php');

// Replace id and apiKey with values from http://www.world-text.com/account/
// ...and the mobile number with a valid one in international format

$id = "XXXXXX";        // Your Account ID
$apiKey = "XXXXXX";    // Your secret API Key

$sms = WorldText\WorldTextSms::CreateSmsInstance($id, $apiKey);

try {
    $info = $sms->send("447989000000", "Example message");
} catch (WorldText\wtException $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}

?>
```

## Documentation

[Full Documentation](http://www.world-text.com/docs/libs/php/ "World Text PHP Library Documentation") for **World-Text-PHP** is on our site, along with detailed API documentation and other libraries and languages.

## Prerequisites

* PHP >= 5.2.3

## Feedback, Ideas and Issues

We would love to hear your ideas and feedback, or if you've added to, or modified this library. Report issues using [Github
Issue Tracker](https://github.com/m-r-h/world-text-php/issues) or email
[support@world-text.com](mailto:support@world-text.com).
