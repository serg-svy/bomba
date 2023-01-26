<?php if(count($retail_ids) > 1) {?>
    <script type="text/javascript">
        (window["rrApiOnReady"] = window["rrApiOnReady"] || []).push(function() {
            try{ rrApi.groupView([<?=implode(',', $retail_ids)?>]); } catch(e) {}
                })
    </script>
<?php } else {?>
    <script type="text/javascript">
        (window["rrApiOnReady"] = window["rrApiOnReady"] || []).push(function() {
            try{ rrApi.view(<?=$product->id?>); } catch(e) {}
        })
    </script>
<?php }?>
