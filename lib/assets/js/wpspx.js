// Stopwatch
(function ($) {

    $.extend({

        APP: {

            formatTimer: function (a) {
                if (a < 10) {
                    a = '0' + a;
                }
                return a;
            },

            startTimer: function (dir) {

                var a;

                // save type
                $.APP.dir = dir;

                // get current date
                $.APP.d1 = new Date();

                switch ($.APP.state) {

                    case 'pause':

                        // resume timer
                        // get current timestamp (for calculations) and
                        // substract time difference between pause and now
                        $.APP.t1 = $.APP.d1.getTime() - $.APP.td;

                        break;

                    default:

                        // get current timestamp (for calculations)
                        $.APP.t1 = $.APP.d1.getTime();

                        // if countdown add ms based on seconds in textfield
                        if ($.APP.dir === 'cd') {
                            $.APP.t1 += parseInt($('#cd_seconds').val()) * 1000;
                        }

                        break;

                }

                // reset state
                $.APP.state = 'alive';
                $('#' + $.APP.dir + '_status').html('Running');

                // start loop
                $.APP.loopTimer();

            },

            pauseTimer: function () {

                // save timestamp of pause
                $.APP.dp = new Date();
                $.APP.tp = $.APP.dp.getTime();

                // save elapsed time (until pause)
                $.APP.td = $.APP.tp - $.APP.t1;

                // change button value
                $('#' + $.APP.dir + '_start').val('Resume');

                // set state
                $.APP.state = 'pause';
                $('#' + $.APP.dir + '_status').html('Paused');

            },

            stopTimer: function () {

                // change button value
                $('#' + $.APP.dir + '_start').val('Restart');

                // set state
                $.APP.state = 'stop';
                $('#' + $.APP.dir + '_status').html('Stopped');

            },

            resetTimer: function () {

                // reset display
                $('#' + $.APP.dir + '_ms,#' + $.APP.dir + '_s,#' + $.APP.dir + '_m,#' + $.APP.dir + '_h').html('00');

                // change button value
                $('#' + $.APP.dir + '_start').val('Start');

                // set state
                $.APP.state = 'reset';
                $('#' + $.APP.dir + '_status').html('Reset & Idle again');

            },

            endTimer: function (callback) {

                // change button value
                $('#' + $.APP.dir + '_start').val('Restart');

                // set state
                $.APP.state = 'end';

                // invoke callback
                if (typeof callback === 'function') {
                    callback();
                }

            },

            loopTimer: function () {

                var td;
                var d2, t2;

                var ms = 0;
                var s = 0;
                var m = 0;
                var h = 0;

                if ($.APP.state === 'alive') {

                    // get current date and convert it into
                    // timestamp for calculations
                    d2 = new Date();
                    t2 = d2.getTime();

                    // calculate time difference between
                    // initial and current timestamp
                    if ($.APP.dir === 'sw') {
                        td = t2 - $.APP.t1;
                        // reversed if countdown
                    } else {
                        td = $.APP.t1 - t2;
                        if (td <= 0) {
                            // if time difference is 0 end countdown
                            $.APP.endTimer(function () {
                                $.APP.resetTimer();
                                $('#' + $.APP.dir + '_status').html('Ended & Reset');
                            });
                        }
                    }

                    // calculate milliseconds
                    ms = td % 1000;
                    if (ms < 1) {
                        ms = 0;
                    } else {
                        // calculate seconds
                        s = (td - ms) / 1000;
                        if (s < 1) {
                            s = 0;
                        } else {
                            // calculate minutes
                            var m = (s - (s % 60)) / 60;
                            if (m < 1) {
                                m = 0;
                            } else {
                                // calculate hours
                                var h = (m - (m % 60)) / 60;
                                if (h < 1) {
                                    h = 0;
                                }
                            }
                        }
                    }

                    // substract elapsed minutes & hours
                    ms = Math.round(ms / 100);
                    s = s - (m * 60);
                    m = m - (h * 60);

                    // update display
                    $('#' + $.APP.dir + '_ms').html($.APP.formatTimer(ms));
                    $('#' + $.APP.dir + '_s').html($.APP.formatTimer(s));
                    $('#' + $.APP.dir + '_m').html($.APP.formatTimer(m));
                    $('#' + $.APP.dir + '_h').html($.APP.formatTimer(h));

                    // loop
                    $.APP.t = setTimeout($.APP.loopTimer, 1);

                } else {

                    // kill loop
                    clearTimeout($.APP.t);
                    return true;

                }

            }

        }

    });

    $('#sw_start').on('click', function () {
        $.APP.startTimer('sw');
    });

    $('#cd_start').on('click', function () {
        $.APP.startTimer('cd');
    });

    $('#sw_stop,#cd_stop').on('click', function () {
        $.APP.stopTimer();
    });

    $('#sw_reset,#cd_reset').on('click', function () {
        $.APP.resetTimer();
    });

    $('#sw_pause,#cd_pause').on('click', function () {
        $.APP.pauseTimer();
    });

})(jQuery);

