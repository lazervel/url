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

class Url extends BaseURL implements UrlInterface
{
  /**
   * Creates a new URL constructor.
   * Initializes a new instance of [Url] with the given $input and $base.
   * 
   * @param string $input             [required]
   * @param string|\Web\Url\Url $base [optional]
   * 
   * @throws \Web\Url\Exception\InvalidUrlException
   * 
   * @return void
   */
  public function __construct(string $input, $base = null)
  {
    parent::__construct($input, $base);
  }
}
?>