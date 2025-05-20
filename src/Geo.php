<?php

namespace IPify;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Uri;
use IPify\Interfaces\Result;
use JsonException;
use Throwable;

class Geo {
  public const DEFAULT_OPTIONS = [
    'country' => true,
    'city' => true,
    'reverseIp' => 0,
    'escapedUnicode' => 0,
  ];

  public const BASE_URL = "https://geo.ipify.org";
  private const FINDIPNET_URL = "https://api.findip.net/{ip}/?token=4c09b8ece6424f168ed1c9c6311105ed";
  /**
   * @var Client Guzzle HTTP client instance.
   */
  private Client $client;

  /**
   * @param string $apiKey The API key to use for authentication.
   */
  public function __construct(private readonly string $apiKey)
  {
    $this->client = new Client([
      'headers' => [
        'Accept' => 'application/json',
        'base_uri' => self::BASE_URL,
      ],
    ]);
  }

  /**
   * Get the IP address information by email.
   *
   * @param string $email The email address to look up.
   * @param array $options Optional parameters for the request, default: `\IPify\Geo::DEFAULT_OPTIONS`
   *
   * @return Result<IPifyResponse, IPifyError>
   */
  public function email(string $email, array $options = self::DEFAULT_OPTIONS): Result
  {
    $endpoint = $this->endpoint([...$options, 'email' => $email]);

    try {
      $response = $this->client->get($endpoint);
      $body = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
      return IPifyResponse::fromArray($body);
    } catch (Throwable $e) {
      return new IPifyError(
        code: $e->getCode(),
        message: $e->getMessage(),
      );
    }
  }

  private function endpoint(array $options): string
  {
    $country = ($options['country'] ?? false) ? 'country' : '';
    $city = ($options['city'] ?? false) ? 'city' : '';
    $path = "/api/v2/$country,$city";

    unset($options['country'], $options['city']);

    $params = [
      'apiKey' => $this->apiKey,
      ...$options,
    ];
    $uri = (new Uri(self::BASE_URL))->withPath(rtrim($path, ','))->withQuery(http_build_query($params));
    return ltrim($uri, '/');
  }

  /**
   * Get the IP address information by domain.
   *
   * @param string $domain The domain name to look up.
   * @param array $options Optional parameters for the request, default: `\IPify\Geo::DEFAULT_OPTIONS`
   *
   * @return Result<IPifyResponse, IPifyError>
   * @noinspection PhpUnused
   */
  public function domain(string $domain, array $options = self::DEFAULT_OPTIONS): Result
  {
    $endpoint = $this->endpoint([...$options, 'domain' => $domain]);
    try {
      $response = $this->client->get($endpoint);
      $body = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
      return IPifyResponse::fromArray($body);
    } catch (Throwable $e) {
      return new IPifyError(
        code: $e->getCode(),
        message: $e->getMessage(),
      );
    }
  }

  /**
   * Refine the IP address information by ASN Type.
   *
   * @param string $ip The ASN to look up.
   * @param array $options Optional parameters for the request, default: `\IPify\Geo::DEFAULT_OPTIONS`
   * @return Result<IPifyResponse, IPifyError>
   */
  public function with_findip_net(string $ip, array $options = self::DEFAULT_OPTIONS): Result
  {
    $baseLookup = $this->ipAddress($ip, $options);
    if ($baseLookup->is_err()) {
      return $baseLookup;
    }

    $baseLookup = $baseLookup->unwrap();
    if ($baseLookup->as->type->isUnknown()) {
      try {
        $response = $this->client->get($this->findip_net_url($ip));
        $body = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        if (isset($body['traits']['user_type'])) {
          match ($body['traits']['user_type']) {
            'residential', 'cellular' => $baseLookup->as->type = types\ASNType::NSP,
            'business' => $baseLookup->as->type = types\ASNType::Cable_DSL_ISP,
            'hosting' => $baseLookup->as->type = types\ASNType::Content,
            default => null
          };
        }
      } catch (GuzzleException|JsonException) {
        return $baseLookup;
      }
    }
    return $baseLookup;
  }

  /**
   * Get the IP address information.
   *
   * Available options = country: bool, city: bool, reversIp: 0|1,
   * @param string $ip The IP address to look up.
   * @param array $options Optional parameters for the request, default: `\IPify\Geo::DEFAULT_OPTIONS`
   *
   * @return Result<IPifyResponse, IPifyError>
   */
  public function ipAddress(string $ip, array $options = self::DEFAULT_OPTIONS): Result
  {
    $endpoint = $this->endpoint([...$options, 'ipAddress' => $ip]);
    try {
      $response = $this->client->get($endpoint);
      $body = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
      return IPifyResponse::fromArray($body);
    } catch (Throwable $e) {
      return new IPifyError(
        code: $e->getCode(),
        message: $e->getMessage(),
      );
    }
  }

  private function findip_net_url(string $ip): string
  {
    return str_replace('{ip}', $ip, self::FINDIPNET_URL);
  }
}
