<?php $this->load->view('/layouts/pages/breadcrumbs'); ?>

<section class="deliver-info return" style="background: initial">
    <div class="wrapper">
        <div class="sides">
            <div class="content-text">
                <div class="content-top ck-editor">
                    <?=$page->text?>
                </div>
            </div>
        </div>
    </div>
</section>

<div>
    <div class="wrapper">
        <div id="video-container" class="example-style-2">
            <video id="qr-video" playsinline="" style="transform: scaleX(-1);"></video>
        </div>
    </div>
</div>

<style>
    #video-container{
        display: flex;
        position: relative;
    }
    #qr-video {
        width: 90vw;
        height: 300px;
        object-fit: cover;
        border-radius: 15px;
        margin: 20px 0;
    }
</style>

<script src="/dist/js/qr-scanner.umd.min.js"></script>
<script>

    $(function (){

        const scanner = new QrScanner(document.getElementById('qr-video'), result => setResult(result), {
            onDecodeError: error => {
            },
            highlightScanRegion: true,
            highlightCodeOutline: true,
        });

        scanner.start();

        function setResult(result) {
            scanner.stop();
            window.location = result.data;
        }

    });

</script>

