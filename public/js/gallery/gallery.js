/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    tid = null;
    $('#control-modal').modal('show');
    $.ajaxSetup({cache: false});

    $('#control-modal').on('shown.bs.modal', function () {
        $('#control-modal #loading').css({
            'height': $('#control-modal #li-foldertree').outerHeight(),
            'width': $('#control-modal #li-foldertree').outerWidth()
        });
    });

    $(window).resize(function () {
        recalibrate();
    });

    $(document).on('click', ".switcher", function () {
        if ($(this).html() === '[+]') {
            $('#control-modal #loading').fadeIn();
            $(this).html('[-]');
            loadfolders($(this).parent().find('.folder-checkbox').attr('data-id'));
        } else {
            $(this).html('[+]');
            $(this).parent().find('ul').remove();
        }
    });

    $(document).on('click', '#li-foldertree', function () {
        if ($('#li-foldertree input[type=checkbox]:checked').length) {
            $('#go').html('Start Gallery');
            $('#go').prop('disabled', false);
        } else {
            $('#go').html('Choose some folders first');
            $('#go').prop('disabled', true);
        }
    })

    $(document).on('click', '.folder-checkbox', function () {
        if ($(this).is(':checked')) {
            var traverse = true;

            $(this).parent().find('.folder input[type=checkbox]').each(function () {
                $(this).click();
            });

            $(this).parent().parent().find('input[type=checkbox]').each(function () {
                if ($(this).not(':checked').length) {
                    traverse = false
                }
            });

            if (traverse) {
                $(this).parent().parent().parent().children('.folder-checkbox').prop('checked', true);
            }

        } else {
            var traverse = true;
            $(this).parent().find('.folder input[type=checkbox]').each(function () {
                if ($(this).not(':checked').length) {
                    traverse = false
                }
            });

            if (traverse) {
                $(this).parent().find('.folder input[type=checkbox]').each(function () {
                    $(this).click();
                });
            }

            if ($(this).parent().parent().parent().children('.folder-checkbox').is(':checked')) {
                $(this).parent().parent().parent().children('.folder-checkbox').click();
            }
        }
    });

    $(document).on('click', '#go', function () {
        start();
    });

    recalibrate();

    loadfolders('/');
});

function fullscreen() {
    if (
            document.fullscreenEnabled ||
            document.webkitFullscreenEnabled ||
            document.mozFullScreenEnabled ||
            document.msFullscreenEnabled
            ) {
        var i = document.getElementById("body");

        // go full-screen
        if (i.requestFullscreen) {
            i.requestFullscreen();
        } else if (i.webkitRequestFullscreen) {
            i.webkitRequestFullscreen();
        } else if (i.mozRequestFullScreen) {
            i.mozRequestFullScreen();
        } else if (i.msRequestFullscreen) {
            i.msRequestFullscreen();
        }
    }
}

function start() {
    $.each($("input[name='folders[]']:checked"), function() {
        Gallery.paths.push( $(this).attr('data-id') );
    });
    
    $.getJSON(Gallery.nextimage + getPaths(), function (data) {
        if(data.status == 'error') returnHome();
        Gallery.images.center.url = data.image;
        Gallery.images.center.hash = data.hash;
        $('#centerlayer .showimage').attr('src', data.image);
        $.getJSON(Gallery.nextimage + getPaths() + '&current' + data.hash, function (data) {
            if(data.status == 'error') returnHome();
            Gallery.images.right.url = data.image;
            Gallery.images.right.hash = data.hash;
            $('#rightlayer .showimage').attr('src', data.image);
            tid = setTimeout(startCounter, 25000);
        });
    });
}

function getPaths() {
    var output = '';
    for (var i = 0; i < Gallery.paths.length; i++) {
        output += '&paths[]=' + Gallery.paths[i];
    }
    return output;
}

function startCounter() {
    $('#rightlayer').fadeIn(400, function() {
       $('#centerlayer').fadeOut(400, function() {
           $('#leftlayer .showimage').attr('src', $('#centerlayer .showimage').attr('src'));
           Gallery.images.left = Gallery.images.center;
           $('#centerlayer .showimage').attr('src', $('#rightlayer .showimage').attr('src'));
           Gallery.images.center = Gallery.images.right;
           $('#centerlayer').show(10, function() {
               $('#rightlayer').hide(10, function() {
                $.getJSON(Gallery.nextimage + getPaths() + '&current=' + Gallery.images.center.hash, function (data) {
                    if(data.status == 'error') returnHome();
                    Gallery.images.right.url = data.image;
                    Gallery.images.right.hash = data.hash;
                    $('#rightlayer .showimage').attr('src', data.image);
                });
               });
           });
       });
    });
    tid = setTimeout(startCounter, 25000);
}

function loadfolders(path) {
    url = Gallery.dirs + '&path=' + encodeURIComponent(path);
    $.getJSON(url, function (data) {
        if (data.status == 'ok') {
            $('#control-modal #loading').fadeOut();
            var id = createid(path);
            var checked = '';
            if ($('#' + id.name).is(':checked')) {
                checked = 'checked';
            }
            ;
            var output = '<ul class="folder" style="margin-left: ' + id.nesting * 20 + 'px">';
            for (var key in data.paths) {
                output += '<li id="li-' + createid(key).name + '"><input type="checkbox" ' + checked + ' id="' + createid(key).name;
                output += '" data-id="' + key + '" class="folder-checkbox" name="folders[]"> ' + '<label for="' + createid(key).name + '">';
                output += data.paths[key].path + '</label> <span class="switcher">[+]</span></li>';
            }
            output += '</ul>';
            $('#li-' + id.name).append(output);
            recalibrate();
        } else {
            returnHome();
        }
    });
}

function returnHome() {
    window.location.href = Gallery.start;
}

function createid(path) {
    if (path == '' || path == '/') {
        return {name: 'foldertree', nesting: 0};
    }

    var re = new RegExp('/', 'g');
    var re2 = new RegExp(' ', 'g');
    return {name: path.replace(re, '---').replace(re2, ''), nesting: 0};
}

function recalibrate() {
    $('#control-modal .modal-dialog').css({
        'margin-top': function () {
            return (($(window).height() - $(this).outerHeight()) / 2);
        },
        'margin-left': function () {
            return -($(this).outerWidth() / 2);
        }
    });
}