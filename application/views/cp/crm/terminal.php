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

$partners = [];
$result = $this->db->where("status", 1)->get("partner")->result();
foreach ($result as $row) {
    $partners[$row->id] = $row;
}

$terminals = [];
$result = $this->db->get("terminals")->result();
foreach ($result as $row) {
    $terminals[$row->fiscal_number] = $row;
}

$statuses = getStatuses(false);

$payments = [];
$result = $this->db->get("payment_type")->result();
foreach ($result as $row) {
    $payments[$row->id] = $row;
}

$this->db->select("
    orders.order_date as order_date,
    orders.fiscal_number as order_fiscal_number,
    orders.id as order_id,
    orders.generated_id as generated_id,
    orders.consultant_id as order_id_consultant,
    orders.refund_amount as order_refund_amount,
    orders.uds_cashback as order_uds_cashback,
    orders.status as order_status,
    orders.payment_type_id as order_payment,
    product.id as product_id,
    product.name_ru as product_name,
    product.partner_id as product_partner_id,
    order_items.quantity as product_qty,
    order_items.price as product_price,
    order_items.reduction as product_reduction,
");
$this->db->join("order_items", "order_items.order_id = orders.id");
$this->db->join("product", "product.id = order_items.product_id");
$this->db->where("orders.terminal_id >", 0);
$this->db->where("orders.order_date >=", $sdr);
$this->db->where("orders.order_date <=", $edr);

if(isset($_POST['terminal']) and !empty($_POST['terminal'])) {
    $this->db->where_in("orders.fiscal_number", $_POST['terminal']);
}

$sort = (isset($_POST['sort']) and !empty($_POST['sort'])) ? $_POST['sort'] : 0;

switch ($sort) {
    case 1:
        $this->db->order_by("orders.status", "desc");
        break;
    case 2:
        $this->db->order_by("product.partner_id", "desc");
        break;
    case 3:
        $this->db->order_by("orders.payment", "asc");
        break;
}
$result = $this->db->get("orders")->result();

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
            <a href="/<?=ADM_CONTROLLER?>/crm/terminal">Отчет - товары по терминалов</a>
		</li>
	</ul>
</div>
<form method="POST">
	<input type="text" class="form-control date-picker" value="<?=$sd?>" name="sd" style="width:120px;margin-right:20px;float:left;">
	<input type="text" class="form-control date-picker" value="<?=$ed?>" name="ed" style="width:120px;margin-right:20px;float:left;">
    <select name="sort" id="" class="form-control" style="width:120px;margin-right:20px;float:left;">
        <option value="">--</option>
        <option <?=(@$_POST['sort'] == '1') ? ' selected' : ''?> value="1">Статус</option>
        <option <?=(@$_POST['sort'] == '2') ? ' selected' : ''?> value="2">Партнер</option>
        <option <?=(@$_POST['sort'] == '3') ? ' selected' : ''?> value="3">оплата</option>
    </select>
    <select name="terminal" id="" class="form-control" style="width:200px;margin-right:20px;float:left;">
        <option value="">--</option>
        <? foreach ($terminals as $terminal) {?>
            <option <?=(@$_POST['terminal'] == $terminal->fiscal_number) ? ' selected' : ''?> value="<?=$terminal->fiscal_number?>"><?=$terminal->address?></option>
        <?}?>
    </select>
	<button type="submit" class="btn green"><i class="fa fa-check"></i> Применить</button>
	<button type="button" class="btn red" onclick="window.location=window.location.pathname;"><i class="fa fa-check"></i> Очистить</button>
    <a href="/<?=ADM_CONTROLLER?>/crm/download?sd=<?=$sd?>&ed=<?=$ed?>" class="btn blue">Скачать</a>
</form>
<div class="">
    <table class="table table-striped table-bordered table-hover dataTable no-footer">
        <tr class="heading nodrop nodrag">
            <th>дата</th>
            <th>теминал Market Place <br> адрес магазина где была <br> произведена продажа </th>
            <th>Рег. Номер кассового <br> аппарата терминала</th>
            <th>номер заказа</th>
            <th>Статус заказа</th>
            <th>Номер чека</th>
            <th>оплата МЕТОД</th>
            <th>Партнер</th>
            <th>Код товара</th>
            <th>Наименование товаров</th>
            <th>Кол-во</th>
            <th>Цена <br> продажи</th>
            <th>Сумма <br> продажи</th>
            <th>УДС начисление <br> /сумма</th>
            <th>УДС использование <br> /сумма</th>
            <th>консультант <br> код</th>
        </tr>
        <? foreach($result as $row) { ?>
            <tr>
                <td><?=$row->order_date?></td>
                <td><?=$terminals[$row->order_fiscal_number]->address?></td>
                <td><?=$row->order_fiscal_number?></td>
                <td><?=$row->generated_id?></td>
                <td><?=$statuses[$row->order_status]?></td>
                <td></td>
                <td><?=$payments[$row->order_payment]->name_ru?></td>
                <td><?=$partners[$row->product_partner_id]->name?></td>
                <td><?=$row->product_id?></td>
                <td><?=$row->product_name?></td>
                <td><?=$row->product_qty?></td>
                <td><?=$row->product_price - $row->product_reduction?></td>
                <td><?=($row->product_price - $row->product_reduction) * $row->product_qty?></td>
                <td><?=$row->order_refund_amount?></td>
                <td><?=$row->order_uds_cashback?></td>
                <td><?=$row->order_id_consultant?></td>
            </tr>
        <?}?>
    </table>
</div>
