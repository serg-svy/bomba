<div class="switch_b_wr">
    <?php foreach($payment_types as $key=>$payment_type){?>
        <?php if($payment_type->id == 1 and $onlyOnlinePayment == 1) {?>
            <?php continue; ?>
        <?php }else{?>
            <label class="switch_b">
                <input <?=($key==0) ? 'checked' : ''?> value="<?=$payment_type->id?>" type="radio" name="order[payment_type_id]" id="payment_type_id-<?=$payment_type->id?>">
                <span class="switch_b_content"><?=$payment_type->title?>
                <?php if($payment_type->img){?>
                    <span class="switch_b_payment">
                        <img src="/public/payment_type/<?=$payment_type->img?>" alt="">
                    </span>
                <?php }?>
                    </span>
            </label>
        <?php }?>
    <?php }?>
</div>
<div class="b_btn">
    <button type="submit" id="submitPaymentBtn" class="btn"><?=SELECT_PAYMENT_METHOD?></button>
</div>
