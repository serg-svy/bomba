<?
$sd=date("Y-m-01",time());
$ed=date("Y-m-d",time());

$sdr=date("Y-m-01",time()).' 00:00:00';
$edr=date("Y-m-d",time()).' 23:59:59';

$terminal = " >= 0";

$category_id = 0;

$partner_id = $this->uri->segment(4);

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

	if($partner_id) $category_id = $_POST['category_id'];
}
$partner = ($partner_id) ? $this->db->where("id", $partner_id)->get("partner")->row() : [];


$all_categories = $this->db->select("category.id as id, category.parent_id as parent_id, category.level as level")->get("category")->result();

if($partner_id) {
    $this->db->select("
                distinct(product.id) as id,
                product.name_ru as name,
                sum(order_items.quantity) as count,
                category_product.category_id as category_id,
            ");
    $this->db->join("order_items", "order_items.order_id = orders.id");
    $this->db->join("product", "product.id = order_items.product_id");
    $this->db->join("category_product", "category_product.product_id = product.id");
    $this->db->where("order_items.partner_id", $partner_id);
    $this->db->where("orders.status", 2);
    $this->db->where_in("orders.terminal_id $terminal");
    $this->db->where("orders.order_date >=", $sdr);
    $this->db->where("orders.order_date <=", $edr);
    $this->db->limit(50);
    $this->db->group_by("id");
    $this->db->order_by("count", "desc");
    $products = $this->db->get("orders")->result();

    $product_ids = [];
    $select_categories = [];
    $partner_categories_ids = [];
    $map_categories = [];
    $counts = [];

    foreach ($products as $product) {
        $product_ids[] = $product->id;
        $count = (isset($counts[$product->category_id])) ? $counts[$product->category_id]+1 : 1;
        $counts[$product->category_id] = $count;
    }

    if($product_ids) {
        $product_ids = array_unique($product_ids);
        $select_categories = $this->db->select("distinct(category.id) as id, category.name_ru as name")
            ->join("category_product", "category_product.category_id = category.id")
            ->where_in("category_product.product_id", $product_ids)
            ->get("category")->result();

        foreach ($select_categories as $select_category) {
            unset($_SESSION['cat_ids']);
            categories_cat_ids($all_categories, $select_category->id);
            $partner_categories_ids = array_merge($partner_categories_ids, $_SESSION['cat_ids']);

            for($i=1; $i<3; $i++) {
                if(isset($_SESSION['cat_ids'][$i])) {
                     if(isset($counts[$_SESSION['cat_ids'][$i]])) {
                         $counts[$_SESSION['cat_ids'][$i]] = $counts[$_SESSION['cat_ids'][$i]] + @$counts[$_SESSION['cat_ids'][3]];
                     } else {
                         $counts[$_SESSION['cat_ids'][$i]] = @$counts[$_SESSION['cat_ids'][3]];
                     }
                }
            }
        }

        if($partner_categories_ids) {
            $map_categories = $this->db->where_in("category.id", $partner_categories_ids)->get("category")->result();
        }
    }
} else {
    $sorted_partners = [];
    $partners = $this->db->where("status", 1)->get("partner")->result();
    foreach ($partners as $partner) {
        $closed = $this->db->select("count(distinct(orders.id)) as count, sum(order_items.price - order_items.reduction) as sum")
            ->join("order_items", "order_items.order_id = orders.id")
            ->where("order_items.partner_id", $partner->id)
            ->where("orders.status", 2)
            ->where("orders.terminal_id $terminal")
            ->where("orders.order_date >=", $sdr)
            ->where("orders.order_date <=", $edr)
            ->get("orders")->row();

        $canceled = $this->db->select("distinct(orders.id)")
            ->join("order_items", "order_items.order_id = orders.id")
            ->where("order_items.partner_id", $partner->id)
            ->where("orders.status", 3)
            ->where_in("orders.terminal_id $terminal")
            ->where("orders.order_date >=", $sdr)
            ->where("orders.order_date <=", $edr)
            ->get("orders")->num_rows();

        $partner->closed = $closed;
        $partner->canceled = $canceled;

        $dop = ($partner->id < 10) ? '0' : '';
        $key = $closed->count . '' . $dop . '' . $partner->id;
        $sorted_partners[$key] = $partner;
    }
    krsort($sorted_partners);
}?>

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
            <a href="/<?=ADM_CONTROLLER?>/crm/time">Отчет - временной интервал</a>
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
    <select name="terminal" id="" class="form-control" style="width:120px;margin-right:20px;float:left;">
        <option <?=(@$_POST['terminal'] == 'all') ? ' selected' : ''?> value="all">все</option>
        <option <?=(@$_POST['terminal'] == 'terminal') ? ' selected' : ''?> value="terminal">терминал</option>
        <option <?=(@$_POST['terminal'] == 'site') ? ' selected' : ''?> value="site">магазин</option>
    </select>
    <? if($partner_id){?>
        <select name="category_id" id="" class="form-control" style="width:220px;margin-right:20px;float:left;">
            <option selected value="">все</option>
            <? foreach ($select_categories as $select_category){?>
                <option <?=($category_id == $select_category->id) ? ' selected' : ''?> value="<?=$select_category->id?>"><?=$select_category->name?></option>
            <?}?>
        </select>
    <?}?>

	<button type="submit" class="btn green"><i class="fa fa-check"></i> Применить</button>
	<button type="button" class="btn red" onclick="window.location=window.location.pathname;"><i class="fa fa-check"></i> Очистить</button>
</form>
<? if($partner_id){?>
    <br>
    <div class="portlet box">
        <div class="portlet-title">
            <div class="caption" style="color:#888;font-size:26px;"><?=$partner->jur_name?></div>
        </div>
    </div>
    <br>
    <div id="sss" style="margin: 20px;"></div>
    <div class="">
        <table class="table table-striped table-bordered table-hover dataTable no-footer">
            <? if($products){ ?>
                <tr class="heading nodrop nodrag">
                    <th width="100">ID</th>
                    <th>Товар</th>
                    <th width="100">штук</th>
                </tr>
                <? $total = 0;?>
                <? foreach($products as $product) { ?>
                    <? if($category_id){?>
                        <? if($product->category_id == $category_id){?>
                            <tr data-id="<?=$product->id?>">
                                <td><a target="_blank" href="/ru/product/<?=$product->id?>/"><?=$product->id?></a></td>
                                <td><?=$product->name?></td>
                                <td><?=$product->count?></td>
                            </tr>
                        <?}?>
                    <?} else {?>
                        <tr data-id="<?=$product->id?>">
                            <td><a target="_blank" href="/ru/product/<?=$product->id?>/"><?=$product->id?></a></td>
                            <td><?=$product->name?></td>
                            <td><?=$product->count?></td>
                        </tr>
                    <?}?>
                    <? $total = $total + $product->count?>
                <?}?>
                <tr><td colspan="2" style="text-align: right; font-weight: bold">итого</td><td><?=$total?></td></tr>
            <?} else {?>
                <tr><td><h2>за это время товаров не найдено</h2></td></tr>
            <?}?>
        </table>
    </div>
<?} else {?>
    <div class="">
        <table class="table table-striped table-bordered table-hover dataTable no-footer">
            <tr class="heading nodrop nodrag">
                <th>Имя</th>
                <th width="100">Закрыт</th>
                <th width="100">Отменен</th>
                <th width="100">Сумма</th>
            </tr>
            <? $total_closed = $total_canceled = $total_sum = 0;?>
            <? foreach($sorted_partners as $partner) { ?>
                <tr>
                    <td><a href="/<?=ADM_CONTROLLER?>/crm/time/<?=$partner->id?>"><?=$partner->jur_name?></a></td>
                    <td><?=$partner->closed->count?></td>
                    <td><?=$partner->canceled?></td>
                    <td><?=($partner->closed->sum) ? $partner->closed->sum.' mdl' : ''?> </td>
                </tr>
                <? $total_closed = $total_closed + $partner->closed->count?>
                <? $total_canceled = $total_canceled + $partner->canceled?>
                <? $total_sum = $total_sum + $partner->closed->sum?>
            <?}?>
            <tr>
                <td style="text-align: right; font-weight: bold">итого</td>
                <td><?=$total_closed?></td>
                <td><?=$total_canceled?></td>
                <td><?=$total_sum?> mdl</td>
            </tr>
        </table>
    </div>
<?}?>

<link href="/theme/assets/global/plugins/jstree/dist/themes/default/style.css" rel="stylesheet" type="text/css"/>
<script src="/theme/assets/global/plugins/jstree/dist/jstree.js" type="text/javascript"></script>

<script>
    $(function () {
        $('#sss').on('changed.jstree', function (e, data) {
        }).jstree({
            'core': {
                'data': [<?=admin_categories_json($map_categories, 0, null, null, $counts)?>]
            }
        });
    });
</script>
