<section class="subscribe">
    <div class="wrapper">
        <div class="subscribe__block">
            <div class="subscribe-h3 index-h3"><?=SUBSCRIBE_TEXT?></div>
            <form action="/<?=$lclang?>/ajax/subscribe/" method="post" id="subscribe">
                <input type="email" placeholder="E-mail" required>
                <input type="submit" value="<?=SUBSCRIBE?>">
            </form>
        </div>
    </div>
</section>
