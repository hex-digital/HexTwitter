<?php 
/**
 * Hex Digital (http://www.hexdigial.com)
 *
 * @link      http://www.hexdigial.com
 * @copyright Copyright (c) 2016 Hex Digital (http://www.hexdigital.com)
 * @license   http://www.hexdigital.com/license License
 */
namespace HexTwitter\Cache;
class Wordpress implements CacheInterface {
    public function add( $key, $data, $group = '', $expire = 0 ) {
        return wp_cache_add( $key, $data, $group, $expire );
    }
    public function set( $key, $data, $group = '', $expire = 0 ) {
        return wp_cache_set( $key, $data, $group, $expire );
    }
    public function get( $key, $group = '', $force = false, $found = null ) {
        return wp_cache_get( $key, $group, $force, $found );
    }
    public function remove( $key, $group = '' ) {
        return wp_cache_delete( $key, $group );
    }
    public function flush() {
        return wp_cache_flush();
    }
}
