<?php
$uri3=$this->uri->segment(3);

$head1='Отфильтрованные товары в категории';
$head2='Страницы с отфильтрованными товарами';
$head3='meta_title';
$addnew='Название';
$tblname='category';
$headerloc='category_filtered';
$err='';
$e_path='/'.ADM_CONTROLLER.'/'.$headerloc.'/';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $check_link = $this->db->where('id', $uri3)->get('category_filtered')->row();
    if(empty($check_link)) {
        $action_type = 'insert';
    }else{
        $action_type = 'update';
    }
    $post = $_POST;
    echo '<pre>';
    print_r($post);
    echo '</pre>';
}

?>

<?if (empty($uri3)) {?>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/<?=ADM_CONTROLLER?>/menu/">Главная</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a><?=$head1?></a>
            </li>
        </ul>
    </div>

    <?=$err?>
    <form class="form-inline" id="get_id" name="form1" method="POST" action="<?=$e_path?>" enctype="multipart/form-data">
        <div class="portlet box">
            <div class="portlet-title">
                <div  style="color:#888;font-size:26px;">
                    <?=$head1?>
                </div>
            </div>
            <div class="portlet-body" >
                <?php
                $this->load->helper('file');
                $mysitemap = read_file(FCPATH.'seocategory_sitemap.xml');
                if(!$mysitemap){ ?>
                    <div class="alert alert-warning">Файл sitemap не был сгенерирован <?php  echo '<a href="https://'.$_SERVER['HTTP_HOST'].'/'.ADM_CONTROLLER.'/render_category_sitemap/" class="btn btn-danger">'; ?>Сгенерировать sitemap</a></div>
                <?php }else{ ?>
                    <div class="alert alert-info">Файл sitemap сгенерирован по ссылке: <?php  echo '<a href="https://'.$_SERVER['HTTP_HOST'].'/seocategory_sitemap.xml">'; ?>seocategory_sitemap.xml</a> - <?php  echo '<a href="https://'.$_SERVER['HTTP_HOST'].'/'.ADM_CONTROLLER.'/render_category_sitemap/" target="_blank" class="btn btn-success">'; ?>Обновить sitemap</a></div>
                <?php } ?>
            </div>
            <div class="portlet-body" >
                <div class="" style="padding:10px 15px;">
                    <div>
                        <input type="text" style="width:50% !important;" class="form-control" name="identificator" id="identificator" placeholder="Название категории" />
                        <button name="check" class="btn btn-success addProduct">Выбрать</button>

                    </div>
                </div>
            </div>
        </div>
    </form>
    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <td colspan="3" style="text-align:center;">Результаты поиска</td>
        </tr>
        <tr><td width="200">ID</td><td>Название категории</td><td>Изменить</td></tr>
        </thead>
        <tbody id="result-elements">
        <tr>
            <td colspan="3" style="text-align:center; color: red;">список пуст</td>
        </tr>
        </tbody>
    </table>
    <script type="text/javascript">
        function show_elements(result) {
            $("#result-elements").html(' ');
            $.each(result, function (key, item) {
                $("#result-elements").append("<tr><td>" + item.id + "</td><td>" + item.name_ru + "</td><td><a href='/<?=ADM_CONTROLLER?>/category_filtered/" + item.id + "/1/' class='btn blue'><i class=\"fa fa-pencil\"></i>Создать фильтрацию</a></td></tr>");
            });
        }
        $("#get_id").submit(function(e){
            return false;
        });
        $(document).ready(function() {
            $('#identificator').change(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: '/<?=ADM_CONTROLLER?>/category_search_list/',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response){
                        if (response.res != '1'){
                            show_elements(response);
                        }else{
                            alert('Не найдено категории!');
                        }
                    }
                });
            });
        });
    </script>
    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <td colspan="3" style="text-align:center;">Создание ссылки</td>
        </tr>
        <tr>
            <td>ID</td>
            <td>Имя категории</td>
            <td>Изменить</td>
        </tr>
        </thead>
        <tbody>
        <?php
        $created_links = $this->db->get('category_filtered')->result();
        if(!empty($created_links)){
            foreach($created_links as $link){
                $get_data = $this->db->where('id', $link->category_id)->get('category')->row();
                ?>
                <tr>
                    <td><?=$link->id?></td>
                    <td><b><?=$get_data->name_ru?></b> - <?=$link->title_ro?></td>
                    <td><a href='/<?=ADM_CONTROLLER?>/category_filtered/<?=$link->id?>/' class='btn blue'><i class="fa fa-pencil"></i>Изменить</a>
                        <a href='/<?=ADM_CONTROLLER?>/delete_category_filtered/<?=$link->id?>/' class='btn red'><i class="fa fa-trash"></i></a></td>
                </tr>
            <? }
        }else{
            echo '<tr><td colspan="3" style="text-align:center; color:red;">список пуст</td></tr>';
        }
        ?>
        </tbody>
    </table>

