<?php

namespace IPify;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use IPify\Interfaces\Result;
use Throwable;

class Geo {
  public const DEFAULT_OPTIONS = [
    'country' => true,
    'city' => true,
    'reverseIp' => 0,
    'escapedUnicode' => 0,
  ];

  public const BASE_URL = "https://geo.ipify.org";

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
}
