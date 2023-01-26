<?
$uri3=$this->uri->segment(3);

$head1='Меню сайта';
$head2='Редактирование меню';
$addnew='пункт меню';
$tblname='menu';
$headerloc='menu';

$e_path='/'.ADM_CONTROLLER.'/menu/';
$delpath='/'.ADM_CONTROLLER.'/delete_row/'.$tblname.'/';
$err='';

$checker=array('title_ru','url','title_ro');

$form1=array(
	array(
		'dop_tr'=>'',
		'dop_style'=>'',
		'class'=>'form-control',
		'type'=>'text',
		'descr'=>'Ссылка',
		'name'=>'url',
		'dop_data'=>'<br /><div style="width: 97%; background-color: #FFFF00; border: 1px solid; padding: 5px;">
			без http://www и т.п. просто английская фраза, без пробелов, отражающая пункт меню, например: <br>
			<em style="font-size: 10px;">Haш подход: our-approach</em>
		</div>'
	),

	array(
		'dop_tr'=>'',
		'dop_style'=>'',
		'type'=>'text',
		'class'=>'form-control',
		'descr'=>'Заголовок RU*',
		'name'=>'title_ru'
	),
	array(
		'dop_tr'=>'',
		'dop_style'=>'',
		'class'=>'form-control',
		'type'=>'text',
		'descr'=>'Заголовок RO*',
		'name'=>'title_ro'
	),
	array(
		'dop_tr'=>'',
		'dop_style'=>'',
		'class'=>'form-control ckeditor',
		'type'=>'textarea',
		'descr'=>'Текст RU',
		'name'=>'text_ru'
	),
	array(
		'dop_tr'=>'',
		'dop_style'=>'',
		'class'=>'form-control ckeditor',
		'type'=>'textarea',
		'descr'=>'Текст RO',
		'name'=>'text_ro'
	),
	array(
        'dop_tr' => '',
        'dop_style' => '',
        'type' => 'select',
        'class' => 'form-control',
        'descr' => 'Раздел в подвале',
        'name' => 'bottom_category_id',
        'source' => 'bottom_category',
        'sourcename' => 'name_ru'
    ),
    array(
        'dop_tr'=>'',
        'dop_style'=>'',
        'type'=>'text',
        'class'=>'form-control',
        'descr'=>'Youtube',
        'name'=>'youtube'
    )
);

$form2=array(
	array(
		'dop_tr'=>'',
		'dop_style'=>'',
		'type'=>'text',
		'class'=>'form-control',
		'descr'=>'Заголовок RU',
		'name'=>'seo_title_ru'
	),
	array(
		'dop_tr'=>'',
		'dop_style'=>'',
		'type'=>'text',
		'class'=>'form-control',
		'descr'=>'Заголовок RO',
		'name'=>'seo_title_ro'
	),
	array(
		'dop_tr'=>'',
		'dop_style'=>'',
		'type'=>'text',
		'class'=>'form-control',
		'descr'=>'Ключевые слова RU',
		'name'=>'seo_kw_ru'
	),
	array(
		'dop_tr'=>'',
		'dop_style'=>'',
		'type'=>'text',
		'class'=>'form-control',
		'descr'=>'Ключевые слова RO',
		'name'=>'seo_kw_ro'
	),
	array(
		'dop_tr'=>'',
		'dop_style'=>'',
		'type'=>'text',
		'class'=>'form-control',
		'descr'=>'Описание RU',
		'name'=>'seo_desc_ru'
	),
	array(
		'dop_tr'=>'',
		'dop_style'=>'',
		'type'=>'text',
		'class'=>'form-control',
		'descr'=>'Описание RO',
		'name'=>'seo_desc_ro'
	)
);

$Name='';
$files=array();
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$erflag=false;

	foreach($checker as $val) {
		if (empty($_POST['data'][$val])) $erflag=true;
	}

	if (!$erflag) {

		$data_array=$_POST['data'];

		if (empty($uri3)) {
			$this->db->insert($tblname,$data_array);
			$id=$this->db->insert_id();
		} else {
			$id=$uri3;
			$this->db->where('ID',$id)->update($tblname,$data_array);
		}

		foreach($files as $filename) {

			if(!empty($_FILES[$filename]['name'])) {

				$this->upload->do_upload($filename);
				$resarr = $this->upload->data();
				$file = $resarr['file_name'];

				if(strtolower($resarr['file_ext']) == '.jpg' || strtolower($resarr['file_ext']) == '.jpeg' || strtolower($resarr['file_ext']) == '.gif' || strtolower($resarr['file_ext']) == '.png')
				{
					$this->db->where('ID',$id)->update($tblname,array($filename=>$file));
				}
			}
		}

		if (!empty($id)) {
			header("Location: /".ADM_CONTROLLER."/$headerloc/");
		exit();
		}
	} else {
		$err.='<div style="padding:10px 0;color:#ff0000;">Все поля отмеченные * обязательны для заполения</div>';
	}
}

