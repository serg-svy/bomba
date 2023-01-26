<div class="popup__credit popup">
    <div class="popup__inner">
        <div class="popup__close"><img alt="delete" src="/dist/img/icons/Delete.svg"></div>
        <div class="popup__title"><?=($product->rate) ? GET_IN_INSTALLMENTS_OR_CREDIT : GET_IN_CREDIT ?></div>
        <div class="credit_header_title">
            <picture>
                <img src="<?=product_image('1.jpg', $product->articol, $product->first_color, 68, 68)?>" alt="<?=$product->title?>">
            </picture>
            <?=$product->title?>
        </div>
        <form name="credit" action="/<?=$lclang?>/product/credit/" method="post" enctype="multipart/form-data">
            <input type="hidden" value="<?=$product->id?>" name="credit[product_id]">
            <input type="hidden" value="credit" name="credit[type]" class="credit_type">
            <div class="tabs_wr">
                <ul>
                    <?php if($product->rate) {?>
                        <li><a href="#tab_a1"><?=INSTALLMENTS?></a></li>
                    <?php }?>
                    <li><a href="#tab_a2"><?=CREDIT?></a></li>
                </ul>
                <?php if($product->rate) {?>
                    <div id="tab_a1">
                        <?=CHOICE_INSTALLMENT_PROGRAM?>
                        <div class="radio__inputs">
                            <div class="radio__item">
                                <label for="installment<?=$product->rate?>">
                                    <input type="radio" class="credit_month" name="credit[month]" id="installment<?=$product->rate?>" value="<?=$product->rate?>" data-type="installment" required>
                                    <small><?=$product->rate?> <?=MONTHS?></small>
                                    <b><?=numberFormat($product->discounted_price/$product->rate)?>,– / <?=MONTH?>.</b>
                                </label>
                            </div>
                        </div>
                    </div>
                <?php }?>
                <div id="tab_a2">
                    <?=CHOICE_CREDIT_PROGRAM?>
                    <div class="radio__inputs">
                        <?php foreach ($credit as $credit_item) {?>
                            <div class="radio__item">
                                <label for="credit<?=$credit_item['months']?>">
                                    <input checked="checked" type="radio" class="credit_month" name="credit[month]" id="credit<?=$credit_item['months']?>" value="<?=$credit_item['months']?>" data-type="credit" required>
                                    <small><?=$credit_item['months']?> <?=MONTHS?></small>
                                    <b><?=calculateCredit($product->discounted_price, $credit_item['months'], $credit)?>,– / <?=MONTH?>.</b>
                                </label>
                            </div>
                        <?php }?>
                    </div>
                </div>
            </div>
            <br>
            <script>
                $('.tabs_wr').tabs();
            </script>
            <div class="block__inputs">
                <div class="input__item">
                    <label for="ip3"><?=PHONE?><sup>*</sup></label>
                    <input type="text" id="credit_phone" value="" name="credit[phone]" required>
                </div>
                <div class="input__item" style="margin-top: 32px;">
                    <input class="btn" type="submit" disabled="disabled" value="<?=SEND?>">
                </div>
            </div>
        </form>
        <div class="credit_footer_text">
            Отправляя данные, вы соглашаетесь с <a href="">Политикой конфиденциальности</a> и <a href="">Обработкой персональных данных</a>
        </div>
    </div>
</div>
