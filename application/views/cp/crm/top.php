<?
$sd=date("Y-m-01",time());
$ed=date("Y-m-d",time());

$sdr=date("Y-m-01",time()).' 00:00:00';
$edr=date("Y-m-d",time()).' 23:59:59';

$terminal = " >= 0";
$partner_id = 0;

if ($_SERVER['REQUEST_METHOD']=='POST') {
	if (!empty($_POST['sd'])) {
		$sd=$_POST['sd'];
		$ed=$_POST['ed'];

		$sdr=$_POST['sd'].' 00:00:00';
		$edr=$_POST['ed'].' 23:59:59';
	}

    if(!empty($_POST['terminal'])) {
        switch ($_POST['terminal']){
            case "all":
                $terminal = " >= 0";
                break;
            case "terminal":
                $terminal = " > 0";
                break;
            case "site":
                $terminal = " = 0";
                break;
        }
    }
    $partner_id = $_POST['partner_id'];
}

$partners = $this->db->where("status", 1)->get("partner")->result();
$partner = ($partner_id) ? $this->db->where("id", $partner_id)->get("partner")->row() : [];

$this->db->select("
    distinct(product.id) as id,
    product.name_ru as name,
    sum(order_items.quantity) as count,
");
$this->db->join("order_items", "order_items.order_id = orders.id");
$this->db->join("product", "product.id = order_items.product_id");
if($partner_id) {
    $this->db->where("order_items.partner_id", $partner_id);
} else {
    $this->db->where("order_items.partner_id > ", 1);
}
$this->db->where("orders.terminal_id $terminal");
$this->db->where("orders.status", 2);
$this->db->where("orders.order_date >=", $sdr);
$this->db->where("orders.order_date <=", $edr);
$this->db->limit(50);
$this->db->group_by("id");
$this->db->order_by("count", "desc");
$products = $this->db->get("orders")->result();

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
            <a href="/<?=ADM_CONTROLLER?>/crm/top">Отчет по самым продаваемым</a>
            <? if($partner_id){?>
                <i class="fa fa-angle-right"></i>
            <?}?>
		</li>
        <? if($partner_id){?>
            <li>
                <a><?=$partner->jur_name?></a>
            </li>
        <?}?>
	</ul>
</div>
<form method="POST">
	<input type="text" class="form-control date-picker" value="<?=$sd?>" name="sd" style="width:120px;margin-right:20px;float:left;">
	<input type="text" class="form-control date-picker" value="<?=$ed?>" name="ed" style="width:120px;margin-right:20px;float:left;">
    <select name="partner_id" id="" class="form-control" style="width:200px;float:left;margin-right:20px;">
        <option value="0">--</option>
        <? foreach ($partners as $partner_for_select) {?>
            <option <?=($partner_for_select->id == $partner_id) ? ' selected' : '' ?> value="<?=$partner_for_select->id?>"><?=$partner_for_select->jur_name?></option>
        <?}?>
    </select>
    <select name="terminal" id="" class="form-control" style="width:120px;margin-right:20px;float:left;">
        <option <?=(@$_POST['terminal'] == 'all') ? ' selected' : ''?> value="all">все</option>
        <option <?=(@$_POST['terminal'] == 'terminal') ? ' selected' : ''?> value="terminal">терминал</option>
        <option <?=(@$_POST['terminal'] == 'site') ? ' selected' : ''?> value="site">магазин</option>
    </select>

	<button type="submit" class="btn green"><i class="fa fa-check"></i> Применить</button>
	<button type="button" class="btn red" onclick="window.location=window.location.pathname;"><i class="fa fa-check"></i> Очистить</button>
</form>
<? if(empty($partner_id)){?>
    <div class="">
        <table class="table table-striped table-bordered table-hover dataTable no-footer">
            <tr class="heading nodrop nodrag">
                <th width="100">ID</th>
                <th>Товар</th>
                <th width="100">штук</th>
            </tr>
            <? $total = 0;?>
            <? foreach($products as $product) { ?>
                <tr data-id="<?=$product->id?>">
                    <td><a target="_blank" href="https://bomba.md/ru/product/<?=$product->id?>/"><?=$product->id?></a></td>
                    <td><?=$product->id?> - <?=$product->name?></td>
                    <td><?=$product->count?></td>
                </tr>
                <? $total = $total + $product->count?>
            <?}?>
            <tr><td colspan="2" style="text-align: right; font-weight: bold">итого</td><td><?=$total?></td></tr>
        </table>
    </div>
<?} else {?>
    <br>
    <div class="portlet box">
        <div class="portlet-title">
            <div class="caption" style="color:#888;font-size:26px;"><?=$partner->jur_name?></div>
        </div>
    </div>
    <br>
    <div class="">
        <table class="table table-striped table-bordered table-hover dataTable no-footer">
            <tr class="heading nodrop nodrag">
                <th width="100">ID</th>
                <th>Товар</th>
                <th width="100">штук</th>
            </tr>
            <? $total = 0;?>
            <? foreach($products as $product) { ?>
                <tr data-id="<?=$product->id?>">
                    <td><a target="_blank" href="https://bomba.md/ru/product/<?=$product->id?>/"><?=$product->id?></a></td>
                    <td><?=$product->name?></td>
                    <td><?=$product->count?></td>
                </tr>
                <? $total = $total + $product->count?>
            <?}?>
            <tr><td colspan="2" style="text-align: right; font-weight: bold">итого</td><td><?=$total?></td></tr>
        </table>
    </div>
<?}?>
