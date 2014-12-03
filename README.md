## TAP Music

Official documentation to come once we get this somewhat cleaned up.

For now just make sure to address the following

- Create a .env.local.php or .env.php (for production) file that returns an array with the following values: TM_DB, TM_DB_USER, TM_DB_PASS
- Fill in the values in app/config/services.php for spotify and pusher and set values as needed for your pusher channels and default values in app/config/settings.php
- Run composer install
- Run php artisan migrate
- Make sure your root url and /auth/spotify-callback are registered under Spotify's developer area as redirect URLs