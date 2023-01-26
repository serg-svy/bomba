<link rel="stylesheet" href="/dist/css/cart.css?time=<?=time()?>">
<script src="/dist/js/jquery-ui.min.js"></script>
<!--main-->
<div id="main" class="clearfix">
    <div class="c_wr2">
            <div class="in">
                <div class="c_wr2_col1">
                    <div class="order_wr">
                        <div class="b_info1">
                            <div class="dop-vigoda">
                                <div class="title3"><?=ADDITIONAL_BENEFITS?></div>
                                <div class="dop-vigoda__block find_uds">
                                    <?php if($_SESSION['cashback'] > 0) {?>
                                        <p><?=str_replace('{count}', $_SESSION['cashback'].'<img src="/dist/img/icons/i34.svg" alt="">', UDS_TEXT_BONUSES)?></p>
                                        <p><?=UDS_TEXT_USE?></p>
                                    <?php }?>
                                    <?php if($_SESSION['cashback'] > 0 or $cart_uds > 0) {?>
                                        <div class="code-uds">
                                            <label for="uds"><?=ENTER_COD_UDS?></label>
                                            <form action="/<?=$lclang?>/uds/find/">
                                                <input name="code" type="number" placeholder="" class="f_input">
                                                <input type="hidden" name="total" value="<?=$cart_total?>">
                                                <button class="btn" id="find_uds" type="submit"><?=APPLY?></button>
                                            </form>
                                            <p class="return__message label-error"></p>
                                            <div class="how-know">
                                                <div class="know__block" style="display: none;">
                                                    <div class="close-know">
                                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M3.75 3.75L16.25 16.25" stroke="#A4A4A5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                            <path d="M3.74988 16.25L16.25 3.75002" stroke="#A4A4A5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </svg>
                                                    </div>
                                                    <?=UDS_TEXT_DOWNLOAD?>
                                                </div>
                                                <p class="click-know">
                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8 16C12.4183 16 16 12.4183 16 8C16 3.58172 12.4183 0 8 0C3.58172 0 0 3.58172 0 8C0 12.4183 3.58172 16 8 16ZM8.84457 7.53V8.79H6.65457V6.03C7.31457 6.195 8.10957 6.12 8.57457 5.715C8.85957 5.475 9.02457 5.1 9.02457 4.68C9.02457 4.38 8.93457 4.125 8.73957 3.915C8.51457 3.675 8.24457 3.63 8.01957 3.63C7.61457 3.63 7.38957 3.78 7.25457 3.915C7.08957 4.095 6.98457 4.455 6.99957 4.755H4.68957C4.70457 3.84 5.09457 3.12 5.51457 2.685C5.87457 2.295 6.65457 1.77 7.98957 1.77C9.17457 1.77 9.93957 2.175 10.4646 2.67C11.0646 3.255 11.3046 4.02 11.3046 4.74C11.3046 5.58 10.9596 6.21 10.5546 6.645C10.2546 6.99 9.69957 7.41 8.84457 7.53ZM7.80957 12.225C7.04457 12.225 6.44457 11.625 6.44457 10.86C6.44457 10.095 7.04457 9.495 7.80957 9.495C8.57457 9.495 9.17457 10.095 9.17457 10.86C9.17457 11.625 8.57457 12.225 7.80957 12.225Z" fill="#A4A4A5"></path>
                                                    </svg>
                                                    <?=HOW_FIND_UDS_CODE?>
                                                </p>
                                            </div>
                                        </div>
                                    <?php }?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view("layouts/pages/order__sidebar.php")?>
            </div>
        </div>
</div>
</div>
<!--/main-->
<script src="/app/js/cart.js?time=<?=time()?>"></script>
<?php $this->load->view("layouts/pages/popup/multiple")?>
