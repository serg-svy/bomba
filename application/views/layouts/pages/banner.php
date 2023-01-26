<section class="photo-banner banner__slider">
    <?php foreach($banners as $banner) {?>
        <?php if($banner->position == $position){?>
            <div>
                <div class="wrapper" style="<?=($position==2) ? 'padding:0px;' : '' ?>">
                    <a href="<?=$banner->uri?>" class="photo-banner__img d-block">
                        <img style="width: 100%" class="" src="<?=newthumbs($banner->img, 'main_banners', 1408, 184, '1408x184x1', 1)?>" alt="">
                    </a>
                    <a href="" class="photo-banner__img d-none">
                        <img style="width: 100%" class="<?=$banner->uri?>" src="<?=newthumbs($banner->mobile_img, 'main_banners', 328, 288, '328x288x1', 1)?>" alt="">
                    </a>
                </div>
            </div>
        <?php }?>
    <?php }?>
</section>
