<?
$sd=date("Y-m-01",time());
$ed=date("Y-m-d",time());

$sdr=date("Y-m-01",time()).' 00:00:00';
$edr=date("Y-m-d",time()).' 23:59:59';

if ($_SERVER['REQUEST_METHOD']=='POST') {
    if (!empty($_POST['sd'])) {
        $sd=$_POST['sd'];
        $ed=$_POST['ed'];

        $sdr=$_POST['sd'].' 00:00:00';
        $edr=$_POST['ed'].' 23:59:59';
    }
}

$sorted_terminals = [];
$terminals = $this->db->get("terminals")->result();
foreach ($terminals as $terminal) {
    $closed = $this->db->select("count(distinct(orders.id)) as count, sum(order_items.price - order_items.reduction) as sum")
        ->join("order_items", "order_items.order_id = orders.id")
        ->where("orders.fiscal_number", $terminal->fiscal_number)
        ->where("orders.status", 2)
        ->where("orders.order_date >=", $sdr)
        ->where("orders.order_date <=", $edr)
        ->get("orders")->row();

    $canceled = $this->db->select("distinct(orders.id)")
        ->join("order_items", "order_items.order_id = orders.id")
        ->where("orders.fiscal_number", $terminal->fiscal_number)
        ->where("orders.status", 3)
        ->where("orders.order_date >=", $sdr)
        ->where("orders.order_date <=", $edr)
        ->get("orders")->num_rows();

    $terminal->closed = $closed;
    $terminal->canceled = $canceled;

    $dop = ($terminal->id < 10) ? '0' : '';
    $key = $closed->count . '' . $dop . '' . $terminal->id;
    $sorted_terminals[$key] = $terminal;
}
krsort($sorted_terminals);
?>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="/<?=ADM_CONTROLLER?>/menu/">Главная</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="/<?=ADM_CONTROLLER?>/crm/">CRM</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="/<?=ADM_CONTROLLER?>/crm/time">Отчеты  по терминалам</a>
        </li>
    </ul>
</div>
<form method="POST">
    <input type="text" class="form-control date-picker" value="<?=$sd?>" name="sd" style="width:120px;margin-right:20px;float:left;">
    <input type="text" class="form-control date-picker" value="<?=$ed?>" name="ed" style="width:120px;margin-right:20px;float:left;">
    <button type="submit" class="btn green"><i class="fa fa-check"></i> Применить</button>
    <button type="button" class="btn red" onclick="window.location=window.location.pathname;"><i class="fa fa-check"></i> Очистить</button>
</form>
<div class="">
    <table class="table table-striped table-bordered table-hover dataTable no-footer">
        <tr class="heading nodrop nodrag">
            <th>Номер</th>
            <th>Имя</th>
            <th width="100">Закрыт</th>
            <th width="100">Отменен</th>
            <th width="100">Сумма</th>
        </tr>
        <? $total_closed = $total_canceled = $total_sum = 0;?>
        <? foreach($sorted_terminals as $terminal) { ?>
            <tr>
                <td><?=$terminal->fiscal_number?></td>
                <td><?=$terminal->address?></td>
                <td><?=$terminal->closed->count?></td>
                <td><?=$terminal->canceled?></td>
                <td><?=($terminal->closed->sum) ? $terminal->closed->sum.' mdl' : ''?> </td>
            </tr>
            <? $total_closed = $total_closed + $terminal->closed->count?>
            <? $total_canceled = $total_canceled + $terminal->canceled?>
            <? $total_sum = $total_sum + $terminal->closed->sum?>
        <?}?>
        <tr>
            <td style="text-align: right; font-weight: bold">итого</td>
            <td><?=$total_closed?></td>
            <td><?=$total_canceled?></td>
            <td><?=$total_sum?> mdl</td>
        </tr>
    </table>
</div>
