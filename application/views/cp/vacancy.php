<?
$uri3=$this->uri->segment(3);

$head1='Вакансии';
$head2='Редактирование вакансии';
$head3='name_ru';
$addnew='валюту';
$tblname='vacancy';
$headerloc='vacancy';

$e_path='/'.ADM_CONTROLLER.'/'.$headerloc.'/';
$delpath='/'.ADM_CONTROLLER.'/delete_row/'.$tblname.'/';
$err='';

$form=array();
$form[]=admin_form_text(true,'name','Название');
$form[]=admin_form_text(true,'short_name','короткое описание');
$form[]=admin_form_textarea(true,'description','Описание', 'ckeditor');

$form1=convert_form($form);

$files=array();
$checker=array('name_ru');

if ($_SERVER['REQUEST_METHOD']=='POST') {
	$err=save_data($_POST['data'],$checker,$files,$tblname,$form1,true);

	$this->db->where('vacancy_id',$err)->delete('vacancy_store');
	if (!empty($_POST['store_id'])) {
		foreach($_POST['store_id'] as $val) {
			$this->db->insert('vacancy_store',array('store_id'=>$val,'vacancy_id'=>$err));
		}
	}
	header("Location: /".ADM_CONTROLLER."/$tblname/");
	exit();
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

				<div class="tabbable tabbable-custom">
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#tab_1_1" data-toggle="tab">Общая информация</a>
						</li>
						<li>
							<a href="#tab_1_2" data-toggle="tab">Магазины</a>
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
								<table class="table table-bordered table-hover">

									<?
									echo "<tr>";
										//if (!empty($barr['checker'])) $mod=' checked'; else $mod='';
										echo '<td width="1"><input type="checkbox" name="store_id[]" value="1"></td>';
										echo '<td>Офис</td>';
									echo "</tr>";
									$city = $this->db->order_by('id asc')->get('city')->result_array();
									foreach($city as $row) {
										$checkb=$this->db->where('city_id',$row['id'])->order_by('Sorder ASC, ID DESC')->get('store')->result_array();
										if(!empty($checkb))
										{
											echo '<tr><td colspan="2"><h3>'.$row['name_ru'].'</h3></td></tr>';
											foreach($checkb as $barr)
											{
												echo "<tr>";
													echo '<td width="1"><input type="checkbox" name="store_id[]" value="'.$barr['id'].'"></td>';
													echo '<td>'.$barr['name_ru'].'</td>';
												echo "</tr>";
											}
										}
									}
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
				echo "<td width=\"40\" celpadding=\"5\" cellspacing=\"10\" oid=\"".$barr['id']."\" align=\"center\" class=\"sorthold\">";
					echo $barr['Sorder'];
				echo "</td>";
				echo "<td><a href=\"$e_path".$barr['id']."/\">".$barr['name_ru']."</a>";
				echo "</td>";
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
		<div class="tabbable tabbable-custom">
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#tab_1_1" data-toggle="tab">Общая информация</a>
				</li>
				<li>
					<a href="#tab_1_2" data-toggle="tab">Магазины</a>
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
						<table class="table table-bordered table-hover">
							<?
							$city = $this->db->order_by('id asc')->get('city')->result_array();
							echo "<tr>";
								//if (!empty($barr['checker'])) $mod=' checked'; else $mod='';
								echo '<td width="1"><input type="checkbox" name="store_id[]" value="1"></td>';
								echo '<td>Офис</td>';
							echo "</tr>";
							foreach($city as $row) {
								$checkb=$this->db->select('store.*,vacancy_store.id as checker')
								->join('vacancy_store','store_id=store.id AND vacancy_id='.$uri3,'left')
								->where('store.city_id',$row['id'])->order_by('store.Sorder ASC, store.id DESC')
								->get('store')->result_array();
								if(!empty($checkb))
								{
									echo '<tr><td colspan="2"><h3>'.$row['name_ru'].'</h3></td></tr>';
									foreach($checkb as $barr)
									{
										echo "<tr>";
											if (!empty($barr['checker'])) $mod=' checked'; else $mod='';
											echo '<td width="1"><input'.$mod.' type="checkbox" name="store_id[]" value="'.$barr['id'].'"></td>';
											echo '<td>'.$barr['name_ru'].'</td>';
										echo "</tr>";
									}
								}
							}
							?>
							<tr>
								<td>&nbsp;</td>
								<td><button type="submit" class="btn green"><i class="fa fa-check"></i> Обновить</button></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</form><br />
<?
}
?>
