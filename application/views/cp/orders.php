<style>
    .uds .fa.fa-close { color: red; font-size: 36px; margin-left: 10px; vertical-align: middle; margin-top: -3px; cursor: pointer;}
    .uds .fa.fa-check { color: green; font-size: 36px; margin-left: 10px; vertical-align: middle; margin-top: -3px; cursor: pointer;}
    .admin_message__edit-btn,
    .admin_message__save-btn,
    .admin_message__cancel-btn {font-size: 12px;border: none; border-radius: 3px}
    .admin_message__edit-btn {bottom: 0;left:0; position: absolute;}
    .admin_message .admin_message__edit-btn,
    .admin_message textarea,
    .admin_message .admin_message__block-btn { display: none;}

    td:hover .admin_message .admin_message__edit-btn {display: block}
    td:hover  .admin_message.admin_message--edit .admin_message__edit-btn {display: none}

    .admin_message.admin_message--edit span  {display: none}
    .admin_message textarea {display: none}
    .admin_message.admin_message--edit textarea  {display: block}
    .admin_message.admin_message--edit .admin_message__block-btn  {display: block}
    .admin_message {position: relative;padding-bottom: 20px ;height: 100%}
    .admin_message.admin_message--edit {padding-bottom: 0;}
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<?php
ini_set('memory_limit', '2048M');
ini_set('max_execution_time', '0');

$tblname='Transfer';
$sd=date("Y-m-d",time()-86400*7);
$sdr=date("Y-m-d",time()-86400*7).' 00:00:00';
$ed=date("Y-m-d",time());
$edr=date("Y-m-d",time()).' 23:59:59';
$cond=" AND `order_date`>='$sdr' AND `order_date`<='$edr'";
$sval='';
$generated_id='';

$uri3=$this->uri->segment(3);

$us=0;
if ($_SERVER['REQUEST_METHOD']=='POST') {
    if (!empty($_POST['sd'])) {
        $sd=$_POST['sd'];
        $sdr=$_POST['sd'].' 00:00:00';
        $ed=$_POST['ed'];
        $edr=$_POST['ed'].' 23:59:59';
        $cond=" AND `order_date`>='$sdr' AND `order_date`<='$edr'";
        $us=$_POST['user'];
    }
    if (!empty($_POST['sval'])) {
        $sval=trim($_POST['sval']);
        $cond .= " AND (phone LIKE '%$sval%' OR email LIKE '%$sval%' OR name LIKE '%$sval%')";
    }
    if(!empty($_POST['generated_id'])) {
        $sval = '';
        $generated_id=trim($_POST['generated_id']);
        $cond = " AND generated_id = $generated_id";
    }
}
if (!empty($us)) $cond.=" AND status=".intval($us);

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
        <?php if (empty($uri3)) {?>
            <li>
                <a>Заказы</a>
            </li>
        <?php } else {?>
            <li>
                <a href="/<?=ADM_CONTROLLER?>/orders/">Заказы</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a>Просмотр заказа</a>
            </li>
        <?php }?>
    </ul>
</div>

