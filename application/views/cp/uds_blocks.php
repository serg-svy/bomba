<?
$uri3=$this->uri->segment(3);

$head1='UDS - блоки';
$head2='Редактирование блока';
$head3='title_ru';
$addnew='блок';
$tblname='uds_blocks';
$headerloc='uds_blocks';

$e_path='/'.ADM_CONTROLLER.'/'.$headerloc.'/';
$delpath='/'.ADM_CONTROLLER.'/delete_row/'.$tblname.'/';
$err='';

$checker=array();

$form=array();
$form[]=admin_form_text(true,'title','Заголовок');
$form[]=admin_form_textarea(true,'text','Текст');
$form[]=admin_form_file(false,'img','Фото',$tblname);

$form1=convert_form($form);

$blocks = [
    1 => 'Bomba.Club',
    2 => 'Условия',
    3 => 'Как зарегистрироваться и получить 100 бонусных леев',
    4 => 'Как использовать бонусы',
    5 => 'Как пригласить друзей и получить бонусы'
];

$Name='';
$files=array('img');
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
                        <? create_form_by_array($form1,@$_POST['data']); ?>
                        <tr>
                            <td width="200">Категория</td>
                            <td>
                                <select class="form-control" name="data[block_id]">
                                    <? foreach($blocks as $key=>$block){?>
                                        <option value="<?=$key?>"><?=$block?></option>
                                    <?}?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><button type="submit" class="btn green"><i class="fa fa-check"></i> Добавить</button></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </form>
    <br />
    <? $result =$this->db->order_by('Sorder ASC, ID DESC')->get($tblname)->result_array(); ?>

    <? if(!empty($result)) { ?>
        <? foreach ($blocks as $block_id => $block_title) {?>
            <h2><?=$block_title?></h2>
            <div class="">
                <table class="table table-striped table-bordered table-hover dataTable no-footer dragger">
                    <tr class="heading nodrop nodrag">
                        <th>Сортировка</th>
                        <th>Название</th>
                        <th width="250">Действия</th>
                    </tr>

                    <? foreach($result as $row) { ?>
                        <? if($block_id == $row['block_id']) {?>
                            <tr>
                                <td width="40" celpadding="5" cellspacing="10" oid="<?=$row['id']?>" align="center" class="sorthold">
                                    <?=$row['sorder']?>
                                </td>
                                <td><a href="<?=$e_path.$row['id']?>"><?=$row['title_ru']?></a>
                                </td>
                                <td align="center">
                                    <a href="<?=$e_path.$row['id']?>/" class="btn blue"><i class="fa fa-pencil"></i> Редактировать</a>
                                    <a href="<?=$delpath.$row['id']?>/" class="btn red"><i class="fa fa-trash"></i> </a>
                                </td>
                            </tr>
                        <?}?>
                    <?}?>
                </table>
            </div>
        <?}?>
    <?}?>
<? } else {
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
                <? create_form_by_array($form1,$data); ?>
                <tr>
                    <td width="200">Категория</td>
                    <td>
                        <select class="form-control" name="data[block_id]">
                            <? foreach($blocks as $key=>$block){?>
                                <option <?=($key==$data['block_id']) ? 'selected' : '' ?> value="<?=$key?>"><?=$block?></option>
                            <?}?>
                        </select>
                    </td>
                </tr>
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
