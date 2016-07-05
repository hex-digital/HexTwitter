<?php 
/**
 * Hex Digital (http://www.hexdigial.com)
 *
 * @link      http://www.hexdigial.com
 * @copyright Copyright (c) 2016 Hex Digital (http://www.hexdigital.com)
 * @license   http://www.hexdigital.com/license License
 */
namespace Core\Cache;
class Memcached implements CacheInterface {
    protected $cache;
    public function cache() {
        if ( $this->cache === null ) {
            $this->cache = new \Memcached();
            $this->cache->addServer( 'localhost', 11211 );
        }
        return $this->cache;
    }
    public function add( $key, $data, $group = '', $expire = 0 ) {
        return $this->cache()->add( $key, $data, $expire );
    }
    public function set( $key, $data, $group = '', $expire = 0 ) {
        return $this->cache()->set( $key, $data, $expire );
    }
    public function get( $key, $group = '', $force = false, $found = null ) {
        return $this->cache()->get( $key );
    }
    public function remove( $key, $group = '' ) {
        return $this->cache()->add( $key, false ); // Or null?
    }
    public function flush() {
        $this->cache()->flush();
    }
}