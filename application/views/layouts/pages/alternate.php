<?php if (!empty($langs_array)) : ?>
    <?php foreach ($langs_array as $lang => $link): ?>
        <?php
        $langLink = $protocol . $host . '/' . \strtolower($lang);
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
        <link rel="alternate" hreflang="<?=$lang?>" href="<?=$langLink?>">
    <?php endforeach; ?>
<?php endif; ?>
