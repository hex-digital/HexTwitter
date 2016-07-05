<?php 
/**
 * Hex Digital (http://www.hexdigial.com)
 *
 * @link      http://www.hexdigial.com
 * @copyright Copyright (c) 2016 Hex Digital (http://www.hexdigital.com)
 * @license   http://www.hexdigital.com/license License
 */
namespace HexTwitter;
use HexTwitter\Cache\Wordpress;
use Core\Cache\Memcached;
class Cache {
    /**
     * Returns a new instance of the caching agent that is available on the
     * server. By default we should be using WordPress's caching functions,
     * otherwise we'll fall back to other caching agents that are installed as
     * PHP modules.
     * 
     * @author Oliver Tappin <oliver@hexdigital.com>
     * @param  string $cachingAgent The name of the caching agent
     * @return object|false
     */
    public function factory( $cachingAgent = null ) {
        if ( $cachingAgent !== null && class_exists( $cachingAgent ) ) {
            return new $cachingAgent;
        } elseif ( extension_loaded( 'memcached' ) ) {
            return new Memcached();
        } elseif ( function_exists( 'wp_cache_add' ) ) {
            return new Wordpress();
        }
        return false;
    }
}
