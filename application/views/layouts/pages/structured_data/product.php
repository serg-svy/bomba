<?php
$review = [];
if($feedbacks > 0) {
    foreach ($feedbacks as $key => $feedback) {
        $review[] = [
            "@type" => "Review",
            "reviewRating" => [
                "@type" => "Rating",
                "ratingValue" => $feedback->score
            ],
            "author" => [
                "@type" => "Person",
                "name" => $feedback->first_name . ' ' . $feedback->last_name,
            ],
            "reviewBody" => str_replace('"', '', str_replace("\n", "", $feedback->text))
        ];
    }
}

$review_json = json_encode($review, JSON_UNESCAPED_UNICODE);
?>

<script type="application/ld+json" data-qmeta="ldProduct">
    {
        "@context": "https://schema.org/",
        "@type": "Product",
        "name": "<?=str_replace('"', '', $product->title)?>",
        "image": "<?=$site_url?><?=product_image('1.jpg', $product->articol, $product->first_color, 68, 68)?>",
        "description": "<?=strip_tags(str_replace('"', '', str_replace("\n", "", $product->description)))?>",
        "brand": {
            "@type": "Brand",
            "name": "<?=$product->brand_name?>"
        },
        "sku": "<?=$product->sku?>",
        "offers": {
            "@type": "Offer",
            "url": "/<?=$lclang?>/<?=PRODUCT_URI?>/<?=$product->uri?>",
            "priceCurrency": "MDL",
            "price": "<?=number_format($product->discounted_price, 0, '.', '')?>",
            "priceValidUntil": "<?=date('Y', strtotime('+ 1 year'))?>-01-31",
            "availability": "https://schema.org/InStock",
            "itemCondition": "https://schema.org/NewCondition"
        }
        <?php if($product->score > 0) {?>
            ,"aggregateRating": {
                "@type": "AggregateRating",
                "ratingValue": "<?=$product->score?>",
                "reviewCount": "<?=$product->feedbacks_count?>"
            }
        <?php }?>
        <?php if($feedbacks > 0) {?>
            ,"review": <?=$review_json?>
      <?php }?>
    }
</script>
