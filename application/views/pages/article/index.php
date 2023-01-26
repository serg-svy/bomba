<?php $this->load->view("layouts/pages/breadcrumbs");?>

<section class="deliver-info for-clients index article-few news">
    <div class="wrapper">
        <div class="sides">
            <div class="content-text">
                <div class="content-top">
                    <div class="content-important">
                        <?php foreach ($articles as $row) { ?>
                            <div class="content-bottom">
                                <?php foreach ($row as $new) {?>
                                    <div class="bottom-item">
                                        <a href="/<?=$lclang?>/<?=$menu['all'][25]->uri?>/<?=$new->uri?>/">
                                            <img src="<?=newthumbs($new->image_list, 'article', 320, 240, '320x240x1', 1)?>" alt="">
                                        </a>
                                        <div class="title-h4">
                                            <a href="/<?=$lclang?>/<?=$menu['all'][25]->uri?>/<?=$new->uri?>/">
                                                <?=$new->title?>
                                            </a>
                                        </div>
                                        <p class="text-p">
                                            <?=$new->announce?>
                                        </p>
                                        <p class="text-span">
                                            <?=date('d.m.Y', strtotime($new->add_date))?>
                                        </p>
                                    </div>
                                <?php }?>
                            </div>
                        <?php }?>
                        <?php $this->load->view("layouts/pages/paginator");?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
