<?php $this->load->view("layouts/pages/breadcrumbs");?>

<section class="deliver-info pick-up-info">
    <div class="wrapper">
        <div class="sides">
            <?php if($page->bottom_category_id){?>
                <aside class="aside-menu">
                    <div class="sticky">
                        <div class="title-h4"><?=$bottom_categories[$page->bottom_category_id]->title?></div>
                        <ul class="aside__menu">
                            <?php foreach ($menu['bottom'] as $menu){?>
                                <?php if($menu->bottom_category_id == $page->bottom_category_id){?>
                                    <li class="aside__item <?=($menu->uri == $page->uri) ? 'aside__item_active' : '' ?>"><a href="/<?=$lclang?>/<?=$menu->uri?>"><?=$menu->title?></a></li>
                                <?php }?>
                            <?php }?>
                        </ul>
                    </div>
                </aside>
            <?php }?>
            <div class="content-text">
                <div class="content-top">
                    <?php foreach ($pickup_blocks as $pickup_block) {?>
                        <div class="top-item">
                            <h2 class="title-h2">
                                <?=$pickup_block->title?>
                            </h2>
                            <p class="text-p"><?=$pickup_block->desc?></p>
                        </div>
                    <?php }?>
                </div>
                <div class="content-map">
                    <h2 class="title-h2">
                        <?=PICKUP_POINTS?>
                    </h2>
                    <div class="content-map-block">
                        <div class="map">
                            <div id="map"></div>
                        </div>
                        <div class="map-right">
                            <div class="city-block-scroll d-block">
                                <?php foreach($cities as $city) {?>
                                    <div class="city-block d-block">
                                        <div class="title-h4">
                                            <?=$city->title?>
                                        </div>
                                        <?php foreach ($stores as $store) {?>
                                            <?php if($store->city_id == $city->id) {?>
                                                <div class="city-item" data-store="<?=$store->id?>">
                                                    <h5 class="title-h5">
                                                        <?=$store->title?>
                                                    </h5>
                                                    <p class="text-p"><?=$store->address?></p>
                                                </div>
                                            <?php }?>
                                        <?php }?>
                                    </div>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-bottom">
                    <div class="content-delivery">
                        <div class="text-p ck-editor">
                            <?=$page->text?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
    #map {
        min-width:688px;
        height:460px;
        margin: 0 auto;
    }
    @media screen and (max-width: 992px) {
        #map {
            min-width: 480px;
            height: 460px;
        }
    }
    @media screen and (max-width: 480px) {
        .map {
            padding: 0;
            min-width: 100%;
        }
        #map {
            min-width: 328px;
            height: 400px;
        }
    }
</style>
<script src="https://maps.googleapis.com/maps/api/js?key=<?=GOOGLE_MAPS_API?>" async defer></script>
<script src="/dist/js/mastercluster.js"></script>
<script>
    let map;
    let markers = [];

    function initialize(element, coords_json, cityCoords) {
        if(element !== undefined) {

            markers = [];

            const LatLng = cityCoords.split(',');

            const mapOptions = {
                center: new google.maps.LatLng($.trim(LatLng[0]), $.trim(LatLng[1])),
                zoom: <?=uri(3) ? 13 : 7?>,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            map = new google.maps.Map(element, mapOptions);

            if(coords_json !== '') mapSet(coords_json);
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

            const LatLng = value.coords.split(',');

            const marker = new google.maps.Marker({
                position: new google.maps.LatLng($.trim(LatLng[0]), $.trim(LatLng[1])),
                icon: image,
                map: map,
            });

            markers[value.id] = marker;

            marker.addListener("click", () => {
                infoWindow.setContent('<h3> ' + value.title + '</h3> <br>' + value.address);
                infoWindow.open(map, marker);
            });
        });

        const clusterStyles = [
            {
                textColor: 'white',
                textSize: 0.001,
                url: '/public/i/cluster1.svg',
                height: 20,
                width: 20
            },
            {
                textColor: 'white',
                textSize: 16,
                url: '/public/i/cluster2.svg',
                height: 40,
                width: 40
            },
            {
                textColor: 'white',
                textSize: 16,
                url: '/public/i/cluster3.svg',
                height: 40,
                width: 40
            }
        ];

        const mcOptions = {
            gridSize: 40,
            styles: clusterStyles,
            maxZoom: 15
        };
        const mc = new MarkerClusterer(map, markers, mcOptions);
    }

    $( document ).ready(function() {
        setTimeout(function() {
            initialize(document.getElementById("map"), '<?=json_encode(str_replace('"', '~', $stores), true)?>', '<?=(uri(3)) ? $current_cities[0]->coords : MAIN_COORDS?>');
        }, 1000);
    });

    $('.city-item').on('click', function () {
        google.maps.event.trigger(markers[$(this).data('store')], 'click');
    });

</script>
