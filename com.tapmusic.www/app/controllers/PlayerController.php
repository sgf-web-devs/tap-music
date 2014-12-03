<?php

class PlayerController extends BaseController {

    protected $app_id;
    protected $app_key;
    protected $app_secret;

    public function __construct()
    {
        $this->app_id = Config::get('services.pusher.app_id');
        $this->app_key = Config::get('services.pusher.app_key');
        $this->app_secret = Config::get('services.pusher.app_secret');
    }

    public function postPlay()
    {
        $pusher = new Pusher( $this->app_key, $this->app_secret, $this->app_id );

        $data = [
            'message' => Input::get('songID')
        ];

        $pusher->trigger( 'tapmusic_channel', 'song-play', $data );

        return Redirect::back();
    }

}
