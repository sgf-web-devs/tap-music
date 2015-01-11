@extends('layouts.master')

@section('content')

    <nav class="mobile_tabs">
        <ul>
            <li><a data-target="now-playing" class="is_active" href="#"><i class="fa fa-play"></i></a></li>
            <li><a data-target="side_search" href="#"><i class="fa fa-search"></i></a></li>
            <li><a data-target="side_queue" href="#"><i class="fa fa-list"></i></a></li>
        </ul>
    </nav>
    @if (Config::get('settings.playerStreamUrl') && !Config::get('settings.playerStreamDisable'))
        <audio controls="controls" id="tap_stream" src="[[ Config::get('settings.playerStreamUrl') ]]" autoplay></audio>
    @endif
    <audio id="preview" src=""></audio>

    <div class="wrapper" ng-controller="TapMusicCtrl">
        <!--<header class="header">
            TAP MUSIC (BETA, Invite-Only, Cloud, #ThisHereLifestyle)
            <a href="#" class="volume_toggle"><i class="fa"></i></a>
        </header>-->


        <div class="flex_wrap">
            <div ng-if="currentTrack.albumArt" class="now-playing">
                <img ng-src="{{ currentTrack.albumArt }}">
                <div class="progress" progress-bar progress-data="progressData">

                </div>
                <div style="clear:both"></div>
                <div class="now_playing_deets">
                    <h5>NOW PLAYING</h5>
                    <div class="now-playing-title">{{ currentTrack.trackName }}</div>



                    <div class="now-playing-artist"><span>by</span> {{ currentTrack.artistName }}</div>
                    <div class="time">
                        <i class="fa fa-clock-o"></i> {{ parseTrackTime(currentTrack.duration) }}
                    </div>
                    <figure class="current-song-user">
                        <img ng-src="{{ currentTrack.userImage }}" title="{{ currentTrack.userName }}" />
                    </figure>
                </div>
            </div>

            <aside class="side_queue">
                <h5 class="title">TAP QUEUE</h5>
                <div class="the-queue">
                    <div class="playing-next" ng-repeat-start="track in queueTracks" ng-if="$first">
                        <div class="next">NEXT</div>
                        <img ng-src="{{ track.userImage }}" title="{{ track.userName }}" />
                        <h1>{{ track.trackName }} <span>- {{ parseTrackTime(track.duration) }}</span></h1>
                        <h5>{{ track.artistName }}</h5>
                    </div>
                    <div style="clear:both"></div>
                    <div class="queued" ng-repeat-end ng-if="!$first">
                        <ul>
                            <li>
                                <img ng-src="{{ track.userImage }}" title="{{ track.userName }}" />
                                <h1>{{ track.trackName }} <span>- {{ parseTrackTime(track.duration) }}</span></h1>
                                <h5>{{ track.artistName }}</h5>
                            </li>
                        </ul>
                    </div>
                </div>
                <h5 class="title">YOUR QUEUE</h5>
                <div class="the-queue local">
                    <div class="queued">
                        <ul ng-sortable="{animation:150}">
                            <li ng-repeat="item in localQueue">
                                <img ng-src="{{ item.albumArt }}" title="{{ item.trackName }}"/>
                                <h1>{{ item.trackName }} <span>- {{ parseTrackTime(item.trackLength) }}</span></h1>
                                <h5>{{ item.artistName }}</h5>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="whos-online">
                    <h5>WHO'S ONLINE</h5>
                    <ul>
                        <li ng-repeat="user in onlineUsers" data-userid="{{ user.id }}" data-name="{{ user.name }}">
                            <img ng-src="{{ user.image }}" alt="{{ user.name }}"/>
                            <div class="user-name">{{ user.name || user.id }}</div>
                        </li>
                    </ul>
                </div>
            </aside>

            <aside class="side_search">
                <div class="search">
                    <div class="add-to-queue">
                        <header>
                            <h5>
                                Add to Queue
                                <a href="#" class="saveToList" ng-click="open()"><i class="fa fa-spotify" style="color:#81b71a; font-size: 16px; padding-left: 10px; outline: 0;"></i></a>
                            </h5>
                            <aside>
                                <a href="#" class="volume_toggle"><i class="fa"></i></a>
                            </aside>
                        </header>

                        <form class="spotify_search">
                            <input type="text" name="songID" id="songID" placeholder="Search for track"
                                   autocomplete="off" required>
                        </form>
                    </div>

                    <div class="album-results">
                        <h5>Album Results</h5>
                        <img src="http://yellowdogrecords.com/presskits/cache/fiona/albumart/ydr_1353_cover_125_cw121_ch121_thumb.jpg"/>
                        <img src="http://media.thesexydetectives.com/SexyD-Album-art-125x125.png"/>
                        <img src="http://yellowdogrecords.com/presskits/cache/fiona/albumart/ydr_1353_cover_125_cw121_ch121_thumb.jpg"/>
                        <img src="http://media.thesexydetectives.com/SexyD-Album-art-125x125.png"/>
                    </div>
                    <div class="artist-results">
                        <h5>Artist Results</h5>
                        <ul>
                            <li>JOHNNY CASH</li>
                            <li>JOHN LEGEND</li>
                            <li>JOHNNY MAYER</li>
                        </ul>
                    </div>
                    <div class="song-results">
                        <h5>Song Results</h5>

                        <div style="text-align: center; display: none;" class="search_loading">
                            <i class="fa fa-circle-o-notch fa-spin"
                               style="color: #B45818; font-size: 1.75em; margin-top: 1em;"></i>
                        </div>
                        <div class="results">

                            <ul>
                                <li ng-repeat="track in searchResults">
                                    <a href="#" class="songIWant" id="{{ track.id }}"><i class="fa fa-plus"
                                                                                         style="color:white; font-size: 16px;"></i></a>
                                    <a href="#" class="songIWantToPreview" id="{{ track.id }}"><i class="fa fa-play"
                                                                                                  style="color:white; font-size: 16px;"></i></a>
                                    <img ng-src="{{ track.album.images[2].url }}">

                                    <div>
                                        <h1 title="{{ track.name }}">{{ track.name }}</h1>
                                        <h5>{{ track.artists[0].name }}</h5>
                                        <h5>{{ parseTrackTime(track.duration_ms) }}</h5>
                                    </div>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>
                <div class="side_chat">
                    @include('partials.chat')
                </div>
            </aside>
        </div>


        <footer class="footer">Footer</footer>

        <script type="text/ng-template" id="myModalContent.html">
            <div class="modal-header playlistModal">
                <a ng-if="playlistModalTitle" ng-click="hideResults()" href="#"><i class="fa fa-chevron-left"></i></a>
                <h3 class="modal-title">{{ playlistModalTitle || "Queue Up Some Spotify" }}</h3>
                <i ng-class="loaderToggleClass" class="fa fa-circle-o-notch fa-spin" style="color: #B45818; font-size: 1.25em; padding: 3px;"></i>
            </div>
            <div class="modal-body">
                <ul class="list-group playlistModal" ng-show="playlistModalTitle">
                    <li class="list-group-item playlistTracks" ng-repeat="track in currentPlaylist">
                        <div class="media">
                            <a class="media-left" href="#">
                                <img ng-src="{{ track.track.album.images[2].url }}">
                            </a>

                            <div class="media-body">
                                <div>
                                    <a href="#" class="songIWant" id="{{ track.track.id }}"><i class="fa fa-plus" style="color: #333; font-size: 16px; margin-right: 10px;"></i></a>
                                    <a href="#" class="songIWantToPreview" id="{{ track.track.id }}"><i class="fa fa-play" style="color: #333; font-size: 16px;"></i></a>
                                </div>
                                <h4 class="media-heading">{{ track.track.name }} - {{ parseTrackTime(track.track.duration_ms) }}</h4>
                                <span>{{ track.track.artists[0].name }}</span>
                            </div>
                        </div>
                    </li>
                </ul>
                <ul class="list-group playlistModal" ng-hide="playlistModalTitle">
                    <li class="list-group-item selectPlaylist"
                        ng-repeat="playlist in playlists"
                        data-id="{{ playlist.id }}"
                        data-name="{{ playlist.name }}"
                        ng-click="selectPlaylist(playlist.id, playlist.name)"
                    >
                        <span class="badge">{{ playlist.tracks.total }}</span>
                        {{ playlist.name }} <i class="fa fa-chevron-right"></i>
                    </li>
                </ul>
            </div>
            <div class="modal-footer">
                <button class="btn btn-warning" ng-click="cancel()">Close</button>
            </div>
        </script>

    </div>
@stop

@section('footer')
    <script src="js/index.js"></script>
@stop