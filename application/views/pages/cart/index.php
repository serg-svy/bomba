<link rel="stylesheet" href="/dist/css/cart.css?time=<?=time()?>">
<script src="/dist/js/jquery-ui.min.js"></script>
<!--main-->
<div id="main" class="clearfix">
    <? if($cartProducts) {?>
        <div class="c_wr2">
            <div class="in">
                <div class="c_wr2_col1">
                    <div class="order_wr">
                        <div class="title3">
                            <?=PRODUCTS?>
                            <a href="/<?=$lclang?>/<?=$menu['all'][16]->uri?>/empty/" class="clear-cart-button"><?=EMPTY_BASCKET?></a>
                        </div>
                        <div class="b_info1">
                            <?php foreach($cartProducts as $key=>$cartProduct) {?>
                                <div class="bi1_item bv_data" data-articol="<?=$cartProduct->articol?>" data-id="<?=$cartProduct->id?>" data-color="<?=$cartProduct->cart_color?>" data-size="<?=$cartProduct->cart_size?>">
                                    <div class="bi1_thumb_wr">
                                        <a href="/<?=$lclang?>/<?=$menu['all'][17]->uri?>/<?=$cartProduct->uri?>/" class="bi1_thumb"><img src="<?=product_image('1.jpg', $cartProduct->articol, $cartProduct->cart_color, 68, 68)?>" alt=""></a>
                                    </div>
                                    <div class="bi1_descr">
                                        <div class="bi1_title">
                                            <a href="/<?=$lclang?>/<?=$menu['all'][17]->uri?>/<?=$cartProduct->uri?>/">
                                                <?=$cartProduct->title?>
                                                <br>
                                                <?=($cartProduct->cart_size!='NO_SIZE') ? $cartProduct->cart_size : ''?>
                                                <?=($cartProduct->cart_color!='NO_COLOR') ? $cartProduct->cart_color : ''?>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="bi1_num">
                                        <input type="number" name="" id="" min="1" max="99" value="<?=$cartProduct->cart_qty?>">
                                        <div class="bi1_num_info"><?=numberFormat($cartProduct->discounted_price)?>,— / <?=PCS?></div>
                                    </div>
                                    <div class="bi1_price_wr">
                                        <div class="price" data-key="<?=$key?>"><?=numberFormat($cartProduct->discounted_price * $cartProduct->cart_qty)?>,—</div>
                                        <?php if($cartProduct->price > $cartProduct->discounted_price){?>
                                            <span class="old__sale">−<?=numberFormat($cartProduct->price - $cartProduct->discounted_price)?> <?=LEI?></span>
                                            <del class="price_old"><?=numberFormat($cartProduct->price)?>,—</del>
                                        <?php }?>
                                        <?php if($cartProduct->uds_cashback) {?>
                                            <span class="pls"><img src="/dist/img/icons/i34.svg"><span>+<?=$cartProduct->uds_cashback?></span></span>
                                        <?php }?>
                                    </div>
                                    <div class="bi1_ctrl_wr">
                                        <div class="bi1_ctrl_b">
                                            <a href="#" data-url="/<?=$lclang?>/<?=$menu['all'][16]->uri?>/del/" data-key="<?=$key?>" class="bi1_ctrl bi1_del"><i class="icon-close"></i><?=DELETE?></a>
                                        </div>
                                    </div>
                                </div>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <?php $this->load->view("layouts/pages/order__sidebar.php")?>
            </div>
        </div>
    <?php }else {?>
        <script src="/dist/js/jquery.star-rating-svg.min.js"></script>

        <div class="c_wr">
            <div class="in">
                <div class="title1"><?=YOUR_CART_IS_EMPTY?></div>
                <p style="margin: 0 0 1.5em 0;"><?=YOUR_CAN_CHOOSE_PRODUCT?></p>
                <a href="<?=($lclang == 'ro') ? '/' : '/'.$lclang.'/'?>" class="btn"><?=GO_SHOPPING?></a>
            </div>
        </div>
        <div class="c_wr_head">
            <div class="in">
                <div class="title2"><?=NEW_PRODUCTS?></div>
            </div>
        </div>
        <div class="c_wr1">
            <div class="in">
                <div class="b_info carousel">
                    <?php foreach($new_products as $new_product){?>
                        <?php $this->load->view('layouts/pages/product/slider', ['product' => (object) $new_product]);?>
                    <?php }?>
                </div>
            </div>
        </div>
    <?php }?>
    </div>
</div>
<!--/main-->
<script src="/app/js/cart.js?time=<?=time()?>"></script>
<?php $this->load->view("layouts/pages/popup/multiple")?>
