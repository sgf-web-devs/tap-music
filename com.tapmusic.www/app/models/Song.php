<?php

class Song extends Eloquent {
    protected $append = array(
        'song_duration'
    );

    public function getSongDurationAttribute () {
        $duration = $this->duration / 1000;
        return date('i:s', $duration);
    }
}
