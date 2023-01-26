<?php $this->load->view("layouts/pages/breadcrumbs");?>

<div class="catalog">
    <div class="wrapper">
        <div class="catalog-sides">
            <div class="catalog__categories">
                <div class="cath4"><?=CATEGORIES?></div>
                <ul class="level-1">
                    <?php foreach ($categories as $category) {?>
                        <?php if (empty($category['children'])) continue; ?>
                        <li class="lvl-1-li"><a href="<?=category_link($lclang, $category)?>" class="li-1-span"><?=$category['title']?></a></li>
                    <?php }?>
                </ul>
            </div>
            <div class="catalog__content">
                <div class="categories__block">
                    <?php foreach ($categories as $category) {?>
                        <a href="<?=category_link($lclang, $category)?>" class="categories__item">
                            <div class="photo"><img alt="" src="<?=newthumbs($category['img'], 'category', 120, 120, '120x120x0', 0)?>"></div>
                            <p><?=$category['title']?></p>
                        </a>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
</div>
