<div class="ajax__list">
    <?php if($products){?>
        <div class="rht__products">
            <?php foreach ($products as $product) {?>
                <?php $this->load->view("layouts/pages/product/main", ['product'=> (object) $product]);?>
            <?php }?>
        </div>
        <?php $this->load->view("layouts/pages/paginator");?>
    <?php } else {?>
        <br>
        <section class="breadcrumbs-title" style="background: initial">
            <h1 class="title-h1"><?=NOTHING_FOUND?></h1>
        </section>
    <?php }?>
</div>