(function ($) {

    $('#publishshows').on("click", function () {
        $('.loading').addClass('show');
    });

    var rangeSlider = function () {
        var slider = $('.range-slider'),
            range = $('.range-slider__range'),
            value = $('.range-slider__value');

        slider.each(function () {

            value.each(function () {
                var value = $(this).prev().attr('value');
                $(this).html(value);
            });

            range.on('input', function () {
                $(this).next(value).html(this.value);
            });
        });
    };

    rangeSlider();

    // $('#cache_selected_data').on('click', function (e) {
    //     e.preventDefault();
    //     var arr = ["venues", "ticket-types", "tag-groups", "stock-items", "statements", "plans", "funds", "bands", "memberships", "events", "instances",];
    //     var count = 0;
    //     var arrcount = arr.length;
    //     var cache_url = $("input[name='cache_url']").val();
    //     var fetch_url = $("input[name='fetch_url']").val();
    //     var spektrix_url = $("input[name='spektrix_url']").val();

    //     $('.done').addClass('show');
    //     $.APP.startTimer('sw');

    //     $.each(arr, function (i, val) {
    //         var cacheditem = '<li class="' + val + '"></div><span>' + val.replace(/\-/g, '') + '</span><svg class="loadinggif" style="background: none; shape-rendering: auto;" width="20px" height="20px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><g transform="translate(50,50)"><g transform="scale(0.7)"><circle cx="0" cy="0" r="50" fill="#808080"></circle><circle cx="0" cy="-28" r="15" fill="#ffffff" transform="rotate(95.7053)"><animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 0 0;360 0 0"></animateTransform></circle></g></g></svg><svg class="donetick" enable-background="new 0 0 515.556 515.556" height="512" viewBox="0 0 515.556 515.556" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m0 274.226 176.549 176.886 339.007-338.672-48.67-47.997-290.337 290-128.553-128.552z"/></svg></li>';
    //         $('.done ul').append(cacheditem);

    //         $.ajax({
    //             type: "GET",
    //             url: fetch_url + '/?resource=' + val,
    //             cache: false,
    //             dataType: "json",
    //             beforeSend: function () {
    //                 $('.done ul li.' + val + ':after').css('width:36px');
    //             },
    //             success: function (json) {
    //                 setTimeout(function () {
    //                     $('.done ul li.' + val + ':after').css('width:64px');
    //                 }, 3000);
    //                 console.log("touched " + fetch_url + '/?resource=' + val);
    //             },
    //             error: function (xhr, ajaxOptions, thrownError) {
    //                 console.log(xhr.responseText);
    //                 console.log(thrownError);
    //             },
    //         }).done(function (data) {
    //             $('.done ul li.' + val).addClass('complete');
    //             count++;
    //             if (count == arrcount) {
    //                 $.APP.stopTimer();
    //             }
    //         });
    //     });

    // });

    wpspx_cache = () => {
        console.log('clicked cache');
        const arr = ["venues", "ticket-types", "tag-groups", "stock-items", "statements", "plans", "funds", "bands", "memberships", "events", "instances",];
        const arrcount = arr.length;
        const cache_url = $("input[name='cache_url']").val();
        const fetch_url = $("input[name='fetch_url']").val();
        const spektrix_url = $("input[name='spektrix_url']").val();
        let count = 0;

        console.log(arr);
        console.log(arrcount);
        console.log(cache_url);
        console.log(fetch_url);
        console.log(spektrix_url);

        $('.done').addClass('show');
        $.APP.startTimer('sw');

        $.each(arr, function (i, val) {
            var cacheditem = '<li class="' + val + '"></div><span>' + val.replace(/\-/g, '') + '</span><svg class="loadinggif" style="background: none; shape-rendering: auto;" width="20px" height="20px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><g transform="translate(50,50)"><g transform="scale(0.7)"><circle cx="0" cy="0" r="50" fill="#808080"></circle><circle cx="0" cy="-28" r="15" fill="#ffffff" transform="rotate(95.7053)"><animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 0 0;360 0 0"></animateTransform></circle></g></g></svg><svg class="donetick" enable-background="new 0 0 515.556 515.556" height="512" viewBox="0 0 515.556 515.556" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m0 274.226 176.549 176.886 339.007-338.672-48.67-47.997-290.337 290-128.553-128.552z"/></svg></li>';
            $('.done ul').append(cacheditem);

            $.ajax({
                type: "POST",
                url: fetch_url + '/?resource=' + val,
                cache: false,
                beforeSend: function () {
                    $('.done ul li.' + val + ':after').css('width:36px');
                },
                success: function (json) {
                    setTimeout(function () {
                        $('.done ul li.' + val + ':after').css('width:64px');
                    }, 3000);
                    console.log("touched " + fetch_url + '/?resource=' + val);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.responseText);
                    console.log(thrownError);
                },
            }).done(function (data) {
                $('.done ul li.' + val).addClass('complete');
                count++;
                if (count == arrcount) {
                    $.APP.stopTimer();
                }
            });
        });
    }

})(jQuery);
