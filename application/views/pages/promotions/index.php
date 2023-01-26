<?php $this->load->view("layouts/pages/breadcrumbs");?>

<section class="stocks">
    <div class="wrapper">
        <div class="stocks__block">
            <?php foreach ($promotions as $promotion) { ?>
                <div class="stocks__item">
                    <a href="/<?=$lclang?>/<?=$menu['all'][10]->uri?>/<?=$promotion->category_id?>/<?=$promotion->uri?>"><div class="photo"><img alt="" src="<?=newthumbs($promotion->image_list, 'promotions', 328, 240, '328x240x1', 1)?>"></div></a>
                    <div class="name"><a href="/<?=$lclang?>/<?=$menu['all'][10]->uri?>/<?=$promotion->uri?>"><?=$promotion->title?></a></div>
                    <div class="date">
                        <?=TO?> <?=date('d.m.Y', strtotime($promotion->end_date))?>
                    </div>
                </div>
            <?php }?>
        </div>
        <?php $this->load->view("layouts/pages/paginator");?>
    </div>
</section>
