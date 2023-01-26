<?php if(!empty($find_categories)){?>
    <?php foreach($find_categories as $category) {?>
        <a class="" href="/<?=$lclang?>/<?=CATEGORY_URI?>/<?=$category['uri']?>/?query=<?=$query?>"><?=$category['title']?></a>
    <?php }?>
<?php }?>
<?php if(!empty($search_categories)){?>
    <?php foreach($search_categories as $category) {?>
        <a class="" href="/<?=$lclang?>/<?=CATEGORY_URI?>/<?=$category['uri']?>/"><?=$category['title']?></a>
    <?php }?>
<?php }?>
