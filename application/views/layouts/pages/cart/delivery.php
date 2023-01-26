<div class="tabs_wr">
    <ul>
        <li data-id="2"><a href="#tab_a1"><?=COURIER_DELIVERY?></a></li>
        <?php if (!empty($stores)) { ?>
            <li data-id="4"><a href="#tab_a2"><?=PICKUP?></a></li>
        <?php }?>
    </ul>
    <div id="tab_a1">
        <div class="f_cols_a">
            <div class="f_row v1">
                <label class="f_label"><?=STREET?><span>*</span></label>
                <div class="f_input_wr"><input type="text" name="order[street]" class="f_input required" value="<?=@$_SESSION['order']['street']?>" placeholder="<?=STREET?>" required></div>
            </div>
            <div class="f_row v1_mob">
                <label class="f_label"><?=HOME_NUMBER?><span>*</span></label>
                <div class="f_input_wr"><input type="text" name="order[home_number]" class="f_input required" value="<?=@$_SESSION['order']['home_number']?>" placeholder="<?=HOME_NUMBER?>" required></div>
            </div>
            <div class="f_row">
                <label class="f_label"><?=APARTMENT_OFFICE?></label>
                <div class="f_input_wr"><input type="text" name="order[apartment]" class="f_input" value="<?=@$_SESSION['order']['apartment']?>" placeholder="<?=APARTMENT_OFFICE?>"></div>
            </div>
            <div class="f_row">
                <label class="f_label"><?=ENTRANCE?></label>
                <div class="f_input_wr"><input type="text" name="order[entrance]" class="f_input" value="<?=@$_SESSION['order']['entrance']?>" placeholder="<?=ENTRANCE?>"></div>
            </div>
            <div class="f_row">
                <label class="f_label"><?=FLOOR?></label>
                <div class="f_input_wr"><input type="text" name="order[floor]" class="f_input" value="<?=@$_SESSION['order']['floor']?>" placeholder="<?=FLOOR?>"></div>
            </div>
        </div>
        <?php if($city['is_shown_slots']) {?>
            <link rel="stylesheet" href="/dist/css/time-slots.css?time=<?=time()?>">
            <div class="time-interval">
                <p class="time-interval__title"><?=SELECT_TIME_INTERVAL?></p>
                <div class="time-interval__content">
                    <input type="hidden" name="order[delivery_date]" value="<?=$delivery_date?>">
                    <?php $current = date('N');?>
                    <?php $currentHour = date('H') + 1;?>
                    <div class="time-interval__slider">
                        <?php for($i=$current; $i < $current+7; $i++) {?>
                            <?php
                            $key = ($i < 8) ? $i : $i - 7;
                            $week = $weeks[$key];
                            $today = date('Y-m-d');
                            switch(true) {
                                case $key == $current :
                                    $date = date('d.m');
                                    break;
                                case $key > $current :
                                    $temp = $key - $current;
                                    $date = date('d.m', strtotime($today . ' + ' . $temp . ' day'));
                                    break;
                                case $key < $current :
                                    $temp = ($key + (7 - $current));
                                    $date = date('d.m', strtotime($today . ' + ' . $temp . ' day'));
                                    break;
                            }
                            ?>
                            <div class="time-interval__item item-time-interval">
                                <div class="item-time-interval__head">
                                    <p class="item-time-interval__date"><?=$date?></p>
                                    <p class="item-time-interval__day"><?=$week['name']?></p>
                                </div>
                                <div class="item-time-interval__body">
                                    <?php if(isset($slots[$key])){?>
                                        <?php foreach($slots[$key] as $index=> $slot) {?>
                                            <?php $h = explode(':', $slot['start']);?>
                                            <?php if(($current == $key and $currentHour >= $h[0]) or (int)$slot['qty'] <= 0) {?>
                                                <div class="item-time-interval__item">
                                                    <input type="radio" class="item-time-interval__radio _disabled">
                                                    <label class="item-time-interval__label _disabled" for="">
                                                        <p><?=INTERVAL_NOT_AVAILABLE?></p>
                                                    </label>
                                                </div>
                                            <?php } else {?>
                                                <div class="item-time-interval__item">
                                                    <?php $delivery_date_time = $delivery_date . '' . $delivery_time?>
                                                    <input
                                                        <?=($delivery_date_time == date('Y-m-d', strtotime($date.'.'.date('Y'))).$slot['start'].' - '.$slot['end'])? ' checked' : ''?>
                                                        type="radio"
                                                        data-key="<?=$key?>-<?=$index?>"
                                                        data-date="<?=date('Y-m-d', strtotime($date.'.'.date('Y')))?>"
                                                        value="<?=$slot['start']?> - <?=$slot['end']?>"
                                                        name="order[delivery_time]"
                                                        id="<?=$index?>-<?=$date?>"
                                                        class="item-time-interval__radio">
                                                    <label class="item-time-interval__label" for="<?=$index?>-<?=$date?>">
                                                        <p class="item-time-interval__time"><?=$slot['start']?> - <?=$slot['end']?></p>
                                                        <p class="item-time-interval__price "><?=($cart_total >= $slot['free']) ? 0 : $slot['price'] ?>,—</p>
                                                    </label>
                                                </div>
                                            <?php }?>
                                        <?php }?>
                                    <?php } else {?>
                                        <div class="item-time-interval__item">
                                            <input type="radio" class="item-time-interval__radio _disabled">
                                            <label class="item-time-interval__label _disabled" for="">
                                                <p><?=INTERVAL_NOT_AVAILABLE?></p>
                                            </label>
                                        </div>
                                    <?php }?>
                                </div>
                            </div>
                        <?php }?>
                    </div>
                </div>
            </div>
            <script src="/dist/js/time-slots.js"></script>
        <?php }else {?>
            <script src="/dist/js/moment.js"></script>
            <div class="f_cols_a">
                <div class="f_row v1">
                    <label class="f_label"><?=DELIVERY_DATE?></label>
                    <div class="f_block-sel">
                        <select name="order[delivery_date]" id="delivery_date">
                            <script>
                                moment.locale('<?=$_SESSION['lang']?>');
                                $("#delivery_date > option").each(function() {
                                    const this_date = $(this).val();
                                    const select_date = moment(this_date, "YYYY-MM-Do").format('Do MMMM, dddd');
                                    $(this).html(select_date);
                                });
                            </script>
                            <?php foreach ($dates as $day_date) {?>
                                <option value="<?=$day_date?>"></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="f_row v1_mob">
                    <label class="f_label"><?=TIME?></label>
                    <div class="f_input_wr"><input type="text" name="order[delivery_time]" id="delivery_time" class="f_input disabled" value="9:00 — 21:00"></div>
                </div>
            </div>
        <?php }?>
    </div>
    <?php if (!empty($stores)) { ?>
        <div id="tab_a2">
            <div class="map_wr">
                <div class="map">
                    <div id="map"></div>
                    <?php foreach ($stores as $store) {?>
                        <div class="map_address_detail map_address_detail_<?=$store->id?>">
                            <div class="close"><i class="icon-close"></i></div>
                            <div class="mad_head">
                                <div class="mad_title"><?=$store->title?></div>
                                <p><?=$store->address?></p>
                            </div>
                            <div class="mad_info">
                                <p><?=nl2br($store->workhours)?></p>
                                <p><?=nl2br($store->parking)?></p>
                                <p><?=nl2br($store->troleibus)?></p>
                            </div>
                        </div>
                    <?php }?>
                </div>
                <div class="map_address_wr">
                    <div class="title6"><?=PICKUP_POINTS?></div>
                    <?php foreach ($stores as $store) {?>
                        <div class="map_address">
                            <div class="ma_head">
                                <div class="ma_title"><?=$store->title?></div>
                                <p><?=$store->address?></p>
                            </div>
                            <div class="b_btn">
                                <a data-id="<?=$store->id?>" href="" class="btn <?=(isset($_SESSION['order']['store_id']) and $store->id == $_SESSION['order']['store_id']) ? 'v1' : 'v2'?>"><i class="icon-check"></i><?=PICKUP_HERE?></a>
                            </div>
                        </div>
                    <?php }?>
                </div>
            </div>
        </div>
        <script>
            function initialize(element, coords_json, cityCoords) {
                if(element !== undefined) {

                    markers = [];

                    let LatLng = cityCoords.split(',');

                    let mapOptions = {
                        center: new google.maps.LatLng($.trim(LatLng[0]), $.trim(LatLng[1])),
                        zoom: 13,
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
                        $(".map_address_detail").hide();
                        $(".map_address_detail_" + value.id).show();
                        $("#store_id").val(value.id);
                        infoWindow.setContent('<h3> ' + value.title + '</h3> <br>' + value.address);
                        infoWindow.open(map, marker);
                    });
                });
            }

            $( document ).ready(function() {
                setTimeout(function() {
                    initialize(document.getElementById("map"), '<?=json_encode($stores, true)?>', '<?=$city['coords']?>');
                }, 1000);
            });

        </script>
        <style>
            .map {
                background: #fff;
                border-radius: 16px;
            }
            #map {
                min-width:450px;
                height:450px;
            }
            @media screen and (max-width: 768px) {
                #map {
                    min-width:100%;
                    height:400px;
                }
            }
        </style>
    <?php }?>
    <input type="hidden" name="order[delivery_type_id]" id="delivery_type_id" value="<?=(isset($_SESSION['order']['delivery_type_id'])) ? $_SESSION['order']['delivery_type_id'] : 2?>">
    <input type="hidden" name="order[store_id]" id="store_id" value="<?=@$_SESSION['order']['store_id']?>">
    <input type="hidden" name="order[delivery_key]" id="delivery_key" value="<?=@$_SESSION['order']['delivery_key']?>">
    <br>
    <div class="b_btn">
        <button type="submit" id="submitDeliveryBtn" class="btn"><?=ENTER_DELIVERY_DATA?></button>
    </div>
</div>
