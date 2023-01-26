<?
$head1 = 'Быстрые заказы';
$addnew = 'заказ';
$tblname = 'credit_orders';
$headerloc = 'credit_orders';

$sd=date("Y-m-d",time()-86400*5);
$sdr=date("Y-m-d",time()-86400*5).' 00:00:00';
$ed=date("Y-m-d",time());
$edr=date("Y-m-d",time()).' 23:59:59';
$cond=" AND `created_at`>='$sdr' AND `created_at`<='$edr'";
$sval='';
$us = 1;
$uri3=$this->uri->segment(3);

if ($_SERVER['REQUEST_METHOD']=='POST') {
    if (!empty($_POST['sd'])) {
        $sd=$_POST['sd'];
        $sdr=$_POST['sd'].' 00:00:00';
        $ed=$_POST['ed'];
        $edr=$_POST['ed'].' 23:59:59';
        $cond=" AND `created_at`>='$sdr' AND `created_at`<='$edr'";
    }

    if ( !empty($_POST['user'])) {
        $us=$_POST['user'];
    }

    if (!empty($_POST['sval'])) {
        $sval=$_POST['sval'];
        $cond .=" AND (phone LIKE '%$sval%' OR username LIKE '%$sval%')";
    }

    switch ($us) {
        case 2:
            $cond .= " AND status=FALSE";
            break;
        case 3:
            $cond .= " AND status=TRUE";
            break;
    }
}

$stat1=array(1=>'Все заказы',2=>'В обработке',3=>'Обработан');
?>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="/<?= ADM_CONTROLLER ?>/menu/">Главная</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a><?= $head1 ?></a>
        </li>
    </ul>
</div>

<?= @$err ?>

<form method="POST" class="chosehold">
    <input type="text" class="form-control date-picker" value="<?=$sd?>" name="sd" style="width:120px;margin-right:20px;float:left;">
    <input type="text" class="form-control date-picker" value="<?=$ed?>" name="ed" style="width:120px;margin-right:20px;float:left;">
    <select name="user" class="form-control" style="width:200px;float:left;margin-right:20px;">
        <?
        foreach($stat1 as $key=>$val) {
            if($key==$us) $mod=' selected'; else $mod='';
            echo '<option'.$mod.' value="'.$key.'">'.$val.'</option>';
        }
        ?>
    </select>

    <input type="text" class="form-control" placeholder="Укажите имя, телефон или e-mail" value="<?=$sval?>" name="sval" style="width:380px;margin-right:20px;float:left;">
    <button type="submit" class="btn green"><i class="fa fa-check"></i> Применить</button>
    <button type="button" class="btn red" onclick="window.location=window.location.pathname;"><i class="fa fa-check"></i> Очистить</button>
</form>
<br clear="all">

<?php $checkb = $this->db->query("SELECT * FROM credit_orders WHERE 1=1$cond ORDER BY created_at DESC")->result_array();?>

<?php if (!empty($checkb)) : ?>
    <div class="">
        <table class="table table-striped table-bordered table-hover dataTable no-footer">
            <tr class="heading nodrop nodrag">
                <th width="100">ID заявки</th>
                <th>Имя</th>
                <th>Тип</th>
                <th>Телефон</th>
                <th>Товар</th>
                <th>Стоимость <br> товара (лей)</th>
                <th>Продол.</th>
                <th>Ежемесячно</th>
                <th width="140">Дата</th>
                <th>ID Товара</th>
                <th width="250">Действия</th>
                <th width="250">UNA</th>
            </tr>
            <?php foreach ($checkb as $barr): ?>
                <?php $d = json_decode($barr['credit_prices']);?>
                <tr>
                    <td><?= $barr['ID'] ?></td>
                    <td><?= $barr['username'] ?></td>
                    <th><?=$barr['type']=='credit'?'Кредит':'Рассрочка'?></th>
                    <td><?= $barr['phone'] ?></td>
                    <td><?= $barr['product'] ?></td>
                    <td><?= $barr['product_price'] ?></td>
                    <td><?= $barr['credit']?></td>
                    <td><?= @$d->{$barr['credit']}?></td>
                    <td><?= date('d.m.Y h:i:s', strtotime($barr['created_at'])) ?></td>
                    <td><?= $barr['product_id'] ?></td>
                    <?php $checked = ($barr['status']) ? 'checked' : '';?>
                    <td>
                        <input type="checkbox" name="c<?= $barr['ID']?>" data-id="<?= $barr['ID'] ?>"
                               class="form-control" <?= $checked ?>> Обработан
                    </td>
                    <td>
                        <button data-id="<?=$barr['ID']?>" type="button" class="btn btn-primary btn-sm" id="manualSendToUna">отправить заказ в программу</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
<?php endif; ?>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('input[type="checkbox"]').on('change', function (e) {
            e.preventDefault();
            let id=$(this).data('id');
            let val;
            if ($(this).is(':checked')) {
                val=1;
            } else {
                val=0;
            }
            $.post('/<?=ADM_CONTROLLER?>/checkboxChange/', { "table": "<?=$tblname?>", "col": "status", "id":id, "val":val } );
        });

        $(document).on('click', '#manualSendToUna', function() {
            let id=$(this).data("id");
            $.post('/<?=ADM_CONTROLLER?>/credit_send_to_una/', {id:id},function (r) {
                alert('заказ был отправлен вручную');
            })
        });
    });
</script>
