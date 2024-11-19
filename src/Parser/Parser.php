<?php

declare(strict_types=1);

namespace Web\Url\Parser;

use Web\Url\Exception\InvalidUrlException;
use Web\URLSearchParams\URLSearchParams;
use Web\Url\Exception\URIError;
use Web\Url\UrlInterface;

final class Parser implements ParserInterface
{
  /**
   * The hash fragment (anchor) of a URL, used for identifying a section within the resource
   * (e.g., '#section1').
   * 
   * @var string
   */
  public $hash;

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
   * Indicates whether slashes should be included in the URL
   * (e.g., 'true' means slashes will be preserved).
   * 
   * @var bool
   */
  public $slashes;

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
   * 
   * @var bool
   */
  private $slashesDenoteHost;

  /**
   * 
   * @var \Web\Url\UrlInterface
   */
  private $url;

  /**
   * 
   * @var string
   */
  private $input;

  /**
   * 
   * @var string|\Web\Url\Url
   */
  private $base;

  /**
   * 
   * @var bool
   */
  private $parseQuery;

  private $regex;

  /**
   * Creates a new Parser constructor.
   * Initializes a new instance of Parser => Url with the given @paramters.
   * 
   * @param string                     [required]
   * @param string|\Web\Url\Url|null   [optional]
   * @param bool                       [optional]
   * @param bool                       [optional]
   * @param \Web\Url\UrlInterface|null [optional]
   * 
   * @return void
   */
  public function __construct(string $input, $base, bool $parseQuery, bool $slashesDenoteHost, UrlInterface $url = null, string $regex)
  {
    $this->slashesDenoteHost = $slashesDenoteHost;
    $this->input             = $input;
    $this->base              = $base;
    $this->url               = $url;
    $this->regex             = $regex;
    $this->parseQuery        = $parseQuery;
  }

  private function throwInvalidUrlException(string $error) : void
  {
    throw new InvalidUrlException(\sprintf('Invalid URL input: \'%s\'', $error));
  }

  /**
   * 
   * @param array<string,string> $component [required]
   * @return string formated url string
   */
  public function format_url(array $component) : string
  {
    // Retrive url scheme or protocol (e.g., http, https, ws, tcp, wss) etc.
    $protocol = $component['scheme'] ?? null;

    $queryFragment = [];
    $auth = [];
    $url  = '';

    // (e.g., http://, tcp://, https://) etc.
    $protocol && ($url .= \sprintf('%s://', $protocol));

    // Retrive url auth username, password and format (e.g., user:password, user, pass)
    $auth[] = isset($component['user']) ? $component['user'] : null;
    $auth[] = isset($component['pass']) ? \sprintf(':%s', $component['pass']) : null;
    $auth   = \join('', \array_diff($auth, [null]));

    // Retrive url host, port and format (e.g., www.example.com, wwww.example.com:5500)
    $host = $component['host'] ?? null;
    $port = $component['port'] ?? null;
    $host = \sprintf('%s/', \join(':', \array_diff([$host, $port], [null])));

    $url .= \join('@', \array_diff([$auth, $host], [null]));

    // Retrive url path suffix with '/' (e.g., /home/signup/index.html, /home/signup)
    $path = \ltrim($component['path'] ?? '', '/');

    // Retrive url query, hash and format (e.g., ?id=1234&user=test, ?id=123&user=test#hash, #hash)
    $queryFragment[] = $component['query'] ?? null;
    $queryFragment[] = isset($component['fragment']) ? \sprintf('#%s', $component['fragment']) : null;
    $queryFragment   = \join('', \array_diff($queryFragment, [null]));

    $url .= \join('?', \array_diff([$path, $queryFragment], [null]));

    return $url;
  }

  /**
   * 
   * @throws \Web\Url\Exception\InvalidUrlException
   * 
   * @return void
   */
  public function validateURL() : void
  {
    $baseComponent = \parse_url($this->base ?? '');
    $component = \parse_url($this->input);

    if (!$component) {
      $this->throwInvalidUrlException($this->input);
    }

    if (\is_string($this->base) && !isset($baseComponent['host'])) {
      $this->throwInvalidUrlException($this->base);
    }

    if ($this->base === null && !isset($component['host'])) {
      $this->throwInvalidUrlException($this->input);
    }

    if (isset($component['query']) && $component['query'] != null) {
      unset($baseComponent['query'], $baseComponent['fragment']);
    }

    if (isset($component['fragment']) && $component['fragment'] != null) {
      unset($baseComponent['fragment']);
    }

    if (($hostExist = isset($component['host']) && $component['host'] != null)) {
      unset($baseComponent['host'], $baseComponent['port']);
    }

    if ((isset($component['path']) && $component['path'] != null) || $hostExist) {
      unset($baseComponent['path'], $baseComponent['query'], $baseComponent['fragment']);
    }
    
    $component = \array_merge($component, $baseComponent);
    $this->input = $this->format_url($component);
  }

  /**
   * The parse() method takes a URL string, parses it, and returns a URL object.
   * A URIError is thrown if the auth property is present but cannot be decoded.
   * 
   * @throws \Web\Url\Exception\InvalidUrlException
   * 
   * @return \Web\Url\Parser\ParserInterface|\Web\Url\UrlInterface
   */
  public function parse()
  {
    $self = $this;
    if ($this->url) {
      $this->validateURL();
      $self = $this->url;
    }

    \preg_match($this->regex, $this->input, $matches);
    $component = \array_filter($matches, function($value, $key) {
      return \is_string($key);
    }, \ARRAY_FILTER_USE_BOTH);

    foreach($component as $key => $value) {
      $self->$key = $value;
    }

    return $this;
  }

  /**
   * Creates a new Parser constructor.
   * Initializes a new instance of Parser => Url with the given @paramters.
   * 
   * @param string                     [required]
   * @param string|\Web\Url\Url|null   [optional]
   * @param bool                       [optional]
   * @param bool                       [optional]
   * @param \Web\Url\UrlInterface|null [optional]
   * 
   * @throws \Web\Url\Exception\InvalidUrlException
   * 
   * @return \Web\Url\Parser\Parser
   */
  public static function with(string $input, $base, bool $parseQuery, bool $slashesDenoteHost, UrlInterface $url = null, string $regex)
  {
    return new self($input, $base, $parseQuery, $slashesDenoteHost, $url, $regex);
  }
}
?>