<?php
$uri3=$this->uri->segment(3);

$head1='Магазины партнеров';
$head2='Редактирование магазина';
$head3='name_ru';
$addnew='магазин';
$tblname='shops';
$headerloc='shops';

$e_path='/'.ADM_CONTROLLER.'/'.$headerloc.'/';
$delpath='/'.ADM_CONTROLLER.'/delete_row/'.$tblname.'/';
$err='';

$form=array();
$form[]=admin_form_text(true,'name','Заголовок');
$form[]=admin_form_textarea(true,'desc','Анонс');

$form[]=admin_form_file(false,'logo','Logo (125px x 25px)',$tblname);
$form[]=admin_form_file(false,'items','Фото Предметы (224px x 224px)',$tblname);
$form[]=admin_form_file(false,'mobile_items','Фото Предметы Моб.(296px x 65px)',$tblname);
$form[]=admin_form_file(true,'banner','Баннер (1408px x 415px)',$tblname);
$form[]=admin_form_file(true,'mobile_banner','Мобильный баннер (328px x 480px)',$tblname);

$form[]=
    array(
        array(
            'name' => "on_main",
            'type' => 'checkbox',
            'class' => 'form-control',
            'descr' => 'на главной'
        )
    );

$form1=convert_form($form);

$files=array('logo','items', 'mobile_items', 'banner_ro', 'banner_ru', 'mobile_banner_ro', 'mobile_banner_ru');
$checker=array();

if ($_SERVER['REQUEST_METHOD']=='POST') {
	$err=save_data($_POST['data'],$checker,$files,$tblname,$form1);
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
            <div class="portlet-body" style="<?php if (empty($err)) echo 'display:none;';?>">
                <div class="tabbable tabbable-custom">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1_1" data-toggle="tab">Общая информация</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1_1">
                            <div class="">
                                <table class="table table-striped table-bordered table-hover">
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
                </div>
            </div>
        </div>
    </form><br />
	<script>
	$(document).ready(function() {
		$('.changer').change(function() {
			table = 'article';
			field = 'show_on_main';
			id=$(this).attr('bid');
			if ($(this).is(':checked')) val=1; else val=0;
			$.post('/<?=ADM_CONTROLLER?>/changeval/',{table:table,field:field,id:id,val:val});
		});
	});
	</script>
    <?php
    $checkb=$this->db->order_by('sorder asc,id desc')->get($tblname)->result_array();
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
                    echo $barr['sorder'];
                echo "</td>";
                echo "<td><a href=\"$e_path".$barr['id']."/\">".$barr['name_ru']."</a></td>";
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

    <form name="form1" method="POST" action="<?= $e_path . $uri3; ?>/" enctype="multipart/form-data">
        <div class="tabbable tabbable-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab_1_1" data-toggle="tab">Общая информация</a>
                </li>
                <li>
                    <a href="#tab_1_3" data-toggle="tab">Привязанные товары</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1_1">
                    <div class="">
                        <table class="table table-striped table-bordered table-hover">
                            <?php create_form_by_array($form1, $data);?>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <button type="submit" class="btn green"><i class="fa fa-check"></i> Добавить</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="tab_1_3">
                    <div class="">
                        <table class="table table-striped table-bordered table-hover">
                            <tbody id="prod_table"></tbody>
                        </table>

                    </div>
                    <div style="width:calc(100% - 150px);float:left;">
                        <input type="text" class="form-control autocomp">
                        <input id="prod_id" type="hidden" value="">
                    </div>
                    <div style="width:120px;float:left;margin-left:29px;"><button onclick="addProd();" type="button" class="btn green"><i class="fa fa-check"></i> Добавить</button></div>
                    <br clear="all">
                </div>
            </div>
        </div>
    </form>
    <link href="/theme/assets/global/plugins/bootstrap-toastr/toastr.css" rel="stylesheet" type="text/css"/>
    <script src="/theme/assets/global/plugins/bootstrap-toastr/toastr.js" type="text/javascript"></script>
    <script>
        function addProd() {
            let shop_id=<?=$uri3?>;
            let product_id=$('#prod_id').val();

            $.get('/<?=ADM_CONTROLLER?>/shop_product/'+shop_id+'/'+product_id+'/',function(data) {
                $('#prod_table').html(data);
                $(".autocomp").val("");
                $("#prod_id").val("");
            });
        }

        function delProd(shop_id,product_id) {
            if(confirm('Вы уверенны? ')){
                $.get('/<?=ADM_CONTROLLER?>/shop_product_del/' + shop_id + '/' + product_id + '/', function (data) {
                    $('#prod_table').html(data);
                });
            }
        }

        $(document).ready(function() {

            let shop_id=<?=$uri3?>;

            $.get('/<?=ADM_CONTROLLER?>/shop_product/'+shop_id+'/',function(data) {
                $('#prod_table').html(data);
            });

            $( ".autocomp" ).autocomplete({
                source: '/<?=ADM_CONTROLLER?>/autocomplete/',
                minLength: 3,
                select: function( event, ui ) {
                    $('#prod_id').val(ui.item.id);
                }
            });

            $('#prod_table').sortable({
                items: '> tr:not(.unsortable)',
                stop: function( event, ui ) {
                    const sorted = $("#prod_table").sortable( "serialize", { key: "products[]" } );
                    $.post('/<?=ADM_CONTROLLER?>/shop_product_sort/'+<?=$uri3?>+'/', sorted, function(response){
                        if (response.status === 500) {
                            toastr["error"]('nok');
                        } else {
                            toastr["success"]('ok');
                        }
                    },'json');
                }
            }).disableSelection();
        });
        function setBest(shop_id, product_id) {
            let val = 0;
            if($('#'+shop_id+'-'+product_id).is(':checked')) {
                val = 1;
            }
            $.post('/cp/setBest/'+shop_id+'/'+product_id+'/'+val+'/');
        }
    </script>
<?php }?>
