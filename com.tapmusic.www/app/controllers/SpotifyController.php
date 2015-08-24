<?php

class SpotifyController extends BaseController {

    protected $client_id;
    protected $client_secret;
    protected $callback_url;

    public function __construct()
    {
        $this->client_id = Config::get('services.spotify.client_id');
        $this->client_secret = Config::get('services.spotify.client_secret');
        $this->callback_url = config::get('services.spotify.callback_url');
    }

    public function getSearch()
    {
        $search = Input::get('search', 'lifestyle');
        $client = new GuzzleHttp\Client();
        $response = $client->get('https://api.spotify.com/v1/search?q='.$search.'&type=track&market=US');
        return $response->getBody();
    }

    public function getTrack()
    {
        $songID = Input::get('songID');
        $client = new GuzzleHttp\Client();
        $response = $client->get('https://api.spotify.com/v1/tracks/'.$songID);

        $trackData = \GuzzleHttp\json_decode($response->getBody());

        $song = [
            'trackName' => $trackData->name,
            'artistName' => $trackData->artists[0]->name,
            'albumArt' => $trackData->album->images[0]->url
        ];

        dd($song);

        //return $trackData;

        return $response->getBody();
    }

    public function getPreview()
    {
        $songID = Input::get('songID');

        $client = new GuzzleHttp\Client();
        $response = $client->get('https://api.spotify.com/v1/tracks/'.$songID);

        $trackData = \GuzzleHttp\json_decode($response->getBody());

        $song = [
            'trackName' => $trackData->name,
            'artistName' => $trackData->artists[0]->name,
            'albumArt' => $trackData->album->images[0]->url
        ];

        //dd($song);

        return $trackData->preview_url;

        //return $response->getBody();
    }

    public function getPlaylists()
    {
        $api = new SpotifyWebAPI\SpotifyWebAPI();

        $api->setAccessToken(Session::get('accessToken'));

        $playlists = $api->getUserPlaylists(Session::get('userID'), array(
            'limit' => 50
        ));

        return $playlists->items;
    }

    public function getPlaylist()
    {
        $api = new SpotifyWebAPI\SpotifyWebAPI();

        $api->setAccessToken(Session::get('accessToken'));

        $playlist = $api->getUserPlaylist(Session::get('userID'), Input::get('playlistID'));

        return $playlist->tracks->items;
    }
}
