<div class="city-item">
    <h5 class="title-h5">
        <?=$store->title?>
    </h5>
    <p class="text-p"><?=$store->address?></p>
    <div class="close-svg close_store_info_mobile">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M3 3L13 13" stroke="#A4A4A5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            <path d="M3 13L13.0001 3.00001" stroke="#A4A4A5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            s</svg>
    </div>
</div>
<div class="city-svg">
    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M8 14C11.3137 14 14 11.3137 14 8C14 4.68629 11.3137 2 8 2C4.68629 2 2 4.68629 2 8C2 11.3137 4.68629 14 8 14ZM8 16C12.4183 16 16 12.4183 16 8C16 3.58172 12.4183 0 8 0C3.58172 0 0 3.58172 0 8C0 12.4183 3.58172 16 8 16Z" fill="#A4A4A5"></path>
        <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4C8.55228 4 9 4.44772 9 5V7.61257L10.6838 7.05132C11.2077 6.87667 11.774 7.15983 11.9487 7.68377C12.1233 8.20772 11.8402 8.77404 11.3162 8.94868L8.31623 9.94868C8.01128 10.0503 7.67606 9.99919 7.41529 9.81124C7.15452 9.62329 7 9.32144 7 9V5C7 4.44772 7.44772 4 8 4Z" fill="#A4A4A5"></path>
    </svg>
    <p class="text-p">
        <?php $pizza = explode("\n", $store->workhours);?>
        <?=$pizza[1]?>
        <?php if(isset($pizza[2])) echo '<br>'.$pizza[2]?>
    </p>
</div>
