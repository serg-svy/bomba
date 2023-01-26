<?php $this->load->view("layouts/pages/breadcrumbs");?>

<section class="deliver-info return credit vacancies">
    <div class="wrapper">
        <div class="sides">
            <div class="content-text">
                <p class="text-p">
                    <?=count($vacancies)?> <?=VACANCIES?>
                </p>
                <?php foreach($vacancies as $key=> $vacancy){?>
                    <div class="shares">
                        <div class="shares-head">
                            <div class="shares-head-lft">
                                <h2 class="title-h2">
                                    <?=$vacancy->title?>
                                </h2>
                                <p class="text-p">
                                    <?=$vacancy->short_title?>
                                </p>
                                <div class="shares-head-city">
                                    <?php foreach ($vacancy->cities as $city) {?>
                                        <div class="shares-city">
                                            <div class="shares-city-open">
                                                <svg width="10" height="15" viewBox="0 0 10 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.58396 7C9.85155 6.38754 10 5.7111 10 5C10 2.23858 7.76142 0 5 0C2.23858 0 0 2.23858 0 5C0 5.7111 0.148448 6.38754 0.416044 7L4.10569 14.4472C4.27508 14.786 4.62134 15 5.00011 15C5.37889 15 5.72515 14.786 5.89454 14.4472L9.58396 7ZM5 7C6.10457 7 7 6.10457 7 5C7 3.89543 6.10457 3 5 3C3.89543 3 3 3.89543 3 5C3 6.10457 3.89543 7 5 7Z" fill="#A4A4A5"/>
                                                </svg>
                                            </div>
                                            <p class="text-p">
                                                <?=$city->title?> <?=$city->count?>
                                            </p>
                                        </div>
                                    <?php }?>
                                </div>
                            </div>
                            <div class="shares-head-rht">
                                <svg width="22" height="13" viewBox="0 0 22 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 2L11 11L20 2" stroke="#A4A4A5" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                        <div class="shares-body">
                            <div class="shares-body-item">
                                <?=$vacancy->text?>
                            </div>
                            <div class="shares-body-map">
                                <h2 class="title-h2">
                                    <?=count($vacancy->stores)?> <?=BRANCH_WITH_THIS_VACANCY?>
                                </h2>
                                <ul class="list-select">
                                    <?php foreach ($vacancy->cities as $city) {?>
                                        <?php $coords_json = str_replace('"', "~", json_encode($city->json, true));?>
                                        <li>
                                            <a href="#" onclick="event.preventDefault(); initialize(document.getElementById('map-<?=$vacancy->id?>'), '<?=$coords_json?>', '<?=$city->coords?>', '<?=$city->id?>') ">
                                                <?=$city->title?> <?=$city->count?>
                                            </a>
                                        </li>
                                    <?php }?>
                                </ul>
                            </div>
                            <div class="content-map-block">
                                <div class="content-map-lft">
                                    <div class="map" id="map-<?=$vacancy->id?>"></div>
                                    <div class="lft-bottom d-block">
                                        <input type="submit" class="btn respond-vacancy" data-id="<?=$vacancy->id?>" data-title="<?=$vacancy->title?>" value="<?=RESPOND?>">
                                    </div>
                                </div>
                                <div class="content-map-rht store-block-scroll">
                                    <?php foreach($vacancy->stores as $store){?>
                                        <div class="content-rht-item store-<?=$store->id?>" data-store="<?=$store->id?>" data-city="<?=$store->city_id?>">
                                            <h2 class="title-h2">
                                                <div class="content-map-dot">
                                                    <div class="content-dot"></div>
                                                </div>
                                                <?=$store->store_title?>
                                            </h2>
                                            <p class="text-p">
                                                <?=$store->store_address?>
                                            </p>
                                        </div>
                                    <?php }?>
                                    <div class="lft-bottom d-none">
                                        <a class="respond-vacancy" data-id="<?=$vacancy->id?>" data-title="<?=$vacancy->title?>" href="#"><?=RESPOND?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }?>
            </div>
        </div>
    </div>
</section>
<?php $this->load->view("layouts/pages/popup/vacancy")?>
<?php if(isset($_SESSION['vacancy_send'])) {?>
    <div class="popup__reviews popup popup__vacancy remove_popup" style="display: flex;">
        <div class="popup__inner">
            <div class="popup__close"><img src="/dist/img/icons/Delete.svg"></div>
            <img class="popup-img" src="/dist/img/icons/Group 609.svg" alt="">
            <p class="popup-text"><?=SUCCESS_VACANCY_SEND?></p>
            <a class="popup-botton remove_popup" href=""><?=GOOD?></a>
        </div>
    </div>
    <?php unset($_SESSION['vacancy_send'])?>
<?php }?>
<style>
    .map {
        width:936px;
        height:400px;
    }
    .content-map-rht {
        height:400px;
        overflow: auto;
        padding-right: 5px;
    }
    @media screen and (max-width: 992px) {
        .map {
            width: 650px;
            height: 400px;
        }
        .content-map-rht {
            height:400px;
        }
    }
    @media screen and (max-width: 480px) {
        .map {
            min-width: 100%;
            width: 288px;
            height: 400px;
        }
        .content-map-rht {
            height:400px;
        }
    }
</style>
<script src="https://maps.googleapis.com/maps/api/js?key=<?=GOOGLE_MAPS_API?>" async defer></script>
<script>
    let map;
    let markers = [];

    function initialize(element, coords_json, cityCoords, cityId) {
        if(element !== undefined) {

            markers = [];

            const LatLng = cityCoords.split(',');
            const mapOptions = {
                center: new google.maps.LatLng($.trim(LatLng[0]), $.trim(LatLng[1])),
                zoom: 12,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            map = new google.maps.Map(element, mapOptions);

            mapSet(coords_json);

            showList(element, cityId);
        }
    }

    function mapSet(coords_json) {

        coords_json = coords_json.replace(/~/g, '"');

        const coords_array = JSON.parse(coords_json);

        const infoWindow = new google.maps.InfoWindow({
            content: "",
            disableAutoPan: true,
        });

        const image = '/public/i/marker.svg';

        $.each(coords_array, function( index, value ) {

            const marker = new google.maps.Marker({
                position: new google.maps.LatLng($.trim(value.lat), $.trim(value.lng)),
                icon: image,
                map: map,
            });

            markers[value.id] = marker;

            marker.addListener("click", () => {
                infoWindow.setContent('<h3> ' + value.title + '</h3> <br>' + value.address);
                infoWindow.open(map, marker);
                $(".store-"+value.id).trigger("click");
            });
        });
    }

    function showList(element, cityId) {
        $(element).closest(".shares-body").find(".content-rht-item").hide();

        $(element).closest(".shares-body").find(".content-rht-item").each(function (index, value) {
            if(parseInt($(this).data("city")) === parseInt(cityId)) {
                $(this).show();
            }
        })
    }

    $('.content-rht-item').on('click', function () {
        $(".magazin-head").val($(this).data('store'));
        //google.maps.event.trigger(markers[$(this).data('store')], 'click');
    });

</script>
