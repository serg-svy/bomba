<link rel="stylesheet" href="/dist/css/cart.css?time=<?=time()?>">
<!--main-->
<div id="main" class="clearfix">
    <div class="c_wr3">
        <div class="in">
            <div class="b_info4">
                <div class="bi4_head">
                    <div class="title4"><?=ORDER_SUCCESSFULLY_SENT?></div>
                    <p><?=str_replace('{email}', '<a href="mailto:'.$order->email.'">'.$order->email.'</a>', str_replace('{phone}', '<a href="tel:'.$order->phone.'">'.$order->phone.'</a>', ORDER_RESULT_TEXT))?></p>
                </div>
                <div class="b_info3 v1">
                    <div class="bi3_item">
                        <div class="bi3_title"><?=ORDER_NUMBER?></div>
                        <p>№ <?=$order->generated_id?></p>
                    </div>
                    <div class="bi3_item">
                        <div class="bi3_title"><?=count($order->products)?> <?=PRODUCTS_FOR_AMOUNT?></div>
                        <p><?=numberFormat($order->total)?>,–</p>
                    </div>
                    <div class="bi3_item">
                        <div class="bi3_title"><?=RECEIPT_METHOD?></div>
                        <p><?=$order->address?></p>
                    </div>
                    <div class="bi3_item">
                        <div class="bi3_title"><?=PAYMENT_METHOD?></div>
                        <p><?=$payment_type->title?></p>
                    </div>
                </div>
                <div class="bi4_foot">
                    <a href="<?=($lclang == 'ro') ? '/' : '/'.$lclang.'/'?>" class="lnk_ico"><i class="icon-arrow_a_left"></i><?=GO_SHOPPING?></a>
                </div>
            </div>
        </div>
    </div>
</div><!--/main-->
<?php $this->load->view("layouts/pages/retail_rocket/order")?>