?>
<script type="text/javascript">
	function toggleb()
	{
		$("#newb").toggle();
	}
</script>
<script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
<script>
    function sort_top() {
        counter=1;
        data='';
        $.each($('.order_top'),function() {
            $(this).html(counter);
            if (counter<2) breaker=''; else breaker='<>';
            data+=breaker+$(this).attr('oid')+':'+counter;
            counter++;
        });
        $.post('/<?=ADM_CONTROLLER?>/edit_table_order_top/',{data:data,table:'<?=$tblname?>'},function(ret) {

        });
    }

    function sort_bottom() {
        counter=1;
        data='';
        $.each($('.order_bottom'),function() {
            $(this).html(counter);
            if (counter<2) breaker=''; else breaker='<>';
            data+=breaker+$(this).attr('oid')+':'+counter;
            counter++;
        });
        $.post('/<?=ADM_CONTROLLER?>/edit_table_order_bottom/',{data:data,table:'<?=$tblname?>'},function(ret) {

        });
    }
</script>
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
			<div class="portlet-body" style="<?if (empty($err)) echo 'display:none;';?>">
				<div class="tabbable tabbable-custom">
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#tab_1_1" data-toggle="tab">Общая информация</a>
						</li>
						<li>
							<a href="#tab_1_2" data-toggle="tab">Служебная информация</a>
						</li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab_1_1">
							<div class="">
							<table class="table table-striped table-bordered table-hover">
								<?
								create_form_by_array($form1,@$_POST['data']);
								?>
								<tr>
									<td>&nbsp;</td>
									<td><button type="submit" class="btn green"><i class="fa fa-check"></i> Добавить</button></td>
								</tr>
							</table>
							</div>
						</div>
						<div class="tab-pane" id="tab_1_2">
							<div class="">
							<table class="table table-striped table-bordered table-hover">
								<?
								create_form_by_array($form2,@$_POST['data']);
								?>
								<tr>
									<td>&nbsp;</td>
									<td><button type="submit" class="btn green"><i class="fa fa-check"></i> Добавить</button></td>
								</tr>
							</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form><br />
	<script>
	    $(document).ready(function() {
		$('.showtop').change(function() {
            let id=$(this).val();
            let val;
            if ($(this).is(':checked')) {
                val=1;
            } else {
                val=0;
            }
            $.post('/<?=ADM_CONTROLLER?>/checkboxChange/', { "table": "<?=$tblname?>", "col": "is_top", "id":id, "val":val } );
        });

		$('.showbottom').change(function() {
            let id=$(this).val();
            let val;
            if ($(this).is(':checked')) {
                val=1;
            } else {
                val=0;
            }
            $.post('/<?=ADM_CONTROLLER?>/checkboxChange/', { "table": "<?=$tblname?>", "col": "is_bottom", "id":id, "val":val } );
		});
	});
	</script>

    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#tab_88" data-toggle="tab"><h3>Верхнее меню</h3></a>
        </li>
        <li>
            <a href="#tab_99" data-toggle="tab"><h3>Нижнее меню</h3></a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_88">
            <? $items=$this->db->order_by('order_top ASC, id DESC')->get($tblname)->result_array(); ?>

            <? if(!empty($items)) {?>
                <div class="">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer sortTop">
                        <tr class="heading nodrop nodrag">
                            <th width="40">Сортировка шапка</th>
                            <th>Название</th>
                            <th width="200"></th>
                            <th width="200"></th>
                            <th width="230">Действия</th>
                        </tr>
                        <? foreach($items as $item) { ?>
                            <tr>
                                <td width="40" celpadding="5" cellspacing="10" oid="<?=$item['id']?>" align="center" class="order_top">
                                    <?=$item['order_top'];?>
                                </td>
                                <td>
                                    <a style="font-weight:bold;" href="<?=$e_path.$item['id']?>"><?=$item['title_ru']?></a>
                                </td>
                                <td><label><input type="checkbox" <?=($item['is_top']) ? ' checked' : ''?> value="<?=$item['id']?>" class="showtop"> Выводить в шапке</label></td>
                                <td><label><input type="checkbox" <?=($item['is_bottom']) ? ' checked' : ''?> value="<?=$item['id']?>" class="showbottom"> Выводить в подвале</label></td>
                                <td align="left">
                                    <a href="<?=$e_path.$item['id']?>/" class="btn blue"><i class="fa fa-pencil"></i> Редактировать</a>
                                    <? if (empty($barr['fixed'])) {?>
                                        <a href="<?=$delpath.$item['id']?>" class="btn red"><i class="fa fa-trash"></i> </a>
                                    <?}?>
                                </td>
                            </tr>

                        <?}?>
                    </table>
                </div>
            <?}?>
        </div>
        <div class="tab-pane" id="tab_99">
            <? $items=$this->db->where('bottom_category_id', 0)->order_by('order_bottom ASC, ID DESC')->get($tblname)->result_array(); ?>

            <? if(!empty($items)) {?>
                <h4>БЕЗ КАТЕГОРИИ</h4>
                <div class="">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer sortBottom">
                        <tr class="heading nodrop nodrag">
                            <th width="40">Сортировка подвал</th>
                            <th>Название</th>
                            <th width="200"></th>
                            <th width="200"></th>
                            <th width="230">Действия</th>
                        </tr>
                        <? foreach($items as $item) { ?>
                            <tr>
                                <td width="40" celpadding="5" cellspacing="10" oid="<?=$item['id']?>" align="center" class="order_bottom">
                                    <?=$item['order_bottom'];?>
                                </td>
                                <td>
                                    <a style="font-weight:bold;" href="<?=$e_path.$item['id']?>"><?=$item['title_ru']?></a>
                                </td>
                                <td><label><input type="checkbox" <?=($item['is_top']) ? ' checked' : ''?> value="<?=$item['id']?>" class="showtop"> Выводить в шапке</label></td>
                                <td><label><input type="checkbox" <?=($item['is_bottom']) ? ' checked' : ''?> value="<?=$item['id']?>" class="showbottom"> Выводить в подвале</label></td>
                                <td align="left">
                                    <a href="<?=$e_path.$item['id']?>/" class="btn blue"><i class="fa fa-pencil"></i> Редактировать</a>
                                    <? if (empty($barr['fixed'])) {?>
                                        <a href="<?=$delpath.$item['id']?>" class="btn red"><i class="fa fa-trash"></i> </a>
                                    <?}?>
                                </td>
                            </tr>

                        <?}?>
                    </table>
                </div>
            <?}?>

            <? $fq = $this->db->order_by('sorder asc,id desc')->get('bottom_category')->result_array();?>
            <? foreach($fq as $bc) { ?>
                <? $items=$this->db->where('bottom_category_id',$bc['id'])->order_by('order_bottom ASC, ID DESC')->get($tblname)->result_array(); ?>

                <? if(!empty($items)) {?>
                    <h4><?=$bc['name_ru']?></h4>
                    <div class="">
                        <table class="table table-striped table-bordered table-hover dataTable no-footer sortBottom">
                            <tr class="heading nodrop nodrag">
                                <th width="40">Сортировка подвал</th>
                                <th>Название</th>
                                <th width="200"></th>
                                <th width="200"></th>
                                <th width="230">Действия</th>
                            </tr>
                            <? foreach($items as $item) { ?>
                                <tr>
                                    <td width="40" celpadding="5" cellspacing="10" oid="<?=$item['id']?>" align="center" class="order_bottom">
                                        <?=$item['order_bottom'];?>
                                    </td>
                                    <td>
                                        <a style="font-weight:bold;" href="<?=$e_path.$item['id']?>"><?=$item['title_ru']?></a>
                                    </td>
                                    <td><label><input type="checkbox" <?=($item['is_top']) ? ' checked' : ''?> value="<?=$item['id']?>" class="showtop"> Выводить в шапке</label></td>
                                    <td><label><input type="checkbox" <?=($item['is_bottom']) ? ' checked' : ''?> value="<?=$item['id']?>" class="showbottom"> Выводить в подвале</label></td>
                                    <td align="left">
                                        <a href="<?=$e_path.$item['id']?>/" class="btn blue"><i class="fa fa-pencil"></i> Редактировать</a>
                                        <? if (empty($barr['fixed'])) {?>
                                            <a href="<?=$delpath.$item['id']?>" class="btn red"><i class="fa fa-trash"></i> </a>
                                        <?}?>
                                    </td>
                                </tr>

                            <?}?>
                        </table>
                    </div>
                <?}?>
            <?}?>
        </div>
    </div>

