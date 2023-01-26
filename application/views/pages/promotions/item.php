<? $this->load->view("layouts/pages/breadcrumbs");?>
<section class="listing">
    <div class="wrapper">
        <div class="listing__sides listing__sides171">
            <div class="side__rht">
                <section class="index-top">
                    <div class="sides">
                        <div class="side__lft">
                            <div class="photo"><img src="<?=newthumbs($promotion->image_header_left, 'promotions', 760, 350, '760x350x1', 1)?>"></div>
                        </div>
                        <div class="side__rht">
                            <div class="photo"><img src="<?=newthumbs($promotion->image_header_right, 'promotions', 328, 350, '328x350x1', 1)?>"></div>
                        </div>
                    </div>
                    <br>
                    <div class="ck-editor">
                        <?=$promotion->text?>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>

