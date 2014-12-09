## TAP Music

Official documentation to come once we get this somewhat cleaned up.

For now just make sure to address the following

- Create a .env.local.php or .env.php (for production) and fill with your setup's details\
```php
<?php

return [
    'TM_DB' => 'tapmusic_dev',

    'TM_DB_USER' => 'root',

    'TM_DB_PASS' => 'password',

    'PUSHER_APP_ID' => '1234',

    'PUSHER_APP_ID' => '1234',

    'PUSHER_APP_ID' => '1234',

    'SPOTIFY_CLIENT_ID' => '1234',

    'SPOTIFY_CLIENT_SECRET' => '1234',

    'SPOTIFY_CALLBACK_URL' => '/auth/spotify-callback',
];
```
- Fill in the values in app/config/services.php for spotify and pusher and set values as needed for your pusher channels and default values in app/config/settings.php
- Run composer install
- Run php artisan migrate
- Make sure your root url and /auth/spotify-callback are registered under Spotify's developer area as redirect URLs
