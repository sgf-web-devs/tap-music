## TAP Music

![alt tag](https://cldup.com/DllsrMa3JH-3000x3000.png)

This is the repo for the web interface to TAP Music.  If you are looking for the player it is at
https://github.com/sgf-web-devs/tap-music-player

Official documentation to come once we get this somewhat cleaned up.

You will need to have a Spotify Developer Account and keys to Spotify Web API
https://developer.spotify.com/

- Create a .env.local.php or .env.php (for production) and fill with your setup's details
```php
<?php

return [
    'TM_DB' => 'tapmusic_dev',

    'TM_DB_USER' => 'root',

    'TM_DB_PASS' => 'password',

    'PUSHER_APP_ID' => '1234',

    'PUSHER_APP_KEY' => '1234',

    'PUSHER_APP_SECRET' => '1234',

    'PRESENCE_CHANNEL' => 'presence-1234',

    'PLAYER_CHANNEL' => '1234',

    'SPOTIFY_CLIENT_ID' => '1234',

    'SPOTIFY_CLIENT_SECRET' => '1234',

    'SPOTIFY_CALLBACK_URL' => '/auth/spotify-callback',
    
    'PLAYER_STREAM_URL' => 'http://server/stream',
    
    'PLAYER_STREAM_DISABLE' => false,
    
    'SLACK_NOTIFICATION_DISABLE' => true
];
```
- Run composer install
- Run php artisan migrate
- Make sure your root url and http://youdomain.com/auth/spotify-callback(or whatever URL you choose to register in config above) are registered under Spotify's developer area as redirect URLs
