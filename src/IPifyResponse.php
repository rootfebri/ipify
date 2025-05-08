<?php

namespace IPify;

use IPify\Interfaces\FromArray;
use IPify\Interfaces\Result;

class IPifyResponse implements FromArray, Result {
  public function __construct(
    public string         $ip,
    public Types\Location $location,
    public Types\Domains  $domains,
    public Types\ASN      $as,
    public string         $isp,
  )
  {
  }

  public static function fromArray(array $params): static
  {
    return new static(
      ip: $params['ip'],
      location: Types\Location::fromArray($params['location']),
      domains: Types\Domains::fromArray($params['domains'] ?? []),
      as: Types\ASN::fromArray($params['as'] ?? null),
      isp: $params['isp'] ?? '',
    );
  }

  public function is_ok(): bool
  {
    return true;
  }

  public function is_err(): bool
  {
    return false;
  }

  public function unwrap()
  {
    return $this;
  }

  public function unwrap_err()
  {
    return null;
  }
}
