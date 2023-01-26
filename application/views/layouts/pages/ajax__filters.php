<?php $flag = false;?>
<?php if(isset($_GET['brand'])){?>
    <?php foreach($brands as $key=>$brand) {?>
        <?php if(in_array($brand['id'], $_GET['brand'])) {?>
            <?php $flag = true; ?>
            <div class="filter__item">
                <span><?=$brand['title']?></span>
                <img data-id="brand-<?=$brand['id']?>" class="f_remove_brand" src="/dist/img/icons/Delete.svg" alt="">
            </div>
        <?php }?>
    <?php }?>
<?php }?>
<?php if(isset($_GET['filters'])){?>
    <?php foreach($tag_attributes as $tag_attribute_id=>$tag_attribute) {?>
        <?php foreach($tag_attribute['values'] as $tag_attribute_value_key=>$tag_attribute_value){?>
            <?php if(isset($_GET['filters'][$tag_attribute_id]) and in_array($tag_attribute_value_key, $_GET['filters'][$tag_attribute_id])) {?>
                <?php $flag = true; ?>
                <div class="filter__item">
                    <span>
                        <?php if($tag_attribute['type'] == 'boolean') {?>
                            <?=($tag_attribute_value == 1) ? YES : NO?>
                        <?php }else{?>
                            <?=$tag_attribute_value?>
                        <?php }?>
                    </span>
                    <img data-id="filter-<?=$tag_attribute_id?>-<?=$tag_attribute_value_key?>" class="f_remove_filter" src="/dist/img/icons/Delete.svg" alt="">
                </div>
            <?php }?>
        <?php }?>
    <?php }?>
<?php }?>
<?php if(isset($_GET['min_price']) and isset($_GET['max_price'])){?>
    <?php $flag = true; ?>
    <div class="filter__item"><span><?=PRICE?>: <?=$_GET['min_price']?>–<?=$_GET['max_price']?></span><img class="f_remove_price" src="/dist/img/icons/Delete.svg"></div>
<?php }?>
<?php if($flag){?>
    <?php
        $reset = "?";
        $reset .= (isset($_GET['query'])) ? 'query='.$_GET['query'].'&' : '';
        $reset .= (isset($_GET['cat'])) ? 'cat='.$_GET['cat'].'&' : '';
    ?>
    <div class="filter__clear"><a href="<?=$reset?>"><?=RESET_ALL?></a></div>
<?php }?>
