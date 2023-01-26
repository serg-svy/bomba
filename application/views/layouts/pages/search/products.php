<?php $notfound = true; ?>
<?php if($is_mobile) {?>
    <?php $notfound = false;?>
    <a class="input__item" href="/<?=$lclang?>/qr/" style="background-color: rgba(244, 244, 244, 1); flex-direction: row">
        <div class="photo">
            <img src="/dist/img/icons/qr.svg" alt="qr">
        </div>
        <span><?=SCAN_QR_CODE?></span>
    </a>
<?php }?>
<?php if(!empty($previous_search)) {?>
    <?php $notfound = false;?>
    <?php foreach($previous_search as $previous_item) {?>
        <a class="input__item" href="/<?=$lclang?>/<?=CATEGORY_URI?>/<?=$previous_item['uri']?>/?query=<?=$previous_item['query']?>">
            <div class="photo">
                <img src="/dist/img/icons/i13.svg" alt="">
            </div>
            <span><?=$previous_item['query']?></span>
        </a>
    <?php }?>
<?php }?>

<?php if(!empty($popular_requests)) {?>
    <?php $notfound = false;?>
    <?php foreach($popular_requests as $popular_request) {?>
        <a class="input__item" href="/<?=$lclang?>/<?=CATEGORY_URI?>/<?=$popular_request->uri?>/?query=<?=$popular_request->query?>">
            <div class="photo"><img src="/dist/img/icons/i33.svg" alt=""></div>
            <span><?=$popular_request->query?></span>
        </a>
    <?php }?>
<?php }?>

<?php if(!empty($find_categories)) {?>
    <?php $notfound = false;?>
    <?php foreach(array_chunk($find_categories, 4)[0] as $category) {?>
        <div class="input__item">
            <div class="photo img"><img src="<?=newthumbs($category['img'], 'category', 48, 48, '48x48x0', 0)?>" alt=""></div>
            <a class="input__item-link" href="/<?=$lclang?>/<?=CATEGORY_URI?>/<?=$category['uri']?>/?query=<?=$query?>">
                <span><?=$category['title']?></span>
            </a>
        </div>
    <?php }?>
<?php }?>

<?php if(!empty($products)){?>
    <?php $notfound = false;?>
    <?php foreach($products as $key=>$product) {?>
        <?php if($key < 2) {?>
            <div class="input__item">
                <div class="photo img"><img src="<?=product_image('1.jpg', $product['articol'], $product['first_color'], 222, 166)?>"></div>
                <a class="input__item-link" href="/<?=$lclang?>/<?=PRODUCT_URI?>/<?=$product['uri']?>/">
                    <span><?=$product['title']?></span>
                    <span><?=@$find_categories[$product['category_id']]['title']?></span>
                </a>
            </div>
        <?php }?>
    <?php }?>
<?php }?>

<?php if($notfound) {?>
    <span style="margin: 10px;font-size: 24px;font-weight: 400;"><?=NOTHING_FOUND?></span>
<?php }?>
