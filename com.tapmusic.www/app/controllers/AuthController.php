<?php

class AuthController extends BaseController {

    protected $client_id;
    protected $client_secret;
    protected $callback_url;

    public function __construct()
    {
        $this->client_id = Config::get('services.spotify.client_id');
        $this->client_secret = Config::get('services.spotify.client_secret');
        $this->callback_url = config::get('services.spotify.callback_url');
    }

    public function getIndex()
    {
        $session = new SpotifyWebAPI\Session($this->client_id, $this->client_secret, $this->callback_url);
        $scopes = array(
            'user-read-private',
            'user-library-read',
            'user-read-email',
            'playlist-read-private',
            'playlist-modify-public',
            'playlist-modify-private'
        );

        $authorizeUrl = $session->getAuthorizeUrl(array(
            'scope' => $scopes
        ));

        header('Location: ' . $authorizeUrl);
        die();
    }

    public function getSpotifyCallback()
    {
        $session = new SpotifyWebAPI\Session($this->client_id, $this->client_secret, $this->callback_url);
        $api = new SpotifyWebAPI\SpotifyWebAPI();

        // Request a access token using the code from Spotify
        $session->requestToken(Input::get('code'));
        $accessToken = $session->getAccessToken();

        // Set the access token on the API wrapper
        $api->setAccessToken($accessToken);

        $refreshToken = $session->getRefreshToken();

        $user = $api->me();

        Session::put('accessToken', $accessToken);
        Session::put('refreshToken', $refreshToken);
        Session::put('userID', $user->id);
        Session::put('userName', $user->display_name);
        Session::put('userImage', $user->images[0]->url);


        return Redirect::to('/');
    }

    public function postLogin()
    {

        // Refresh Spotify token so we are hopefully always fresh
        $session = new SpotifyWebAPI\Session($this->client_id, $this->client_secret, $this->callback_url);
        $api = new SpotifyWebAPI\SpotifyWebAPI();

        $session->setRefreshToken(Session::get('refreshToken'));
        $session->refreshToken();

        $accessToken = $session->getAccessToken();

        Session::put('accessToken', $accessToken);

        $api->setAccessToken($accessToken);

        $user = $api->me();


        // Authorize with Pusher
        $userName = $user->id;
        $pusher = new Pusher(
            Config::get('services.pusher.app_key'),
            Config::get('services.pusher.app_secret'),
            Config::get('services.pusher.app_id')
        );

        $presence_data = array(
            'id' => $userName,
            'name' => $user->display_name,
            'image' => $user->images[0]->url
        );

        return $pusher->presence_auth(
            Input::get('channel_name', 'presence-tapmusic1'),
            Input::get('socket_id'),
            $userName,
            $presence_data
        );
    }

    public function getUser()
    {
        $session = new SpotifyWebAPI\Session($this->client_id, $this->client_secret, $this->callback_url);
        $api = new SpotifyWebAPI\SpotifyWebAPI();

        $api->setAccessToken(Session::get('accessToken'));

        $user = $api->me();
        dd($user);

        //return Config::get('services.spotify.callbackUrl');

        //dd(Session::get('userImage'));
    }
}
