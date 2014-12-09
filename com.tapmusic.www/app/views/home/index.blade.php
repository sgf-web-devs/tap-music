@extends('layouts.master')

@section('content')

    <audio id="tap_stream" src="http://173.236.19.90:8027/stream1" autoplay></audio>
    <audio id="preview" src=""></audio>
    [[--<audio src="http://192.168.12.132:8000/stream1" autoplay></audio>--]]

    <div class="wrapper" ng-controller="TapMusicCtrl">
        <header class="header">
            TAP MUSIC (BETA, Invite-Only, Cloud, #ThisHereLifestyle)
            <a href="#" class="volume_toggle"><i class="fa"></i></a>
        </header>
        <div ng-if="currentTrack.albumArt" class="now-playing">
            <img ng-src="{{ currentTrack.albumArt }}">
            <h5>NOW PLAYING</h5>
            <img class="current-song-user" ng-src="{{ currentTrack.userImage }}" title="{{ currentTrack.userName }}" />
            <div class="now-playing-title">{{ currentTrack.trackName }}</div>
            <div class="now-playing-artist"><span>by</span> {{ currentTrack.artistName }}</div>
        </div>
        <aside class="aside aside-1">
             <h5>TAP QUEUE</h5>
            <div class="the-queue">
            <div class="playing-next" ng-repeat-start="track in queueTracks" ng-if="$first">
                <div class="next">NEXT</div>
                <img ng-src="{{ track.userImage }}" title="{{ track.userName }}" />
                <h1>{{ track.trackName }}</h1>
                <h5>{{ track.artistName }}</h5>
            </div>
            <div style="clear:both"></div>
            <div class="queued" ng-repeat-end ng-if="!$first">
                <ul>
                    <li>
                        <img ng-src="{{ track.userImage }}" title="{{ track.userName }}" />
                        <h1>{{ track.trackName }}</h1>
                        <h5>{{ track.artistName }}</h5>
                    </li>
                </ul>
            </div>
            </div>
           <div class="whos-online">
               <h5>WHO'S ONLINE</h5>
               <ul>
                   <li ng-repeat="user in onlineUsers" data-userid="{{ user.id }}" data-name="{{ user.name }}">
                       <img ng-src="{{ user.image }}" alt="{{ user.name }}"/>
                       <div class="user-name">{{ user.name }}</div>
                   </li>
               </ul>
           </div>
        </aside>
        <aside class="aside aside-2">
            <div class="add-to-queue">
                <h5>Add to Queue</h5>
                <form class="spotify_search">
                    <input type="text" name="songID" id="songID" placeholder="Search for track" autocomplete="off" required>
                </form>
            </div>

            <div class="album-results">
                <h5>Album Results</h5>
                <img src="http://yellowdogrecords.com/presskits/cache/fiona/albumart/ydr_1353_cover_125_cw121_ch121_thumb.jpg" />
                <img src="http://media.thesexydetectives.com/SexyD-Album-art-125x125.png" />
                <img src="http://yellowdogrecords.com/presskits/cache/fiona/albumart/ydr_1353_cover_125_cw121_ch121_thumb.jpg" />
                <img src="http://media.thesexydetectives.com/SexyD-Album-art-125x125.png" />
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
                    <i class="fa fa-circle-o-notch fa-spin" style="color: #B45818; font-size: 1.75em; margin-top: 1em;"></i>
                </div>
                <div class="results">

                    <ul>
                        <li ng-repeat="track in searchResults">
                            <a href="#" class="songIWant" id="{{ track.id }}"><i class="fa fa-plus" style="color:white; font-size: 16px;"></i></a>
                            <a href="#" class="songIWantToPreview" id="{{ track.id }}"><i class="fa fa-play" style="color:white; font-size: 16px;"></i></a>
                            <img ng-src="{{ track.album.images[2].url }}">
                            <div>
                            <h1 title="{{ track.name }}">{{ track.name }}</h1>
                            <h5>{{ track.artists[0].name }}</h5>
                            <a href="#" class="saveToList" ng-click="open()"><i class="fa fa-floppy-o" style="color:white; font-size: 16px; padding-left: 10px;"></i></a>
                            </div>
                        </li>
                    </ul>

                </div>
            </div>
        </aside>
        <footer class="footer">Footer</footer>

        <script type="text/ng-template" id="myModalContent.html">
            <div class="modal-header">
                <h3 class="modal-title">Save To Playlist</h3>
            </div>
            <div class="modal-body">
                <ul>
                    <li ng-repeat="item in items">
                        <a ng-click="selected.item = item">{{ item }}</a>
                    </li>
                </ul>
                Selected: <b>{{ selected.item }}</b>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" ng-click="ok()">OK</button>
                <button class="btn btn-warning" ng-click="cancel()">Cancel</button>
            </div>
        </script>
    </div>
@stop

@section('header')
    <script src="js/index.js"></script>
@stop