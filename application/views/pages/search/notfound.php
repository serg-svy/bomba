<?php $this->load->view("layouts/pages/breadcrumbs");?>

<script src="/dist/js/jquery.star-rating-svg.min.js"></script>

<section class="search-zero">
    <div class="wrapper">
        <h2><?=$menu['all'][10]->title?></h2>
        <div class="stocks__block">
            <?php foreach ($promotions as $promotion){?>
                <div class="stocks__item">
                    <div class="photo"><img alt="" src="<?=newthumbs($promotion->image_list, 'promotions', 328, 240, '328x240x1', 1)?>"></div>
                    <div class="name"><a href="/<?=$lclang?>/<?=$menu['all'][10]->uri?>/<?=$promotion->uri?>"><?=$promotion->title?></a></div>
                    <div class="date"><?=TO?> <?=date('d.m.Y', strtotime($promotion->end_date))?></div>
                </div>
            <?php }?>
        </div>
        <h2><?=NEW_PRODUCTS?></h2>
        <div class="slider__tovars slider__tovars-2">
            <?php foreach($new_products as $new_product){?>
                <?php $this->load->view('layouts/pages/product/slider', ['product' => (object) $new_product]);?>
            <?php }?>
        </div>
    </div>
</section>
