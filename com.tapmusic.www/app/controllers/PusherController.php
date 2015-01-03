<?php

class PusherController extends BaseController {

    protected $app_id;
    protected $app_key;
    protected $app_secret;

    public function __construct()
    {
        $this->app_id = Config::get('services.pusher.app_id');
        $this->app_key = Config::get('services.pusher.app_key');
        $this->app_secret = Config::get('services.pusher.app_secret');
    }

    public function getMemberRemoved()
    {
        $user = OnlineUser::where('userID', '=', Input::get('userID'))->delete();

        return $user;
    }

    public function postSendChatMessage()
    {
        $pusher = new Pusher($this->app_key, $this->app_secret, $this->app_id);

        $data = [
            'message' => Input::get('message'),
            'user' => Session::get('userName'),
            'image' => Session::get('userImage'),
            'time' => time()
        ];

        $pusher->trigger(Config::get('settings.presenceChannel'), 'chatMessageSent', $data);
    }

}
