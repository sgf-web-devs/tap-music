// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());


/*
 *	TypeWatch 2.2.1
 *
 *	Examples/Docs: github.com/dennyferra/TypeWatch
 *
 *  Copyright(c) 2014
 *	Denny Ferrassoli - dennyferra.com
 *   Charles Christolini
 *
 *  Dual licensed under the MIT and GPL licenses:
 *  http://www.opensource.org/licenses/mit-license.php
 *  http://www.gnu.org/licenses/gpl.html
 */

!function(root, factory) {
    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        factory(require('jquery'));
    } else {
        factory(root.jQuery);
    }
}(this, function($) {
    'use strict';
    $.fn.typeWatch = function(o) {
        // The default input types that are supported
        var _supportedInputTypes =
            ['TEXT', 'TEXTAREA', 'PASSWORD', 'TEL', 'SEARCH', 'URL', 'EMAIL', 'DATETIME', 'DATE', 'MONTH', 'WEEK', 'TIME', 'DATETIME-LOCAL', 'NUMBER', 'RANGE'];

        // Options
        var options = $.extend({
            wait: 750,
            callback: function() { },
            highlight: true,
            captureLength: 2,
            inputTypes: _supportedInputTypes
        }, o);

        function checkElement(timer, override) {
            var value = $(timer.el).val();

            // Fire if text >= options.captureLength AND text != saved text OR if override AND text >= options.captureLength
            if ((value.length >= options.captureLength && value.toUpperCase() != timer.text)
                || (override && value.length >= options.captureLength))
            {
                timer.text = value.toUpperCase();
                timer.cb.call(timer.el, value);
            }
        };

        function watchElement(elem) {
            var elementType = elem.type.toUpperCase();
            if ($.inArray(elementType, options.inputTypes) >= 0) {

                // Allocate timer element
                var timer = {
                    timer: null,
                    text: $(elem).val().toUpperCase(),
                    cb: options.callback,
                    el: elem,
                    wait: options.wait
                };

                // Set focus action (highlight)
                if (options.highlight) {
                    $(elem).focus(
                        function() {
                            this.select();
                        });
                }

                // Key watcher / clear and reset the timer
                var startWatch = function(evt) {
                    var timerWait = timer.wait;
                    var overrideBool = false;
                    var evtElementType = this.type.toUpperCase();

                    // If enter key is pressed and not a TEXTAREA and matched inputTypes
                    if (typeof evt.keyCode != 'undefined' && evt.keyCode == 13 && evtElementType != 'TEXTAREA' && $.inArray(evtElementType, options.inputTypes) >= 0) {
                        timerWait = 1;
                        overrideBool = true;
                    }

                    var timerCallbackFx = function() {
                        checkElement(timer, overrideBool)
                    }

                    // Clear timer
                    clearTimeout(timer.timer);
                    timer.timer = setTimeout(timerCallbackFx, timerWait);
                };

                $(elem).on('keydown paste cut input', startWatch);
            }
        };

        // Watch Each Element
        return this.each(function() {
            watchElement(this);
        });

    };
});