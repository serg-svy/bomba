<?php $this->load->view("layouts/pages/breadcrumbs");?>

<section class="deliver-info pick-up-info stores stores-menu">
    <div class="wrapper">
        <div class="uls-ss">
            <ul class="list-select list-select-ov">
                <li class="all_cities <?=(uri(3) == '') ? 'list_active' : ''?>"><a href="/<?=$lclang?>/<?=$menu['all'][14]->uri?>/"><?=ALL_STORES?></a></li>
                <?php foreach($all_cities as $a_city) {?>
                    <li class="<?=(uri(3) == $a_city->id) ? 'list_active' : ''?>"><a href="/<?=$lclang?>/<?=$menu['all'][14]->uri?>/<?=$a_city->id?>/"><?=$a_city->title?></a></li>
                <?php }?>
            </ul>
        </div>

        <div class="sides">
            <div class="content-text">
                <div class="content-map">
                    <div class="content-map-block content-map-block-height">
                        <div class="map">
                            <div id="map"></div>
                        </div>
                        <div class="map-right">
                            <div class="block__input">
                                <label for="i1"><?=LOCALITY?></label>
                                <input <?=(uri(3) != '') ? 'disabled' : ''?> id="i1" type="text" value="" placeholder="<?=ENTER_LOCALITY?>">
                            </div>
                            <div class="city-block-scroll d-block">
                                <?php foreach($current_cities as $c_city) {?>
                                    <div class="city-block d-block" data-city="<?=$c_city->title?>">
                                        <div class="title-h4">
                                            <?=$c_city->title?>
                                        </div>
                                        <?php foreach ($stores as $store) {?>
                                            <?php if($store->city_id == $c_city->id) {?>
                                                <div class="city-item store-link" data-store="<?=$store->id?>" id="store-<?=$store->id?>">
                                                    <div class="title-h5">
                                                        <?=$store->title?>
                                                    </div>
                                                    <p class="text-p"><?=$store->address?></p>
                                                </div>
                                            <?php }?>
                                        <?php }?>
                                    </div>
                                <?php }?>
                            </div>
                            <div class="city-block-mobile"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
<style>
    .map {
        background: #fff;
        border-radius: 16px;
        padding: 16px;
        margin: 0 auto;
        min-width: calc(100% - 288px);
    }
    #map {
        min-width:1px;
        height:694px;
    }
    @media screen and (max-width: 1024px) {
        #map {
            /*min-width: 640px;*/
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
            height: 420px;
        }
    }
</style>
<script src="https://maps.googleapis.com/maps/api/js?key=<?=GOOGLE_MAPS_API?>" async defer></script>
<script src="/dist/js/mastercluster.js"></script>
<script>
    let map;
    let markers = [];
    let ids = [];
    let backHtml = $(".city-block-scroll").html();
    let mapCoords = '<?=(uri(3)) ? $current_cities[0]->coords : MAIN_COORDS?>';
    let mapZoom = <?=uri(3) ? 13 : 7?>;

    function initialize(element, coords_json) {

        if(element !== undefined) {

            markers = [];
            ids = [];

            const LatLng = mapCoords.split(',');

            const mapOptions = {
                center: new google.maps.LatLng($.trim(LatLng[0]), $.trim(LatLng[1])),
                zoom: mapZoom,
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

        let width = $(window).width();

        let image;
        if (width < 768) {
            image = '/public/i/cluster1.svg';
        } else {
            image = '/public/i/marker.svg';
        }

        $.each(coords_array, function( index, value ) {

            const LatLng = value.coords.split(',');

            const marker = new google.maps.Marker({
                position: new google.maps.LatLng($.trim(LatLng[0]), $.trim(LatLng[1])),
                icon: image,
                map: map,
            });

            markers[value.id] = marker;
            ids.push(marker);

            marker.addListener("click", () => {
                if (width < 768) {
                   clearActiveMarkers();
                   marker.setIcon('/public/i/marker.svg');
                    $.post('/<?=$lclang?>/ajax/get_store_info_mobile/',{id: value.id}, function(data) {
                        let block = $(".city-block-mobile");
                        block.html(data.html);
                    }, 'json');
                    map.setCenter(markers[value.id].getPosition());
                    map.setZoom(12);
                } else {
                    $.post('/<?=$lclang?>/ajax/get_store_info/',{id: value.id}, function(data) {
                        let block = $(".city-block-scroll");
                        block.html(data.html);
                    }, 'json');
                }
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
            initialize(document.getElementById("map"), '<?=json_encode(str_replace('"', '~', $stores), true)?>');
        }, 1000);
    });

    $(document).on("click", ".store-link", function (event) {
        let id = $(this).data('store');
        $.post('/<?=$lclang?>/ajax/get_store_info/',{id: id}, function(data) {
            let block = $(".city-block-scroll");
            block.html(data.html);
        }, 'json');

        google.maps.event.trigger(markers[id], 'click');
        map.setZoom(15);
        map.setCenter(markers[id].position);
    });

    $(document).on("click", ".back_stores", function (event) {
        event.preventDefault();
        $(".city-block-scroll").html(backHtml);

        map.setZoom(mapZoom);
        const LatLng = mapCoords.split(',');
        map.setCenter(new google.maps.LatLng($.trim(LatLng[0]), $.trim(LatLng[1])));
    });

    $(document).on('click', '.close_store_info_mobile', function (){
        clearActiveMarkers();
       $('.city-block-mobile').html('');
    });

    $(document).on("keyup", "#i1", function() {
        let value = $(this).val().toLowerCase();
        $(".city-block").filter(function() {
            if(value.length===0) {
                $(this).toggle($(this).data('city').toLowerCase().indexOf(value) === 0);
            } else {
                $(this).toggle($(this).data('city').toLowerCase().indexOf(value) > -1);
            }
        });
    });

    function clearActiveMarkers() {
        for (let i = 0; i < ids.length; i++) {
            ids[i].setIcon('/public/i/cluster1.svg');
        }
    }

</script>
