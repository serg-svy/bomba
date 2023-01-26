<?php $this->load->view("layouts/pages/breadcrumbs");?>
<?php $this->load->view("layouts/pages/retail_rocket/category");?>

<script src="/dist/js/jquery.star-rating-svg.min.js"></script>

<div class="catalog">
    <div class="wrapper">
        <div class="catalog-sides">
            <div class="catalog__categories">
                <div class="cath4"><?=CATEGORIES?></div>
                <ul class="level-1">
                    <?php foreach($category->subcategories as $subcategory){?>
                        <li class="lvl-1-li"><a href="<?=category_link($lclang, $subcategory)?>" class="li-1-span"><?=$subcategory->title?></a></li>
                    <?php }?>
                </ul>
            </div>
            <div class="catalog__content">
                <section class="index-top">
                    <?php if((!empty($left_banners) || !empty($right_banners)) and !$is_mobile) {?>
                        <div class="sides">
                            <div class="top__slider left__banner">
                                <?php if($is_mobile){?>
                                    <?php foreach ($mobile_banners as $mobile_banner) {?>
                                        <div class="photo">
                                            <img alt="" class="d-none" src="<?=newthumbs($mobile_banner->img, 'category_banners', 328, 480, '328x480x1', 1)?>">
                                        </div>
                                    <?php }?>
                                <?php } else {?>
                                    <?php foreach ($left_banners as $left_banner) {?>
                                        <div class="photo">
                                            <img alt="" class="d-block" src="<?=newthumbs($left_banner->img, 'category_banners', 760, 350, '760x350x1', 1)?>">
                                        </div>
                                    <?php }?>
                                <?php }?>
                            </div>
                            <?php if(!empty($right_banners)) {?>
                                <div class="right__slider right__banner side__rht side__rht123">
                                    <?php foreach ($right_banners as $right_banner) {?>
                                        <div class="photo">
                                            <img alt="" src="<?=newthumbs($right_banner->img, 'category_banners', 328, 350, '328x350x1', 1)?>">
                                        </div>
                                    <?php }?>
                                </div>
                            <?php }?>
                        </div>
                        <br>
                    <?php }?>
                </section>
                <div class="categories__block">
                    <?php foreach($category->subcategories as $subcategory){?>
                        <a href="<?=category_link($lclang, $subcategory)?>" class="categories__item">
                            <div class="photo">
                                <img alt="" src="<?=newthumbs($subcategory->img, 'category', 120, 120, '120x120x0', 0)?>">
                            </div>
                            <p><?=$subcategory->title?></p>
                        </a>
                    <?php }?>
                </div>
                <?php if($popular){?>
                    <section class="seen">
                        <h2 class="index-h2"><?=POPULAR_PRODUCTS?></h2>
                        <div class="slider__tovars121">
                            <?php foreach($popular as $product){?>
                                <?php $this->load->view("layouts/pages/product/slider", ['product'=> (object) $product]);?>
                            <?php }?>
                        </div>
                    </section>
                <?php }?>
                <?php if($recommended){?>
                    <section class="benefit">
                        <h2 class="index-h2"><?=PROFITABLE_OFFER?></h2>
                        <div class="block__square">
                            <?php foreach($recommended as $product){?>
                                <?php $this->load->view("layouts/pages/product/square", ['product'=> (object) $product]);?>
                            <?php }?>
                        </div>
                    </section>
                <?php }?>

                <?php $this->load->view("layouts/pages/brands");?>

                <div class="text-p ck-editor">
                    <?=$category->text?>
                </div>
            </div>
        </div>
    </div>
</div>
