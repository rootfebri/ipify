# README.md

## IPify Integration Library

A PHP library for interacting with the IPify API to retrieve IP geolocation information based on IP addresses, domain
names, or email addresses. The library is designed to be flexible, allowing detailed control over the options and
providing robust error handling.

---

## Features

- Look up geolocation information using:
  - IP addresses (IPv4 & IPv6 supported)
  - Domain names
  - Email addresses
- Retrieve detailed information including country, city, ISP, ASN, and more.
- Configurable API options, such as escaping Unicode or enabling reverse IP search.
- Built-in error handling using `Result` interfaces.
- Fully object-oriented and compatible with modern PHP (8.0+).
- Simple testing using Pest.

---

## Requirements

- PHP 8.2 or later
- [Guzzle HTTP](https://github.com/guzzle/guzzle) (used for making API requests)
- IPify API Key (available from [IPify](https://geo.ipify.org/))

---

## Installation

Install the library and its dependencies using Composer:

```shell script
composer require guzzlehttp/guzzle
```

Next, integrate this library into your project.

---

## Configuration

The `Geo` class in this library requires an API key for authentication with the IPify API. Set this key as an
environment variable or pass it directly to the constructor.

To set it as an environment variable, add the following to your `.env` file:

```
IPIFY_APIKEY=your_api_key_here
```

---

## Usage

### 1. Get IP Address Information

To perform a lookup of an IP address:

```php
<?php

require 'vendor/autoload.php';

use IPify\Geo;

$ipify = new Geo('your_api_key_here');
$response = $ipify->ipAddress('8.8.8.8');

if ($response->is_ok()) {
    $data = $response->unwrap();
    echo 'IP Address: ' . $data->ip . PHP_EOL;
    echo 'Country: ' . $data->location->country . PHP_EOL;
    echo 'City: ' . $data->location->city . PHP_EOL;
    echo 'ISP: ' . $data->isp . PHP_EOL;
} else {
    $error = $response->unwrap_err();
    echo 'Error: ' . $error->getMessage();
}
```

### 2. Get Domain Name Information

Retrieve information about a specific domain:

```php
<?php

use IPify\Geo;

$ipify = new Geo('your_api_key_here');
$response = $ipify->domain('example.com');

if ($response->is_ok()) {
    $data = $response->unwrap();
    echo 'IP: ' . $data->ip . PHP_EOL;
    echo 'ASN Name: ' . $data->as->name . PHP_EOL;
} else {
    $error = $response->unwrap_err();
    echo 'Error: ' . $error->getMessage();
}
```

### 3. Get Email Information

Retrieve geolocation details associated with an email address:

```php
<?php

use IPify\Geo;

$ipify = new Geo('your_api_key_here');
$response = $ipify->email('test@example.com');

if ($response->is_ok()) {
    $data = $response->unwrap();
    echo 'Country: ' . $data->location->country . PHP_EOL;
    echo 'Timezone: ' . $data->location->timezone . PHP_EOL;
} else {
    $error = $response->unwrap_err();
    echo 'Error: ' . $error->getMessage();
}
```

### 4. Custom Options for Requests

Options can be provided to customize the lookup behavior. Here's an example:

```php
$options = [
    'country' => true,
    'city' => false,
    'reverseIp' => 1,
    'escapedUnicode' => 1,
];

$response = $ipify->ipAddress('8.8.8.8', $options);
```

---

## Error Handling

The library uses a `Result` interface with two states for handling responses:

1. **Success (`is_ok`)**: Use the `unwrap` method to access the response data.
2. **Error (`is_err`)**: Use the `unwrap_err` method to get details about the error.

Example of error handling:

```php
$response = $ipify->ipAddress('8.8.8.8');
if ($response->is_err()) {
    $error = $response->unwrap_err();
    echo 'Error Code: ' . $error->getCode() . PHP_EOL;
    echo 'Error Message: ' . $error->getMessage();
}
```

---

## Testing

This library includes unit tests written using Pest.

### Run Tests

To execute the tests, run the following command:

```shell script
vendor/bin/pest
```

## Example Test Breakdown

Here is a sample `GeoTest.php` structure:

```php
use IPify\Geo;

// Test creating an instance of the Geo class
test('instance', function () {
    $geo = new Geo(getenv('IPIFY_APIKEY'));
    expect($geo)->toBeInstanceOf(IPify\Geo::class);
});

// Test lookup for an IPv4 address
test('ipv4 address lookup', function () {
    $geo = new Geo(getenv('IPIFY_APIKEY'));
    $response = $geo->ipAddress('8.8.8.8');

    expect($response->is_ok())->toBeTrue()
        ->and($response->unwrap()->ip)->toBe('8.8.8.8');
});

// Test lookup for a domain
test('domain lookup', function () {
    $geo = new Geo(getenv('IPIFY_APIKEY'));
    $response = $geo->domain('example.com');

    expect($response->is_ok())->toBeTrue();
});
```

---

## Further Enhancements

- Extend the library to support additional endpoints.
- Add caching mechanisms for repeated lookups.
- Allow custom Guzzle HTTP client configuration to support proxy servers.

---

## License

This project is licensed under the [MIT License](LICENSE).

---

## Contribution

Contributions are welcome! Feel free to submit a PR or raise issues for bugs or feature requests.

---

Let me know if you'd like more explanations or customizations!
