<?php

declare(strict_types=1);

/**
 * URL
 * PHP Version 8.1.4
 * 
 * Github Repository
 * @see       https://github.com/javercel/path
 * 
 * @version   1.0.0
 * 
 * @author    Shahzada Modassir <codingmodassir@gmail.com>
 * @author    Shahzadi Afsara <shahzadiafsara@gmail.com>
 * 
 * @copyright 2020 - 2024 Shahzada Modassir
 * @copyright 2024 - 2025 Shahzadi Afsara
 * @copyright 2024 - All rights reserved!
 * 
 * @license   MIT License
 * @see       https://github.com/javercel/url/blob/main/LICENSE
 */
namespace Url;

class Url
{

  /**
   * 
   * @var string
   */
  private const RURL = '/^(?:(([\w-]+:(?=\/\/)*)(\/\/)?|)(([\w-]+):([\w-]+)@)*(www\.)*(([^\/:?#]+)*(:(\d+))*)(([^?#]+)*(\?([^#]+))*)(#(.*))*)$/';

  /**
   * The default version of URL v1.0.0
   * @var string VERSION
   */
  public const VERSION = '1.0.0';

  private static $url_object;

  public $auth, $pathname, $search, $query, $hash, $slashes, $www, $protocol, $username, $password, $host, $hostname, $href, $port, $path;

  /**
   * 
   * 
   * @param string $url
   * @param bool   $parse_query
   * @param bool   $slash_denote_host
   * 
   * @return void
   * @throws
   */
  public function __construct(string $url)
  {
    @\preg_match(self::RURL, $url, $matches);
    self::$url_object             = [];
    $this->auth     = @\substr($matches[4], 0, -1);
    $this->pathname = $matches[13] ?? '';
    $this->search   = $matches[14] ?? '';
    $this->query    = $matches[15] ?? '';
    $this->hash     = $matches[17] ?? '';
    $this->slashes  = !!$matches[3];
    $this->www      = !!$matches[7];
    $this->protocol = $matches[2];
    $this->username = $matches[5];
    $this->password = $matches[6];
    $this->host     = $matches[8];
    $this->hostname = $matches[9];
    $this->href     = $matches[0];
    $this->port     = $matches[11];
    $this->path     = $matches[12];
  }

  /**
   * The URL method resolves a target URL relative to a base URL in a manner similar to that of a web browser
   * resolving an anchor tag.
   * 
   * @param string $from
   * @param string $to
   * 
   * @return string
   * @throws
   */
  public static function resolve(string $from, string $to) : string
  {
    // This feature are not available
  }

  /**
   * 
   * 
   * @param array $url
   * @param 
   * 
   * @return string
   * @throws
   */
  public static function format(array $url, $format_option) : string
  {
    // This feature are not available
  }

  /**
   * 
   * 
   * @param string $url
   * @param bool   $parse_query
   * @param bool   $slash_host
   * 
   * @return array
   * @throws
   */
  public static function parse(string $url, bool $parse_query=true, bool $slash_denote_host=false) : array
  {
    
  }
}
?>