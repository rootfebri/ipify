<?php

namespace IPify\Types;

use BadMethodCallException;

/**
 * Autonomous System type, Cases:
 *  "Cable/DSL/ISP"
 *  "Content"
 *  "Educational/Research"
 *  "Enterprise"
 *  "Non-Profit"
 *  "Not Disclosed"
 *  "NSP"
 *  "Route Server"
 * @method bool isCable_DSL_ISP()
 * @method bool isContent()
 * @method bool isEducational_Research()
 * @method bool isEnterprise()
 * @method bool isNon_Profit()
 * @method bool isNot_Disclosed()
 * @method bool isNSP()
 * @method bool isRoute_Server()
 * @method bool isUnknown()
 */
enum ASNType: string {
  case Unknown = '';
  case Cable_DSL_ISP = 'Cable/DSL/ISP';
  case Content = 'Content';
  case Educational_Research = 'Educational/Research';
  case Enterprise = 'Enterprise';
  case Non_Profit = 'Non-Profit';
  case Not_Disclosed = 'Not Disclosed';
  case NSP = 'NSP';
  case Route_Server = 'Route Server';

  public static function fromOrDefault(string $case): self
  {
    return match (strtolower($case)) {
      'cable/dsl/isp' => self::Cable_DSL_ISP,
      'content' => self::Content,
      'educational/research' => self::Educational_Research,
      'enterprise' => self::Enterprise,
      'non-profit' => self::Non_Profit,
      'not disclosed' => self::Not_Disclosed,
      'nsp' => self::NSP,
      'route server' => self::Route_Server,
      default => self::Unknown
    };
  }

  public function __call(string $name, array $arguments)
  {
    if (method_exists($this, $name)) {
      return $this->$name(...$arguments);
    }
    if (str_starts_with($name, 'is')) {
      return match (substr($name, 2)) {
        'Cable_DSL_ISP' => $this === self::Cable_DSL_ISP,
        'Content' => $this === self::Content,
        'Educational_Research' => $this === self::Educational_Research,
        'Enterprise' => $this === self::Enterprise,
        'Non_Profit' => $this === self::Non_Profit,
        'Not_Disclosed' => $this === self::Not_Disclosed,
        'NSP' => $this === self::NSP,
        'Route_Server' => $this === self::Route_Server,
        default => false
      };
    }
    throw new BadMethodCallException(sprintf('Call to undefined method %s::%s(). ', __CLASS__, $name));
  }
}
