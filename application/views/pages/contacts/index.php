<?php $this->load->view("layouts/pages/breadcrumbs");?>

<section class="deliver-info return product-2 pick-up-info stores contacts">
    <div class="wrapper">
        <div class="content-top content-width-100">
            <div class="content-item">
                <img src="/dist/img/icons/Phone.svg" alt="">
                <div class="title-h4">
                    <?=CUSTOMER_SERVICE?>
                </div>
                <a class="number" href="tel:<?=CALL_CENTER_NUMBER?>"><?=CALL_CENTER_NUMBER?></a>
            </div>
            <div class="content-item">
                <img src="/dist/img/icons/Subtract_1.svg" alt="">
                <div class="title-h4">
                    <?=$menu['all'][14]->title?>
                </div>
                <a class="on-map" href="/<?=$lclang?>/<?=$menu['all'][14]->uri?>"><?=FIND_ON_MAP?></a>
            </div>
            <div class="content-item">
                <img src="/dist/img/icons/Email.svg" alt="">
                <div class="title-h4">
                    <?=WRITE_TO_US?>
                </div>
                <a class="e-mail" href="mailto:<?=CONTACT_EMAIL?>"><?=CONTACT_EMAIL?></a>
            </div>
            <div class="content-item">
                <img src="/dist/img/icons/Chats.svg" alt="">
                <div class="title-h4">
                    <?=MESSENGERS?>
                </div>
                <div class="social">
                    <a target="_blank" href="<?=TELEGRAM_LINK?>"><img src="/dist/img/icons/tg.svg" alt=""></a>
                    <a target="_blank" href="<?=VIBER_LINK?>"><img src="/dist/img/icons/vb.svg" alt=""></a>
                </div>
            </div>
        </div>
        <?php foreach($contacts as $contact){?>
            <div class="shares">
                <div class="shares-head">
                    <div class="shares-head-lft">
                        <h2 class="title-h2">
                            <?=$contact->title?>
                        </h2>
                    </div>
                    <div class="shares-head-rht">
                        <svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M0.292893 0.292893C0.683417 -0.0976311 1.31658 -0.0976311 1.70711 0.292893L5 3.58579L8.29289 0.292893C8.68342 -0.0976311 9.31658 -0.0976311 9.70711 0.292893C10.0976 0.683417 10.0976 1.31658 9.70711 1.70711L5.70711 5.70711C5.31658 6.09763 4.68342 6.09763 4.29289 5.70711L0.292893 1.70711C-0.0976311 1.31658 -0.0976311 0.683417 0.292893 0.292893Z" fill="#A4A4A5"/>
                        </svg>
                    </div>
                </div>
                <div class="shares-body">
                    <p class="text-p">
                        <?=nl2br($contact->desc)?>
                    </p>
                </div>
            </div>
        <?php }?>

        <form method="post" name="contact">
            <h2><?=FEEDBACK?></h2>
            <?php if(isset($_SESSION['SEND_FEEDBACK'])) {?>
                <div class="alert alert-success"><?=MESSAGE_SUCCESSFULLY_SENT?></div>
                <?php unset($_SESSION['SEND_FEEDBACK']);?>
            <?php }?>
            <div class="block__inputs">
                <div class="input__item">
                    <label for="ip1"><?=NAME?><sup>*</sup></label>
                    <input type="text" name="contact[name]" id="ip1" value="<?=$this->input->post('name')?>" required>
                </div>
                <div class="input__item">
                    <label for="ip2">E-mail<sup>*</sup></label>
                    <input type="email" name="contact[email]" id="ip2" value="<?=$this->input->post('email')?>" required>
                </div>
            </div>
            <div class="input__item">
                <label for="ip3"><?=YOUR_QUESTION?><sup>*</sup></label>
                <textarea name="contact[question]" id="ip3" required><?=$this->input->post('question')?></textarea>
            </div>
            <div class="checkbox__item">
                <label for="sr2-1">
                    <input type="checkbox" id="sr2-1" required name="contact[personal_data]">
                    <span><?=I_ACCEPT?> <a href="#"><?=$menu['all'][18]->title?></a></span>
                </label>
            </div>
            <div class="checkbox__item">
                <label for="sr2-2">
                    <input type="checkbox" id="sr2-2" name="contact[terms]">
                    <span><?=I_ACCEPT?> <a href="#"><?=$menu['all'][19]->title?></a></span>
                </label>
            </div>
            <input class="btn" type="submit" value="<?=SEND?>" disabled="disabled">
        </form>

        <div class="content-map">
            <h2 class="title-h2"><?=MAIN_OFFICE?></h2>
            <div class="content-map-block content-map-block-height">
                <div class="map">
                    <div id="map"></div>
                </div>
                <div class="map-right">
                    <div class="city-block-scroll d-block">
                        <div class="city-block d-block">
                            <div class="city-item-top">
                                <div class="city-item-rht">
                                    <h3 class="title-h3">
                                        <?=$cities[1]->title?>
                                    </h3>
                                </div>
                            </div>
                            <div class="city-item">
                                <h6 class="title-h6">
                                   <?=$store->title?>
                                </h6>
                                <p class="text-p">
                                    <?=$store->address?>
                            </div>
                            <?php $pizza = explode("\n", $store->workhours);?>
                            <div class="city-item">
                                <h6 class="title-h6">
                                    <?=$pizza[0]?>
                                </h6>
                                <p class="text-p">
                                    <?=$pizza[1]?>
                                </p>
                            </div>
                            <div class="city-item">
                                <h6 class="title-h6">
                                    <?=PHONE?>
                                </h6>
                                <p class="text-p">
                                    <a href="tel:<?=$store->phone?>"><?=$store->phone?></a>
                                </p>
                            </div>
                            <?php $pizza = explode("\n", $store->parking);?>
                            <div class="city-item">
                                <h6 class="title-h6">
                                    <?=$pizza[0]?>
                                </h6>
                                <p class="text-p">
                                    <?=$pizza[1]?>
                                </p>
                            </div>
                            <?php $pizza = explode("\n", $store->troleibus);?>
                            <div class="city-item">
                                <h6 class="title-h6">
                                    <?=$pizza[0]?>
                                </h6>
                                <p class="text-p">
                                    <?=$pizza[1]?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-bottom content-width-100">
            <div class="bottom-h2">
                <h2 class="title-h2"><?=OUR_UNITS?></h2>
            </div>
            <?php foreach($departaments as $row) {?>
                <div class="content-bottom-item">
                    <?php foreach ($row as $item) {?>
                        <div class="rht-lft">
                            <div class="title-h4">
                                <?=$item->title?>
                            </div>
                            <div class="how-order-phone">
                                <a href="tel:<?=$item->phones?>"><?=nl2br($item->phones)?></a>
                            </div>
                            <div class="how-order-site">
                                <a href="mailto:<?=$item->email?>"><?=$item->email?></a>
                            </div>
                            <div class="how-order-graphic">
                                <p class="text-p long-graphic">
                                    <?=nl2br($item->worktime)?>
                                </p>
                                <p class="text-p short-graphic">
                                    <?php $array = explode("\n", $item->short_worktime);?>
                                    <?php foreach($array as $i=>$row) {?>
                                        <?php if($i!=0){?><br><?php }?>
                                        <span><?=$row?></span>
                                    <?php }?>
                                </p>
                            </div>
                        </div>
                    <?php }?>
                </div>
            <?php }?>
        </div>
    </div>
</section>
<style>
    .map {
        min-width: calc(100% - 288px);
    }
    #map {
        border-radius: 8px;
        min-width:1px;
        height:694px;
        margin: 0 auto;
    }
    @media screen and (max-width: 1024px) {
        #map {
            /*min-width: 630px;*/
            height: 400px;
        }
    }
    @media screen and (max-width: 480px) {
        .map {
            padding: 0;
            min-width: 100%;
        }
        #map {
            /*min-width: 328px;*/
            height: 400px;
        }
    }
</style>
<script src="https://maps.googleapis.com/maps/api/js?key=<?=GOOGLE_MAPS_API?>"></script>
<script>
    let map;

    function initialize(element, mainCoords) {

        const LatLng = mainCoords.split(',');
        const mapOptions = {
            center: new google.maps.LatLng($.trim(LatLng[0]), $.trim(LatLng[1])),
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(element, mapOptions);

        const image = '/public/i/marker.svg';

        const marker = new google.maps.Marker({
            position: new google.maps.LatLng($.trim(LatLng[0]), $.trim(LatLng[1])),
            icon: image,
            map: map,
        });
    }

    $(function(){
        initialize(document.getElementById("map"), '<?=$store->coords?>');
    });
</script>
