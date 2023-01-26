<div class="popup__quick popup">
    <div class="popup__inner">
        <div class="popup__close"><img alt="delete" src="/dist/img/icons/Delete.svg"></div>
        <div class="popup__title"><?=BUY_IN_ONE_CLICK?></div>
        <form name="quick" class="review-form" action="/<?=$lclang?>/product/multiple/" method="post" enctype="multipart/form-data">
            <div class="block__inputs">
                <div class="input__item">
                    <label for="quick_name"><?=NAME?><sup>*</sup></label>
                    <input type="text" id="quick_name" value="" name="quick[name]" required>
                </div>
            </div>
            <div class="block__inputs">
                <div class="input__item">
                    <label for="quick_name"><?=PHONE?><sup>*</sup></label>
                    <input class="inlt-input" type="text" id="quick_phone" value="" name="quick[phone]" required>
                </div>
            </div>
            <div class="block__inputs">
                <div class="input__item">
                    <label for="quick_name">E-mail<sup>*</sup></label>
                    <input type="text" id="quick_email" value="" name="quick[email]" required>
                </div>
            </div>
            <div class="input__item">
                <label for="review_text"><?=PRODUCT_COMMENT?></label>
                <textarea id="review_text" name="quick[text]" style="height: 100px"></textarea>
            </div>
            <br>
            <input class="btn" type="submit" disabled="disabled" value="<?=SEND?>">
        </form>
    </div>
</div>
