<?php $this->load->view("layouts/pages/breadcrumbs");?>

<section class="deliver-info return credit">
    <div class="wrapper">
        <div class="sides">
            <?php if($page->bottom_category_id){?>
                <aside class="aside-menu">
                    <div class="sticky">
                        <div class="title-h4"><?=$bottom_categories[$page->bottom_category_id]->title?></div>
                        <ul class="aside__menu">
                            <?php foreach ($menu['bottom'] as $menu){?>
                                <?php if($menu->bottom_category_id == $page->bottom_category_id){?>
                                    <li class="aside__item <?=($menu->uri == $page->uri) ? 'aside__item_active' : '' ?>"><a href="/<?=$lclang?>/<?=$menu->uri?>"><?=$menu->title?></a></li>
                                <?php }?>
                            <?php }?>
                        </ul>
                    </div>
                </aside>
            <?php }?>
            <div class="content-text">
                <div class="content-top">
                    <div class="content-important ck-editor">
                        <?=$page->text?>
                    </div>
                </div>
                <?php foreach($credit_companies as $credit) {?>
                    <div class="shares">
                        <div class="shares-head">
                            <div class="shares-head-lft">
                                <img src="<?=newthumbs($credit->img ,'credit_companies', 75, 25, '75x25x0', 0)?>" alt="">
                                <p class="text-p">
                                    <?=$credit->title?>
                                </p>
                            </div>
                            <div class="shares-head-rht">
                                <svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0.292893 0.292893C0.683417 -0.0976311 1.31658 -0.0976311 1.70711 0.292893L5 3.58579L8.29289 0.292893C8.68342 -0.0976311 9.31658 -0.0976311 9.70711 0.292893C10.0976 0.683417 10.0976 1.31658 9.70711 1.70711L5.70711 5.70711C5.31658 6.09763 4.68342 6.09763 4.29289 5.70711L0.292893 1.70711C-0.0976311 1.31658 -0.0976311 0.683417 0.292893 0.292893Z" fill="#A4A4A5"/>
                                </svg>
                            </div>
                        </div>
                        <div class="shares-body ck-editor">
                            <?=$credit->text?>
                        </div>
                    </div>
                <?php }?>
            </div>
        </div>
    </div>
</section>
