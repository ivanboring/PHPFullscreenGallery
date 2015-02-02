

<div class="modal fade" id="login-modal">
    <div class="modal-dialog modal-dialog-center">
        <div class="modal-content">

            <div class="modal-body text-center">
                <h1>Simple Touchscreen Gallery</h1>
                <p>This is a very simple service for people who wants to use their touschreen device (ipad, iphone, android etc) as a digital photo frame using Dropbox photos.</p>
                <p>Just login to Dropbox, choose the folder that you want to show and it will start. Easy as easy can be!</p>
                <a href="{$dropboxLogin}" class="btn btn-lg btn-primary">Login to Dropbox</a>
                <p></p>
                <p><em>* Note that the reason that we want to have access to writing to Dropbox is so that you can add textfiles for image descriptions.</em></p>
                <p></p>
                <p>Don't trust us? Download the code and see for your self!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="fullscreen()">Fullscreen</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('#login-modal').modal('show');
        $('#login-modal .modal-dialog').css({
            'margin-top': function () {
                return (($(window).height() - $(this).outerHeight()) / 2);
            },
            'margin-left': function () {
                return -($(this).outerWidth() / 2);
            }
        });
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
</script>