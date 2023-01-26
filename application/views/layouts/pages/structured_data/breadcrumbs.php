<?php
    $json = '{"@type":"ListItem","position":1,"name":"'.HOME. '","item":"'.$site_url.'"},';
    $i = 2;
    $url = '/' . $lclang . '/';

    if (!empty($breadcrumbs)) {
        foreach ($breadcrumbs as $key => $bc) {
            if (in_array(uri(2), [PRODUCT_URI, CATEGORY_URI])) {
                $url = (!empty($bc['url'])) ? "/" . $lclang . "/" . CATEGORY_URI . "/{$bc['url']}/" : '';
            } else {
                $url .= (!empty($bc['url'])) ? "{$bc['url']}/" : '';
            }

            $json .= '{"@type":"ListItem","position":' . $i . ',"name":"' . $bc['title'] . '","item":"' . $site_url . $url . '"},';
            $i++;
        }
    }

    $json = substr($json, 0, -1);
?>
<script type="application/ld+json" data-qmeta="ldBreadcrumbList">
    {"@context":"https://schema.org/","@type":"BreadcrumbList","itemListElement":[<?=$json?>]}
</script>
