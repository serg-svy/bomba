<?
$uri3=$this->uri->segment(3);

$head1='Обзоры';
$head2='Редактирование обзора';
$head3='header1_ru';
$addnew='обзор';
$tblname='reviews';
$headerloc='reviews';

$e_path='/'.ADM_CONTROLLER.'/'.$headerloc.'/';
$delpath='/'.ADM_CONTROLLER.'/delete_row/'.$tblname.'/';
$err='';

$form=array();
$form[]=admin_form_text(false,'url','URL');
$form[]=admin_form_text(false,'add_date','Дата','date-picker');
$form[]=admin_form_text(true,'header1','Заголовок1');
$form[]=admin_form_text(true,'header2','Заголовок2');
$form[]=admin_form_text(true,'header3','Заголовок3');
$form[]=admin_form_textarea(true,'announce','Анонс');
$form[]=admin_form_textarea(true,'text','Текст новости','ckeditor');

$form[]=admin_form_file(true,'image_list','Фото в списке (360px x 190px)',$tblname);
$form[]=admin_form_file(true,'image_head','Фото в шапке (1230px x 430px)',$tblname);

$form1=convert_form($form);

$files=array('image_list_ru','image_head_ru','image_list_ro','image_head_ro');
$checker=array('header1_ru');

if ($_SERVER['REQUEST_METHOD']=='POST') {
	$err=save_data($_POST['data'],$checker,$files,$tblname,$form1);
}
standart_form_script($tblname);
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
				<div class="">
					<table class="table table-striped table-bordered table-hover">
						<?
						if (empty($_POST['data']['add_date'])) $_POST['data']['add_date']=date('Y-m-d');
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
	<?
	$checkb=$this->db->order_by('ID DESC')->get($tblname)->result_array();
	if(!empty($checkb))
	{
		echo '<div class="">
		<table class="table table-striped table-bordered table-hover dataTable no-footer dragger">';
		echo '<tr class="heading nodrop nodrag">
			<th width="100">Дата</th>
			<th>Название</th>
			<th width="250">Действия</th>
		</tr>';

		foreach($checkb as $barr)
		{
			echo "<tr>";

				echo "<td>".date('d.m.Y',strtotime($barr['add_date']))."</td>";
				echo "<td><a href=\"$e_path".$barr['id']."/\">".$barr['header1_ru']."</a></td>";
				echo "<td align=\"center\">";
					echo '<a href="'.$e_path.$barr['id'].'/" class="btn blue"><i class="fa fa-pencil"></i> Редактировать</a>';
					echo '<a href="'.$delpath.$barr['id'].'/" class="btn red"><i class="fa fa-trash"></i> </a>';
				echo "</td>";
			echo "</tr>";
		}
		echo "</table>";
		echo '</div>';
	}
} else {
	$data=$this->db->where('id',$uri3)->get($tblname)->row_array();
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
	<?=$err?>

	<form name="form1" method="POST" action="<?=$e_path.$uri3;?>/" enctype="multipart/form-data">
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
                            <td><button type="submit" class="btn green"><i class="fa fa-check"></i> Обновить</button></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="tab-pane" id="tab_1_2">
                <div class="">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                            <tr>
                                <td width="200">Заголовок RU</td>
                                <td><input name="data[seo_title_ru]" type="text" id="seo_title_ru" class="form-control" value="<?= $data['seo_title_ru'] ?>"></td>
                            </tr><tr>
                                <td width="200">Заголовок RO</td>
                                <td><input name="data[seo_title_ro]" type="text" id="seo_title_ro" class="form-control" value="<?= $data['seo_title_ro'] ?>"></td>
                            </tr><tr>
                                <td width="200">Ключевые слова RU</td>
                                <td><input name="data[seo_kw_ru]" type="text" id="seo_kw_ru" class="form-control" value="<?= $data['seo_kw_ru'] ?>"></td>
                            </tr><tr>
                                <td width="200">Ключевые слова RO</td>
                                <td><input name="data[seo_kw_ro]" type="text" id="seo_kw_ro" class="form-control" value="<?= $data['seo_kw_ro'] ?>"></td>
                            </tr><tr>
                                <td width="200">Описание RU</td>
                                <td><input name="data[seo_description_ru]" type="text" id="seo_desc_ru" class="form-control" value="<?= $data['seo_description_ru'] ?>"></td>
                            </tr><tr>
                                <td width="200">Описание RO</td>
                                <td><input name="data[seo_description_ro]" type="text" id="seo_desc_ro" class="form-control" value="<?= $data['seo_description_ro'] ?>"></td>
                            </tr>					<tr>
                                <td>&nbsp;</td>
                                <td><button type="submit" class="btn green"><i class="fa fa-check"></i> Добавить</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
	</form><br />
<?
}
?>
