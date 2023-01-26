<div class="f_cols_b">
    <div class="f_row">
        <label class="f_label"><?=NAME?><span>*</span></label>
        <div class="f_input_wr"><input type="text" name="order[name]" class="f_input required" value="" placeholder="<?=NAME?>"></div>
    </div>
    <div class="f_row">
        <label class="f_label"><?=PHONE?><span>*</span></label>
        <div class="f_input_wr"><input type="text" name="order[phone]" class="f_input firstTel tel required" value="" placeholder="<?=PHONE?>"></div>
    </div>
    <div class="f_row">
        <label class="f_label">E-mail<span>*</span></label>
        <div class="f_input_wr"><input type="email" name="order[email]" class="f_input email required" value="" placeholder="E-mail"></div>
    </div>
    <div class="f_row">
        <label class="f_label"><?=COMMENT_TO_ORDER?></label>
        <div class="f_input_wr"><textarea name="order[message]" id="" cols="30" rows="10" class="f_input"></textarea></div>
    </div>
    <div class="f_row f_check_wr checkbox__item">
        <label for="is_gift" class="check-box">
            <input id="is_gift" class="ch1-cl" type="checkbox" name="order[is_gift]" value="1">
            <span><?=BUY_AS_GIFT?></span>
        </label>
    </div>
    <div class="f_row gift_row">
        <label class="f_label"><?=NAME?><span>*</span></label>
        <div class="f_input_wr"><input type="text" name="order[receive_gift_name]" class="f_input" placeholder="<?=NAME?>"></div>
    </div>
    <div class="f_row gift_row">
        <label class="f_label"><?=PHONE?><span>*</span></label>
        <div class="f_input_wr"><input type="text" name="order[receive_gift_phone]" class="f_input secondTel tel" placeholder="<?=PHONE?>"></div>
    </div>
    <div class="f_row f_check_wr">
        <div class="checkbox__item">
            <label for="personal_data">
                <input type="checkbox" name="order[personal_data]" id="personal_data" checked>
                <span><?=I_ACCEPT?> <a class="personal_data" href="#"><?=$menu['all'][18]->title?></a></span>
            </label>
        </div>
        <div class="checkbox__item">
            <label for="terms">
                <input type="checkbox" name="order[terms]" id="terms" checked>
                <span><?=I_ACCEPT?> <a class="terms" href="#"><?=$menu['all'][19]->title?></a></span>
            </label>
        </div>
    </div>
</div>
<div class="b_btn">
    <button type="submit" id="submitContactBtn" class="btn btn_accept"><?=CHECK_DATA?></button>
</div>
