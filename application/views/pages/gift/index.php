<? $this->load->view("layouts/pages/breadcrumbs");?>

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
                <div class="content-top">
                    <div class="content-important">
                        <div class="text-p ck-editor">
                            <?=$page->text?>
                        </div>
                    </div>
                    <div class="content-important">
                        <div id="tabs">
                            <ul class="important-item-flex">
                                <? foreach($cards as $key=>$card){?>
                                    <li class="important-item">
                                        <a href="#tab-<?=$key?>">
                                            <?=$card->title?>
                                        </a>
                                    </li>
                                <?}?>
                            </ul>
                            <? foreach($cards as $key=>$card){?>
                                <div class="text-p ck-editor" id="tab-<?=$key?>">
                                    <img class="d-block" src="<?=newthumbs($card->img, 'gift_cards', 224, 140, '224x140x1', 1 )?>" alt="">
                                    <?=$card->text?>
                                </div>
                            <?}?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="/dist/js/jquery-ui.min.js"></script>
<script>
    $( function() {
        $( "#tabs" ).tabs();
    } );
</script>
