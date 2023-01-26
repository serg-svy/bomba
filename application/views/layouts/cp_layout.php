<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<html lang="en">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$_SERVER['HTTP_HOST']?> - Admin area</title>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
<link href="/theme/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="/theme/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
<link href="/theme/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="/theme/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link href="/theme/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
<link href="/theme/assets/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="/theme/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="/theme/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link href="/theme/assets/admin/layout/css/themes/darkblue.css" rel="stylesheet" type="text/css"/>
<link href="/theme/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
<link href="/theme/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css"/>
<link href="/dist/css/admin/chosen.css" rel="stylesheet" type="text/css"/>
<link href="/dist/css/admin/colorpicker.css" rel="stylesheet" type="text/css"/>
<link href="/dist/css/admin/jquery-ui2.css" rel="stylesheet">

<style>
    .chosen-container {width:100%!important;}
    .branch td:nth-child(2),
    .branch td:nth-child(3) {color:#09128b;font-weight:bold;}
</style>
<!--[if lt IE 9]>
<script src="/theme/assets/global/plugins/respond.min.js"></script>
<script src="/theme/assets/global/plugins/excanvas.min.js"></script>
<![endif]-->
<script src="/theme/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="/theme/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<script src="/theme/assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<script src="/theme/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/theme/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="/theme/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="/theme/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/theme/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="/theme/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="/theme/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script src="/theme/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="/theme/assets/global/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.ru.js" type="text/javascript"></script>
<script src="/theme/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="/theme/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="/theme/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="/theme/assets/admin/layout/scripts/demo.js" type="text/javascript"></script>
<script src="/dist/js/admin/jquery.tablednd.js" type="text/javascript"></script>
<script>
    jQuery(document).ready(function() {
        // initiate layout and plugins
        Metronic.init(); // init metronic core components
        Layout.init(); // init current layout
        QuickSidebar.init(); // init quick sidebar
        Demo.init(); // init demo features
        $(".sortTop").tableDnD({
            onDrop: function(table, row) {
                sort_top();
            }
        });
        $(".sortBottom").tableDnD({
            onDrop: function(table, row) {
                sort_bottom();
            }
        });
        $(".dragger").tableDnD({
            onDrop: function(table, row) {
                localsort();
            }
        });
        $(document).on("click", ".myRemoveImage", function (event) {
            event.preventDefault();
            if (confirm('Вы уверены?')) {
                let id = $(this).data('id');
                let col = $(this).data('col');
                let table = $(this).data('table');
                $.post('/<?=ADM_CONTROLLER?>/myRemoveImage/', { "table": table, "col": col, "id": id}, function (){
                    window.location.reload();
                });
            } else {
                return false;
            }
        });
        $('.btn.red').click(function(e) {
            if (confirm('Вы уверены?')) {
            } else {
                e.preventDefault();
                return false;
            }
        });
    });
</script>
<style>
    .portlet-title {cursor:pointer;}
    .tablist td {vertical-align:middle!important;padding:5px!important;}
    .portlet.box {margin-bottom:0;}
    .btn.default.yellow-stripe {}
    .btn.default.yellow-stripe.collapse {display:block;visibility:visible;}
</style>
<script>
    $(document).ready(function() {
        $('.date-picker').datepicker({
            orientation: "left",
            autoclose: true,
            language: 'ru-RU'
        });
    });
</script>
</head>
<body class="page-header-fixed page-quick-sidebar-over-content">
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner">
        <!-- BEGIN LOGO -->
        <!--div class="page-logo">
        </div-->
        <!-- END LOGO -->
        <!-- BEGIN HORIZANTAL MENU -->
        <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
        <!-- DOC: This is desktop version of the horizontal menu. The mobile version is defined(duplicated) sidebar menu below. So the horizontal menu has 2 seperate versions -->
        <div class="hor-menu hor-menu-light hidden-sm hidden-xs">
            <ul class="nav navbar-nav">
                <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the horizontal opening on mouse hover -->
                <?
                $uri2 = $this->uri->segment(2);
                $cnt = $this->db->where('status',1)->where("pay_flag", 1)->get('orders')->num_rows();
                $cnt2 = $this->db->where('status', 1)->get('quick_orders')->num_rows();
                $cnt3 = $this->db->where('status', 0)->get('credit_orders')->num_rows();
                $cnt4 = $this->db->where('status', 1)->get('preorder_orders')->num_rows();
                $cnt5 = $this->db->where('isShown', 0)->get('product_feedback')->num_rows();
                $menu = array(
                    "orders" 	=> array('name'=>"Заказы",'ico'=>'<i class="icon-folder"></i>',
                        'childs' => array(
                            "orders" 	=> array('name'=>"Заказы <span class=\"badge badge-roundless badge-danger\">".$cnt."</span>",'ico'=>'<i class="fa fa-shopping-cart"></i>'),
                            "quick_orders" => array('name' =>"Быстрые заказы <span class='badge badge-roundless badge-danger quick_orders_badge'>$cnt2</span>", 'ico'=>'<i class="fa fa-shopping-cart"></i>'),
                            "credit_orders" => array('name' =>"Заказы в кредит <span class='badge badge-roundless badge-danger credit_orders_badge'>$cnt3</span>", 'ico'=>'<i class="fa fa-shopping-cart"></i>'),
                            "preorders" => array('name' =>"Предзаказ <span class='badge badge-roundless badge-danger quick_orders_badge'>$cnt4</span>", 'ico'=>'<i class="fa fa-shopping-cart"></i>'),
                        ),
                    ),
                    "products" => array('name'=>"Товары",'ico'=>'<i class="icon-folder"></i>',
                        'childs' => array(
                            "category" => array('name'=>"Категории",'ico'=>'<i class="fa fa-folder-open"></i>'),
                            "category_filtered" => array('name'=>"SEO фильтры",'ico'=>'<i class="fa fa-folder-open"></i>'),
                            "product_feedback" => ['name' => "Отзывы <span class=\"badge badge-roundless badge-danger\">".$cnt5."</span>", 'ico' => '<i class="fa fa-th-large"></i>'],
                            "popular_requests" => ['name' => "Популярные запросы <span class=\"badge badge-roundless badge-danger\"></span>", 'ico' => '<i class="fa fa-th-large"></i>'],
                            'delete_image_thumbs' => array('name'=>"Удалить превью изображения",'ico'=>'<i class="icon-picture"></i>'),
                            "product_seo" => array('name'=>"CEO товаров",'ico'=>'<i class="fa fa-sitemap"></i>'),
                            "product_block" => array('name'=>"Блоки товаров",'ico'=>'<i class="fa fa-th"></i>'),
                        ),
                    ),
                    "main" 	=> array('name'=>"Главная",'ico'=>'<i class="icon-folder"></i>',
                        'childs' => array(
                            "menu" => array('name'=>"Меню сайта",'ico'=>'<i class="fa fa-bars"></i>'),
                            "bottom_category" => array('name'=>"Категории меню",'ico'=>'<i class="fa fa-bars"></i>'),
                            "slider" => array('name'=>"Слайдер",'ico'=>'<i class="fa fa-image"></i>'),
                            "main_blocks" => array('name'=>"блоки на главной",'ico'=>'<i class="fa fa-folder-open"></i>'),
                            "main_banners" => array('name'=>"баннеры на главной",'ico'=>'<i class="fa fa-image"></i>'),
                            "header_options" => array('name'=> 'Шапка сайта', 'ico'=>'<i class="fa fa-pencil"></i>'),
                            "bestseller" => array('name'=> 'хит продаж', 'ico'=>'<i class="fa fa-trophy"></i>'),
                        ),
                    ),
                    "partner" 	=> array('name'=>"Партнеры",'ico'=>'<i class="icon-folder"></i>',
                        'childs' => array(
                            "shops" 	=> array('name'=>"Магазины",'ico'=>'<i class="fa fa-book"></i>'),
                            "shop_advantages" 	=> array('name'=>"Преимущества",'ico'=>'<i class="fa fa-cogs"></i>'),
                            "shop_categories" 	=> array('name'=>"Категории",'ico'=>'<i class="icon-grid"></i>'),
                        ),
                    ),
                    "category_banners" => array('name'=>"Баннеры в категориях",'ico'=>'<i class="fa fa-image"></i>'),
                    "brand_banners" => array('name'=>"Баннеры в брендах",'ico'=>'<i class="fa fa-image"></i>'),
                    "news" => array('name'=>"Новости",'ico'=>'<i class="icon-calendar"></i>'),
                    "article" 	=> array('name'=>"Статьи",'ico'=>'<i class="icon-calendar"></i>'),
                    "vacancy" => array('name'=>"Вакансии",'ico'=>'<i class="icon-users"></i>'),
                    "store" => array('name'=>"Магазины",'ico'=>'<i class="icon-home"></i>'),
                    "city" => array('name'=>"Города",'ico'=>'<i class="fa fa-globe"></i>'),
                    "regions" => array('name'=>"Регионы",'ico'=>'<i class="fa fa-globe"></i>'),
                    "contacts" => array('name'=>"Контакты",'ico'=>'<i class="icon-folder"></i>',
                        'childs' => array(
                            "contacts" => array('name'=>"Контакты",'ico'=>'<i class="icon-grid"></i>'),
                            "departments" => array('name'=>"Департаменты",'ico'=>'<i class="icon-grid"></i>'),
                        ),
                    ),
                    "about" => array('name'=>"О компании",'ico'=>'<i class="icon-folder"></i>',
                        'childs' => array(
                            "about_images" => array('name'=>"О нас - изображения",'ico'=>'<i class="fa fa-image"></i>'),
                            "about_blocks" => array('name'=>"О нас - блоки",'ico'=>'<i class="fa fa-folder-open"></i>'),
                        ),
                    ),
                    "payment_blocks" => array('name'=>"Оплата - блоки",'ico'=>'<i class="fa fa-euro"></i>'),
                    "pickup_blocks" => array('name'=>"Самовывоз - блоки",'ico'=>'<i class="fa fa-car"></i>'),
                    "gift_cards" => array('name'=>"Подарочные карты",'ico'=>'<i class="fa fa-credit-card"></i>'),
                    "uds_blocks" => array('name'=>"UDS",'ico'=>'<i class="icon-present"></i>'),
                    "credit_companies" => array('name'=>"Кредитные компании",'ico'=>'<i class="fa fa-bank"></i>'),
                    "credit" => ['name' => 'Проценты кредитов', 'ico' => '<i class="fa fa-percent">%</i>'],
                    "promotions" => array('name'=>"Акции",'ico'=>'<i class="icon-present"></i>'),
                    "promotion_category" => array('name'=>"Категории акций",'ico'=>'<i class="icon-present"></i>'),
                    "payment_type" => array('name'=>"Типы оплаты",'ico'=>'<i class="fa fa-money"></i>'),
                    "delivery_type" => array('name'=>"Типы доставки",'ico'=>'<i class="fa fa-bus"></i>'),
                    "terminals" => array('name'=>"Терминалы",'ico'=>'<i class="icon-folder"></i>',
                        'childs' => array(
                            "terminals" => array('name'=>"Терминалы",'ico'=>'<i class="fa fa-mobile"></i>'),
                            "terminal_users" => array('name'=>"Пользователи терминалов",'ico'=>'<i class="icon-users"></i>'),
                        ),
                    ),
                    "crm" => array('name'=>"CRM",'ico'=>'<i class="icon-folder"></i>',
                        'childs' => array(
                            'crm/time' => array('name' => 'Отчет - временной интервал', 'ico' => '<i class="icon-calendar"></i>'),
                            'crm/top' => array('name' => 'Отчет по самым продаваемым', 'ico' => '<i class="icon-badge"></i>'),
                            'crm/preorder' => array('name' => 'Отчет - товары под заказ', 'ico' => '<i class="icon-clock"></i>'),
                            'crm/overdue_orders' => array('name' => 'Отчет - просроченные заказы', 'ico' => '<i class="icon-clock"></i>'),
                            'crm/top_terminal' => array('name' => 'Отчеты  по терминалам', 'ico' => '<i class="glyphicon glyphicon-inbox"></i>'),
                            'crm/terminal' => array('name' => 'Отчет - товары по терминалов', 'ico' => '<i class="glyphicon glyphicon-inbox"></i>'),
                        ),
                    ),
                    "constants" => array('name'=>"Константы",'ico'=>'<i class="icon-notebook"></i>'),

                    "reviews" 				=> array('name'=>"Обзоры",'ico'=>'<i class="icon-calendar"></i>'),
                );
                ?>
            </ul>
        </div>
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <li class="dropdown dropdown-user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <i class="icon-user"></i>
                        <span class="username username-hide-on-mobile">
					<?=$_SESSION['login']?> </span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <a href="/<?=ADM_CONTROLLER?>/users/" class="olink"><i class="icon-user"></i>Пользователи</a>
                        </li>
                        <li>
                            <a href="http://<?=$_SERVER['HTTP_HOST']?>/" target="_blank"><i class="icon-globe"></i><?=$_SERVER['HTTP_HOST']?></a>
                        </li>
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->
                <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-quick-sidebar-toggler">
                    <a href="/<?=ADM_CONTROLLER?>/logout" class="dropdown-toggle">
                        <i class="icon-logout"></i>
                    </a>
                </li>
                <!-- END QUICK SIDEBAR TOGGLER -->
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
        <div class="page-sidebar navbar-collapse collapse">
            <ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                <li class="sidebar-toggler-wrapper">
                    <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                    <div class="sidebar-toggler">
                    </div>
                    <!-- END SIDEBAR TOGGLER BUTTON -->
                </li>
                <?php foreach ($menu as $key => $val): ?>
                    <?php if (!isset($val['childs'])): ?>
                        <li class="nav-item <?= ($key == $this->uri->segment(2)) ? 'start active open' : '' ?>">
                            <a href="/cp/<?= $key ?>/" class="nav-link ">
                                <?= $val['ico'] ?>
                                <span class="title"><?= $val['name'] ?></span>
                                <?php if ($key == $this->uri->segment(2)) : ?>
                                    <span class="selected"></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php else: ?>
                        <?php
                        $flag = false;
                        foreach ($val['childs'] as $k => $v) {
                            if ($k == $this->uri->segment(2)) {
                                $flag = true;
                            }
                        }
                        ?>
                        <li class="nav-item <?= ($flag) ? 'start active open' : '' ?>">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <?= $val['ico'] ?>
                                <span class="title"><?= $val['name'] ?></span>
                                <?php if ($flag) : ?>
                                    <span class="selected"></span>
                                <?php endif; ?>
                                <span class="arrow <?=($flag)?' open':''?>"></span>
                            </a>
                            <?php if (!empty($val['childs'])) : ?>
                                <ul class="sub-menu">
                                    <?php foreach ($val['childs'] as $k => $v): ?>
                                        <?php
                                        $status = ($k == $this->uri->segment(2)) ? 'start active open' : '';
                                        $link = $k;
                                        ?>
                                        <li class="nav-item <?= $status ?>">
                                            <a href="/cp/<?= $link ?>/" class="nav-link <?= $status ?>">
                                                <?= $v['ico'] ?>
                                                <span class="title"><?= $v['name'] ?></span>
                                                <?php if ($k == $this->uri->segment(2)) : ?>
                                                    <span class="selected"></span>
                                                <?php endif; ?>
                                                <?php if (!empty($v['badge']) && isset($v['badge_value'])): ?>
                                                    <span class="badge badge-<?= $v['badge'] ?>"><?= $v['badge_value'] ?></span>
                                                <?php endif; ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>

            </ul>
            <!-- END SIDEBAR MENU -->
        </div>
    </div>
    <!-- END SIDEBAR -->
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <div class="page-content">
            <?
            $this->load->view($inner_view);
            ?>
        </div>
    </div>
</div>
<div class="page-footer">
    <div class="page-footer-inner">
    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>
</body>
</html>
