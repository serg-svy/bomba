<?php
$json = [];
foreach ($order->products as $item) {
    $json[] = [
        'id' => $item->product_id,
        'qnt' => $item->quantity,
        'price' => $item->price
    ];
}
$json = json_encode($json);
?>
<script type="text/javascript">
    (window["rrApiOnReady"] = window["rrApiOnReady"] || []).push(function() {
        try {
            rrApi.setEmail("<?=$order->email?>");
            rrApi.order({
                "transaction": "<?=$order->generated_id?>",
                "items": <?=$json?>});
        } catch(e) {}
    })
</script>
