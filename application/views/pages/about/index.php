<? $this->load->view("layouts/pages/breadcrumbs");?>

<section class="deliver-info payment about-company">
    <div class="wrapper">
        <div class="sides">
            <div class="content-text">
                <div class="content-upper">
                    <div class="upper-lft">
                        <div class="text-p ck-editor">
                            <?=$page->text?>
                        </div>
                    </div>
                    <div class="upper-rht">
                        <iframe style="border-radius: 15px;" width="448" height="250" src="https://www.youtube.com/embed/<?=get_youtube_video_ID($page->youtube)?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="content-top">
                    <? foreach($about_blocks as $about_block){?>
                        <div class="top-item">
                            <h2 class="title-h2">
                                <?=$about_block->title?>
                            </h2>
                            <p class="text-p">
                                <?=$about_block->desc?>
                            </p>
                            <a class="payment-button" href="<?=$about_block->uri?>"><?=$about_block->uri_name?></a>
                        </div>
                    <?}?>
                </div>
                <div class="content-bottom">
                    <h2 class="title-h2">
                        <?=EVERYDAY_LIFE?>
                    </h2>
                    <div class="content-bottom-item d-block">
                        <? foreach ($about_images as $about_image) {?>
                            <img src="<?=newthumbs($about_image->img, 'about_images', 688, 458, '688x458x1', 1)?>" alt="">
                        <?}?>
                    </div>
                    <div class="content-bottom-item d-none">
                        <? foreach ($about_images as $about_image) {?>
                            <img src="<?=newthumbs($about_image->img, 'about_images', 328, 220, '328x220x1', 1)?>" alt="">
                        <?}?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
