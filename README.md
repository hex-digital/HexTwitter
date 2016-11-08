# HexTwitter

![Version 1.0.0](https://img.shields.io/badge/version-1.0.0-brightgreen.svg)


## Introduction

This is the repository for the Hex Twitter package.

The aim of the package is to allow simplistic integration of Hex's Twitter functionality 
into existing and future websites, as well as create a central environment within which
updates, new features and changes can be created, and then easily propogated.


## Dependencies

This integration requires abraham/twitteroauth, version ^0.6.4.


## Initial Installation

The fastest way to install the Hex Twitter package is with [Composer](https://getcomposer.org/).
Just setup the require and repositories section of your composer.json file as below:

    "require": {
        "hex-digital/HexTwitter": "dev-master"
    },
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/hex-digital/HexTwitter"
        }
    ]

Now run `php composer.phar install` to load the dependencies for your project. You may be told
'Your GitHub credentials are required to fetch private repository metadata (https://github.com/hex-digital/HexTwitter)',
in which case copy the URL shown and follow the instructions to set up a token, then copy and paste it back into
the terminal.

Once finished, you can import the new dependencies using the autoloader:

    require "vendor/autoload.php";

Now, open the settings.php and enter the user ID for the twitter handle you wish to
specify as the default.  
Also check the default number of tweets to grab when not specified
in the function call.


## Usage

To set up the initial Tweet caching, run a cron job that calls `Twitter->download()`.  
The simplest way to do this is to place this code in your functions.php near the top,

    use HexTwitter\Twitter;
    if ( isset( $_GET['twitter'] ) && $_GET['twitter'] == 'download' ) {
        $twitter = new Twitter();
        echo $twitter->download();
        exit;
    }

then run a cron job that points to http://*YOURURL*/?twitter=download (e.g. `http://hexdigital.com/?twitter=download`).  
This will cache the tweets to a json encoded file to then be read from.


To read the tweets, simply call `$twitter->fetch()` to return an Array of tweets.  
Fetch can also take an integer variable to decide how many tweets to return, if different from default.


## Common Functionality Examples

>Note: The following examples assume you use `foreach( $tweets as $tweet ) :`

Get the Twitter Handle:  
`echo $tweet->user->screen_name`

Correctly display a timestamp:  
`<time datetime="<?= $timestamp->format('c') ?>"><?= $timestamp->format('j F, Y'); ?></time>`

Beautify the tweet, to correctly set hyperlinks for Twitter Handles, Hashtags and external URLs:  
`echo $twitter->beautify($tweet->text)`
