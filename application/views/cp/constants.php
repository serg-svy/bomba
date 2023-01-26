<script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
<?
$items = $this->db->order_by('sorder ASC,ID ASC')->get('constants')->result_array();
?>
<div class="page-bar">
		<ul class="page-breadcrumb">
			<li>
				<i class="fa fa-home"></i>
				<a href="/<?=ADM_CONTROLLER?>/menu/">Главная</a>
				<i class="fa fa-angle-right"></i>
			</li>
			<li>
				<a>Константы</a>
			</li>
		</ul>
	</div>
<?
echo "<h3 class=\"page-title\">Константы</h3>";
if(!empty($items))
{
	echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"/".ADM_CONTROLLER."/constants/\">";
	foreach($items as $item)
	{
		echo '<div class="portlet box blue">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-cogs"></i> ('.$item['ConstantName'].')
				</div>
				<div class="tools">
					<a href="javascript:;" class="collapse"></a>
				</div>
			</div>
			<div class="portlet-body">
				<div class="">
					<table class="table table-striped table-bordered table-hover">';
						echo '<tr>';
							echo "<td>";
								if ($item['ckeditor'] == 1) $dopclass='ckeditor'; else $dopclass='form-control';
								echo "<textarea name=\"ru[".$item['ID']."]\" rows=\"3\" class=\"$dopclass\">".$item['ru']."</textarea>";
							echo "</td>";
							echo "<td>";
								if ($item['ckeditor'] == 1) $dopclass='ckeditor'; else $dopclass='form-control';
								echo "<textarea name=\"ro[".$item['ID']."]\" rows=\"3\" class=\"$dopclass\">".$item['ro']."</textarea>";
							echo "</td>";
						echo "</tr>";
					echo '</table>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	}
	echo "<br /><button type=\"submit\" class=\"btn green\"><i class=\"fa fa-check\"></i> Обновить</button>";
	echo "</form>";
}
?>
