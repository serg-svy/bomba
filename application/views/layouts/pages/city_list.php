<?php foreach ($cities as $city) {?>
    <?php
        $title = $city->title;
        $caps_match = ucfirst($match);
        $title = str_replace($match, "<span>$match</span>", $title);
        $title = str_replace($caps_match, "<span>$caps_match</span>", $title);
    ?>
    <li class="<?=($city->id == $_SESSION['city_id']) ? 'li_active' : ''?>" data-id="<?=$city->id?>"><?= $title /*.'&nbsp;'.$regions[$city->region_id]->title*/?></li>
<?php }?>
