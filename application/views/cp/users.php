<?
$user_id=$this->uri->segment(3);
$headerloc='users';
$e_path='/'.ADM_CONTROLLER.'/'.$headerloc.'/';
$tblname = 'user';
$fil=$this->db->order_by('Sorder ASC')->get('city')->result_array();
$fils[0]='Администратор';
?>

<script type="text/javascript">
	function toggleu()
	{
		$("#newu").toggle();
	}
</script>
<div class="page-bar">
	<ul class="page-breadcrumb">
		<li>
			<i class="fa fa-home"></i>
			<a href="/<?=ADM_CONTROLLER?>/menu/">Главная</a>
			<i class="fa fa-angle-right"></i>
		</li>
		<li>
			<a href="<?= $e_path?>">Пользователи</a>
		</li>
	</ul>
</div>

<?php if (empty($user_id)): ?>


<div class="portlet box">
<div class="portlet-title">
		<div class="caption" style="color:#888;font-size:26px;">
			Пользователи
		</div>
		<div class="tools" style="display:none;">
			<a href="javascript:;" class="expand"></a>
		</div>
		<div class="actions">
			<a class="btn default yellow-stripe">
			<i class="fa fa-plus"></i>
			<span class="hidden-480">
			Добавить пользователя</span>
			</a>
		</div>
	</div>
	<div class="portlet-body" <?if (empty($err1)) {?>style="display:none;"<?}?>>
		<form id="form1" name="form1" method="post" action="/<?=ADM_CONTROLLER?>/newuser/">
		<div class="">
			<?=@$err1?>
			<table class="table table-striped table-bordered table-hover">
				<tr>
				  <td width="200">Логин</td>
				  <td><input type="text" name="login"  class="form-control" /></td>
				</tr>
				<tr style="display:none;">
				  <td width="200">Тип</td>
				  <td>
					<select class="form-control" name="city_id">
						<option value="0">Администратор</option>
					</select>
				  </td>
				</tr>
				<tr>
				  <td width="200">Пароль</td>
				  <td><input type="password" name="newp"  class="form-control" /></td>
				</tr>

				<tr>
				  <td>Подтверждение пароля</td>
				  <td><input type="password" name="newp1"  class="form-control" /></td>
				</tr>
				<tr>
				  <td align="right"></td>
				  <td><button type="submit" class="btn green"><i class="fa fa-check"></i> Добавить</button></td>
				</tr>
			  </table>

		</div></form>
	</div>
</div>

<?
$check_u = $this->db->get("user")->result_array();
if(count($check_u)>0)
{
	echo "<div class=\"\">
			<table class=\"table table-striped table-bordered table-hover tablist\">";
		echo '<tr class="heading nodrop nodrag">
			<th>Логин</th>
			<th width="100">Действия</th>
		</tr>';
		foreach($check_u as $uarr)
		{
			if($uarr['id'] != 1)
			{
			echo "<tr>";
				echo "<td>";
					echo $uarr['login'];
				echo "</td>";
				echo "<td>";
					echo '<a href="/'.ADM_CONTROLLER.'/delUser/'.$uarr['id'].'/" class="btn red"><i class="fa fa-trash"></i> </a>';
				echo "</td>";
			echo "</tr>";
			}
		}
	echo "</table></div>";
}
?>
<?php endif ?>

<?php if (empty($user_id)): ?>
	<div style="height:20px;"></div><h3 class="page-title\">Смена пароля</h3>
	<form id="form1" name="form1" method="post" action="/<?=ADM_CONTROLLER?>/users/">
	  <div class="">
				<?=@$err2?>
				<table class="table table-striped table-bordered table-hover">
	    <tr>
	      <td width="200">Старый пароль</td>
	      <td><input type="password" name="oldp" class="form-control" /></td>
	    </tr>
	    <tr>
	      <td width="200">Новый пароль</td>
	      <td><input type="password" name="newp" class="form-control" /></td>
	    </tr>
	    <tr>
	      <td>Подтверждение пароля</td>
	      <td><input type="password" name="newp1" class="form-control" /></td>
	    </tr>
	    <tr>
	      <td align="right"></td>
	      <td><button type="submit" class="btn green"><i class="fa fa-check"></i> Обновить</button></td>
	    </tr>
	  </table>
	  </div>
	</form>
<?php endif ?>
