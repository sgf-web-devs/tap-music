# TAP Music

![alt tag](https://cldup.com/DllsrMa3JH-3000x3000.png)

This repository is the interface for the TAP Music player. To install the player, please go to [Tap Music Player](https://github.com/sgf-web-devs/tap-music-player) repository for instructions.

## Installation

It is recommended that before you install this application and develop for it you become familiar with compiling libraries and using the command line on a Mac OSx/Unix/Linux system. Windows may have little support on some of the items you'll need to install this interface.

Assumptions are asserted that you have Apache/Nginx, MySQL, and PHP already installed on your system.

#### Things You'll Need

1. [Spotify Developer Account](https://developer.spotify.com/) *Note: this requires a premium Spotify account.*
2. [Libsass](https://github.com/sass/libsass)
3. [SassC](http://libsass.org/#sassc)
4. [Node.js](http://nodejs.org/download/)
5. [Composer](https://getcomposer.org/)
6. [Pusher](https://pusher.com/) *Note: this is a free service*


### Process

*Note: This is the install of the interface, please go to [Tap Music Player](https://github.com/sgf-web-devs/tap-music-player) to install the player so the interface will work :)*

Setup a vhost to what ever URI you choose for this application. You will need this URI when you setup the Spotify app callback URI.

#### Spotify

Simple. Click the link above and create an app. Set your app's callback URI to `http://yoururhere/auth/spotify-callback`

Take note of the `Cliend ID`, and `Client Secret`

#### Pusher

Click the link above, and create an account (its free!). After that, create a app and take note of the `app_id`, `key`, and `secret`.

#### Interface
Clone the repository to your favorite local/production web server directory
```
$ git clone git@github.com:sgf-web-devs/tap-music.git
```

In **< root >/com.tapmusic.www**
```
$ sudo composer self-update
$ composer install
```

In **< root >/com.tapmusic.www/public**
```
$ npm install
```
*Note: Gulp may need to be installed on the system globally*

Create a **< root >/com.tapmusic.www/.env.local.php** or .env.php (for production) and fill with your setup's details
```php
<?php

return [
    'TM_DB' => 'my_databse',
    'TM_DB_USER' => 'my_mysql_user',
    'TM_DB_PASS' => 'my_password',

    'PUSHER_APP_ID' => 'app_id',
    'PUSHER_APP_KEY' => 'key',
    'PUSHER_APP_SECRET' => 'secret',

    // You can name the following to w/e you want!
    'PLAYER_CHANNEL' => 'my_channel_name',

    // Name this exactly like the "PLAYER_CHANNEL" except, leave the "presence-" prefixed
    'PRESENCE_CHANNEL' => 'presence-my_channel_name',

    'SPOTIFY_CLIENT_ID' => 'client_id',
    'SPOTIFY_CLIENT_SECRET' => 'client_secret',
    'SPOTIFY_CALLBACK_URL' => '/auth/spotify-callback', // Leave this alone

    // Leave these defaults alone. (Additional instructions for these to come)
    'PLAYER_STREAM_DISABLE' => true,
    'PLAYER_STREAM_URL' => 'http://server/stream',

    'SLACK_NOTIFICATION_DISABLE' => true
];
```

## Useage

TODO

## Development

TODO