<?php

namespace IPify\Types;

use IPify\Interfaces\FromArray;

class ASN implements FromArray {
  public ASNType $type;

  public function __construct(
    public string $asn,
    public string $name,
    public string $domain,
    public string $route,
    string        $type,
  )
  {
    $this->type = ASNType::fromOrDefault($type);
  }

  public static function fromArray(?array $params): static
  {
    if (is_null($params)) {
      return new static('', '', '', '', '');
    }

    return new static(...$params);
  }
}
