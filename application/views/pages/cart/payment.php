<?php
    require_once realpath('public/payment').'/'.'Transaction.php';
    date_default_timezone_set('UTC');
?>
<form id="af" action="<?=MICB_URI?>" method="post" target="_self" style="display: none;">
    <table style="width:800px;">
        <tr><td>AMOUNT :     </td><td style="width:800px;"><input type="text" name="AMOUNT" value="<?=number_format(($order['total'] + $order['delivery_amount']),2,'.','')?>" /></td></tr>
        <tr><td>CURRENCY :   </td><td><input type="text" name="CURRENCY"   value="MDL" /></td></tr>
        <tr><td>ORDER :      </td><td><input type="text" name="ORDER"      value="<?=$num?>" /></td></tr>
        <tr><td>DESC :       </td><td><input type="text" name="DESC"       value="Comanda Nr. <?=$num?>" /></td></tr>
        <tr style=""><td>TRTYPE :     </td><td><input type="text" name="TRTYPE"     value="0" /></td></tr>
        <tr style=""><td>NONCE :      </td><td><input type="text" name="NONCE"      value="<?=Transaction::set_nonce(); ?>" /></td></tr>
        <tr style=""><td>TIMESTAMP :  </td><td><input type="text" name="TIMESTAMP"  value="<?=date('YmdHis', time())?>" /></td></tr>
        <tr style=""><td>TERMINAL :   </td><td><input type="text" name="TERMINAL"   value="<?=$terminal?>" /></td></tr>
        <tr style=""><td>BACKREF :    </td><td><input type="text" name="BACKREF"    value="https://bomba.md/<?=$lclang?>/order/result/<?=$order['generated_id']?>/"/></td></tr>
        <tr style=""><td>MERCH_URL :  </td><td><input type="text" name="MERCH_URL"  value="https://bomba.md/" /></td></tr>
        <tr style=""><td>MERCHANT :   </td><td><input type="text" name="MERCHANT"   value="<?=$merchant?>" /></td></tr>
        <tr style=""><td>MERCH_NAME : </td><td><input type="text" name="MERCH_NAME" value="bomba.md" /></td></tr>
        <tr style=""><td>MERCH_GMT :  </td><td><input type="text" name="MERCH_GMT"  value="+2" /></td></tr>
        <tr style=""><td>P_SIGN :     </td><td><input type="text" name="P_SIGN"     value="" /></td></tr>
        <tr style=""><td>COUNTRY :    </td><td><input type="text" name="COUNTRY"    value="MD" /></td></tr>
        <tr style=""><td>EMAIL :      </td><td><input type="text" name="EMAIL"      value="bodarev@ilab.md" /></td></tr>
        <tr style=""><td>LANG :       </td><td><input type="text" name="LANG"       value="<?=$clang?>" /></td></tr>
        <tr><td></td><td><input type="submit"/></td></tr>
    </table>
</form>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script>
    let $input = '&TRTYPE='   +$("input[name$='TRTYPE']").val();
    $input = $input+'&TERMINAL=' +$("input[name$='TERMINAL']").val();
    $input = $input+'&ORDER='    +$("input[name$='ORDER']").val();
    $input = $input+'&CURRENCY=' +$("input[name$='CURRENCY']").val();
    $input = $input+'&AMOUNT='   +$("input[name$='AMOUNT']").val();
    $input = $input+'&TIMESTAMP='+$("input[name$='TIMESTAMP']").val();
    $input = $input+'&NONCE='    +$("input[name$='NONCE']").val();
    $input = $input+'&DESC='     +$("input[name$='DESC']").val();
    $input = $input+'&BACKREF='  +$("input[name$='BACKREF']").val();

    $.ajax({
            url: "/public/payment/psign.php",
            global: false,
            type: "POST",
            data: $input,
            dataType: "text",
            success: function(msg){
                $("input[name$='P_SIGN']").val(msg);
                $('#af').submit();
            }
        }
    );
</script>
