/**
 * Open Source Social Network
 *
 * @package   (softlab24.com).ossn
 * @author    OSSN Core Team <info@softlab24.com>
 * @copyright 2014-2017 SOFTLAB24 LIMITED
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
Ossn.RegisterStartupFunction(function() {
    $(document).ready(function() {
        var cYear = (new Date).getFullYear();
        var alldays = Ossn.Print('datepicker:days');
        var shortdays = alldays.split(",");
        var allmonths = Ossn.Print('datepicker:months');
        var shortmonths = allmonths.split(",");

        var datepick_args = {
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd/mm/yy',
            yearRange: '1950:' + cYear,
        };

        if (Ossn.isLangString('datepicker:days')) {
            datepick_args['dayNamesMin'] = shortdays;
        }
        if (Ossn.isLangString('datepicker:months')) {
            datepick_args['monthNamesShort'] = shortmonths;
        }
        $("input[name='birthdate']").datepicker(datepick_args);

        /**
         * Reposition cover
         */
        $('#reposition-cover').click(function() {
            $('#profile-menu').hide();
            $('#cover-menu').show();
            $(function() {
                $.globalVars = {
                    originalTop: 0,
                    originalLeft: 0,
                    maxHeight: $("#draggable").height() - $("#container").height(),
                    maxWidth: $("#draggable").width() - $("#container").width()
                };
                $("#draggable").draggable({
                    start: function(event, ui) {
                        if (ui.position != undefined) {
                            $.globalVars.originalTop = ui.position.top;
                            $.globalVars.originalLeft = ui.position.left;
                        }
                    },
                    drag: function(event, ui) {
                        var newTop = ui.position.top;
                        var newLeft = ui.position.left;
                        if (ui.position.top < 0 && ui.position.top * -1 > $.globalVars.maxHeight) {
                            newTop = $.globalVars.maxHeight * -1;
                        }
                        if (ui.position.top > 0) {
                            newTop = 0;
                        }
                        if (ui.position.left < 0 && ui.position.left * -1 > $.globalVars.maxWidth) {
                            newLeft = $.globalVars.maxWidth * -1;
                        }
                        if (ui.position.left > 0) {
                            newLeft = 0;
                        }
                        ui.position.top = newTop;
                        ui.position.left = newLeft;

                        Ossn.ProfileCover_top = newTop;
                        Ossn.ProfileCover_left = newLeft;
                    }
                });
            });
        });
        $("#upload-photo").submit(function(event) {
            event.preventDefault();
            var formData = new FormData($(this)[0]);
            var $url = Ossn.site_url + 'action/profile/photo/upload';
            $.ajax({
                url: Ossn.AddTokenToUrl($url),
                type: 'POST',
                data: formData,
                async: true,
                beforeSend: function() {
                    $('.upload-photo').attr('class', 'user-photo-uploading');
                },
                error: function(xhr, status, error) {
                    if (error == 'Internal Server Error' || error !== '') {
                        Ossn.MessageBox('syserror/unknown');
                    }
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(callback) {
                    $time = $.now();
                    $('.user-photo-uploading').attr('class', 'upload-photo').hide();
                    $imageurl = $('.profile-photo').find('img').attr('src') + '?' + $time;
                    $('.profile-photo').find('img').attr('src', $imageurl);
                    $topbar_icon_url = $('.ossn-topbar-menu').find('img').attr('src') + '?' + $time;
                    $('.ossn-topbar-menu').find('img').attr('src', $topbar_icon_url);
                }
            });

            return false;
        });

        $("#upload-cover").submit(function(event) {
            event.preventDefault();
            var formData = new FormData($(this)[0]);
            var $url = Ossn.site_url + 'action/profile/cover/upload';
            $.ajax({
                url: Ossn.AddTokenToUrl($url),
                type: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function(xhr, obj) {
                    $('.profile-cover-img').attr('class', 'user-cover-uploading');

                    var fileInput = $('#upload-cover').find("input[type=file]")[0],
                        file = fileInput.files && fileInput.files[0];

                    if (file) {
                        var img = new Image();

                        img.src = window.URL.createObjectURL(file);

                        img.onload = function() {
                            var width = img.naturalWidth,
                                height = img.naturalHeight;

                            window.URL.revokeObjectURL(img.src);
                            if (width < 850 || height < 300) {
                                xhr.abort();
                                Ossn.trigger_message(Ossn.Print('profile:cover:err1:detail'), 'error');
                                return false;
                            }
                        };
                    }
                },
                success: function(callback) {
                    $time = $.now();
                    $('.profile-cover').find('img').removeClass('user-cover-uploading');
                    $imageurl = $('.profile-cover').find('img').attr('src') + '?' + $time;
                    $('.profile-cover').find('img').attr('src', $imageurl);
                    $('.profile-cover').find('img').attr('style', '');
                },
            });
            return false;
        });

        /* Profile extra menu */
        $('#profile-extra-menu').on('click', function() {
            $div = $('.ossn-profile-extra-menu').find('div');
            if ($div.is(":not(:visible)")) {
                $div.show();
            } else {
                $div.hide();
            }
        });
    });

});

Ossn.repositionCOVER = function() {
    var $pcover_top = $('.profile-cover-img').css('top');
    var $pcover_left = $('.profile-cover-img').css('left');
    $url = Ossn.site_url + "action/profile/cover/reposition";
    $.ajax({
        async: true,
        type: 'post',
        data: '&top=' + $pcover_top + '&left=' + $pcover_left,
        url: Ossn.AddTokenToUrl($url),
        success: function(callback) {
            $('#profile-menu').show();
            $('#cover-menu').hide();
            $("#draggable").draggable({
                drag: function() {
                    return false;
                }
            });
        },
    });
};
