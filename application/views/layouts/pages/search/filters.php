<form class="filters" action="" method="GET">
    <div class="category__top">
        <div class="cath3 d-none"><?=FILTERS?></div><img class="category__close d-none" src="/dist/img/icons/Delete.svg">
    </div>
    <div class="category__places d-none"></div>
    <?php if(isset($find_categories_tree) and !empty($find_categories_tree)){?>
        <div class="catalog__categories category__item">
            <div class="cath4"><?=CATEGORIES?></div>
            <ul class="">
                <?php foreach($find_categories_tree as $category) {?>
                    <li class="">
                        <a href="/<?=$lclang?>/<?=$menu['all'][11]->uri?>/<?=$category['uri']?>/<?=$get_params_for_search?>" class="li-1-span <?=(isset($cat) and $cat == $category['id']) ? 'active_category' : '' ?>"><?=$category['title']?></a>
                    </li>
                <?php }?>
            </ul>
        </div>
    <?php }?>
    <div class="catalog__price category__item">
        <div class="cath4"><?=PRICE?></div>
        <div class="block__checkboxes">
            <div class="price__inputs">
                <input type="text" placeholder="<?=FROM?>" name="min_price" value="<?=($_GET['min_price']) ?? intval($min_price)?>">
                <input type="text" placeholder="<?=TO?>" name="max_price" value="<?=($_GET['max_price']) ??  intval($max_price)?>">
            </div>
        </div>
    </div>
    <?php if(!empty($brands)){?>
        <div class="catalog__checkboxes category__item" style="display:none">
            <div class="cath4"><span><?=BRANDS?><span class="ft_num"></span></span></div>
            <div class="block__checkboxes">
                <?php foreach($brands as $key=>$brand) {?>
                    <div class="checkbox__item">
                        <label for="brand-<?=$brand['id']?>">
                            <input <?=(isset($_GET['brand']) && in_array($brand['id'], $_GET['brand'])) ? 'checked' : ''?>
                                value="<?=$brand['id']?>"
                                type="checkbox"
                                id="brand-<?=$brand['id']?>"
                                name="brand[<?=$brand['id']?>]">
                            <span><?=$brand['title']?> (<?=$brand['count']?>)</span>
                        </label>
                    </div>
                <?php }?>
            </div>
        </div>
    <?php }?>
    <input type="hidden" name="page" id="page" value="1">
    <input type="hidden" name="limit" id="limit" value="<?=$limit?>">
    <input type="hidden" name="query" id="query" value="<?=$query?>">
</form>
