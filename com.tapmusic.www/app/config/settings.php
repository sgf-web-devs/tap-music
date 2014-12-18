<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Testing out a query for fetching the songs in order
    |--------------------------------------------------------------------------
    |
    | Storing here to easily use site wide
    |
    */

    'roundRobinQuery' => 'select id, spotifyURL, trackName, artistName, albumArt, userID, userName, userImage, broadcasting, userOnlineID from (select songs.*, @rn := if(userID != @ng, 1, @rn + 1) as ng, @ng := userID from songs, (select @rn:=0, @ng:=null) v order by userID, id ) sq where broadcasting = FALSE order by ng, userOnlineID',


    /*
    |--------------------------------------------------------------------------
    | Default Track
    |--------------------------------------------------------------------------
    |
    | Done a lot a shit just to live this here lifestyle
    |
    */

    'defaultTrack' => '7DTlsMOQjGysXHpwwpHuPl',


    /*
    |--------------------------------------------------------------------------
    | Presence Channel
    |--------------------------------------------------------------------------
    |
    | Pusher presence channel used to facilitate messaging to group members
    |
    */

    'presenceChannel' => getenv('PRESENCE_CHANNEL'),


    /*
    |--------------------------------------------------------------------------
    | Player Channel
    |--------------------------------------------------------------------------
    |
    | Standard Pusher channel used to communicate with the music player
    |
    */

    'playerChannel' => getenv('PLAYER_CHANNEL'),


    /*
    |--------------------------------------------------------------------------
    | Stream URL
    |--------------------------------------------------------------------------
    |
    | Stream URL to attach audio tag to.  If this is omitted the audio tag will not render
    |
    */

    'playerStreamUrl' => getenv('PLAYER_STREAM_URL'),


    /*
    |--------------------------------------------------------------------------
    | Stream Disable
    |--------------------------------------------------------------------------
    |
    | Great for working in development
    |
    */

    'playerStreamDisable' => getenv('PLAYER_STREAM_DISABLE') || false

);
