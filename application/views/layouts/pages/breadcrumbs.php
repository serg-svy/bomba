<?php if (!empty($breadcrumbs)){ ?>
    <section class="breadcrumbs-mob breadcrumbs-article-few d-none credit-bd-bck">
        <a onclick="history.back();">
            <img src="/dist/img/icons/i38.svg"><span><?=BACK?></span>
        </a>
        <div class="breadcrumbs-mob-down">
            <img src="/dist/img/icons/down.svg" alt="">
            <div class="bd-bck">
            <ul>
                <li><a href="/<?= ($lclang=='ro') ? '' : $lclang ?>"><?= HOME?></a></li>
                <?php $url = '/' . $lclang . '/'; ?>
                <?php foreach ($breadcrumbs as $key => $bc) { ?>
                    <?php
                    if(in_array(uri(2), [ PRODUCT_URI, CATEGORY_URI ] )) {
                        $url = (!empty($bc['url'])) ? "/".$lclang."/".CATEGORY_URI."/{$bc['url']}/" : '';
                    } else {
                        $url .= (!empty($bc['url'])) ? "{$bc['url']}/" : '';
                    }
                    ?>
                    <?php if(isset($breadcrumbs[($key + 1)])){?>
                        <li><a href="<?= $url ?>"><?= $bc['title'] ?></a></li>
                    <?php }else{?>
                        <li class="color"><?= $bc['title'] ?></li>
                    <?php }?>
                <?php }?>
            </ul>
        </div>
        </div>
    </section>

    <section class="breadcrumbs-title">
        <div class="wrapper">
            <ul class="breadcrumbs">
                <li><a href="/<?= ($lclang=='ro') ? '' : $lclang ?>"><?= HOME?></a></li>
                <?php $url = '/' . $lclang . '/'; ?>
                <?php foreach ($breadcrumbs as $key => $bc) { ?>
                    <?php
                        if(in_array(uri(2), [ PRODUCT_URI, CATEGORY_URI ] )) {
                            $url = (!empty($bc['url'])) ? "/".$lclang."/".CATEGORY_URI."/{$bc['url']}/" : '';
                        } else {
                            $url .= (!empty($bc['url'])) ? "{$bc['url']}/" : '';
                        }
                    ?>
                    <?php if(isset($breadcrumbs[($key + 1)])){?>
                        <li><a href="<?= $url ?>"><?= $bc['title'] ?></a></li>
                    <?php }else{?>
                        <li><span><?= $bc['title'] ?></span></li>
                    <?php }?>
                <?php }?>
            </ul>

            <?php if(uri(2) == $menu['all'][17]->uri){?>
                <?php if($product->labels) {?>
                    <div class="title__sticks">
                        <?php foreach($product->labels as $label) {?>
                            <span class="span-top" style="background: <?=$label->color?>">
                                <span><?=$label->title?></span>
                            </span>
                        <?php }?>
                    </div>
                <?php }?>
            <?php }?>

            <h1 class="title-h1"><?=$page->title?></h1>
            <?php if(isset($page->subtitle)) {?>
                <p class="subtitle"><?=$page->subtitle?></p>
            <?php }?>

            <?php if(uri(2) == $menu['all'][17]->uri){?>
                <div class="bottom__button"><a class="harak link" href="#"><?=SPECIFICATIONS?></a>
                    <div class="bm">
                        <div class="stars">
                            <div class="reviews__stars"></div>
                            <a href="#" class="allrev link">
                                <?=generateFeedbackText($product->feedbacks_count);?>
                            </a>
                        </div>
                        <script>
                            $(".reviews__stars").starRating({
                                starSize: 20,
                                readOnly: true,
                                totalStars: 5,
                                emptyColor: 'lightgray',
                                activeColor: 'rgb(213 9 16)',
                                initialRating: <?=(!$product->score) ? 0 : $product->score ?>,
                                strokeWidth: 0,
                                useGradient: false,
                                minRating: 1,
                            });
                        </script>
                        <div class="icons bv_data" data-size="<?=(!empty($product->sizes)) ? 'null' : 'NO_SIZE';?>" data-color="<?=(!empty($product->colors)) ? 'null' : 'NO_COLOR';?>" data-id="<?=$product->id?>" data-articol="<?=$product->articol?>">

                            <div class="product__buttons-icons">
                                <div class="tooltip">
                                    <span class="ico add_to_compare <?=(in_array($product->id, $_SESSION['compare'])) ? 'active' : ''?>">
                                        <?=file_get_contents(base_url()."/dist/img/icons/status.svg");?>
                                    </span>
                                    <div class="info">
                                        <p><?=PROUCT_ADDED_TO_COMPARE?></p>
                                        <a href="/<?=$lclang?>/<?=$menu['all'][15]->uri?>/"><?=GO_TO?></a>
                                        <div class="arrow"></div>
                                    </div>
                                </div>
                                <span class="ico add_to_favorite <?=(in_array($product->id, $_SESSION['favorite'])) ? 'active' : ''?>">
                                    <?=file_get_contents(base_url()."/dist/img/icons/Favorite.svg");?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <p><?=PRODUCT_CODE?>: <?=$product->id?></p>
                </div>
            <?php }?>

            <?php if(uri(2) == $menu['all'][10]->uri and uri(4) == '' ) {?>
                <div class="round-blocks">
                    <a href="/<?=$lclang?>/<?=$menu['all'][10]->uri?>" class="block__item <?=(uri(3) == '') ? 'active' : ''?>">
                        <div class="photo"><img src="/dist/img/promo-logo.svg"></div>
                        <div class="name"><?=ALL_CATEGORIES?></div>
                        <div class="num"></div>
                    </a>
                    <?php foreach($promotion_categories as $promotion_category) {?>
                        <?php if($promotion_category->count > 0){?>
                            <a href="/<?=$lclang?>/<?=$menu['all'][10]->uri?>/<?=$promotion_category->id?>" class="block__item <?=(uri(3) == $promotion_category->id) ? 'active' : ''?>">
                                <div class="photo"><img src="<?=newthumbs($promotion_category->img, 'promotion_category', 120, 120, '120x120x1', 1)?>"></div>
                                <div class="name"><?=$promotion_category->title?></div>
                                <div class="num"><?=$promotion_category->count?></div>
                            </a>
                        <?php }?>
                    <?php }?>
                </div>
            <?php }?>
        </div>
    </section>
<?php }?>
