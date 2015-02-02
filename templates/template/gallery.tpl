<script type="text/javascript" src="js/gallery/gallery.js"></script>
<script>
    Gallery = {literal}{{/literal}
        start: "{$start}",
        nextimage: "{$nextimage}",
        previousimage: "{$previousimage}",
        image: "{$image}",
        dirs: "{$dirs}",
        images: {literal}{"left": {url: "", hash: ""}, "center": {url: "", hash: ""}, "right": {url: "", hash: ""}},
        paths: []
    }{/literal};
</script>
<div class="modal fade" id="control-modal">
    <div class="modal-dialog modal-dialog-center">
        <div class="modal-content">

            <div class="modal-body text-center">
                <h1>Simple Touchscreen Gallery</h1>
                <p>Choose the folders to show images from</p>
                <div id="li-foldertree">
                    <div id="loading"></div>
                </div>
            </div>
            <div class="modal-footer">
                <p>On computers click F11 for fullscreen</p>
                <button type="button" id="go" class="btn btn-default" disabled data-dismiss="modal">Choose some folders first</button>
            </div>
        </div>
    </div>
</div>
<div id="leftlayer" class="imagelayer"><img class="img-responsive showimage"></div>
<div id="centerlayer" class="imagelayer"><img class="img-responsive showimage"></div>
<div id="rightlayer" class="imagelayer"><img class="img-responsive showimage"></div>