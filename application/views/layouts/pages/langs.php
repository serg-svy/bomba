<?php if (!empty($langs_array)) : ?>
    <?php foreach ($langs_array as $lang => $link): ?>
        <?php
        $langLink = $protocol . $host . '/' . \strtolower($lang);
        //$langLink .= (isset($page_uri)) ? '/'.$page_uri : '';
        if(is_array($link)) {
            foreach($link as $item) {
                $langLink .= (!empty($item)) ? '/'.$item : '';
            }
        } else {
            $langLink .= (!empty($link)) ? '/'.$link : '';
        }
        $langLink .= '/';
        $data_link_without_get = $langLink;
        $langLink .= (!empty($get_data)) ? '?' . $get_data : '';
        ?>
        <? if($current_lang!=$lang) {?>
            <a class="languages__item" href="<?= $langLink ?>"><?=strtoupper($lang)?></a>
        <?} else {?>
            <span class="languages__item languages__item_active"><?=strtoupper($lang)?></span>
        <?}?>
    <?php endforeach; ?>
<?php endif; ?>