<?php if (empty($uri3)) {?>
    <form method="POST" class="chosehold">
        <input type="text" class="form-control date-picker" value="<?=$sd?>" name="sd" style="width:120px;margin-right:20px;float:left;">
        <input type="text" class="form-control date-picker" value="<?=$ed?>" name="ed" style="width:120px;margin-right:20px;float:left;">
        <select name="user" class="form-control" style="width:200px;float:left;margin-right:20px;">
            <?php
            foreach($stat1 as $key=>$val) {
                if($key==$us) $mod=' selected'; else $mod='';
                echo '<option'.$mod.' value="'.$key.'">'.$val.'</option>';
            }
            ?>
        </select>

        <input type="text" class="form-control" placeholder="Укажите нр. заказа" value="<?=$generated_id?>" name="generated_id" style="width:160px;margin-right:20px;float:left;">
        <input type="text" class="form-control" placeholder="Укажите имя, телефон или e-mail" value="<?=$sval?>" name="sval" style="width:350px;margin-right:20px;float:left;">
        <button type="submit" class="btn green"><i class="fa fa-check"></i> Применить</button>
        <button type="button" class="btn red" onclick="window.location=window.location.pathname;"><i class="fa fa-check"></i> Очистить</button>
    </form>
    <br clear="all">
    <?php
    $q=$this->db->query("SELECT * FROM orders WHERE pay_flag=1 $cond ORDER BY order_date DESC")->result_array();

    if(count($q)>0)
    {
        echo '<div class="">
	<table class="table table-striped table-bordered table-hover dataTable no-footer">';
        echo '<tr class="heading nodrop nodrag">
		<th width="90">№ заказа</th>
		<th width="90">Дата</th>
		<th width="90">Дата доставки</th>
		<th width="130">Имя</th>
		<th width="90">Телефон</th>
		<th width="130">E-mail</th>
		<th width="">категории товаров</th>
		<th width="100">источник</th>
		<th style="min-width:120px">UDS</th>
		<th style="min-width:150px">Сумма</th>
		<th style="min-width:150px">Статус</th>
		<th width="150">Коммент</th>
	</tr>';
        foreach($q as $arr) {

            $products = $this->db->where("order_id", $arr['id'] )->select("product_id")->get("order_items")->result();

            $category_string = '';
            $category_names = [];
            $i=0;
            foreach ($products as $product) {
                $request = $this->db->select("category.name_ru as category_name")
                    ->join("category_product", "category_product.category_id = category.id")
                    ->where("category_product.product_id", $product->product_id )
                    ->get("category")->row();

                if($request) $category_names[$request->category_name] = $request->category_name;
            }

            foreach ($category_names as $category_name) {
                if(!empty($category_name)) {
                    if($i != 0) $category_string .= ', ';
                    $category_string .= $category_name;
                    $i++;
                }
            }

            $style = '';
            if($arr['payment_type_id'] == 7) {
                $real_sum = $this->db->select("sum(value) as sum")->where("order_id", $arr['generated_id'])->get("cash_log")->row()->sum;
                $style = ($real_sum < $arr['total'] + (int)$arr['delivery_amount']) ? 'background-color:lightsalmon;color:white;' : '';
            }

            echo '<tr style="'.$style.'" data-id="'.$arr['id'].'">';
            if (empty($arr['generated_id'])) $arr['generated_id']=$arr['id'];
            echo '<td><a href="/'.ADM_CONTROLLER.'/orders/'.$arr['id'].'/">'.$arr['generated_id'].'</a></td>';
            echo '<td>'.date('d.m.Y H:i',strtotime($arr['order_date'])).'</td>';
            echo '<td>'.$arr['delivery_date'].'</td>';
            echo '<td>'.$arr['name'].'</td>';
            echo '<td>'.$arr['phone'].'</td>';
            echo '<td>'.$arr['email'].'</td>';
            echo '<td>'.$category_string.'</td>';
            echo '<td>'.($arr['terminal_id'] ? 'с терминала' : 'на сайте').'</td>';

            $uds_buttons = '';
            if(!empty($arr['uds_uid'])) {
                $uds_buttons = '<img src="/dist/img/icons/i34.svg" class="uds_info" width="36" style="cursor: pointer;">';
                if($arr['status'] == 3) {
                    $uds_buttons .= '<i class="fa fa-close" style="color: silver;" title="Отменен"></i>';
                } else {
                    $uds_buttons .= (!$arr['uds_accepted'])
                        ?
                        '<i class="fa fa-check cls" style="color: silver;" title="Бонус еще не зачислен"></i>'
                        :
                        '<i class="fa fa-check" title="Зачислен бонус в размере '.$arr['uds_cashback'].' баллов"></i>';
                }
            }
            echo '<td class="uds" style="text-align: center;">' . $uds_buttons ;

            if((!empty($arr['uds_id']) || !empty($arr['uds_uid'])) && !is_null($arr['uds_response'])) {
                echo '<div class="uds_info" style="display: none;">';
                echo '<pre style="text-align: initial;">';
                print_r(json_decode($arr['uds_response'], true));
                echo '</pre>';
                echo '</div>';
            }

            echo '</td>';

            echo '<td>'.number_format($arr['total'],0,'.',' ').' mdl</td>';
            echo '<td>';
            $add_css = ((!empty($arr['uds_id']) || !empty($arr['uds_uid'])) ? 'uds_order':'');
            echo '<select class="form-control statchange ' . $add_css . '" oid="'.$arr['id'].'">';
            foreach($stat2 as $key=>$val) {
                if (intval($arr['status'])==intval($key)) $mod=' selected'; else $mod='';
                echo '<option'.$mod.' value="'.$key.'">'.$val.'</option>';
            }
            echo '</select>';
            echo '</td>';

            echo '<td>
            <div class="admin_message">
                <span>' . $arr['message'] . '</span>
                <textarea  style="width: 100%; margin-bottom: 10px" cols="10" rows="3" data-id="' . $arr['id'] . '">' . $arr['message'] . '</textarea>
                <div class="admin_message__block-btn">
                    <button class="admin_message__cancel-btn btn btn-xs" type="button">Cancel</button>
                    <button class="admin_message__save-btn btn green btn-xs" type="button">Save</button>
                </div>
                <button class="admin_message__edit-btn btn btn-primary btn-xs" type="button">Edit</button>
            </div>
            </td>';
            echo '</tr>';
        }
        echo "</table>";
        echo '</div>';
    }
    ?>

<?php } else {?>
    <?php
        $pq = $this->db
            ->select("
                product.*,
                order_items.price,
                order_items.reduction,
                order_items.quantity,
                order_items.color,
                order_items.size"
            )
            ->from("order_items")
            ->join("product", "order_items.product_id=product.id")
            ->where("order_items.order_id",$uri3)
            ->group_by("product.id")
            ->order_by("order_items.product_id DESC")->get()->result_array();

        $products_ids = [];

        foreach ($pq as $product) {
            $products_ids[] = $product['id'];
        }

        $preorders_product_ids = $this->product_model->isProductsPreOrder($products_ids);

        foreach ($pq as &$item) {
            $item['preorder'] = $preorders_product_ids[$item['id']];
        }
        unset($item);

        $arr=$this->db->where("ID", $uri3)->get("orders")->row_array();
        if (empty($arr['generated_id'])) $arr['generated_id']=$arr['id'];

        $duplicate = $this->db->select('id')->from('quick_orders')->where('phone', $arr['phone'])->where("created_at >", date('Y-m-d'))->get()->row();
    ?>

    <?php if ($duplicate) {?>
        <div class="alert alert-warning">
            <i class="fa fa-warning"></i> Заказ дублирован в "Быстрые заказы" <a href="/<?=ADM_CONTROLLER?>/quick_orders/#order_<?=$duplicate->id?>">#<?=$duplicate->id?></a>
        </div>
    <?php }?>

    <div class="col-md-6">
        <h2>Данные заказа</h2>
        <table class="table table-striped table-bordered table-hover dataTable no-footer">
            <tr>
                <th width="150">№ заказа</th>
                <td><?=$arr['generated_id']?></td>
            </tr>
            <tr>
                <th width="150">Дата</th>
                <td><?=date('d.m.Y H:i',strtotime($arr['order_date']))?></td>
            </tr>
            <tr>
                <th width="150">Дата доставки</th>
                <td style="font-size:18px;color:#428bca;"><?=$arr['delivery_date']?></td>
            </tr>
            <tr>
                <th width="150">Заказ</th>
                <td><?=number_format($arr['total'],2,'.',' ')?> MDL</td>
            </tr>
            <tr>
                <th width="150">Доставка</th>
                <td><?=number_format($arr['delivery_amount'],0,'.',' ') ?> MDL</td>
            </tr>

            <tr>
                <th width="150">Итоговая сумма</th>
                <td><?=number_format($arr['total'] + (int)$arr['delivery_amount'],2,'.',' ')?> MDL</td>
            </tr>

            <tr>
                <?php $paym = $this->db->where('id',$arr['payment_type_id'])->get('payment_type')->row_array();?>
                <th width="150">Оплата</th>
                <td>
                    <span style="font-size:18px;color:#428bca;"><?=$paym['name_ru'];?></span>
                </td>
            </tr>

            <?php if ($arr['payment_type_id'] == 3) {?>
                <tr>
                    <th width="150">Статус транзакции</th>
                    <?php if (empty($arr['transaction_id']) || empty($arr['rrn_id'])) {?>
                        <td>Не подтверждено</td>
                    <?php } else {?>
                        <?php if ($arr['refund_amount']) {?>
                            <td>Осуществлен возврат денежных средств на карту клиента в размере <?=number_format($arr['refund_amount'], 2, '.', ' ')?> MDL</td>
                        <?php } else {?>
                            <?php $temp_total = $arr['total']+$arr['delivery_amount'];?>
                            <td>Подтверждено, данные по транзакции сохранены в Базе Данных <a href="/<?=ADM_CONTROLLER?>/cashback/<?=$arr['id']?>" class="btn red ilab_cashback" data-price="<?=$temp_total?>" style="float: right;">ОФОРМИТЬ ВОЗВРАТ</a></td>
                        <?php }?>
                    <?php }?>
                </tr>
            <?php }?>

            <?php if ($arr['payment_type_id'] == 6) {?>
                <tr>
                    <th width="150">Статус транзакции</th>
                    <td>
                        <div style="max-height: 300px; overflow: auto">
                            <?php
                        $path = str_replace('public_html', 'terminal.bomba.md', realpath('application'));
                        $path .= '/logger/'.substr($arr['generated_id'], -2).'/'.substr($arr['generated_id'], -4, 2).'/'.$arr['generated_id'].'.log';

                        if($terminal_logs = file_get_contents($path)){
                            $pizza = explode("=>", $terminal_logs);
                            dump($pizza, true);
                        }

                        ?>
                        </div>
                    </td>
                </tr>
            <?php }?>

            <?php if($arr['payment_type_id'] == 7) {?>
                <tr>
                    <th width="150">Реально оплачено</th>
                    <td>
                        <?php $real_sum = $this->db->select("COALESCE(SUM(value),0) as sum")->where("order_id", $arr['generated_id'])->get("cash_log")->row()->sum;?>
                        <?php $color = ($real_sum < $arr['total'] + (int)$arr['delivery_amount']) ? 'red' : 'yellowgreen';?>
                        <span style="font-size: 18px;color:<?=$color?>">
                            <?=$real_sum?> MDL
                            <?php if($real_sum < $arr['total'] + (int)$arr['delivery_amount']){?>
                                <span id="blink">Не полная оплата</span>
                            <?php }?>
                        </span>
                    </td>
                </tr>
            <?php }?>

            <?php if ($arr['terminal_id']) {?>
                <tr>
                    <th width="150">Терминал</th>
                    <td>
                        <?php $terminal = $this->db->where('fiscal_number',$arr['fiscal_number'])->get('terminals')->row_array();?>
                        <span><?=$arr['fiscal_number']?> <?=$terminal['address']?></span>
                    </td>
                </tr>
            <?php }?>

            <tr>
                <?php $paym = $this->db->where('id',$arr['delivery_type_id'])->get('delivery_type')->row_array();?>
                <?php $city = $this->db->where('id', $arr['city_id'])->get('city')->row();?>

                <?php
                    $store ='';
                    if($arr['store_id']>0) {
                        $store = $this->db->select('*')->where('id',$arr['store_id'])->get('store')->row();
                    }
                ?>

                <th width="150">Тип доставки</th>
                <?php if($arr['store_id'] != 0){?>
                    <td><?=$paym['name_ru']?> - <?=@$city->name_ru?> - <?=$store->name_ru?> - <?=$store->address_ru?></td>
                <?php } else {?>
                    <td><?=$paym['name_ru']?> - <?=@$city->name_ru?> - <?=$arr['address']?></td>
                <?php }?>
            </tr>

            <tr>
                <th>Статус заказа</th>
                <td>
                    <?php $add_css = ((!empty($arr['uds_id']) || !empty($arr['uds_uid'])) ? 'uds_order':'');?>
                    <select class="form-control statchange ' . $add_css . '" oid="<?=$arr['id']?>">
                        <?php foreach($stat2 as $key=> $val) {?>
                            <?php if ($arr['status']==$key) $mod=' selected'; else $mod='';?>
                        <option <?=$mod?> value="<?=$key?>"><?=$val?></option>
                        <?php }?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>комментарии</th>
                <td>
                    <textarea name="message" id="message" data-id="<?=$uri3?>" cols="30" rows="5" class="form-control" style="margin-bottom: 10px"><?=$arr['message']?></textarea>
                    <button type="button" class="btn btn-primary btn-sm" id="saveOrderMessage">Сохранить</button>
                </td>
            </tr>
        </table>
    </div>

    <div class="col-md-6">
        <h2>Данные пользователя</h2>
        <table class="table table-striped table-bordered table-hover dataTable no-footer">
            <tr>
                <th width="150">Имя</th>
                <td><?=$arr['name']?></td>
            </tr>
            <tr>
                <th>Адрес доставки</th>
                <td><?=$arr['address']?></td>
            </tr>
            <tr>
                <th width="150">Телефон</th>
                <td><?=$arr['phone']?></td>
            </tr>
            <?php if (!empty($arr['email'])) {?>
                <tr>
                    <th width="150">E-mail</th>
                    <td><?=$arr['email']?></td>
                </tr>
            <?php }?>
            <tr>
                <th>IP адрес клиента</th>
                <td><span class="text-danger"><?=$arr['client_ip']?></span></td>
            </tr>
            <?php if($arr['is_gift']) {?>
                <tr>
                    <th width="150">** Контактные данные получателя (ПОДАРОК)</th>
                    <td></td>
                </tr>
            <?php }?>
        </table>
        <button data-id="<?=$uri3?>" type="button" class="btn btn-primary btn-sm" id="manualSendToUna">отправить заказ в программу</button>
        <div class="portlet box">
            <div class="portlet-title">
                <div class="caption" style="color:#888;font-size:26px;">
                    <h2>Logs</h2>
                </div>
                <div class="tools" style="display:none;">
                    <a href="javascript:;" class="expand"></a>
                </div>
            </div>
            <div class="portlet-body" style="<?php if (empty($err)) echo 'display:none;';?> max-height: 300px;overflow: auto">
                <?php
                if($arr['terminal_id']) {
                    $path = str_replace('public_html', 'terminal.bomba.md', realpath('application'));
                    $path .= '/logger/'.substr($arr['generated_id'], -2).'/'.substr($arr['generated_id'], -4, 2).'/'.$arr['generated_id'].'.log';
                    //print_r(realpath());
                } else {
                    $id = (int)$uri3;
                    $id = $id + 900000000;
                    $logs = $this->db->like("request", $id)->limit(4)->order_by("id desc")->get("payment_log")->result();
                }
                ?>
                <?php if(isset($path)){?>
                    <?php if($terminal_logs = file_get_contents($path)) : ?>
                        <pre style="white-space: pre-wrap;"><?= nl2br($terminal_logs); ?></pre>
                    <?php endif; ?>
                <?php }?>
                <?php foreach($logs as $log){?>

                    <?php $ar = json_decode($log->Request, JSON_UNESCAPED_UNICODE);?>
                    <?php if(is_array($ar)) { ?>
                        <pre style="white-space: pre-wrap;"> <?php print_r($ar);?> </pre>
                    <?php } else {?>
                        <pre style="white-space: pre-wrap;"> <?php print_r($log->Request);?> </pre>
                    <?php } ?>
                <?php }?>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <?php $clang='RU';?>
        <h2>Товары</h2>
        <table class="table table-striped table-bordered table-hover dataTable no-footer">
            <tr>
                <th>Код</th>
                <th>Фото</th>
                <th>Товар</th>
                <th>Категория</th>
                <th>Цвет</th>
                <th>Размер</th>
                <th>Кол.</th>
                <th>Цена</th>
                <th>UDS скидка</th>
                <th>Стоимость</th>
            </tr>

            <?php
            $partners = [];
            $request = $this->db->get("partner")->result();
            foreach ($request as $row) {
                $partners[$row->id] = $row->jur_name;
            }
            ?>

            <?php foreach($pq as $parr) { ?>
                <?php $prevdiv=substr($parr['articol'],-2);
                //$prevdiv=strtoupper($prevdiv);
                $color = (isset($parr['photo_color'])) ? $parr['photo_color'] : $parr['first_color'];
                $dir = 'products/'.$prevdiv.'/'.$parr['articol'].'/'.$color.'/'; ?>
                <?php $image = '/public/'.$dir.'1.jpg';?>

                <?php

                $category_name = $this->db->select("category.name_ru as category_name")
                    ->join("category_product", "category_product.category_id = category.id")
                    ->where("category_product.product_id", $parr['id'] )
                    ->get("category")->row()->category_name;

                ?>
                <tr>
                    <td>
                        <div class="name"><a target="_blank" href="/ru/product/<?=$parr['id']?>"><?=$parr['id']?></a></div>
                    </td>
                    <td width="90"><img class="center_magic" width="90" src="<?=$image?>"></td>
                    <td>
                        <div class="name"><a target="_blank" href="/ru/product/<?=$parr['id']?>"><?=$parr['name_ru']?></a></div>
                        <?php if($parr['preorder']) {?>
                            <div class="alert alert-warning">
                                <i class="fa fa-warning"></i> дублируется вразделе CRM - Отчет товары под заказ
                            </div>
                        <?php }?>
                    </td>
                    <td>
                        <div class="name"><?=$category_name?> - <?=(isset($partners[$parr['partner_id']]))? $partners[$parr['partner_id']] : '';?></div>
                    </td>
                    <td>
                        <div class="name"><?=($parr['color'] != 'NO_COLOR') ? $parr['color'] : ''?></div>
                    </td>
                    <td>
                        <div class="name"><?=($parr['size'] != 'NO_SIZE') ? $parr['size'] : ''?></div>
                    </td>
                    <td>
                        <div class="help_block"><?=$parr['quantity']?> шт</div>
                    </td>
                    <td>
                        <div class="help_block"><?=number_format($parr['price'],2,'.',' ')?> MDL</div>
                    </td>
                    <td>
                        <div class="help_block"><?=number_format($parr['reduction'],2,'.',' ')?> MDL</div>
                    </td>
                    <td>
                        <div class="help_block"><?=number_format(($parr['quantity'] * $parr['price']) - $parr['reduction'],2,'.',' ')?> MDL</div>
                    </td>
                </tr>
            <?php }?>
        </table>
    </div>
<?php }?>

