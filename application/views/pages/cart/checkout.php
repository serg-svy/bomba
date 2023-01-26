<link rel="stylesheet" href="/dist/css/cart.css?time=<?=time()?>">
<link rel="stylesheet" href="/dist/css/noty.css">
<link rel="stylesheet" href="/dist/css/semanticui.css">
<script src="/dist/js/noty.min.js"></script>
<!--main-->
<div id="main" class="clearfix">
    <div class="c_wr2">
        <div class="in">
            <div class="c_wr2_col1">
                <div class="order_wr delivery_wr">
                    <div class="title3">
                        1. <?=RECEIPT_METHOD?>
                        <i class="check_ico"></i>
                    </div>
                    <div class="point-delivery"></div>
                    <div class="f_cols_a">
                        <div class="f_row v1">
                            <input type="hidden" name="order[city_id]" value="<?=@$_SESSION['order']['city_id']?>" id="city_id" autocomplete="off" data-url="/<?=$lclang?>/ajax/set_city/">
                            <label class="f_label"><?=LOCALITY?><span>*</span></label>
                            <div class="delivery__select-city f_input_wr"><input type="text" name="order[city_name]" data-url="/<?=$lclang?>/ajax/get_city/" id="get_city" class="f_input" value="<?=@$_SESSION['order']['city_name']?>" autocomplete="off"></div>
                            <div class="delivery__city-list">
                                <ul class="city__list"></ul>
                            </div>
                        </div>
                    </div>
                    <div class="ajax_delivery">
                    </div>
                    <div class="edit_ctrl_wr">
                        <a href="" class="edit_ctrl change-wr-delivery"><i class="icon-edit"></i><?=CHANGE?></a>
                    </div>
                </div>

                <div class="order_wr payment_wr disabled-block">
                    <div class="title3">
                        2. <?=PAYMENT_METHOD?>
                        <i class="check_ico"></i>
                    </div>
                    <div class="point-payment"></div>
                    <div class="ajax_payment"></div>
                    <div class="edit_ctrl_wr">
                        <a href="" class="edit_ctrl change-wr-payment"><i class="icon-edit"></i><?=CHANGE?></a>
                    </div>
                </div>

                <div class="order_wr contact_wr disabled-block">
                    <div class="title3">
                        3. <?=CONTACT_DETAILS?>
                        <i class="check_ico"></i>
                    </div>
                    <div class="point-contact"></div>
                    <div class="ajax_contact"></div>
                    <div class="edit_ctrl_wr">
                        <a href="" class="edit_ctrl change-wr-contact"><i class="icon-edit"></i><?=CHANGE?></a>
                    </div>
                </div>

            </div>
            <?php $this->load->view('layouts/pages/order__sidebar.php')?>
        </div>
    </div>
</div>
</div>
<?php $this->load->view('/layouts/pages/popup__accept'); ?>
<!--/main-->
<script src="/app/js/cart.js?time=<?=time()?>"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?=GOOGLE_MAPS_API?>" async defer></script>
<script>
    let map;
    let markers = [];
</script>
<?php $this->load->view("layouts/pages/popup/multiple")?>
