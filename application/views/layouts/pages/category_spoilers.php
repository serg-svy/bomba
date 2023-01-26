<?php if($spoiler){?>
    <div itemscope="itemscope" itemtype="https://schema.org/FAQPage">
        <br>
        <h2><?=SPOILER_HEADER?> <?=$category->title?></h2>
        <div itemprop="mainEntity" itemscope itemtype="https://schema.org/Question" class="shares">
            <div class="shares-head">
                <div class="shares-head-lft">
                    <p class="text-p" itemprop="name">
                        <?=str_replace('{category}', $category->title, SPOILER_Q1);?>
                    </p>
                </div>
                <div class="shares-head-rht">
                    <svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.292893 0.292893C0.683417 -0.0976311 1.31658 -0.0976311 1.70711 0.292893L5 3.58579L8.29289 0.292893C8.68342 -0.0976311 9.31658 -0.0976311 9.70711 0.292893C10.0976 0.683417 10.0976 1.31658 9.70711 1.70711L5.70711 5.70711C5.31658 6.09763 4.68342 6.09763 4.29289 5.70711L0.292893 1.70711C-0.0976311 1.31658 -0.0976311 0.683417 0.292893 0.292893Z" fill="#A4A4A5"/>
                    </svg>
                </div>
            </div>
            <div class="shares-body ck-editor" itemprop="acceptedAnswer" itemscope="itemscope" itemtype="https://schema.org/Answer">
                <div itemprop="text">
                    <p>
                        <?=str_replace('{category}', $category->title, SPOILER_A1);?>
                    </p>
                    <ul>
                        <?php foreach($spoiler[0] as $item) {?>
                            <li><a href="/<?=$lclang?>/<?=PRODUCT_URI?>/<?=$item['uri']?>/"><?=$item['title']?></a></li>
                        <?php }?>
                    </ul>
                </div>
            </div>
        </div>

        <div itemprop="mainEntity" itemscope itemtype="https://schema.org/Question" class="shares">
            <div class="shares-head">
                <div class="shares-head-lft">
                    <p class="text-p" itemprop="name">
                        <?=str_replace('{category}', $category->title, SPOILER_Q2);?>
                    </p>
                </div>
                <div class="shares-head-rht">
                    <svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.292893 0.292893C0.683417 -0.0976311 1.31658 -0.0976311 1.70711 0.292893L5 3.58579L8.29289 0.292893C8.68342 -0.0976311 9.31658 -0.0976311 9.70711 0.292893C10.0976 0.683417 10.0976 1.31658 9.70711 1.70711L5.70711 5.70711C5.31658 6.09763 4.68342 6.09763 4.29289 5.70711L0.292893 1.70711C-0.0976311 1.31658 -0.0976311 0.683417 0.292893 0.292893Z" fill="#A4A4A5"/>
                    </svg>
                </div>
            </div>
            <div class="shares-body ck-editor" itemprop="acceptedAnswer" itemscope="itemscope" itemtype="https://schema.org/Answer">
                <div itemprop="text">
                    <p>
                        <?=str_replace('{category}', $category->title, SPOILER_A2);?>
                    </p>
                    <ul>
                        <?php foreach($spoiler[1] as $item) {?>
                            <li><a href="/<?=$lclang?>/<?=PRODUCT_URI?>/<?=$item['uri']?>/"><?=$item['title']?></a></li>
                        <?php }?>
                    </ul>
                </div>
            </div>
        </div>

        <div itemprop="mainEntity" itemscope itemtype="https://schema.org/Question" class="shares">
            <div class="shares-head">
                <div class="shares-head-lft">
                    <p class="text-p" itemprop="name">
                        <?=str_replace('{category}', $category->title, SPOILER_Q3);?>
                    </p>
                </div>
                <div class="shares-head-rht">
                    <svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.292893 0.292893C0.683417 -0.0976311 1.31658 -0.0976311 1.70711 0.292893L5 3.58579L8.29289 0.292893C8.68342 -0.0976311 9.31658 -0.0976311 9.70711 0.292893C10.0976 0.683417 10.0976 1.31658 9.70711 1.70711L5.70711 5.70711C5.31658 6.09763 4.68342 6.09763 4.29289 5.70711L0.292893 1.70711C-0.0976311 1.31658 -0.0976311 0.683417 0.292893 0.292893Z" fill="#A4A4A5"/>
                    </svg>
                </div>
            </div>
            <div class="shares-body ck-editor" itemprop="acceptedAnswer" itemscope="itemscope" itemtype="https://schema.org/Answer">
                <div itemprop="text">
                    <p>
                        <?=str_replace('{category}', $category->title, SPOILER_A3);?>
                    </p>
                    <ul>
                        <?php foreach($spoiler[2] as $item) {?>
                            <li><a href="/<?=$lclang?>/<?=PRODUCT_URI?>/<?=$item['uri']?>/"><?=$item['title']?></a></li>
                        <?php }?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php }?>
