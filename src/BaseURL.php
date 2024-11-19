<?php

declare(strict_types=1);

/**
 * URL resolution and parsing meant to have feature parity with PHP core.
 * PHP Version 7.0.0
 * 
 * Github (url) Repository
 * @see       https://github.com/javercel/url
 * 
 * @author    Shahzada Modassir <codingmodassir@gmail.com>
 * @author    Shahzadi Afsara <shahzadiafsara@gmail.com>
 * 
 * @copyright 2020 - 2024 Shahzada Modassir
 * @copyright 2024 - 2025 Shahzadi Afsara
 * @copyright 2024 - All rights reserved!
 * 
 * @license   MIT License
 * @see       https://github.com/lazervel/url/blob/main/LICENSE
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Web\Url;

use Web\Url\Parser\Parser;

/**
 * @internal
 */
abstract class BaseURL
{
  /**
   * PHP Regular Expression
   * 
   * ? (Capture: <href>)     (e.g., 'https://example.com:8080/path?id=123#section1')
   * ? (Capture: <origin>)   (e.g., 'https://example.com')
   * ? (Capture: <protocol>) (e.g., 'http:', 'https:', 'ftp:')
   * ? (Capture: <slashes>)  (e.g., '//' will be preserved)
   * ? (Capture: <auth>)     (e.g., 'user:password' in 'user:password@domain.com')
   * ? (Capture: <username>) (e.g., 'user' in 'user@domain.com')
   * ? (Capture: <password>) (e.g., 'password' in 'user:password@domain.com')
   * ? (Capture: <host>)     (e.g., 'example.com' or 'example.com:8080')
   * ? (Capture: <www>)      (e.g., 'www' for 'www.example.com')
   * ? (Capture: <hostname>) (e.g., 'example.com')
   * ? (Capture: <port>)     (e.g., '8080' in 'http://example.com:8080')
   * ? (Capture: <uri>)      (e.g., '/path?id=123')
   * ? (Capture: <pathname>) (e.g., '/path/to/resource')
   * ? (Capture: <search>)   (e.g., '?id=123&name=test')
   * ? (Capture: <query>)    (e.g., 'id=123&name=test')
   * ? (Capture: <hash>)     (e.g., '#section1' in 'https://example.com/#section1')
   * 
   * @var string
   */
  private const URL = '/^(?P<href>(?:(?P<origin>((?P<protocol>[\w-]+:(?=\/\/)*)(?P<slashes>\/\/)?|)((?P<auth>(?P<username>[\w-]+)(?:\:(?P<password>[\w-]+))?)@)*(?<host>(?P<www>www\.)*((?P<hostname>[^\/:?#]+)*(:(?P<port>\d+))*)))(?P<uri>(?P<pathname>[^?#]+)*(?P<search>\?(?P<query>[^#]+))*)(?P<hash>#[\w-]*(\?[=\&\w-]+)?)*))$/';

   /**
   * The hash fragment (anchor) of a URL, used for identifying a section within the resource
   * (e.g., '#section1').
   * 
   * @var string
   */
  public $hash;

  /**
   * The password component of a URL
   * (e.g., 'password' in 'user:password@domain.com').
   * 
   * @var string
   */
  public $password;

  /**
   * The username component of a URL
   * (e.g., 'user' in 'user@domain.com').
   * 
   * @var string
   */
  public $username;

  /**
   * The search/query string portion of the URL, typically after the '?'
   * (e.g., '?id=123&name=test').
   * 
   * @var string
   */
  public $search;

  /**
   * The query string of a URL, usually in the form of key-value pairs after the '?'
   * (e.g., 'id=123').
   * 
   * @var string
   */
  public $query;

  /**
   * The origin of the URL, which includes the protocol and hostname
   * (e.g., 'https://example.com').
   * 
   * @var string
   */
  public $origin;

  /**
   * Indicates whether slashes should be included in the URL
   * (e.g., 'true' means slashes will be preserved).
   * 
   * @var bool
   */
  public $slashes;

  /**
   * A PHP implementation URLSearchParams for handling query parameters easily.
   * 
   * @var \Web\URLSearchParams\URLSearchParams
   */
  public $searchParams;

  /**
   * The username, password both or single component of a URL
   * (e.g., 'user:password' or 'user' in 'user:password@domain.com').
   * 
   * @var string
   */
  public $auth;

  /**
   * The protocol part of the URL
   * (e.g., 'http:', 'https:', 'ftp:').
   * 
   * @var string
   */
  public $protocol;

  /**
   * Indicates if the URL includes 'www' prefix
   * (e.g., 'true' for 'www.example.com').
   * 
   * @var bool
   */
  public $www;

  /**
   * The host portion of the URL, which includes both hostname and optional port
   * (e.g., 'example.com' or 'example.com:8080').
   * 
   * @var string
   */
  public $host;

  /**
   * The full URL, including protocol, hostname, port, path, search, and hash
   * (e.g., 'https://example.com:8080/path?id=123#section1').
   * 
   * @var string
   */
  public $href;

  /**
   * The pathname part of the URL, which comes after the domain and port
   * (e.g., '/path/to/resource').
   * 
   * @var string
   */
  public $pathname;

  /**
   * The port number used in the URL, if specified
   * (e.g., '8080' in 'http://example.com:8080').
   * 
   * @var int
   */
  public $port;

  /**
   * The path part of the URL, which comes after the domain and port with
   * search query key-value pairs after the '?'
   * (e.g., '/path/to/resource?name=test&id=1234').
   * 
   * @var string
   */

  /**
   * The URI of the URL, which is typically the path and query string
   * (e.g., '/path?id=123').
   * 
   * @var string
   */
  public $uri;

  /**
   * The hostname of the URL, which is the domain name without the protocol or port
   * (e.g., 'example.com').
   * 
   * @var string
   */
  public $hostname;

  /**
   * Creates a new URL constructor.
   * Initializes a new instance of BaseURL => Url with the given $input and $base.
   * 
   * @param string $input             [required]
   * @param string|\Web\Url\Url $base [optional]
   * 
   * @throws \Web\Url\Exception\InvalidUrlException
   * 
   * @return void
   */
  public function __construct(string $input, $base)
  {
    Parser::with($input, $base, false, false, $this, self::URL)->parse();
  }

  /**
   * The Url::parse() method takes a URL string, parses it, and returns a URL object.
   * 
   * @param string $url              [required]
   * @param bool   $parseQuery       [optional]
   * @param bool   slashesDenoteHost [optional]
   * 
   * @return \Web\Url\ParserInterface Returns a URL object
   */
  public static function parse(string $url, bool $parseQuery = false, bool $slashesDenoteHost = false)
  {
    return Parser::with($url, null, $parseQuery, $slashesDenoteHost, null, self::URL)->parse();
  }
}
?>