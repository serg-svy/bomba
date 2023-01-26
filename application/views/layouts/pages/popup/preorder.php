<div class="popup__preorder popup">
    <div class="popup__inner">
        <div class="popup__close"><img alt="delete" src="/dist/img/icons/Delete.svg"></div>
        <div class="popup__title"><?=PREORDER?></div>
        <form name="preorder" class="review-form" action="/<?=$lclang?>/product/preorder/" method="post" enctype="multipart/form-data">
            <input type="hidden" value="<?=$product->id?>" name="preorder[product_id]">
            <div class="block__inputs">
                <div class="input__item">
                    <label for="preorder_name"><?=NAME?><sup>*</sup></label>
                    <input type="text" id="preorder_name" value="" name="preorder[name]" required>
                </div>
            </div>
            <div class="block__inputs">
                <div class="input__item">
                    <label for="preorder_name"><?=PHONE?><sup>*</sup></label>
                    <input class="inlt-input" type="text" id="preorder_phone" value="" name="preorder[phone]" required>
                </div>
            </div>
            <div class="block__inputs">
                <div class="input__item">
                    <label for="preorder_name">E-mail<sup>*</sup></label>
                    <input type="text" id="preorder_email" value="" name="preorder[email]" required>
                </div>
            </div>
            <div class="input__item">
                <label for="review_text"><?=PRODUCT_COMMENT?></label>
                <textarea id="review_text" name="preorder[text]" style="height: 100px"></textarea>
            </div>
            <input class="btn" type="submit" disabled="disabled" value="<?=SEND?>">
        </form>
    </div>
</div>
