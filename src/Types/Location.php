<?php

namespace IPify\Types;

use IPify\Interfaces\FromArray;

class Location implements FromArray {

  public function __construct(
    public string $country,
    public string $region,
    public string $city,
    public float  $lat,
    public float  $lng,
    public string $postalCode,
    public string $timezone,
    public int    $geonameId,
  )
  {
  }

  public static function fromArray(array $params): static
  {
    return new static(
      $params['country'] ?? '',
      $params['region'] ?? '',
      $params['city'] ?? '',
      $params['lat'] ?? 0.0,
      $params['lng'] ?? 0.0,
      $params['postalCode'] ?? '',
      $params['timezone'] ?? '',
      $params['geonameId'] ?? 0,
    );
  }
}
