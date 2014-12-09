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

    'PUSHER_APP_ID' => '96190',

    'PUSHER_APP_KEY' => 'bb58ca596665e104e52a',

    'PUSHER_APP_SECRET' => 'e0e17934e6e4facda0c6',

    'SPOTIFY_CLIENT_ID' => '7d113f2c09ef4021b4ea69949e6a55f8',

    'SPOTIFY_CLIENT_SECRET' => 'b0dadf9563cf4092aec821cecb4c4544',

    'SPOTIFY_CALLBACK_URL' => '/auth/spotify-callback',
];
```
- Run composer install
- Run php artisan migrate
- Make sure your root url and http://youdomain.com/auth/spotify-callback(or whatever URL you choose to register in config above) are registered under Spotify's developer area as redirect URLs
