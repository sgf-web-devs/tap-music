<?php

class QueueController extends BaseController
{

    public function __construct()
    {
        // Whole lotta thoughts going on in this fatty
        // Very much intend to come back and split up
        // this logic....logically
    }

    public function getSongs()
    {
        return Song::orderBy('order')->get();
    }

    public function postAddSong()
    {
        $songID = Input::get('songID') ? Input::get('songID') : Config::get('settings.defaultTrack');
        $songCount = Song::all()->count();

        // Spotify call to get all of the track data
        $client = new GuzzleHttp\Client();
        $response = $client->get('https://api.spotify.com/v1/tracks/' . $songID);

        $trackData = \GuzzleHttp\json_decode($response->getBody());

        // New up a song and save to db
        $song = new Song;
        $song->spotifyURL = $songID;
        $song->trackName = $trackData->name;
        $song->artistName = $trackData->artists[0]->name;
        $song->albumArt = $trackData->album->images[0]->url;
        $song->userID = Session::get('userID');
        $song->userName = Session::get('userName');
        $song->userImage = Session::get('userImage');
        $song->userOnlineID = Session::get('userOnlineID');
        $song->broadcasting = false;
        $song->duration = $trackData->duration_ms;
        if($songCount == 0){
            $song->broadcasting = true;
            $song->order = 1;
        }
        $song->save();

        $app_id = Config::get('services.pusher.app_id');
        $app_key = Config::get('services.pusher.app_key');
        $app_secret = Config::get('services.pusher.app_secret');

        $pusher = new Pusher($app_key, $app_secret, $app_id);

        $data = [
            'message' => 'spotify:track:' . Input::get('songID')
        ];

        // Bah, super gross to duplicate this pusher logic here.  Will fix later
        if ($songCount == 0) {
            $pusher->trigger(Config::get('settings.playerChannel'), 'song-play', $data);
        }

        $this->getGenerateRoundRobinOrder();

        $pusher->trigger(Config::get('settings.presenceChannel'), 'songAddedToQueue', $data);

        return Redirect::to('/');
    }

    public function getDeleteAll()
    {

        $songs = Song::all();

        foreach ($songs as $song) {
            $song->delete();
        }

        $app_id = Config::get('services.pusher.app_id');
        $app_key = Config::get('services.pusher.app_key');
        $app_secret = Config::get('services.pusher.app_secret');

        $pusher = new Pusher($app_key, $app_secret, $app_id);

        $pusher->trigger(Config::get('settings.presenceChannel'), 'queueCleared', '');

        return 'deleted';
    }

    public function getPlayFirstSong()
    {
        $firstSong = Song::orderBy('order')->first();

        if (!$firstSong) {
            return 'spotify:track:' . Config::get('settings.defaultTrack'); // This here lifestyle
        }

        $firstSong->start_time = time();
        $firstSong->save();

        $app_id = Config::get('services.pusher.app_id');
        $app_key = Config::get('services.pusher.app_key');
        $app_secret = Config::get('services.pusher.app_secret');

        $pusher = new Pusher($app_key, $app_secret, $app_id);

        $data = [
            'message' => 'spotify:track:' . $firstSong->spotifyURL
        ];

        $pusher->trigger(Config::get('settings.playerChannel'), 'song-play', $data);

        return $firstSong->spotifyURL;
    }

    public function getNextSong()
    {
        $currentSong = Song::orderBy('order')->first();
        $songCount = Song::all()->count();

        if (!$currentSong) {
            return 'spotify:track:' . Config::get('settings.defaultTrack'); // This here lifestyle
        }

        if ($songCount > 1) {
            $currentSong->delete();
        }

        $nextSong = Song::orderBy('order')->firstOrFail();

        if ($songCount == 1) {
            $nextSong->delete();
        }

        $app_id = Config::get('services.pusher.app_id');
        $app_key = Config::get('services.pusher.app_key');
        $app_secret = Config::get('services.pusher.app_secret');

        $pusher = new Pusher($app_key, $app_secret, $app_id);

        DB::table('songs')
            ->update(array('broadcasting' => false));

        $nextSong->broadcasting = true;
        $nextSong->start_time = time();
        $nextSong->save();

        $pusher->trigger(Config::get('settings.presenceChannel'), 'nextSong', '');

        $this->notifySlack($nextSong);

        return 'spotify:track:' . $nextSong->spotifyURL;
    }

    public function getSkipSong()
    {
        $currentSong = Song::orderBy('order')->first();
        $songCount = Song::all()->count();

        if (!$currentSong) {
            return 'spotify:track:' . Config::get('settings.defaultTrack'); // This here lifestyle
        }

        if ($songCount > 1) {
            $currentSong->delete();
        }

        $nextSong = Song::orderBy('order')->firstOrFail();

        if ($songCount == 1) {
            $nextSong->delete();
        }

        $app_id = Config::get('services.pusher.app_id');
        $app_key = Config::get('services.pusher.app_key');
        $app_secret = Config::get('services.pusher.app_secret');

        $pusher = new Pusher($app_key, $app_secret, $app_id);

        $data = [
            'message' => 'spotify:track:' . $nextSong->spotifyURL
        ];

        DB::table('songs')
            ->update(array('broadcasting' => false));

        $nextSong->broadcasting = true;
        $nextSong->start_time = time();
        $nextSong->save();

        $pusher->trigger(Config::get('settings.presenceChannel'), 'nextSong', '');
        $pusher->trigger(Config::get('settings.playerChannel'), 'song-play', $data);

        $this->notifySlack($nextSong);

        return 'spotify:track:' . $nextSong->spotifyURL;
    }

    public function getRefreshRobin()
    {
        $this->getGenerateRoundRobinOrder();

        return Redirect::to('/');
    }

    public function getGenerateRoundRobinOrder()
    {
        try{
            $currentSong = Song::where('broadcasting', '=', true)->firstOrFail();
            $counter = $currentSong->order + 5; // just make sure we are starting ABOVE whatever the current song is
        }catch (Exception $e){
            $counter = 0;
        }

        $songs = DB::select(DB::raw(Config::get('settings.roundRobinQuery')));

        foreach ($songs as $s) {
            $counter++;
            Song::where('id', '=', $s->id)->update(array('order' => $counter));
        }

        return 'success';
    }

    public function notifySlack($song)
    {
        if (Config::get('settings.slackChannels') && !Config::get('settings.slackNotificationDisable')) {
            $client = new GuzzleHttp\Client();

            $userName = $song->userName ?: 'Somebody';
            $messageBody = "$userName is now playing: $song->trackName by $song->artistName " . Request::root();

            foreach (Config::get('settings.slackChannels') as $channel) {
                $client->post($channel['API_PATH'] . '&channel=%23' . $channel['CHANNEL_NAME'], [
                    'body' => $messageBody,
                    'allow_redirects' => true
                ]);
            }
        }
    }

}