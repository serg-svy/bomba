<?php
$uri3=$this->uri->segment(3);

$head1='Категории';
$head2='Редактирование категории';
$head3='name_ru';
$addnew='категорию';
$tblname='category';
$headerloc='category';

$e_path='/'.ADM_CONTROLLER.'/'.$headerloc.'/';
$delpath='/'.ADM_CONTROLLER.'/delete_row/'.$tblname.'/';
$err='';

$form=array();
$form[]=admin_form_text(false,'url','URL категории');
$form[]=admin_form_text(true,'name','Название');
$form[]=admin_form_text(true,'h1','H1');
$form[]=admin_form_text(false,'delivery_price','Стоимость доставки');
$form[]=admin_form_text(true,'seo_title','SEO заголовок');
$form[]=admin_form_text(true,'seo_kw','SEO ключевые слова');
$form[]=admin_form_text(true,'seo_description','SEO описание');
$form[]=admin_form_textarea(true,'description','описание','ckeditor');
$form[]=admin_form_text(true,'fixed_link','Фиксированнаяя ссылка');
$form[]=admin_form_file(false,'image','Фото (300px x 300px)',$tblname);
$form[]=admin_form_file(true,'image_size','Фото с размерами (600px x 600px)',$tblname);
$form[]=admin_form_file(false,'image_terminal','Фото для терминала (300px x 300px)',$tblname);
$form[]=admin_form_text(true,'jivosite','JivoSite - ссылка');
$form[]=admin_form_text(true,'link_terminal_cat','Ссылка банера для терминала');
$form[]=
    array(
        array(
            'name' => "is_russian_size",
            'type' => 'checkbox',
            'class' => 'form-control',
            'descr' => 'Русский размер'
        )
    );
$form[]=
    array(
        array(
            'name' => "is_new",
            'type' => 'checkbox',
            'class' => 'form-control',
            'descr' => 'new'
        )
    );
$form[]=
    array(
        array(
            'name' => "multiple_filters",
            'type' => 'checkbox',
            'class' => 'form-control',
            'descr' => 'Несколько фильтров'
        )
    );
$form[]=
    array(
        array(
            'name' => "is_popular",
            'type' => 'checkbox',
            'class' => 'form-control',
            'descr' => 'Popular'
        )
    );
$form[]=
    array(
        array(
            'name' => "on_drop",
            'type' => 'checkbox',
            'class' => 'form-control',
            'descr' => 'В поиске'
        )
    );

$form[]=admin_form_textarea(false,'tags','ключ для поиска', '');

$form1=convert_form($form);

$files=array('image', 'image_size_ru', 'image_size_ro', 'image_terminal');
$checker=array();

$query = $this->db
->select(
    'name_ru as name,
    id,
    url,
    sorder,
    parent_id,
    is_shown,
    level'
)
->where('level <=',1)
->order_by('sorder asc,id desc')
->get('category');

$categories_result = $query->result_array();

foreach($categories_result as $key=>$row) {
	if (empty($row['url'])) $row['url']=$row['id'];
	$categories[$row['id']] = $row;
}

