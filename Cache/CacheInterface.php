<?php 
/**
 * Hex Digital (http://www.hexdigial.com)
 *
 * @link      http://www.hexdigial.com
 * @copyright Copyright (c) 2016 Hex Digital (http://www.hexdigital.com)
 * @license   http://www.hexdigital.com/license License
 */
namespace HexTwitter\Cache;
interface CacheInterface {
    public function add( $key, $data, $group = '', $expire = 0 );
    public function set( $key, $data, $group = '', $expire = 0 );
    public function get( $key, $group = '', $force = false, $found = null );
    public function remove( $key, $group = '' );
    public function flush();
}