<? } else {?>
    <? $data=$this->db->where('id',$uri3)->get($tblname)->row_array(); ?>
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
				<a>Редактирование "<?=$data['title_ru']?>"</a>
			</li>
		</ul>
	</div>
	<?=$err?>

	<form name="form1" method="POST" action="<?=$e_path.$uri3;?>/" enctype="multipart/form-data">
	<div class="tabbable tabbable-custom">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#tab_1_1" data-toggle="tab">Общая информация</a>
			</li>
			<li>
				<a href="#tab_1_2" data-toggle="tab">Служебная информация</a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="tab_1_1">
				<div class="">
				<table class="table table-striped table-bordered table-hover">
					<?
					create_form_by_array($form1,$data);
					?>
					<tr>
						<td>&nbsp;</td>
						<td><button type="submit" class="btn green"><i class="fa fa-check"></i> Добавить</button></td>
					</tr>
				</table>
				</div>
			</div>
			<div class="tab-pane" id="tab_1_2">
				<div class="">
				<table class="table table-striped table-bordered table-hover">
					<?
					create_form_by_array($form2,$data);
					?>
					<tr>
						<td>&nbsp;</td>
						<td><button type="submit" class="btn green"><i class="fa fa-check"></i> Добавить</button></td>
					</tr>
				</table>
				</div>
			</div>
		</div>
	</div>
	</form>
<?} ?>
