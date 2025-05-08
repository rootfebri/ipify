<?php

use IPify\Geo;

test('instance', function () {
  $geo = new Geo(getenv('IPIFY_APIKEY'));
  expect($geo)->toBeInstanceOf(IPify\Geo::class);
});

test('ip v4 address', function () {
  $geo = new Geo($_ENV['IPIFY_APIKEY']);
  $response = $geo->ipAddress('8.8.8.8');

  expect($response)
    ->and($response->is_ok())->toBeTrue()
    ->and($response->unwrap())->toBeInstanceOf(IPify\IPifyResponse::class)
    ->and($response->unwrap()->as->type)->toBe(IPify\Types\ASNType::Content);
});

test('ip v6 address', function () {
  $geo = new Geo($_ENV['IPIFY_APIKEY']);
  $response = $geo->ipAddress('2001:0db8:85a3:0000:0000:8a2e:0370:7334');

  expect($response)
    ->and($response->is_ok())->toBeTrue()
    ->and($response->unwrap())->toBeInstanceOf(IPify\IPifyResponse::class)
    ->and($response->unwrap()->ip)->toBe('2001:0db8:85a3:0000:0000:8a2e:0370:7334')
    ->and($response->unwrap()->as->type)->toBe(IPify\Types\ASNType::Unknown);
});
