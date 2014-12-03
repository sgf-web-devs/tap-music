(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
// Requires... nothing here yet :)
//var dropdown = require('./dropdown');


    var tapmusicApp = angular.module('tapmusicApp', []);

    tapmusicApp.controller('TapMusicCtrl', function ($scope, $http) {
        $scope.songs = [
            {'song': 'Lifestyle', 'artist': 'Rich Gang'},
            {'song': 'Hey Ya!', 'artist': 'Outkast'},
            {'song': 'Dear Momma', 'artist': '2Pac'},
            {'song': 'Drunk in Love', 'artist': 'Beyonce'}

        ];

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
    });

    tapmusicApp.controller('QueueController', function ($scope, $http) {

        var pusher = new Pusher('bb58ca596665e104e52a', {authEndpoint: '/auth'});
        var channel = pusher.subscribe('presence-tapmusic');

        channel.bind('pusher:subscription_succeeded', function () {
            var me = channel.members.me;
            console.log(me);
            console.log(channel.members.count);
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

        function updateQueue()
        {
            $http.get('/queue/songs').
                success(function(data, status, headers, config) {
                    console.log(data);
                    $scope.queueTracks = data;
                }).
                error(function(data, status, headers, config) {
                    // called asynchronously if an error occurs
                    // or server returns response with an error status.
                });
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

        // jQuery nastiness surely there is some ng directive we can be using here
        // but the controllers needs cleaned up first so we can scope the dom better
        jQuery(function ($) {
            $('.spotify_search').on('submit', function()
            {
                return false;
            });
        });
    });






},{}]},{},[1]);
