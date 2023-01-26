<?
$sd=date("Y-m-01",time());
$ed=date("Y-m-d",time());

$sdr=date("Y-m-01",time()).' 00:00:00';
$edr=date("Y-m-d",time()).' 23:59:59';

$terminal = array(0,1);

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
                $terminal = array(0,1);
                break;
            case "terminal":
                $terminal = array(1);
                break;
            case "site":
                $terminal = array(0);
                break;
        }
    }
}
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
            <a href="">Отчет - товары под заказ</a>
		</li>
	</ul>
</div>
<form method="POST">
	<input type="text" class="form-control date-picker" value="<?=$sd?>" name="sd" style="width:120px;margin-right:20px;float:left;">
	<input type="text" class="form-control date-picker" value="<?=$ed?>" name="ed" style="width:120px;margin-right:20px;float:left;">
    <select name="terminal" id="" class="form-control" style="width:120px;margin-right:20px;float:left;">
        <option <?=(@$_POST['terminal'] == 'all') ? ' selected' : ''?> value="all">все</option>
        <option <?=(@$_POST['terminal'] == 'terminal') ? ' selected' : ''?> value="terminal">терминал</option>
        <option <?=(@$_POST['terminal'] == 'site') ? ' selected' : ''?> value="site">магазин</option>
    </select>

	<button type="submit" class="btn green"><i class="fa fa-check"></i> Применить</button>
	<button type="button" class="btn red" onclick="window.location=window.location.pathname;"><i class="fa fa-check"></i> Очистить</button>
</form>
<?
     $products = $this->db->select("product.id as id, product.name_ru as name, orders.order_date as date, orders.id as order_id")
         ->join("order_items", "order_items.order_id = orders.id")
         ->join("product", "product.id = order_items.product_id")
         ->join("product_stock", "product_stock.product_id = order_items.product_id")
         ->where("preorder", 1)
         ->where_in("orders.terminal", $terminal)
         ->where("orders.order_date >=", $sdr)
         ->where("orders.order_date <=", $edr)
         ->order_by("order_date", "DESC")
         ->get("orders")->result();
?>
<div class="">
    <table class="table table-striped table-bordered table-hover dataTable no-footer">
        <tr class="heading nodrop nodrag">
        <th width="100">Номер заказа</th>
        <th>Имя</th>
        <th width="100">Дата заказа</th>
        <th width="100">Дата доставки</th>
    </tr>
        <? foreach($products as $product) { ?>
            <tr>
                <td><a target="_blank" href="/<?=ADM_CONTROLLER?>/orders/<?=$product->order_id?>/"><?=$product->order_id?></a></td>
                <td><a target="_blank" href="/ru/product/<?=$product->id?>/"><?=$product->name?></a></td>
                <td><?=date('d.m.Y', strtotime($product->date))?></td>
                <td><?=date('d.m.Y', strtotime($product->date . ' + 20 days'))?></td>
            </tr>
        <?}?>
    </table>
</div>