$searches = [];
if(isset($_GET['query']) and !empty($_GET['query'])) {
    $searches = $this->db->select("
        name_ru as name,
        id,
        url,
        sorder,
        parent_id,
        is_shown,
        level
        ")
        ->group_start()
            ->like('name_ru', $_GET['query'])
            ->or_like('name_ro', $_GET['query'])
        ->group_end()
        ->order_by('sorder asc,id desc')->get('category')->result_array();
}

if ($_SERVER['REQUEST_METHOD']=='POST') {
    $_POST['data']['need_update'] = 1;
	if (!empty($uri3)) {
        if(!empty($_POST['terminal'])) {
            $this->db->where('id', $uri3)->update('category', array('terminal_features' => json_encode($_POST['terminal'])));
        } else {
            $this->db->where('id', $uri3)->update('category', array('terminal_features' => null));
        }

		$inserter = $_POST['data'];
		if (!empty($inserter['is_manual'])) {
			if ($inserter['parent_id']==0) {
				$inserter['level'] = 1;
			} else {
				$pcat = $this->db->where('id',$inserter['parent_id'])->get('category')->row_array();
				$inserter['level'] = $pcat['level']+1;
			}
		}
		$err=save_data($inserter,$checker,$files,$tblname,$form1,true);


	} else {
		$inserter = $_POST['data'];
		$inserter['sorder'] = 1;
		if ($inserter['parent_id']==0) {
			$inserter['level'] = 1;
		} else {
			$pcat = $this->db->where('id',$inserter['parent_id'])->get('category')->row_array();
			$inserter['level'] = $pcat['level']+1;
		}
		$err=save_data($inserter,$checker,$files,$tblname,$form1,true);
	}



	if (!empty($uri3)) {
		$data=$this->db->where('id',$uri3)->get($tblname)->row_array();

		$prod_id_query = $this->db->select('DISTINCT(category_product.product_id) as id')
            ->from('category_product')
            ->join('product_price','product_price.product_id=category_product.product_id AND product_price.price>0','inner')
            ->join('product_stock','product_stock.product_id=category_product.product_id AND product_stock.quantity>0','inner')
            ->where('category_id',$uri3)->get()->result();
        $id_list = array_map(function($item){return $item->id;},$prod_id_query);


        $filters = get_filters_for_set('ru', $data['set_id'], $id_list, []);

        $inserter = array();
        if(!empty($filters) and is_array($filters)) {
            foreach ($filters as $group) {
                if (isset($group['attributes'])) {
                    foreach ($group['attributes'] as $attribute) {
                        if (!empty($_POST['attr2'][$attribute['id']])) {
                            if (!empty($attribute['values'])) {
                                if ($attribute['attribute_type'] == 'string') {
                                    $vals_ru_a = array();
                                    $vals_ro_a = array();
                                    foreach ($attribute['values'] as $row) {
                                        $vals_ro_a[] = array(
                                            'value' => $row['value_ro'],
                                            'count' => $row['count'],
                                            'position' => $row['position']
                                        );
                                        $vals_ru_a[] = array(
                                            'value' => $row['value'],
                                            'count' => $row['count'],
                                            'position' => $row['position']
                                        );
                                        $vals_ro = json_encode($vals_ro_a, JSON_UNESCAPED_UNICODE);
                                        $vals_ru = json_encode($vals_ru_a, JSON_UNESCAPED_UNICODE);
                                    }
                                } else {
                                    $vals_ru = $vals_ro = json_encode($attribute['values']);
                                }
                            } else {
                                $vals_ru = $vals_ro = '';
                            }
                            $ins = array(
                                'category_id' => $uri3,
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
        }

        $this->db->where('category_id',$uri3)->delete('category_attribute');
		if (!empty($inserter)) $this->db->insert_batch('category_attribute', $inserter);

        curl_get('category/deleteCache', true);
	}

	if (is_numeric($err)) {
		$cid = $err;
		$url = $_POST['data']['url'];
		$simcats = $this->db->select('id,name_ru')->from('category')->where('url',$url)->get()->result_array();
		if (count($simcats)>1) {
			$err = '<p style="color:#ff0000;">Со ссылкой "'.$url.'" были найдены следующие категории: <br />';
			foreach($simcats as $cat) {
				if ($cat['id']==$cid) continue;
				$err.='<a target="_blank" href="/'.ADM_CONTROLLER.'/category/'.$cat['id'].'/">'.$cat['name_ru'].'</a><br>';
			}
			$err.='</p>';
		}
	}
}
standart_form_script($tblname);
?>

<?php if (empty($uri3)) {?>
	<div class="page-bar">
		<ul class="page-breadcrumb">
			<li>
				<i class="fa fa-home"></i>
				<a href="/<?=ADM_CONTROLLER?>/menu/">Главная</a>
				<i class="fa fa-angle-right"></i>
			</li>
			<li>
                <a href="/<?=ADM_CONTROLLER?>/<?=$headerloc?>/"><?=$head1?></a>
			</li>
		</ul>
	</div>


<?php
	if (!is_numeric($err)) echo $err;
	?>

	<script>
        $(document).on("keyup", ".changesord", function() {
            val=$(this).val();
            id=$(this).attr('cid');
            $.get('/<?=ADM_CONTROLLER?>/catsort/'+id+'/'+val+'/');
        });
	</script>
	<form name="form1" method="POST" action="<?=$e_path?>" enctype="multipart/form-data">
		<div class="portlet box">
			<div class="portlet-title">
				<div class="caption" style="color:#888;font-size:26px;">
					<?=$head1?>
				</div>
				<div class="tools" style="display:none;">
					<a href="javascript:;" class="expand"></a>
				</div>
				<div class="actions">
					<a class="btn default yellow-stripe">
					<i class="fa fa-plus"></i>
					<span class="hidden-480">
					Добавить <?=$addnew?></span>
					</a>
				</div>
			</div>
			<div class="portlet-body" style="<?php if (empty($err)) echo 'display:none;';?>">
				<div class="">
					<table class="table table-striped table-bordered table-hover">
						<tr>
							<td width="200">Родительская категория</td>
							<td>
								<select class="form-control" name="data[parent_id]">
									<option value="0"></option>
                                    <?php
									foreach($categories as $category) {
										echo '<option value="'.$category['id'].'">'.$category['name'].'</option>';
									}
									?>
								</select>
								<input type="hidden" name="data[is_manual]" value="1">
								<input type="hidden" name="data[is_shown]" value="1">
							</td>
						</tr>
                        <?php
						create_form_by_array($form1,@$_POST['data']);
						?>
						<tr>
							<td>&nbsp;</td>
							<td><button type="submit" class="btn green"><i class="fa fa-check"></i> Добавить</button></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</form><br />


	<script>
	function toggleCat(id,obj) {
        if(!$('.cat-'+id).length) {
            $.post('/<?=ADM_CONTROLLER?>/getSubcategories/', { category_id: id}, function(r) {
                if(r.status == 'ok') {
                    $(obj).closest('tr').after(r.html);
                } else if(r.status == 'empty') {
                    alert('У данной категории нет подкатегорий');
                } else {
                    alert('Произошла ошибка, попробуйте еще раз!');
                }
            }, 'json');
        }
	}

	$(document).ready(function() {
		$(document).on('change', '.changer2', function() {
            let id=$(this).attr('oid');
            let val;
            if ($(this).is(':checked')) {
                val=1;
            } else {
                val=0;
            }
            $.post('/<?=ADM_CONTROLLER?>/checkboxChange/', { "table": "<?=$tblname?>", "col": "is_shown", "id":id, "val":val } );
		});

		$(document).on('change', '.changer4', function() {
            let id=$(this).attr('oid');
            let val;
            if ($(this).is(':checked')) {
                val=1;
            } else {
                val=0;
            }
            $.post('/<?=ADM_CONTROLLER?>/checkboxChange/', { "table": "<?=$tblname?>", "col": "show_popup_18", "id":id, "val":val } );
		});

		$(document).on('change', '.changer5', function() {
            let id=$(this).attr('oid');
            let val;
            if ($(this).is(':checked')) {
                val=1;
            } else {
                val=0;
            }
            $.post('/<?=ADM_CONTROLLER?>/checkboxChange/', { "table": "<?=$tblname?>", "col": "is_russian_size", "id":id, "val":val } );
		});

        $(document).on('change', '.changer7', function() {
            let id=$(this).attr('oid');
            let val;
            if ($(this).is(':checked')) {
                val=1;
            } else {
                val=0;
            }
            $.post('/<?=ADM_CONTROLLER?>/checkboxChange/', { "table": "<?=$tblname?>", "col": "is_new", "id":id, "val":val } );
        });
	});
	</script>
    <?php if(!empty($categories) or !empty($searches)) {?>
        <form method="get">
            <input type="text" class="form-control" placeholder="Напишите что-нибудь" value="<?=@$_GET['query']?>" name="query" style="width:200px;margin-right:20px;float:left;">
            <button type="submit" class="btn green"><i class="fa fa-check"></i> Применить</button>
            <button type="button" class="btn red" onclick="window.location=window.location.pathname;"><i class="fa fa-trash"></i> Очистить</button>
        </form>
        <br>
        <?php if(!empty($searches)) {?>
            <div class="">
                <table class="table table-striped table-bordered table-hover dataTable no-footer">
                    <tr class="heading nodrop nodrag">
                        <th>Название</th>
                        <td width="250"></td>
                        <th width="220">Действия</th>
                    </tr>
                    <?php foreach($searches as $cat) {?>
                        <tr>
                            <td><a style="font-size: 20px; text-decoration: none"><?=$cat['name']?></a></td>
                            <td></td>
                            <td>
                                <a href="<?=$e_path.$cat['id']?>/" class="btn blue"><i class="fa fa-pencil"></i> Редактировать</a>
                                <a href="<?=$e_path.'delete/'.$cat['id']?>/" class="btn red category_remove"><i class="fa fa-trash"></i> </a>
                            </td>
                        </tr>
                    <?php }?>
                </table>
            </div>
	    <?php }?>
        <?php if(!empty($categories) and empty($searches)) {?>
            <div class="">
                <table class="table table-striped table-bordered table-hover dataTable no-footer">
                    <tr class="heading nodrop nodrag">
                        <th width="40">Порядок</th>
                        <th>Название</th>
                        <td width="250"></td>
                        <th width="220">Действия</th>
                    </tr>
                    <?php foreach($categories as $category) {?>
                        <tr>
                            <td oid="<?=$category['id']?>" class="sorthold">
                                <input type="text" class="form-control changesord" cid="<?=$category['id']?>" value="<?=$category['sorder']?>" name="sord[<?=$category['id']?>]">
                            </td>
                            <td><a style="margin-left: 20px; font-size: 24px; text-decoration: none" onclick="toggleCat(<?=$category['id']?>,this);" href="javascript:void(0);"><?=$category['name']?></a></td>
                            <td></td>
                            <td>
                                <a href="<?=$e_path.$category['id']?>/" class="btn blue"><i class="fa fa-pencil"></i> Редактировать</a>
                                <a href="<?=$e_path.'delete/'.$category['id']?>/" class="btn red category_remove"><i class="fa fa-trash"></i> </a>
                            </td>
                        </tr>
                    <?php }?>
                </table>
            </div>
	    <?php }?>
	<?php }?>
<?php } else {?>
	<?php
        $data=$this->db->where('id',$uri3)->get($tblname)->row_array();
	    $terminal_features = !empty($data['terminal_features']) ? json_decode($data['terminal_features'], true) : array();
	?>
	<div class="page-bar">
		<ul class="page-breadcrumb">
			<li>
				<i class="fa fa-home"></i>
				<a href="/<?=ADM_CONTROLLER?>/menu/">Главная</a>
				<i class="fa fa-angle-right"></i>
			</li>
			<li>
				<a href="/<?=ADM_CONTROLLER?>/<?=$headerloc?>/"><?=$head1?></a>
				<i class="fa fa-angle-right"></i>
			</li>
			<li>
				<a>Редактирование "<?=$data[$head3]?>"</a>
			</li>
		</ul>
	</div>
                <?php
	if (!is_numeric($err)) echo $err;

	function categoryTree($data, $parent_id=0, $space=0, $current=0) {
		foreach ($data as $item) {
			if ($item['parent_id'] == $parent_id) {
				?>
				<option value="<?=$item['id']?>" <?=$current==$item['id']?'selected':''?>><?=str_pad('', $space, '-')?><?=' '.$item['name']?></option>
				<?php
				categoryTree($data, $item['id'], $item['level'], $current);
			}
		}
	}
	?>

	<form name="form1" method="POST" action="<?=$e_path.$uri3;?>/" enctype="multipart/form-data">
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
                            <tr>
                                <td>Родительская категория</td>
                                <td>
                                    <select name="data[parent_id]" id="" class="form-control">
                                        <option value="0">- Без родителя -</option>
                                        <?php categoryTree($this->db->select(['id', 'parent_id', 'name_ru AS name', 'level'])->get('category')->result_array(), 0, 0, $data['parent_id']);?>
                                    </select>
                                </td>
                            </tr>
                            <?php $brands = $this->db->from('brand')->select(['id', 'name'])->where('id >', 0)->get()->result()?>
                            <tr>
                                <td width="200">Бренд</td>
                                <td>
                                    <select class="form-control" name="data[brand_id]">
                                        <option value="0"></option>
                                        <?php
                                            foreach ($brands as $brand) {
                                                ?>
                                                <option value="<?=$brand->id?>" <?=$brand->id==$data['brand_id']?'selected':''?>><?=$brand->name?></option>
                                                <?php
                                            }
										?>
                                    </select>
                                    <input type="hidden" name="data[is_manual]" value="1">
                                </td>
                            </tr>
                            <?php
							create_form_by_array($form1,$data);
							?>
							<tr>
								<td>&nbsp;</td>
								<td><button type="submit" class="btn green"><i class="fa fa-check"></i> Обновить</button></td>
							</tr>
						</table>
					</div>
				</div>

				<div class="tab-pane" id="tab_1_2">
                    <div class="">
                        <table class="table table-striped table-bordered table-hover">
                            <tr class="heading nodrop nodrag">
                                <td width="100">Вывод в фильтрах</td>
                                <td width="100">Открыт</td>
                                <td width="100">Порядок в фильтрах</td>
                                <td width="100">Тип</td>
                                <td width="220">Название</td>
                                <td>Значения</td>
                                <td width="220">Для терминала</td>
                            </tr>
                            <?php
                            $prod_id_query = $this->db->select('DISTINCT(category_product.product_id) as id')
                                ->from('category_product')
                                ->join('product','category_product.product_id = product.id','inner')
                                ->join('product_price','product_price.product_id = product.id','inner')
                                ->where('product_price.price >',0)
                                ->where('category_id',$uri3)->get()->result();

                            $id_list = array_unique(array_map(function($item){return $item->id;},$prod_id_query));

                            $filters = get_filters_for_set('ru', $data['set_id'], $id_list, []);

                            $mnflt = $this->db->select('attribute_id,sorder,checked,opened')->where('category_id',$uri3)->get('category_attribute')->result_array();
                            $clist=array();
                            $clist2=array();
                            $clist3=array();
                            foreach($mnflt as $row) {
                                $clist[$row['attribute_id']]=$row['sorder'];
                                $clist2[$row['attribute_id']]=$row['checked'];
                                $clist3[$row['attribute_id']]=$row['opened'];
                            }

                            $f1=$f2=array();

                            if(isset($filters)) {
                                foreach ($filters as $group) {
                                    if (isset($group['attributes'])) {
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
                            }

                            function sort_elems($a,$b) {
                                return $a['real_sorder']>$b['real_sorder'];
                            }

                            usort($f1,'sort_elems');

                            $result = array_merge($f1,$f2);
                            foreach($result as $attribute) {
                                echo '<tr>';
                                if (!empty($clist2[$attribute['id']])) $mod=' checked'; else $mod='';
                                if (!empty($clist3[$attribute['id']])) $mod2=' checked'; else $mod2='';
                                echo "<td><input class=\"form-control\" type=\"checkbox\" name=\"attr2[".$attribute['id']."]\"$mod value=\"1\"></td>";
                                echo "<td><input class=\"form-control\" type=\"checkbox\" name=\"attr3[".$attribute['id']."]\"$mod2 value=\"1\"></td>";
                                if (!empty($clist2[$attribute['id']]))
                                    echo "<td><input class=\"form-control\" type=\"text\" name=\"attr[".$attribute['id']."]\" value=\"".intval(@$clist[$attribute['id']])."\"  style=\"height:24px;text-align:center;\"></td>";
                                else
                                    echo '<td></td>';
                                echo '<td>'.$attribute['attribute_type'].'</td>';
                                echo '<td>'.$attribute['name'].'</td>';
                                echo '<td>';
                                if (!empty($attribute['values'])) {
                                    if (!empty($attribute['values']['min'])) {
                                        echo 'от '.$attribute['values']['min'].' до '.$attribute['values']['max'];
                                    } else {
                                        $cval=array();
                                        foreach($attribute['values'] as $row) {
                                            $cval[]=$row['value'];
                                        }
                                        $cva=implode(', ',$cval);
                                        echo $cva;
                                    }
                                }
                                echo '</td>';
                                echo '<td>';

                                if(!empty($mod) || $attribute['name'] == 'Бренд') {
                                    if (!empty($attribute['values'])) {
                                        if (!isset($attribute['values']['min'])) {
                                            foreach($attribute['values'] as $row) {
                                                $checked = '';
                                                if(isset($terminal_features[$attribute['id']]) && $key = array_search($row['value'], $terminal_features[$attribute['id']]) !== false) {
                                                    $checked = 'checked';
                                                }

                                                echo '<input type="checkbox" '.$checked.' name="terminal['.$attribute['id'].'][]" value="'.$row['value'].'"> '.$row['value'].'<br/>';
                                            }
                                        }
                                    }
                                }

                                echo '</td>';
                                echo '</tr>';
                            }
                            ?>
                            <tr class="heading nodrop nodrag">
                                <td colspan="7"><button type="submit" class="btn green"><i class="fa fa-check"></i> Обновить</button></td>
                            </tr>
                        </table>
                    </div>
                </div>

			</div>
		</div>
	</form><br />
            <?php } ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>
    $(document).ready(function() {

        $('input[name=image_terminal]').change(function () {
            let _self = $(this);
            var file = $(this)[0].files[0];
            var _URL = window.URL || window.webkitURL;

            var img = new Image();

            var minWidth = 300;
            var minHeight = 300;

            img.src = _URL.createObjectURL(file);
            img.onload = function() {

                imgwidth = this.width;
                imgheight = this.height;

                if ((imgwidth !== minWidth) || (imgheight !== minHeight)){
                    img.onload = null;
                    _self.val('');
                    Swal.fire({
                        title: "Статус",
                        text: "Размеры изображений не допускаются. размеры должны быть точными, как указано в описании",
                        type: "warning",
                        showCancelButton: true,
                        showConfirmButton: false,
                        confirmButtonClass: "btn-danger",
                        cancelButtonText: "OK",
                        closeOnCancel: true
                    });
                }

            }
        });


        $(document).on('click', '.category_remove', function(e) {
            e.preventDefault();

            var that = $(this);

            Swal.fire({
                title: 'Введите пароль для удаления',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                cancelButtonText: 'Отмена',
                confirmButtonText: 'Удалить',
                showLoaderOnConfirm: true,
                preConfirm: (pass) => {
                    return fetch(that.attr('href') + '?pass=' + pass)
                        .then(response => {
                            if(response.ok) {
                                return response.json();
                            }
                            throw new Error(response.statusText);
                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                                `Или пароль пустой или не совпадает`
                            )
                        });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.value) {
                    window.location.reload();
                }
            })
        });
    });
</script>
<style>
    .flex_checkbox {
        display: flex;
    }
    .flex_checkbox label {
        flex-basis: 25%;
        text-align: center;
    }
    .space {
        display: block;
        min-width: 30px;
    }
</style>
