<?php

namespace IPify\Types;

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
}
