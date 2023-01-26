
<?php if(isset($_SESSION['cart'][$key])){?>
    <a class="btn4" href="#">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M14.664 2.753a1 1 0 01.083 1.411l-8 9a1 1 0 01-1.454.043l-4-4a1 1 0 011.414-1.414l3.25 3.25 7.296-8.207a1 1 0 011.411-.083z" fill="#d00a10"/></svg>
        &nbsp;<?=IN_CART?> <?=$_SESSION['cart'][$key]['qty']?> <?=PCS?>
    </a>
    <a class="btn add_to_cart" href="#">+1 <?=PCS?></a>
<?php } else {?>
    <a class="btn add_to_cart" href="#"><?=ADD_TO_CART?></a>
<?php }?>
