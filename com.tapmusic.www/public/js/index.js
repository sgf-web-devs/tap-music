// Requires... nothing here yet :)
//var dropdown = require('./dropdown');

    var tapmusicApp = angular.module('tapmusicApp', ['ui.bootstrap']);

    tapmusicApp.controller('TapMusicCtrl', function ($scope, $http, $interval, $modal) {

        var $appTitle = jQuery('#app-title'),
            pusher = new Pusher(pusherConf.publicKey, {authEndpoint: '/auth/login'}),
            channel = pusher.subscribe(pusherConf.presenceChannel),
            defaultAppTitle = $appTitle.html(),
            progressInterval;

        $scope.currentTrack = {
            trackName: '',
            artistName: '',
            albumArt: ''
        };

        $scope.progressData = {
            percent: 0,
            time_since: 0,
            duration: 0
        };

        $scope.playlists = [];


        $scope.open = function (size) {

            $http.get('/spotify/playlists').
                success(function (data, status, headers, config) {

                    //$scope.playlists = data;
                    var modalInstance = $modal.open({
                        templateUrl: 'myModalContent.html',
                        controller: 'ModalInstanceCtrl',
                        size: size,
                        resolve: {
                            playlists: function () {
                                return data;
                            }
                        }
                    });

                    modalInstance.result.then(function (selectedItem) {
                        $scope.selected = selectedItem;
                    }, function () {
                        //$log.info('Modal dismissed at: ' + new Date());
                    });
                }).
                error(function (data, status, headers, config) {
                }
            );
        };

        $scope.parseTrackTime = function(duration) {
            return moment(parseInt(duration)).format("m:ss");
        }

        $scope.onlineUsers = [];

        //var moment = moment();
        //console.log(moment.duration(1000));

        channel.bind('pusher:subscription_succeeded', function () {
            var me = channel.members.me;
            $scope.$apply(function () {
                $scope.onlineUsers = _.toArray(channel.members.members);
            });

            console.log(me);
            console.log(channel.members.members);

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

        updateQueue();

        $scope.queueTracks = [];

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
                var parent = $(this).closest('li');
                $('h1, h5, h4, span, img', parent).animateCSS('bounce');

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

            $('.mobile_tabs a').on('click', function(){
                $('.now-playing, .side_queue, .side_search').hide();
                $('.' + $(this).data('target')).show();
                $('.mobile_tabs a').removeClass('is_active');
                $(this).addClass('is_active');
                console.log($(this).data('target') == 'now-playing');
                if ($(this).data('target') == 'now-playing') {
                    $('#tap_stream').removeClass('is_hidden');
                } else {
                    $('#tap_stream').addClass('is_hidden');
                }
            });

        });

        $scope.stopProgressInterval = function() {
            if (angular.isDefined(progressInterval)) {
                $interval.cancel(progressInterval);
                progressInterval = undefined;
            }
        };

        $scope.$on('$destroy', function() {
            $scope.stopProgressInterval();
        });

        function updateProgressBar () {

            console.log('update progress');

            var track = $scope.currentTrack,
                start_time = track.start_time,
                duration = track.duration / 1000;

            if (angular.isDefined(progressInterval))
                return;

            progressInterval = $interval(function(){
                var time_since,
                    now = (new Date().getTime()) / 1000,
                    percent;

                // Reset counter if song is last song in queue
                if ($scope.progressData.percent >= 100) {
                    $scope.progressData = {
                        percent: 0,
                        time_since: 0,
                        duration: 0
                    };

                    start_time = now;
                }

                time_since = now - start_time;
                percent = (time_since / duration) * 100;

                $scope.progressData = {
                    percent: percent,
                    time_since: time_since,
                    duration: duration
                };

            }, 1000);
        }

        function updatePageTitle (track) {
            var newTitle = '';
            if (typeof track !== 'undefined') {
                newTitle = track.trackName + ' - ' + track.artistName + ' | ' + defaultAppTitle;
                $appTitle.html(newTitle);
            }
        }

        function searchTracks(searchPhrase)
        {
            $http.get('/spotify/search', { params: { search: searchPhrase } }).
                success(function(data, status, headers, config) {
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
                    console.log('Queue Updated', data);
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
            console.log('Now Playing', track);
            $scope.currentTrack = track;
            updatePageTitle(track);
            updateProgressBar();
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

    tapmusicApp.directive('progressBar', function ($parse, $window) {
        return {
            restrict: 'EA',
            template: '<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>',
            link: function (scope, elem, attrs) {
                var progressBarData = $parse(attrs.progressData)(scope),
                    progressBar = jQuery(elem[0].children[0]);

                scope.$watchCollection('progressData', function(newData, oldData){
                    progressBarData = newData;
                    draw();
                });

                function draw () {
                    progressBar.attr('aria-valuenow', progressBarData.percent);
                    progressBar.css('width', progressBarData.percent + '%');
                }

                draw();

                elem.on('$destroy', function() {
                    $interval.cancel(progressInterval);
                });
            }
        }
    });


tapmusicApp.controller('ModalInstanceCtrl', function ($scope, $modalInstance, playlists, $http) {

    $scope.playlistModalTitle = '';
    $scope.playlists = playlists;
    $scope.currentPlaylist = [];
    $scope.loaderToggleClass = 'hide';

    $scope.ok = function () {
        //$modalInstance.close($scope.selected.item);
    };

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };

    $scope.hideResults = function () {
        $scope.playlistModalTitle = '';
    };

    $scope.parseTrackTime = function (duration) {
        return moment(parseInt(duration)).format("m:ss");
    }

    $scope.selectPlaylist = function(id, name) {
        $scope.loaderToggleClass = '';

        $http.get('/spotify/playlist', { params: {playlistID: id } }).
            success(function (data, status, headers, config) {
                $scope.loaderToggleClass = 'hide';
                $scope.playlistModalTitle = name;
                $scope.currentPlaylist = data;
                jQuery('.modal').animate({scrollTop: 0}, 'fast');
            }).
            error(function (data, status, headers, config) {
            }
        );
    };

});