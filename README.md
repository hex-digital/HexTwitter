# HexTwitter

![Version 1.0](https://img.shields.io/badge/version-1.0-brightgreen.svg)


## Introduction

This is the repository for the Hex Twitter package.

The aim of the package is to allow simplistic integration of Hex's Twitter functionality 
into existing and future websites, as well as create a central environment within which
updates, new features and changes can be created, and then easily propogated.


## Dependencies

This integration requires abraham/twitteroauth, version ^0.6.4.
[Instructions for installing this can be found here](https://twitteroauth.com/).


## Initial Installation

Simply clone this repository into your project (we recommend in a classes/ folder),
then edit the credentials.php file to include your credentials (from apps.twitter.com).

Next, open the settings.php and enter the user ID for the twitter handle you wish to
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
