<?php
/**
 * Hex Digital (http://www.hexdigial.com)
 *
 * @link      http://www.hexdigial.com
 * @copyright Copyright (c) 2016 Hex Digital (http://www.hexdigital.com)
 * @license   http://www.hexdigital.com/license License
 * @since     1.0.0
 * @version   1.0.0
 */
namespace HexTwitter;
use Abraham\TwitterOAuth\TwitterOAuth;
use HexTwitter\Cache;
use HexTwitter\Cache\FactoryInterface;
use HexTwitter\Units\Time;

$dir = dirname(__FILE__);

if ( file_exists( $dir . "/settings.php" ) ) {
    
}
if ( file_exists( $dir . "credentials.php" ) ) {
    
}

class Twitter implements FactoryInterface {
    const CACHE_KEY = 'mcb_twitter';
    const CACHE_FILE = 'twitter.json';
    private $dir;
    protected $cache;
    protected $connection;
    protected $error_reporting;
    public function __construct( $error_reporting = true ) {
        // Check that we have everything we need to correctly function
        $this->error_reporting = $error_reporting;
        $dir = dirname(__FILE__);
        $error_string = "";
        if ( file_exists( $dir . "/settings.php" ) ) {
            include $dir . "/settings.php";
        } else {
            $error_string .= 'File not found: settings.php.<br>';
            $setup_error = true;
        }
        if ( file_exists( $dir . "/credentials.php" ) ) {
            include $dir . "/credentials.php";
        }  else {
            $error_string .= 'File not found: credentials.php.<br>';
            $setup_error = true;
        }
        if ( ! defined( 'TWITTER_CONSUMER_KEY' ) || ! defined( 'TWITTER_CONSUMER_SECRET' ) ||
             ! defined( 'TWITTER_ACCESS_TOKEN' ) || ! defined( 'TWITTER_ACCESS_TOKEN_SECRET' ) ) {
            $error_string .= 'One or more credential tokens are not set.<br>';
            $setup_error = true;
        }
        if ( ! is_development() ) {
            if ( isset( $setup_error ) && $setup_error === true && $this->error_reporting === true) {
                echo $error_string;
                echo 'Please ensure HexTwitter is correctly set up. <a href="http://github.com/hex-digital/HexTwitter">Visit the github repo for installation instructions</a><br>
                    To squash this message, turn off error messaging via the Twitter constructor.';
            }
        }
    }
    public function error_reporting( $bool ) {
        $this->error_reporting = $bool;
    }
    public function cache() {
        if ( $this->cache === null ) {
            $cache = new Cache();
            $this->cache = $cache->factory();
        }
        return $this->cache;
    }
    public function connection() {
        if ( $this->connection === null ) {
            $this->connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, TWITTER_ACCESS_TOKEN, TWITTER_ACCESS_TOKEN_SECRET);
        }
        return $this->connection;
    }
    public function download( $count = DEFAULT_COUNT, $id = USER_ID ) {
        $statuses = $this->connection()->get( "statuses/user_timeline", array(
            'count' => $count,
            'exclude_replies' => true,
            'user_id' => $id
        ));
        return file_put_contents( ABSPATH . self::CACHE_FILE, json_encode( $statuses ) );
    }
    public function fetch( $count = 10 ) {
        $cacheKey = self::CACHE_KEY . '_' . $count;
        if ( ! $cached = $this->cache()->get( $cacheKey ) ) {
            if ( ! file_exists( ABSPATH . self::CACHE_FILE ) ) return false;
            $statuses = json_decode( file_get_contents( ABSPATH . self::CACHE_FILE ) );
            $this->cache()->set( $cacheKey, $statuses, Time::HOUR * 4 );
            // Limit results using $count variable
            if ( sizeof( $statuses ) > $count ) {
                return array_slice($statuses, 0, $count);
            } else {
                return $statuses;
            }
        }
        return $cached;
    }
    public function beautify($text) {
        $text = preg_replace( '@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a target="_blank" href="$1">$1</a>', $text );
        $text = preg_replace( '/(^|\s)#(\w*[a-zA-Z_]+\w*)/', '\1<a target="_blank" href="https://twitter.com/search?q=%23\2">#\2</a>', $text );
        $text = preg_replace( '/(^|\s)@(\w*[a-zA-Z_]+\w*)/', '\1<a target="_blank" href="https://twitter.com/\2">@\2</a>', $text );
        return $text;
    }
}
