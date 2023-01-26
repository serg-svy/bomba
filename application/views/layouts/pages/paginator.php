<?php if (!empty($paginator)) { ?>
    <?php if ($paginator->getNumPages() > 1){ ?>
        <?php if($paginator->getNextUrl()){?>
            <a class="view-all" data-page="<?=$paginator->getNextPage()?>" href="<?=$paginator->getNextUrl();?>"><?=SHOW_MORE?></a>
        <?php }?>
        <ul class="pagination">
            <?php if($paginator->getPrevUrl()){?>
                <li class="prev"><a href="<?=$paginator->getPrevUrl();?>" data-page="<?=$paginator->getPrevPage()?>"><?=PREV?></a></li>
            <?php }?>
            <?php foreach ($paginator->getPages() as $page) { ?>
                <?php $href = ($page['num'] == 1) ? $_SESSION['without_get_url'] : $_SESSION['without_get_url']."?page=".$page['num'];?>
                <li>
                    <?php if(($page['isCurrent'])) {?>
                        <span><?=$page['num']?></span>
                    <?php }else{?>
                        <a data-page="<?=$page['num']?>" href="<?=$href?>"><?=$page['num']?></a>
                    <?php }?>
                </li>
            <?php } ?>
            <?php if($paginator->getNextUrl()){?>
                <li class="next"><a href="<?=$paginator->getNextUrl();?>" data-page="<?=$paginator->getNextPage()?>"><?=NEXT?></a></li>
            <?php }?>
        </ul>
    <?php } ?>
<?php } ?>
