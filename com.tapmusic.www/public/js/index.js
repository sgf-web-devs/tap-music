// Requires... nothing here yet :)
//var dropdown = require('./dropdown');


    var tapmusicApp = angular.module('tapmusicApp', []);

    tapmusicApp.controller('TapMusicCtrl', function ($scope, $http) {

        var pusher = new Pusher('bb58ca596665e104e52a', {authEndpoint: '/auth/login'}),
            channel = pusher.subscribe('presence-tapmusic1');

        $scope.currentTrack = {
            trackName: '',
            artistName: '',
            albumArt: ''
        };

        $scope.onlineUsers = [];

        channel.bind('pusher:subscription_succeeded', function () {
            var me = channel.members.me;
            $scope.$apply(function () {
                $scope.onlineUsers = _.toArray(channel.members.members);
            });

            console.log(me);
            console.log(channel.members.members);
            //console.log(_.toArray(channel.members.members));

            // Set user in online_users table
            //$http.get('/pusher/subscription-succeeded', { params: { userID : me.id } }).
            //    success(function(data, status, headers, config) {
            //        console.log(data);
            //    }).
            //    error(function(data, status, headers, config) {}
            //);

            if(channel.members.count == 1)
            {
                $http.get('/queue/play-first-song').
                    success(function(data, status, headers, config) {
                        console.log('Welcome, first visitor.  Enjoy some tunes.');
                    }).
                    error(function(data, status, headers, config) {}
                );
            }
        });

        channel.bind('pusher:member_removed', function(member) {
            console.log(member);
            $http.get('/pusher/member-removed', { params: { userID : member.id } }).
                success(function(data, status, headers, config) {
                    console.log(data);
                }).
                error(function(data, status, headers, config) {}
            );
        });

        channel.bind('pusher:member_added', function (member) {
            // for example:
            $scope.$apply(function () {
                $scope.onlineUsers = _.toArray(channel.members.members);
            });
        });

        channel.bind('pusher:member_removed', function (member) {
            // for example:
            $scope.$apply(function () {
                $scope.onlineUsers = _.toArray(channel.members.members);
            });
        });

        channel.bind('songAddedToQueue', function (data) {
            updateQueue();
        });

        channel.bind('queueCleared', function (data) {
            updateQueue();
        });

        channel.bind('nextSong', function (data) {
            updateQueue();
        });

        $scope.queueTracks = [];

        updateQueue();

        jQuery(function ($) {
            var options = {
                callback: function (value) { searchTracks($(this).val()); },
                wait: 500,
                highlight: true
            }

            $('.spotify_search #songID').typeWatch( options );

            $('.spotify_search #songID').on('input', function () {
                if($(this).val())
                {
                    $('.search_loading, .song-results .results').show();
                    $('.song-results .results').hide();
                }else{
                    $('.search_loading').hide();
                    $('.song-results .results').show();
                }
            });

            $('body').on('click', '.songIWant',function(){
                var songID = $(this).attr('id');

                $http.post('/queue/add-song', { songID: songID }).
                    success(function (data, status, headers, config) {
                        console.log('awesome, your song was added');
                    }).
                    error(function (data, status, headers, config) {
                        console.log('error');
                        // called asynchronously if an error occurs
                        // or server returns response with an error status.
                    });
                    return false;

            });


            $('body').on('click', '.songIWantToPreview',function(){

                var current = $(this);
                var songID = $(this).attr('id');
                var previewUrl = "";
                var preview = $(document.getElementById('preview'));
                var audio = document.getElementById('tap_stream');

                preview.bind('ended', function(){
                    audio.muted = false;
                    $('.songIWantToPreview').removeClass('is_active');
                    $('.songIWantToPreview i').removeClass('fa-stop').addClass('fa-play');
                });


                if(!current.hasClass("is_active")) { // Playing an initial/follow-up preview

                    $('.songIWantToPreview').removeClass('is_active');
                    $('.songIWantToPreview i').removeClass('fa-stop').addClass('fa-play');
                    current.addClass('is_active');
                    $('i', current).removeClass('fa-play').addClass('fa-stop');

                    $http.get('/spotify/preview', { params: { songID: songID } }).

                        success(function(data, status, headers, config) {

                            previewUrl = String(data);
                            preview.attr('src', previewUrl);
                            audio.muted = true;

                            preview[0].play();
                        }).

                        error(function(data, status, headers, config) {
                            // Welp, see ya later.
                        });
                } else{ // Stopping the preview
                    preview.attr('src', "");
                    audio.muted = false;
                    $('.songIWantToPreview').removeClass('is_active');
                    $('.songIWantToPreview i').removeClass('fa-stop').addClass('fa-play');
                }

                return false;
            });



            $('.spotify_search').on('submit', function()
            {
                return false;
            });

            $('.volume_toggle').on('click', function () {
                $(this).toggleClass('mute');
                var audio = document.getElementById('tap_stream');
                audio.muted = !audio.muted;
                return false;
            });

        });

        function searchTracks(searchPhrase)
        {
            $http.get('/spotify/search', { params: { search: searchPhrase } }).
                success(function(data, status, headers, config) {
                    console.log(data.tracks.items);
                    $('.search_loading').hide();
                    $('.song-results .results').show();
                    $scope.searchResults = data.tracks.items;
                }).
                error(function(data, status, headers, config) {
                    // called asynchronously if an error occurs
                    // or server returns response with an error status.
                });
        }

        function updateQueue()
        {
            $http.get('/queue/songs').
                success(function(data, status, headers, config) {
                    console.log(data);
                    $scope.queueTracks = _.rest(data, 1);
                    updateNowPlaying(_.first(data));
                }).
                error(function(data, status, headers, config) {
                    // called asynchronously if an error occurs
                    // or server returns response with an error status.
                });
        }

        function updateNowPlaying(track)
        {
            $scope.currentTrack = track;
        }

        function addSongToQueue(songID)
        {
            $http.post('/queue/add-song', { songID: songID }).
                success(function (data, status, headers, config) {
                    console.log('awesome, your song was added');
                }).
                error(function (data, status, headers, config) {
                    console.log('error');
                    // called asynchronously if an error occurs
                    // or server returns response with an error status.
                });
        }
    });