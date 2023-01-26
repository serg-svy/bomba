<?php
$uri3=$this->uri->segment(3);

$head1='хит продаж';
$head2='Редактирование';
$head3='name_ru';
$addnew='блок';
$tblname='bestseller';
$headerloc='bestseller';

$e_path='/'.ADM_CONTROLLER.'/'.$headerloc.'/';
$delpath='/'.ADM_CONTROLLER.'/delete_row/'.$tblname.'/';
$err='';

$form=array();
$form[]=admin_form_text(true,'name','Название');

$form1=convert_form($form);

$files=array();
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
    <div class="portlet box">
        <div class="portlet-title" style="cursor:initial;">
            <div class="caption" style="color:#888;font-size:26px;">
                <?=$head1?>
            </div>
        </div>
    </div>
    <br>
    <?php
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
            echo $barr['sorder'];
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
                    <a href="#tab_1_2" data-toggle="tab">Продукты</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1_1">
                    <div class="">
                        <table class="table table-striped table-bordered table-hover">
                            <?php create_form_by_array($form1,$data); ?>
                            <tr>
                                <td>&nbsp;</td>
                                <td><button type="submit" class="btn green"><i class="fa fa-check"></i> Обновить</button></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="tab_1_2">
                    <div class="">
                        <table class="table table-striped table-bordered table-hover" id="prod_table">
                        </table>
                        <div style="width:calc(100% - 150px);float:left;">
                            <input type="text" class="form-control autocomp">
                            <input id="prod_id" type="hidden" value="">
                        </div>
                        <div style="width:120px;float:left;margin-left:29px;"><button onclick="addProd();" type="button" class="btn green"><i class="fa fa-check"></i> Добавить</button></div>
                        <br clear="all">
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script>
        function addProd() {
            let main_block_id=<?=$uri3?>;
            let product_id=$('#prod_id').val();

            $.get('/<?=ADM_CONTROLLER?>/bestseller_product/'+main_block_id+'/'+product_id+'/',function(data) {
                $('#prod_table').html(data);
                $(".autocomp").val("");
                $("#prod_id").val("");
            });
        }

        function delProd(main_block_id,product_id) {
            if(confirm('Вы уверенны? ')){
                $.get('/<?=ADM_CONTROLLER?>/bestseller_product_del/' + main_block_id + '/' + product_id + '/', function (data) {
                    $('#prod_table').html(data);
                });
            }
        }

        $(document).ready(function() {

            let main_block_id=<?=$uri3?>;

            $.get('/<?=ADM_CONTROLLER?>/bestseller_product/'+main_block_id+'/',function(data) {
                $('#prod_table').html(data);
            });

            $( ".autocomp" ).autocomplete({
                source: '/<?=ADM_CONTROLLER?>/autocomplete/',
                minLength: 3,
                select: function( event, ui ) {
                    $('#prod_id').val(ui.item.id);
                }
            });
        });
    </script>
<?php } ?>
