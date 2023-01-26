<? $this->load->view("layouts/pages/breadcrumbs");?>

<section class="deliver-info payment bonus-programm">
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
                <div class="content-top">
                    <? foreach ($blocks as $block){?>
                        <? if($block->block_id != 1) continue;?>
                        <div class="top-item">
                            <h2 class="title-h2">
                                <?=$block->title?>
                            </h2>
                            <p class="text-p">
                                <?=nl2br($block->text)?>
                            </p>
                        </div>
                    <?}?>
                </div>
                <div class="content-important">
                    <h2 class="title-h2">
                        <?=CONDITIONS?>
                    </h2>
                    <ul class="ul__line">
                        <? foreach ($blocks as $block){?>
                            <? if($block->block_id != 2) continue;?>
                            <li><?=$block->title?></li>
                        <?}?>
                    </ul>
                </div>
                <div class="content-important">
                    <h2 class="title-h2">
                        <?=HOW_TO_REGISTER?>
                    </h2>
                    <ol class="ol__line">
                        <? foreach ($blocks as $block){?>
                            <? if($block->block_id != 3) continue;?>
                            <li>
                                <?=$block->title?>
                                <? if($block->img){?>
                                    <div class="ol-img">
                                        <img class="block-photo" src="/public/uds_blocks/<?=$block->img?>" alt="">
                                        <? if($block->id == 35) {?>
                                            <a href="<?=GOOGLE_PLAY_URI?>" target="_blank"><img style="height: 32px" src="/dist/img/gplay.svg" alt=""></a>
                                            <a href="<?=APPLE_STORE_URI?>" target="_blank"><img style="height: 32px" src="/dist/img/apple.svg" alt=""></a>
                                        <?}?>
                                    </div>
                                <?}?>
                            </li>
                        <?}?>
                    </ol>
                </div>
                <div class="content-important">
                    <h2 class="title-h2">
                        <?=HOW_TO_USE_BONUSES?>
                    </h2>
                    <? foreach ($blocks as $block){?>
                        <? if($block->block_id != 4) continue;?>
                        <p class="text-p">
                            <?=$block->title?>
                        </p>
                        <? if($block->img){?>
                            <img class="content-img" src="/public/uds_blocks/<?=$block->img?>" alt="">
                        <?}?>
                    <?}?>
                </div>
                <div class="content-important">
                    <h2 class="title-h2">
                        <?=HOW_TO_INVITE_FRIENDS?>
                    </h2>
                    <ol class="ol__line">
                        <? foreach ($blocks as $block){?>
                        <? if($block->block_id != 5) continue;?>
                            <li>
                                <?=$block->title?>
                                <? if($block->img){?>
                                    <div class="ol-img">
                                        <img src="/public/uds_blocks/<?=$block->img?>" alt="">
                                    </div>
                                <?}?>
                            </li>
                        <?}?>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>
