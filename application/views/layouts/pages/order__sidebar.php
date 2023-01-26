<div class="c_wr2_col2">
    <div class="order_info_wr">
        <div class="title3"><?=YOUR_ORDER?></div>
        <table cellpadding="0" cellspacing="0" class="order_info">
            <tbody>
            <tr>
                <th><span class="qty"><?=$cart_count?></span> <?=PRODUCTS?></th>
                <td><?=numberFormat($cart_total)?>,—</td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <th><?=DELIVERY?></th>
                <td class="delivery">0,—</td>
            </tr>
            <?php if($cart_points>0){?>
                <tr>
                    <th><?=PAYMENT_WITH_BONUNSES?></th>
                    <td><?=numberFormat($cart_points)?>,—</td>
                </tr>
            <?php }?>
            </tfoot>
        </table>
        <div class="order_info_total">
            <?=TOTAL?>
            <b class="total"><?=numberFormat($cart_total - $cart_points)?>,—</b>
        </div>
        <?php if($next == 'create') {?>
            <div class="b_btn btnVerify" style="display:none;">
                <form action="/<?=$lclang?>/<?=$menu['all'][16]->uri?>/<?=$next?>/" method="POST">
                    <button type="submit" class="btn"><?=CHECKOUT?></button>
                </form>
            </div>
            <a class="btn2 quick_order" href="#"><?=QUICK_ORDER?></a>
        <?php }else{?>
            <div class="b_btn btnVerify">
                <a href="/<?=$lclang?>/<?=$menu['all'][16]->uri?>/<?=$next?>/" class="btn"><?=CHECKOUT?></a>
            </div>
            <a class="btn2 quick_order" href="#"><?=QUICK_ORDER?></a>
        <?php }?>
    </div>
</div>
