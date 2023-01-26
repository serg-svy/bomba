<?
$uri3=$this->uri->segment(3);

$head1='Слайдер';
$head2='Редактирование слайда';
$head3='name_ru';
$addnew='слайд';
$tblname='slider';
$headerloc='slider';
$image_path = "/public/" . $tblname . '/';

$e_path='/'.ADM_CONTROLLER.'/'.$headerloc.'/';
$delpath='/'.ADM_CONTROLLER.'/delete_row/'.$tblname.'/';
$err='';

$form=array();
$form[]=admin_form_text(true,'name','Заголовок');
$form[]=admin_form_text(true,'description','Описание');
$form[]=admin_form_text(false,'price','Цена');
$form[]=admin_form_text(true,'link','Ссылка');
$form[]=admin_form_file(true,'image','Фото (1048px x 540px)',$tblname);
$form[]=admin_form_file(true,'image_mobile','Фото для мобильного приложения (346px x 506px)',$tblname);
$form[]=admin_form_file(true,'image_terminal','Фото для терминала (1000 x 460px)',$tblname);
$form[]=admin_form_file(true,'image_terminal_sleep','Фото для терминала режим сна (1080 x 1920px)',$tblname);

$form1=convert_form($form);

$files=array('image_ru','image_ro', 'image_mobile_ru', 'image_mobile_ro', 'image_terminal_ro', 'image_terminal_ru', 'image_terminal_sleep_ru', 'image_terminal_sleep_ro');
$checker=array();

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
	$checkb=$this->db->order_by('Sorder ASC, ID DESC')->get($tblname)->result_array();
	if(!empty($checkb))
	{
		echo '<div class="">
		<table class="table table-striped table-bordered table-hover dataTable no-footer dragger">';
		echo '<tr class="heading nodrop nodrag">
			<th>Сортировка</th>
			<th></th>
			<th>Название</th>
			<th>Превью</th>
			<th width="250">Действия</th>
		</tr>';

		foreach($checkb as $barr)
		{
			if (empty($barr['name_ru'])) $barr['name_ru']='Без названия';
			echo "<tr>";
				echo "<td width=\"40\" celpadding=\"5\" cellspacing=\"10\" oid=\"".$barr['id']."\" align=\"center\" class=\"sorthold\">";
					echo $barr['sorder'];
				echo "</td>";
                $cmod1='';
                if (!empty($barr['isShown'])) $cmod1=' checked';
                echo "<td><label><input type=\"checkbox\"$cmod1 value=\"".$barr['id']."\" class=\"myChange\"> Выводить</label></td>";
				echo "<td><a href=\"$e_path".$barr['id']."/\">".$barr['name_ru']."</a></td>";
				echo '<td><div class="gallery" style="display:flex;align-items: center">';
				echo '<img src="' . $image_path . $barr['image_ru'] . '"  title="image_ru" alt="image_ru" style="margin-right:5px;max-width:100px">';
				echo '<img src="' . $image_path . $barr['image_ro'] . '"  title="image_ro" alt="image_ro" style="margin-right:5px;max-width:100px">';
				echo "</div></td>";
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
	</form><br />
<?
}
?>

<script>
    $(document).ready(function() {
        $('.myChange').change(function() {
            let id=$(this).val();
            let val;
            if ($(this).is(':checked')) {
                val=1;
            } else {
                val=0;
            }
            $.post('/<?=ADM_CONTROLLER?>/checkboxChange/', { "table": "<?=$tblname?>", "col": "isShown", "id":id, "val":val } );
        });
    });
</script>
