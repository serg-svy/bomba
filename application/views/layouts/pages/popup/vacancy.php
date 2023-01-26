<div class="popup__vacancy popup">
    <div class="popup__inner">
        <div class="popup__close"><img src="/dist/img/icons/Delete.svg"></div>
        <div class="popup__title"><?=JOB_APPLICATION?></div>
        <div class="block-vacancy">
            <h3>Кассир</h3>
            <!--<p>Опыт от 1 года. Полная занятость, полный день.</p>-->
        </div>
        <form method="post" name="vacancy" enctype="multipart/form-data">
            <input type="hidden" name="vacancy[id]" value="">
            <div class="block__inputs">
                <div class="input__item">
                    <label for="ip1"><?=NAME?><sup>*</sup></label>
                    <input type="text" id="ip1" value="" name="vacancy[name]" required>
                </div>
                <div class="input__item">
                    <label for="ip2"><?=SURNAME?><sup>*</sup></label>
                    <input type="text" id="ip2" value="" name="vacancy[surname]" required>
                </div>
            </div>
            <div class="block__inputs">
                <div class="input__item">
                    <label for="ip3"><?=PHONE?><sup>*</sup></label>
                    <input type="text" id="vacancy_phone" value="" name="vacancy[phone]" required>
                </div>
                <div class="input__item input__item-file">
                    <label for="fl1" class="sd">
                        <img src="/dist/img/Attach.png" alt="">
                        <div>
                            <?=ATTACH_RESUME?><sup>*</sup>
                            <p><?=PDF_FORMATS?></p>
                        </div>
                    </label>
                    <input id="fl1" type="file" name="vacancy[file]" accept=".pdf,.doc" required>
                </div>
            </div>
            <div class="input__item input__magazin">
                <div class="magazin">
                    <label for="ip4"><?=STORE?><sup>*</sup></label>
                    <select name="vacancy[store_id]" id="" class="magazin-head" required>
                        <option value=""></option>
                        <? foreach($vacancies as $vacancy) {?>
                            <?php foreach($vacancy->stores as $store){?>
                                <option class="vacancy-<?=$vacancy->id?>" value="<?=$store->id?>"><?=$store->store_title?>, <?=$store->store_address?></option>
                            <?php }?>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="checkbox__item">
                <label for="sr2-1">
                    <input type="checkbox" id="sr2-1" required name="vacancy[personal_data]">
                    <span><?=I_ACCEPT?> <a href="#"><?=$menu['all'][18]->title?></a></span>
                </label>
            </div>
            <div class="checkbox__item">
                <label for="sr2-2">
                    <input type="checkbox" id="sr2-2" name="vacancy[terms]">
                    <span><?=I_ACCEPT?> <a href="#"><?=$menu['all'][19]->title?></a></span>
                </label>
            </div>
            <input class="btn" type="submit" disabled="disabled" value="<?=SEND?>">
        </form>
    </div>
</div>
