<form class="filters" action="" method="GET">
    <div class="category__top">
        <div class="cath3 d-none"><?=FILTERS?></div><img class="category__close d-none" src="/dist/img/icons/Delete.svg">
        <div class="filters__checked d-none ajax__filters">
            <?php $this->load->view("layouts/pages/ajax__filters");?>
        </div>
    </div>
    <div class="category__places d-none"></div>
    <?php if(isset($find_categories) and !empty($find_categories)){?>
        <div class="catalog__categories category__item">
            <div class="cath4"><?=CATEGORIES?></div>
            <ul class="">
                <?php if(uri(2) == $menu['all'][10]->uri) {?>
                    <li><a href="<?=strtok($_SERVER["REQUEST_URI"],'?')?>" class="li-1-span <?=(!isset($cat)) ? 'active_category' : '' ?>"><?=ALL_CATEGORIES?></a></li>
                <?php }?>
                <?php foreach($find_categories as $category) {?>
                    <li class="">
                        <?php $url = (uri(2) == CATEGORY_URI) ? '/'.$lclang.'/'.CATEGORY_URI.'/'.$category['uri'].'/?query='.$query : '?cat='.$category['id'];?>
                        <a href="<?=$url?>" class="li-1-span <?=(isset($cat) and $cat == $category['id']) ? 'active_category' : '' ?>"><?=$category['title']?></a>
                    </li>
                <?php }?>
            </ul>
        </div>
    <?php }?>
    <?php if(isset($cat)) {?>
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
            <div class="catalog__checkboxes category__item">
                <div class="cath4"><span><?=BRANDS?><span class="ft_num"></span></span></div>
                <div class="block__checkboxes">
                    <?php foreach($brands as $key=>$brand) {?>
                        <div class="checkbox__item" style="display: <?=($key > 4) ? 'none' : 'block'?>">
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
                    <?php if(count($brands) > 5) {?>
                        <div class="checkbox__more"><span><?=MORE?> <?=count($brands) - 5?></span></div>
                    <?php }?>
                </div>
            </div>
        <?php }?>
        <?php if(!empty($filters)) {?>
            <?php foreach($filters as $attribute) {?>
                <?php
                $display = (empty($attribute['opened'])) ? '' : 'h4-op';
                if (!empty($attribute['values'])) {
                    $attribute['values'] = json_decode($attribute['values'],true);
                    $attribute['values_ro'] = json_decode($attribute['values_ro'],true);

                    usort($attribute['values'], function ($a, $b) {
                        if ($a['position'] == $b['position'])  return 0;
                        return ($a['position'] > $b['position']) ? 1 : -1;
                    });

                    usort($attribute['values_ro'], function ($a, $b) {
                        if ($a['position'] == $b['position'])  return 0;
                        return ($a['position'] > $b['position']) ? 1 : -1;
                    });
                }
                if (empty($attribute['values'])) continue;

                $get_vals=array();
                if (!empty($_GET['filters'][$attribute['attribute_id']])) {
                    foreach($_GET['filters'][$attribute['attribute_id']] as $get_val) {
                        $get_vals[]=$get_val;
                    }
                }
                ?>
                <div class="catalog__checkboxes category__item">
                    <div class="cath4 <?=$display?>"><span><?=$attribute['name']?><span class="ft_num"></span></span></div>
                    <div class="block__checkboxes">
                        <?php if($attribute['type']=='boolean') {?>
                            <?php
                            $geta=@$_GET['filters'][$attribute['attribute_id']];
                            if (empty($geta)) $geta=array();
                            ?>
                            <div class="checkbox__item">
                                <label for="filter-<?=$attribute['attribute_id']?>-da">
                                    <input type="checkbox"
                                           data-attribute="<?=$attribute['attribute_id']?>"
                                        <?=(in_array(1,$geta)) ? 'checked':''?>
                                           name="filters[<?=$attribute['attribute_id']?>][]"
                                           value="1"
                                           id="filter-<?=$attribute['attribute_id']?>-da">
                                    <span><?=YES?></span>
                                </label>
                            </div>
                            <div class="checkbox__item">
                                <label for="filter-<?=$attribute['attribute_id']?>-nu">
                                    <input type="checkbox"
                                           data-attribute="<?=$attribute['attribute_id']?>"
                                        <?=(in_array(0,$geta)) ? 'checked':''?>
                                           name="filters[<?=$attribute['attribute_id']?>][]"
                                           value="0"
                                           id="filter-<?=$attribute['attribute_id']?>-nu">
                                    <span><?=NO?></span>
                                </label>
                            </div>
                        <?php } elseif($attribute['type']=='string') {?>
                            <?php $i=0;?>
                            <?php foreach($attribute['values'] as $a_key => $a_val) { ?>
                                <?php if($a_val['value'] != '\N') {?>
                                    <?php $value = transliteration($attribute['values_ro'][$a_key]['value']);?>
                                    <div class="checkbox__item" style="display: <?=($i > 4) ? 'none' : 'block'?>">
                                        <label for="filter-<?=$attribute['attribute_id']?>-<?=$value?>">
                                            <input type="checkbox"
                                                   data-attribute="<?=$attribute['attribute_id']?>"
                                                <?=(in_array($value, $get_vals) || in_array($value, $link_parts)) ? ' checked' : '';?>
                                                   name="filters[<?=$attribute['attribute_id']?>][]"
                                                   value="<?=$value?>"
                                                   id="filter-<?=$attribute['attribute_id']?>-<?=$value?>">
                                            <span><?=$a_val['value']?>&nbsp;<span class="count">(<?=$a_val['count']?>)</span></span>
                                        </label>
                                    </div>
                                    <?php $i++;?>
                                <?php }?>
                            <?php }?>
                            <?php if($i > 5) {?>
                                <div class="checkbox__more"><span><?=MORE?> <?=$i - 5?></span></div>
                            <?php }?>
                        <?php } elseif($attribute['type']=='integer' || $attribute['type']=='decimal') {?>
                        <?php }?>
                    </div>
                </div>
            <?php }?>
        <?php }?>
        <input type="hidden" name="page" id="page" value="1">
        <input type="hidden" name="sort" id="sort" value="<?=$sort?>">
        <input type="hidden" name="limit" id="limit" value="<?=$limit?>">
        <input type="hidden" name="store" id="store" value="<?=$get_store?>">
        <input type="hidden" name="query" id="query" value="<?=$query?>">
        <input type="hidden" name="cat" id="cat" value="<?=$cat?>">
    <?php }?>
    <div class="filters__btn">
        <?php
            $reset = "?";
            $reset .= (isset($_GET['query'])) ? 'query='.$_GET['query'].'&' : '';
            $reset .= (isset($_GET['cat'])) ? 'cat='.$_GET['cat'].'&' : '';
            if($reset == "?") $reset = "";
        ?>
        <a href="<?=$reset?>" class="btn2 "><?=RESET_ALL?></a>
        <div class="btn category__close "><?=APPLY?></div>
    </div>
</form>
