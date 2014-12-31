<?php

/**
 * Song
 *
 * @property integer $id
 * @property string $spotifyURL
 * @property string $trackName
 * @property string $artistName
 * @property string $albumArt
 * @property integer $order
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $userID
 * @property string $userName
 * @property string $userImage
 * @property boolean $broadcasting
 * @property integer $userOnlineID
 * @property integer $duration
 * @property integer $start_time
 * @property-read mixed $song_duration
 * @method static \Illuminate\Database\Query\Builder|\Song whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Song whereSpotifyURL($value) 
 * @method static \Illuminate\Database\Query\Builder|\Song whereTrackName($value) 
 * @method static \Illuminate\Database\Query\Builder|\Song whereArtistName($value) 
 * @method static \Illuminate\Database\Query\Builder|\Song whereAlbumArt($value) 
 * @method static \Illuminate\Database\Query\Builder|\Song whereOrder($value) 
 * @method static \Illuminate\Database\Query\Builder|\Song whereCreatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\Song whereUpdatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\Song whereUserID($value) 
 * @method static \Illuminate\Database\Query\Builder|\Song whereUserName($value) 
 * @method static \Illuminate\Database\Query\Builder|\Song whereUserImage($value) 
 * @method static \Illuminate\Database\Query\Builder|\Song whereBroadcasting($value) 
 * @method static \Illuminate\Database\Query\Builder|\Song whereUserOnlineID($value) 
 * @method static \Illuminate\Database\Query\Builder|\Song whereDuration($value) 
 * @method static \Illuminate\Database\Query\Builder|\Song whereStartTime($value) 
 */
class Song extends Eloquent {
    protected $append = array(
        'song_duration'
    );

    public function getSongDurationAttribute () {
        $duration = $this->duration / 1000;
        return date('i:s', $duration);
    }
}
