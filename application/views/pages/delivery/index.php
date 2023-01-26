<? $this->load->view("layouts/pages/breadcrumbs");?>

<section class="deliver-info">
    <div class="wrapper">
        <div class="sides">
            <? if($page->bottom_category_id){?>
                <aside class="aside-menu">
                    <div class="sticky">
                        <div class="title-h4"><?=$bottom_categories[$page->bottom_category_id]->title?></div>
                        <ul class="aside__menu">
                            <? foreach ($menu['bottom'] as $menu){?>
                                <? if($menu->bottom_category_id == $page->bottom_category_id){?>
                                    <li class="aside__item <?=($menu->uri == $page->uri) ? 'aside__item_active' : '' ?>"><a href="/<?=$lclang?>/<?=$menu->uri?>"><?=$menu->title?></a></li>
                                <?}?>
                            <?}?>
                        </ul>
                    </div>
                </aside>
            <?}?>
            <div class="content-text">
                <div class="content-p">
                    <p class="text-p ck-editor">
                        <?=$page->text?>
                    </p>
                </div>
                <div class="block__input d-block delivery__select-city">
                    <label for="i1"><?=SELECT_LOCALITY?> <sup>*</sup></label>
                    <input <?=(uri(3) != '') ? 'disabled' : ''?> id="i1" data-url="/<?=$lclang?>/ajax/get_city/" type="text" value="<?= $cities[$_SESSION['city_id']]->title ?>">
                    <div class="delivery__city-list">
                        <ul class="city__list" data-url="/<?=$lclang?>/ajax/set_city/"></ul>
                    </div>
                </div>
                <div class="block__info ck-editor delivery__text-courier">
                    <?=str_replace(['{nearest_date_delivery}', '{nearest_date}'], $deliveryCourier['day'], $cities[$_SESSION['city_id']]->text_courier)?>
                </div>
                <div class="block__info ck-editordelivery__text-pickup">
                    <?=str_replace(['{nearest_date_delivery}', '{nearest_date}'], $deliveryCourier['day'], $cities[$_SESSION['city_id']]->text_pickup)?>
                </div>
            </div>
        </div>
    </div>
</section>
