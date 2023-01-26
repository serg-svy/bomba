<?
$uri3=$this->uri->segment(3);

$head1='Звонки';
$head2='Редактирование звонка';
$head3='NameRU';
$addnew='звонок';
$tblname='calls';
$headerloc='calls';

$e_path='/'.ADM_CONTROLLER.'/'.$headerloc.'/';
$delpath='/'.ADM_CONTROLLER.'/delete_row/'.$tblname.'/';
$err='';

$form=array();
$form[]=admin_form_text(false,'UriName','Ссылка');
$form[]=admin_form_text(true,'Name','Название');
$form[]=admin_form_textarea(false,'actionDate','Дата','ckeditor');
$form1=convert_form($form);

$files=array();
$checker=array('NameRU','UriName');

if ($_SERVER['REQUEST_METHOD']=='POST') {
	$err=save_data($_POST['data'],$checker,$files,$tblname);
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
			<th>Название</th>
			<th width="250">Действия</th>
		</tr>';

		foreach($checkb as $barr)
		{
			echo "<tr>";
				echo "<td width=\"40\" celpadding=\"5\" cellspacing=\"10\" oid=\"".$barr['ID']."\" align=\"center\" class=\"sorthold\">";
					echo $barr['Sorder'];
				echo "</td>";
				echo "<td><a href=\"$e_path".$barr['ID']."/\">".$barr['NameRU']."</a>";
				echo "</td>";
				echo "<td align=\"center\">";
					echo '<a href="'.$e_path.$barr['ID'].'/" class="btn blue"><i class="fa fa-pencil"></i> Редактировать</a>';
					echo '<a href="'.$delpath.$barr['ID'].'/" class="btn red"><i class="fa fa-trash"></i> </a>';
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