<script>
    jQuery(document).ready(function ($) {

        let body = $('body');

        function markUdsBonusesAdded(tr, message) {
            let btn = tr.find('.fa-check');

            if(btn.length > 0) {
                btn.removeClass('cls');
                btn.attr('title', message);
                btn.css('color','');
            }

            Swal.fire(message, '', 'success');
        }

        body.on('click', '.uds_info', function() {
            Swal.fire({
                title: $(this).closest('td').find('div.uds_info').html(),
                width: 600
            });
        });

        body.on('click', '.uds .fa-check.cls', function(e) {
            e.preventDefault();

            let tr = $(this).closest('tr');
            let id = tr.attr('data-id');

            Swal.fire({
                title: 'Вы уверены что хотите начислить бонусы за заказ?',
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Нет',
                confirmButtonText: 'Да'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.post('/<?=ADM_CONTROLLER?>/uds_reward/', { id: id}, function(r) {
                        if(r.status === 'ok') {
                            markUdsBonusesAdded(tr, r.message);
                        } else {
                            Swal.fire(r.message, '', 'error');
                        }
                    }, 'json');
                }
            });
        });

        body.on('change', '.statchange', function() {
            var stat=$(this).val();
            var is_uds_order = $(this).hasClass('uds_order');
            var id=$(this).attr('oid');
            var tr = $(this).closest('tr');
            $.get('/<?=ADM_CONTROLLER?>/change_order_status/'+id+'/'+stat+'/');
            if(is_uds_order) {
                if(stat == '3') {
                    let tr = $(this).closest('tr');
                    $.post('/<?=ADM_CONTROLLER?>/uds_refund/', { id: id}, function(r) {
                        if(r.status === 'ok') {
                            let  icon = tr.find('.fa-check');
                            if(icon.length > 0) {
                                icon.removeClass('fa-check');
                                icon.removeClass('cls');
                                icon.addClass('fa-close');
                                icon.attr('title', 'отмена');
                            }
                            Swal.fire(r.message, '', 'success');
                        } else {
                            Swal.fire(r.message, '', 'error');
                        }

                    }, 'json');
                }
            }
        });

        body.on('click', '#saveOrderMessage', function() {
            let textArea = $('#message');
            let message=textArea.val();
            let id=textArea.data("id");
            $.post('/<?=ADM_CONTROLLER?>/set_order_message/', {id:id, message:message},function (r) {
                alert('сообщение сохранено');
            })
        });

        body.on('click', '#manualSendToUna', function() {
            let id=$(this).data("id");
            $.post('/<?=ADM_CONTROLLER?>/manual_send_to_una/', {id:id},function (r) {
                alert('заказ был отправлен вручную');
            })
        });


        body.on('click', '.admin_message__edit-btn', function (e) {
            e.preventDefault();
            $(this).closest('.admin_message').addClass('admin_message--edit');
        });

        body.on('click', '.admin_message__cancel-btn', function(e) {
            e.preventDefault();
            $(this).closest('.admin_message').removeClass('admin_message--edit');


        });

        body.on('click', '.admin_message__save-btn', function (e) {
            e.preventDefault();
            let
                container = $(this).closest('.admin_message'),
                val = container.find('textarea').val(),
                id = container.find('textarea').data("id");
            $.ajax({
                url: '/<?=ADM_CONTROLLER?>/set_order_message/',
                type: "POST",
                data:{
                    id: id,
                    message: val
                },
                success: function (r) {
                    container.find('span').text(val);
                    container.removeClass('admin_message--edit');
                },
                error: function (r) {
                    alert('Ошибка сохранения сообщения');
                }
            });
        });

        body.on('click', '.ilab_cashback', function (e) {
            e.preventDefault();
            var s = $(this);
            var p = s.attr('data-price');
            var l = s.attr('href');
            if (confirm('Вы точно уверены, что хотите оформить возврат средств? После подтверждения операцию невозможно будет отменить.')) {
                var a = prompt('Введите сумму для возврата');
                a = parseFloat(a);
                if (!isNaN(a)) {
                    if (a > parseFloat(p)) {
                        alert('Сумма к возврату не может превышать стоимость страховки');
                        return false;
                    }
                    if (a <= 0) {
                        alert('Сумма к возврату не может быть меньше либо равной 0 (нулю)')
                    }
                    var new_href = l + '?q=' + a;
                    window.location.href = new_href;
                } else {
                    alert('Введенные Вами данные не прошли валидацию. Просим Вас при введении суммы, ввести числовое значение.');
                    return false;
                }
            }
            return false;
        })

    });
</script>
<style>
    #blink {
        -webkit-animation: blink 2s linear infinite;
        animation: blink 2s linear infinite;
    }
    @-webkit-keyframes blink {
        100% { color: rgba(34, 34, 34, 0); }
    }
    @keyframes blink {
        100% { color: rgba(34, 34, 34, 0); }
    }
</style>
