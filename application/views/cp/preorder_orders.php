<?
$head1 = 'Предзаказ';
$addnew = 'заказ';
$tblname = 'preorder_orders';
$headerloc = 'preorder_orders';
$cond = "";


$sd=date("Y-m-d",time()-86400*5);
$sdr=date("Y-m-d",time()-86400*5).' 00:00:00';
$ed=date("Y-m-d",time());
$edr=date("Y-m-d",time()).' 23:59:59';
$cond_time =" AND `created_at`>='$sdr' AND `created_at`<='$edr'";
$sval='';

$uri3=$this->uri->segment(3);
$us = 0;
if ($_SERVER['REQUEST_METHOD']=='POST') {
    if (!empty($_POST['sd'])) {
        $sd=$_POST['sd'];
        $sdr=$_POST['sd'].' 00:00:00';
        $ed=$_POST['ed'];
        $edr=$_POST['ed'].' 23:59:59';
        $cond_time = " AND `created_at`>='$sdr' AND `created_at`<='$edr'";
    }

    if (!empty($_POST['sval']) && trim($_POST['sval'])) {
        $sval=trim($_POST['sval']);
        $cond .=" AND (phone LIKE '%$sval%' OR name LIKE '%$sval%')";
    }

    if (!empty($_POST['user'])) {
        $us = $_POST['user'];
        $cond .= " AND status = $us";
    }
}

$cond .= $cond_time;

$stat1=getStatuses(true);
$stat2=getStatuses(false);
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

    <input type="text" class="form-control" placeholder="Укажите имя или телефон" value="<?=$sval?>" name="sval" style="width:380px;margin-right:20px;float:left;">
    <button type="submit" class="btn green"><i class="fa fa-check"></i> Применить</button>
    <button type="button" class="btn red" onclick="window.location=window.location.pathname;"><i class="fa fa-check"></i> Очистить</button>
</form>
<br clear="all">

<?php
    $checkb = $this->db->query("SELECT * FROM $tblname WHERE 1=1$cond ORDER BY created_at DESC")->result_array();
?>

<?php if (!empty($checkb)) : ?>
    <div class="">
        <table class="table table-striped table-bordered table-hover dataTable no-footer">
            <tr class="heading nodrop nodrag">
                <th width="100">ID заявки</th>
                <th>Имя</th>
                <th>Телефон</th>
                <th>Товар</th>
                <th>Стоимость (лей)</th>
                <th width="140">Дата</th>
                <th>ID Товара</th>
                <th width="300">Действия</th>
            </tr>
            <?php foreach ($checkb as $barr): ?>
                <tr id="order_<?=$barr['id']?>">
                    <td><?= $barr['id'] ?></td>
                    <td><?= $barr['name'] ?></td>
                    <td><?= $barr['phone'] ?></td>
                    <td><?= strip_tags($barr['product_name']) ?></td>
                    <td><?= $barr['product_price'] ?></td>
                    <td><?= date('d.m.Y H:i:s', strtotime($barr['created_at'])) ?></td>
                    <td><?= $barr['product_id'] ?></td>
                    <?php $checked = ($barr['status']) ? 'checked' : '';?>
                    <td style="display: flex">
                        <button data-id="<?=$barr['id']?>" type="button" class="btn btn-primary btn-sm" id="manualSendToUna">отправить в UNA</button>
                        <select class="form-control statchange" data-id="<?= $barr['id'] ?>">
                            <?php foreach($stat2 as $key=>$val) {
                                if (intval($barr['status'])==intval($key)) $mod=' selected'; else $mod='';
                                echo '<option'.$mod.' value="'.$key.'">'.$val.'</option>';
                            } ?>
                        </select>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
<?php endif; ?>

<script type="text/javascript">
    $('body').on('change', '.statchange', function() {
        let id=$(this).val();
        let val = $(this).val();
        $.post('/<?=ADM_CONTROLLER?>/checkboxChange/', { "table": "<?=$tblname?>", "col": "status", "id":id, "val":val } );
    });

    $(function () {
        var dest = 0;
        dest = $(this.hash).offset().top;
        $('html,body').animate({
            scrollTop: dest
        }, 2000, 'swing');
    });

    $(document).on('click', '#manualSendToUna', function() {
        let id=$(this).data("id");
        $.post('/<?=ADM_CONTROLLER?>/fast_send_to_una/', {id:id},function (r) {
            alert('заказ был отправлен вручную');
        })
    });
</script>
