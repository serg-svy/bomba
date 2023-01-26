<?php $this->load->view('/layouts/pages/breadcrumbs'); ?>

<section class="deliver-info return credit gift-cards">
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
                <div class="content-top ck-editor">
                    <?=$page->text?>
                </div>
            </div>
        </div>
    </div>
</section>