<? } else { ?>

    <div class="portlet box">
        <div class="portlet-title">
            <div  style="color:#888;font-size:26px;">
                <?=$head2?>
            </div>
        </div>
        <div class="portlet-body" >
        </div>
    </div>

    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/<?=ADM_CONTROLLER?>/menu/">Главная</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="/<?=ADM_CONTROLLER?>/category_filtered/"><?=$head1?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a><?=$head2?></a>
            </li>
        </ul>
    </div>

    <?=$err?>

    <? $categ_data = $this->db->where('id', $uri3)->get('category_filtered')->row();
    $categ = $this->db->where('id', $categ_data->category_id)->get('category')->row();
    if($categ){
        $getdatafilt = $this->db->where('id', $uri3)->get('category_filtered')->row();
        if($getdatafilt){
            $filtercateg = array(
                'rendered_link' => $getdatafilt->rendered_link,
                'title_ro' => $getdatafilt->title_ro,
                'title_ru' => $getdatafilt->title_ru,
                'meta_title_ro' => $getdatafilt->meta_title_ro,
                'meta_title_ru' => $getdatafilt->meta_title_ru,
                'seo_desc_ro' => $getdatafilt->seo_desc_ro,
                'seo_desc_ru' => $getdatafilt->seo_desc_ru,
                'desc_ro' => $getdatafilt->desc_ro,
                'desc_ru' => $getdatafilt->desc_ru,
                'attr_json_data' => json_decode($getdatafilt->attr_json_data),
                'index_page' => $getdatafilt->index_page
            );
        }else{
            $filtercateg = array(
                'rendered_link' => '',
                'title_ro' => '',
                'title_ru' => '',
                'meta_title_ro' => '',
                'meta_title_ru' => '',
                'seo_desc_ro' => '',
                'seo_desc_ru' => '',
                'desc_ro' => '',
                'desc_ru' => '',
                'index_page' => 0
            );
        }
    }else{
        header('Location: /'.ADM_CONTROLLER.'/category_filtered/');
    }
    ?>

    <form class="form" name="form1" id="add-form" action="/<?=ADM_CONTROLLER?>/category_filtered_validate/"  method="POST" enctype="multipart/form-data">
        <div class="portlet box">
            <div class="portlet-body">
                <div class="" >
                    <table class="table table-striped table-bordered table-hover">
                        <tr>
                            <td>
                                <?php
                                $filtered_link = $this->db->where('id', $this->uri->segment(3))->get('category_filtered')->row();
                                if(empty($filtered_link)){
                                    ?>
                                    <input type="text" style="color:red;text-align: center; cursor: default; border-color:red;" value="После генерации ваша ссылка появится здесь" class="form-control" readonly>
                                <?php }else{ ?>
                                    <input type="text" style="color:blue;text-align: left; cursor: default !important; border-color: green;" value="<?=$filtered_link->rendered_link?>" class="form-control" readonly>

                                <?php } ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="tabbable tabbable-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab_1_1" data-toggle="tab">Общая информация</a>
                </li>
                <li>
                    <a href="#tab_1_2" data-toggle="tab">Фильтры</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1_1">
                    <div class="">
                        <table class="table table-striped table-bordered table-hover">
                            <tbody>
                            <tr style="display:none">
                                <td></td>
                                <td style=" font-size: medium"><input type="hidden" name="category_id" value="<?php echo $categ->id; ?>"></td>
                            </tr>
                            <tr style="display:none">
                                <td></td>
                                <td style=" font-size: medium"><input type="hidden" name="filt_id" value="<?php echo $uri3; ?>"></td>
                            </tr>
                            <tr>
                                <td>Категория: </td>
                                <td style=" font-size: medium"><?php echo $categ->name_ru; ?></td>
                            </tr>
                            <tr>
                                <td>Название ru: </td>
                                <td style=" font-size: medium"><input type="text" name="title_ru" value="<?=$filtercateg['title_ru']?>" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>Название ro: </td>
                                <td style=" font-size: medium"><input type="text" name="title_ro" value="<?=$filtercateg['title_ro']?>" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>SEO заголовок ru: </td>
                                <td style=" font-size: medium"><input type="text" name="meta_title_ru" value="<?=$filtercateg['meta_title_ru']?>" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>SEO заголовок ro: </td>
                                <td style=" font-size: medium"><input type="text" name="meta_title_ro" value="<?=$filtercateg['meta_title_ro']?>" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>SEO описание ru: </td>
                                <td style=" font-size: medium"><textarea name="seo_desc_ru" class="form-control"><?=$filtercateg['seo_desc_ru']?></textarea></td>
                            </tr>
                            <tr>
                                <td>SEO описание ro: </td>
                                <td style=" font-size: medium"><textarea name="seo_desc_ro" class="form-control"><?=$filtercateg['seo_desc_ro']?></textarea></td>
                            </tr>
                            <tr>
                                <td>описание ru: </td>
                                <td style=" font-size: medium"><textarea name="desc_ru" class="form-control"><?=$filtercateg['desc_ru']?></textarea></td>
                            </tr>
                            <tr>
                                <td>описание ro: </td>
                                <td style=" font-size: medium"><textarea name="desc_ro" class="form-control"><?=$filtercateg['desc_ro']?></textarea></td>
                            </tr>
                            <tr>
                                <td>Индексировать Страницу: </td>
                                <td style=" font-size: medium">
                                    <select name="index_page" class="form-control">
                                        <option value="0" <?php if($filtercateg['index_page'] == 0) echo 'selected="selected"'; ?>>Нет</option>
                                        <option value="1" <?php if($filtercateg['index_page'] == 1) echo 'selected="selected"'; ?>>Да</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td width="200"></td>
                                <td>
                                    <button type="submit" class="btn btn-success" >Генерировать</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="tab_1_2">
                    <div class="">
                        <table class="table table-striped table-bordered table-hover">
                            <tr class="heading nodrop nodrag">
                                <td width="40">Включить / Отключить</td>
                                <td>Название</td>
                                <td>Значение</td>
                            </tr>
                            <?php
                            $prods_id = $this->db->select('product.id as id, product.brand_id as brand')
                                ->join('category_product', 'category_product.product_id = product.id')
                                ->where('category_product.category_id', $categ_data->category_id)
                                ->where('product.is_shown', 1)
                                ->get('product')
                                ->result_array();

                            if(empty($prods_id)) echo '<tr><td colspan="4" style="text-align: center;color:red">Ничего не найдено для этой категории</td></tr>';

                            if (!empty($categ_data->category_id)) {

                                $mnflt = $this->db->select('attribute_id,sorder,checked,opened')->where('category_id',$categ_data->category_id)->get('category_attribute')->result_array();
                                $clist=array();
                                $clist2=array();
                                $clist3=array();
                                $attribute_id_list=[];
                                foreach($mnflt as $row) {
                                    $clist[$row['attribute_id']]=$row['sorder'];
                                    $clist2[$row['attribute_id']]=$row['checked'];
                                    $clist3[$row['attribute_id']]=$row['opened'];
                                    $attribute_id_list[] = $row['attribute_id'];
                                }

                                $data=$this->db->where('id',$categ_data->category_id)->get($tblname)->row_array();

                                $prod_id_query = $this->db->select('DISTINCT(category_product.product_id) as id')->from('category_product')->join('product_price','product_price.product_id=category_product.product_id AND product_price.price>0','inner')->where('category_id',$categ_data->category_id)->get()->result_array();

                                $id_list = array(0);
                                foreach($prod_id_query as $product) {
                                    $id_list[] = $product['id'];
                                }
                                $filters = get_filters_for_set('ru', $data['set_id'],$id_list, $attribute_id_list);

                                $inserter = array();
                                foreach($filters as $group) {
                                    if(isset($group['attributes']) and is_array($group['attributes'])) {
                                        foreach ($group['attributes'] as $attribute) {
                                            if (!empty($_POST['attr2'][$attribute['id']])) {
                                                if (!empty($attribute['values'])) {
                                                    if ($attribute['attribute_type'] == 'string') {
                                                        $vals_ru_a = array();
                                                        $vals_ro_a = array();
                                                        foreach ($attribute['values'] as $row) {
                                                            $vals_ru_a[] = array(
                                                                'value' => $row['value'],
                                                                'count' => $row['count'],
                                                                'position' => $row['position']
                                                            );
                                                            $vals_ro_a[] = array(
                                                                'value' => $row['value_ro'],
                                                                'count' => $row['count'],
                                                                'position' => $row['position']
                                                            );
                                                            $vals_ro = json_encode($vals_ro_a);
                                                            $vals_ru = json_encode($vals_ru_a);
                                                        }
                                                    } else {
                                                        $vals_ru = $vals_ro = json_encode($attribute['values']);
                                                    }
                                                } else {
                                                    $vals_ru = $vals_ro = '';
                                                }
                                                $ins = array(
                                                    'category_id' => $categ_data->category_id,
                                                    'opened' => intval(@$_POST['attr3'][$attribute['id']]),
                                                    'attribute_id' => $attribute['id'],
                                                    'type' => $attribute['attribute_type'],
                                                    'name_ru' => @$attribute['name'],
                                                    'name_ro' => @$attribute['name_ro'],
                                                    'sorder' => intval(@$_POST['attr'][$attribute['id']]),
                                                    'checked' => 1,
                                                    'values_ru' => $vals_ru,
                                                    'values_ro' => $vals_ro
                                                );

                                                $inserter[] = $ins;
                                                $attrList[] = $attribute['id'];

                                            }

                                        }
                                    }
                                }

                                if (!empty($attrList) && !empty($id_list)) {
                                    $attr_val_cache = array();
                                    $list = array();

                                    if(count($id_list) > 999) {

                                        $new_data = array();
                                        $i = 0;

                                        foreach ($id_list as $id) {
                                            $i++;
                                            $new_data[$i] = $id;

                                            if ($i == 999) {
                                                // scoatem cate 1000
                                                $list = $this->db->where_in('product_id', $new_data)->where_in('attribute_id', $attrList)->get('product_attribute_value')->result_array();

                                                foreach($list as $val) {
                                                    $ins=array(
                                                        'category_id'=>$categ_data->category_id,
                                                        'product_id'=>$val['product_id'],
                                                        'attribute_id'=>$val['attribute_id'],
                                                        'value_ro'=>$val['value_ro'],
                                                        'value_ru'=>$val['value_ru']
                                                    );
                                                    $attr_val_cache[]=$ins;
                                                }

                                                if (!empty($attr_val_cache)) {

                                                    if(count($attr_val_cache) > 999) {
                                                        $new_attr_val_cache = array();
                                                        $i=0;
                                                        foreach($attr_val_cache as $attr) {
                                                            $i++;
                                                            $new_attr_val_cache[$i] = $attr;
                                                            if($i==999) {
                                                                // inseram cate 1000
//                                                                    $this->db->insert_batch('product_attribute_value_cached', $new_attr_val_cache);
                                                                $new_attr_val_cache = array();
                                                                $i=0;
                                                            }
                                                        }
                                                        // inseram ultimle ramase
//                                                            $this->db->insert_batch('product_attribute_value_cached', $new_attr_val_cache);
                                                    } else {
//                                                            $this->db->insert_batch('product_attribute_value_cached', $attr_val_cache);
                                                    }
                                                }

                                                $new_data = array();
                                                $i = 0;
                                            }
                                        }
                                        $list = $this->db->where_in('product_id', $new_data)->where_in('attribute_id', $attrList)->get('product_attribute_value')->result_array();

                                        foreach($list as $val) {
                                            $ins=array(
                                                'category_id'=>$categ_data->category_id,
                                                'product_id'=>$val['product_id'],
                                                'attribute_id'=>$val['attribute_id'],
                                                'value_ro'=>$val['value_ro'],
                                                'value_ru'=>$val['value_ru']
                                            );
                                            $attr_val_cache[]=$ins;
                                        }

                                        if (!empty($attr_val_cache)) {

                                            if(count($attr_val_cache) > 999) {
                                                $new_attr_val_cache = array();
                                                $i=0;
                                                foreach($attr_val_cache as $attr) {
                                                    $i++;
                                                    $new_attr_val_cache[$i] = $attr;
                                                    if($i==999) {
                                                        // inseram cate 1000
//                                                            $this->db->insert_batch('product_attribute_value_cached', $new_attr_val_cache);
                                                        $new_attr_val_cache = array();
                                                        $i=0;
                                                    }
                                                }
                                                // inseram ultimle ramase
//                                                    $this->db->insert_batch('product_attribute_value_cached', $new_attr_val_cache);
                                            } else {
//                                                    $this->db->insert_batch('product_attribute_value_cached', $attr_val_cache);
                                            }
                                        }
                                    } else {
                                        $list = $this->db->where_in('product_id', $id_list)->where_in('attribute_id', $attrList)->get('product_attribute_value')->result_array();

                                        foreach($list as $val) {
                                            $ins=array(
                                                'category_id'=>$categ_data->category_id,
                                                'product_id'=>$val['product_id'],
                                                'attribute_id'=>$val['attribute_id'],
                                                'value_ro'=>$val['value_ro'],
                                                'value_ru'=>$val['value_ru']
                                            );
                                            $attr_val_cache[]=$ins;
                                        }

                                        if (!empty($attr_val_cache)) {

                                            if(count($attr_val_cache) > 999) {
                                                $new_attr_val_cache = array();
                                                $i=0;
                                                foreach($attr_val_cache as $attr) {
                                                    $i++;
                                                    $new_attr_val_cache[$i] = $attr;
                                                    if($i==999) {
                                                        // inseram cate 1000
//                                                            $this->db->insert_batch('product_attribute_value_cached', $new_attr_val_cache);
                                                        $new_attr_val_cache = array();
                                                        $i=0;
                                                    }
                                                }
                                                // inseram ultimle ramase
//                                                    $this->db->insert_batch('product_attribute_value_cached', $new_attr_val_cache);
                                            } else {
//                                                    $this->db->insert_batch('product_attribute_value_cached', $attr_val_cache);
                                            }
                                        }
                                    }
                                }
                            }

                            if (is_numeric($err)) {
                                $cid = $err;
                                $url = $_POST['data']['url'];
                                $simcats = $this->db->select('id,name_ru')->from('category')->where('url',$url)->get()->result_array();
                                if (count($simcats)>1) {
                                    $err = '<p style="color:#ff0000;">Со ссылкой '.$url.' были найдены следующие категории: <br />';
                                    foreach($simcats as $cat) {
                                        if ($cat['id']==$cid) continue;
                                        $err.='<a target="_blank" href="/'.ADM_CONTROLLER.'/category/'.$cat['id'].'/">'.$cat['name_ru'].'</a><br>';
                                    }
                                    $err.='</p>';
                                }
                            }

                            $f1=$f2=array();

                            foreach($filters as $group) {
                                if(isset($group['attributes']) and is_array($group['attributes'])) {
                                    foreach ($group['attributes'] as $attribute) {
                                        if (!empty($clist2[$attribute['id']])) {
                                            $attribute['real_sorder'] = $clist[$attribute['id']];
                                            $f1[] = $attribute;
                                        } else {
                                            $f2[] = $attribute;
                                        }
                                    }
                                }
                            }

                            function sort_elems($a,$b) {
                                return $a['real_sorder']>$b['real_sorder'];
                            }

                            usort($f1,'sort_elems');

                            $result = array_merge($f1,$f2);

                            //get specify brands
                            $brand_ids = array();
                            foreach($prods_id as $key => $item){
                                $brand_ids[] = $item['brand'];
                            }
                            $brands = $this->db->where_in('id', $brand_ids)->get('brand')->result_array();
                            $jsondata = $filtercateg['attr_json_data'];

                            echo '<tr>';
                            echo '<td colspan="2" style="text-align: center">Производитель</td>';
                            echo '<td>';
                            echo '<select name="brand" class="form-control">';
                            echo '<option value="0" selected="selected">--- Выберите бренд ---</option>';
                            foreach($brands as $key => $brand){
                                if($jsondata[0]->brand == $brand['id']){
                                    $bid = $jsondata[0]->brand;
                                    $selected = 'selected="selected"';
                                }else{
                                    $bid = 0;
                                    $selected = '';
                                }
                                echo '<option value="'.$brand['id'].'" '.$selected.'>'.$brand['name'].'</option>';
                            }
                            echo '</select>';
                            echo '</td>';
                            echo '</tr>';
                            $idsatt = [];
                            foreach($jsondata as $key => $atribut){
                                if (!isset($atribut->brand)) {
                                    $idsatt[] = $atribut->atribute;
                                    $vlsatt[] = $atribut->value;
                                }
                            }

                            foreach($result as $attribute) {
                                if (!empty($attribute['values'])) {
                                    if (!empty($attribute['values']['min'])) {
                                    } else {
                                        if($attribute['attribute_type'] == 'string'){
                                            echo '<tr>';
                                            if (!empty($clist2[$attribute['id']])) $mod=' checked'; else $mod='';
                                            if (!empty($clist3[$attribute['id']])) $mod2=' checked'; else $mod2='';
                                            if(in_array($attribute['id'], $idsatt)){
                                                $inpcheck = 'checked="checked"';
                                            }else{
                                                $inpcheck = '';
                                            }

                                            echo "<td><input style=\"height:22px\" class=\"form-control\" type=\"checkbox\" name=\"atribute[status][".$attribute['id']."]\" value=\"".$attribute['id']."\" ".$inpcheck."></td>";

                                            if (!empty($clist2[$attribute['id']]))
                                                echo "<td style=\"display:none\"><input class=\"form-control\" type=\"text\" name=\"sort[".$attribute['id']."]\" value=\"".intval(@$clist[$attribute['id']])."\"  style=\"width:50px;height:24px;text-align:center;\"></td>";
                                            else
                                                echo "<td style=\"display:none\"><input class=\"form-control\" type=\"text\" name=\"sort[".$attribute['id']."]\" value=\"0\"  style=\"width:50px;height:24px;text-align:center;\"></td>";
                                            echo '<td>'.$attribute['name'].'</td>';
                                            echo '<td>';
                                            $cval=array();
                                            $vx = 0;
                                            echo '<select name="atribute[value]['.$attribute['id'].']" class="form-control">';
                                            echo '<option value="'.$vx.'" selected="selected">--- Выбрать Опцию ---</option>';
                                            foreach($attribute['values'] as $row) {
                                                if(in_array($row['value_ro'], $vlsatt)){
                                                    $inpcheck = 'selected="selected"';
                                                }else{
                                                    $inpcheck = '';
                                                }
                                                $cval[]=$row['value'];
                                                $vx++;
                                                echo '<option value="'.$row['value_ro'].'" '.$inpcheck.'>'.$row['value'].'</option>';
                                            }
                                            echo '</select>';
                                            echo '</td>';
                                            echo '</tr>';
                                        }
                                    }

                                }

                            }
                            ?>
                            <tr>
                                <td width="200"></td>
                                <td colspan="3">
                                    <button type="submit" class="btn btn-success" >Генерировать</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </form>



<? } ?>
