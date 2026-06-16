<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => '',
    'title_prefix' => '',
    'title_postfix' => ' | RIALTO PATROL',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => true,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => 'RIALTO PATROL',
    'logo_img' => 'images/logo_shield.png',
    'logo_img_class' => 'brand-image elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'RIALTO PATROL Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'images/logo_shield.png',
            'alt' => 'RIALTO PATROL Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
        'img' => [
            'path' => 'images/logo_shield.png',
            'alt' => 'RIALTO PATROL Logo',
            'effect' => 'animation__shake',
            'width' => 80,
            'height' => 90,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => true,
    'usermenu_desc' => false,
    'usermenu_profile_url' => true,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => true,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-olive elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => 'admin/profile',

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For detailed instructions you can look the laravel mix section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        // NOTIFICACIONES EN EL NAVBAR
        [
            'type' => 'navbar-notification',
            'id' => 'my-notification',
            'icon' => 'fas fa-bell',
            'can' => 'admin.notificaciones',
            'url' => '#',
            'topnav_right' => true,
            'dropdown_mode' => true,
            'dropdown_flabel' => '',
            'update_cfg' => [
                'url' => 'notifications/get',
                'period' => 30,
            ],
        ],
        // MENU SIDEBAR
        [
            'text' => 'Inicio',
            'icon' => 'fas fa-fw fa-home',
            'route' => 'home',
            'can' => 'home',
        ],
        ['header' => 'Operativa'],


        [
            'text' => 'Guardias',
            'icon' => 'fas fa-fw fa-user-shield',
            'submenu' => [
                [
                    'text' => 'Designaciones',
                    'route' => 'designaciones.index',
                    'icon' => 'fas fa-clipboard',
                    'can' => 'designaciones.index',
                ],
                [
                    'text' => 'Historial Designaciones',
                    'route' => 'admin.designacione-guardias',
                    'icon' => 'fas fa-history',
                    'can' => 'designaciones.index',
                ],
            ],
        ],
        [
            'text' => 'Supervisores',
            'icon' => 'fas fa-fw fa-user-secret',
            'submenu' => [
                [
                    'text' => 'Designaciones',
                    'route' => 'admin.designacionessupervisores',
                    'icon' => 'fas fa-user-check',
                    // 'can'   =>  'designaciones.index',
                ],
                [
                    'text' => 'Turnos',
                    'route' => 'admin.turnosupervisores',
                    'icon' => 'fas fa-clock',
                    // 'can'   =>  'designaciones.index',
                ],
                [
                    'text' => 'Caja Chica',
                    'route' => 'admin.cajachica',
                    'icon' => 'fas fa-wallet',
                    // 'can'   =>  'designaciones.index',
                ],
                [
                    'text' => 'Elaboración de Checklist',
                    'route' => 'admin.listadocuestionarios',
                    'icon' => 'fas fa-clipboard',
                    // 'can'   =>  'designaciones.index',
                ],
            ],
        ],

        [
            'text' => 'Recursos Humanos',
            'icon' => 'fas fa-fw fa-users-cog',
            'submenu' => [
                [
                    'text' => 'Empleados',
                    'url' => 'admin/empleados',
                    'can' => 'empleados.index',
                    'icon' => 'fas fa-fw fa-users'
                ],
                [
                    'text' => 'Sueldos',
                    'route' => 'admin.sueldos',
                    'can' => 'rrhhsueldos.index',
                    'icon' => 'fas fa-fw fa-dollar-sign'
                ],
                // [
                //     'text'  =>  'Control Asistencias',
                //     'route' =>  'rrhhctrlasistencias',
                //     'icon'  =>  'fas fa-user-clock',
                //     'can'   =>  'rrhhctrlasistencias',
                // ],
                [
                    'text' => 'Asistencias Web',
                    'route' => 'admin.asistencias',
                    'icon' => 'fas fa-fw fa-mobile-alt',
                    'can' => 'empleados.index',
                ],
            ],
        ],
        [
            'text' => 'Registros',
            'icon' => 'fas fa-fw fa-folder-open',
            'submenu' => [

                [
                    'text' => 'Sanciones/Descuentos',
                    'route' => 'admin.sanciones',
                    'icon' => 'fas fa-fw fa-file-invoice-dollar',
                    'can' => 'admin.registros.descuentos',
                ],
                [
                    'text' => 'Pánico',
                    'route' => 'admin.regactividad',
                    'icon' => 'fas fa-fw fa-shield-alt',
                    'can' => 'admin.registros.panico',
                ],
                [
                    'text' => 'Visitas con Pases',
                    'route' => 'admin.flujopases',
                    'icon' => 'fas fa-fw fa-qrcode',
                    'can' => 'admin.registros.visitas',
                ],
                [
                    'text' => 'Visitas',
                    'route' => 'admin.visitas',
                    'icon' => 'fas fa-fw fa-glasses',
                    'can' => 'admin.registros.visitas',
                ],
                [
                    'text' => 'Rondas',
                    'route' => 'admin.rondas',
                    'can' => 'admin.registros.rondas',
                    'icon' => 'fas fa-fw fa-street-view'
                ],
                [
                    'text' => 'Novedades',
                    'route' => 'admin.novedades',
                    'can' => 'admin.registros.novedades',
                    'icon' => 'fas fa-fw fa-newspaper'
                ],
                [
                    'text' => 'Tareas',
                    'route' => 'admin.tareas',
                    'can' => 'tareas.index',
                    'icon' => 'fas fa-fw fa-tasks'
                ],
                [
                    'text' => 'Hombre Vivo',
                    'route' => 'admin.hombre_vivo',
                    'can' => 'admin.hombre_vivo',
                    'icon' => 'fas fa-fw fa-heartbeat'
                ],


            ],
        ],
        [
            'text' => 'Reportes',
            'icon' => 'fas fa-fw fa-flag',
            'submenu' => [
                [
                    'text' => 'Cronograma Mensual',
                    'route' => 'admin.cronogramadiaslibres',
                    'can' => 'admin.cronogramadiaslibres',
                    'icon' => 'fas fa-fw fa-calendar-check'
                ],
                [
                    'text' => 'Resumen Operacional',
                    'route' => 'admin.resumenoperacional',
                    'can' => 'admin.resumenoperacional',
                    'icon' => 'fas fa-fw fa-users-cog'
                ],
            ]
        ],
        [
            'text' => 'Generador Docs',
            'icon' => 'fas fa-fw fa-cogs',
            'submenu' => [
                [
                    'text' => 'Informes',
                    'route' => 'admin.citesinformes',
                    'can' => 'admin.generador.informe',
                    'icon' => 'fas fa-fw fa-file-pdf'
                ],
                [
                    'text' => 'Memorandum',
                    'route' => 'admin.citesmemorandum',
                    'can' => 'admin.generador.memorandum',
                    'icon' => 'fas fa-fw fa-file-pdf'
                ],
                [
                    'text' => 'Cobros',
                    'route' => 'admin.citescobro',
                    'can' => 'admin.generador.cobro',
                    'icon' => 'fas fa-fw fa-file-pdf'
                ],
                [
                    'text' => 'Recibo',
                    'route' => 'admin.citesrecibo',
                    'can' => 'admin.generador.recibo',
                    'icon' => 'fas fa-fw fa-file-pdf'
                ],
                [
                    'text' => 'Cotizaciones',
                    'route' => 'admin.citescotizacion',
                    'can' => 'admin.generador.cotizacion',
                    'icon' => 'fas fa-fw fa-file-pdf'
                ],
            ],
        ],
        [
            'text' => 'Clientes',
            'url' => 'admin/clientes',
            'can' => 'clientes.index',
            'icon' => 'fas fa-fw fa-address-book'
        ],
        [
            'text' => 'Propietarios',
            'url' => 'admin/listado-propietarios',
            'can' => 'propietarios.index',
            'icon' => 'fas fa-fw fa-user-tag'
        ],
        ['header' => 'Mantenimiento'],
        [
            'text' => 'Administración',
            'icon' => 'fas fa-fw fa-cog',
            'submenu' => [
                [
                    'text' => 'Oficinas',
                    'url' => 'admin/oficinas',
                    'can' => 'oficinas.index',
                    'icon' => 'fas fa-fw fa-list'
                ],
                [
                    'text' => 'Cargos',
                    'route' => 'rrhhcargos.index',
                    'can' => 'rrhhcargos.index',
                    'icon' => 'fas fa-user-graduate'
                ],
                [
                    'text' => 'Feriados',
                    'route' => 'feriados',
                    'can' => 'rrhhferiados.index',
                    'icon' => 'fas fa-birthday-cake'
                ],

            ]
        ],


        [
            'text' => 'Config. Inicial',
            'icon' => 'fas fa-fw fa-cog',
            'submenu' => [

                [
                    'text' => 'Tipo Contratos',
                    'route' => 'rrhhtipocontratos.index',
                    'can' => 'rrhhtipocontratos.index',
                    'icon' => 'fas fa-file-contract'
                ],
                [
                    'text' => 'Tipo Permisos',
                    'route' => 'rrhhtipopermisos.index',
                    'can' => 'rrhhtipopermisos.index',
                    'icon' => 'fas fa-folder-plus'
                ],
                [
                    'text' => 'Motivo Visita',
                    'route' => 'motivos.index',
                    'can' => 'motivos.index',
                    'icon' => 'fas fa-glasses'
                ],


                [
                    'text' => 'Tipo Boletas',
                    'route' => 'tipoboletas.index',
                    // 'can'   =>  'tipoboletas.index',
                    'icon' => 'fas fa-ticket-alt'
                ],
                [
                    'text' => 'Tipo Bonos',
                    'route' => 'rrhhtipobonos.index',
                    'can' => 'rrhhtipobonos.index',
                    'icon' => 'fas fa-funnel-dollar'
                ],
                [
                    'text' => 'Tipo Descuentos',
                    'route' => 'rrhhtipodescuentos.index',
                    'can' => 'rrhhtipodescuentos.index',
                    'icon' => 'fas fa-search-dollar'
                ],
                [
                    'text' => 'Estado Dotaciones',
                    'route' => 'rrhhestadodotacions.index',
                    'can' => 'rrhhestadodotaciones.index',
                    'icon' => 'fas fa-toolbox'
                ],
                [
                    'text' => 'Estados Asistencia',
                    'route' => 'rrhhestados.index',
                    'can' => 'rrhhestados.index',
                    'icon' => 'fas fa-exclamation'
                ],
                [
                    'text' => 'Areas Laborales',
                    'url' => 'admin/areas',
                    'can' => 'areas.index',
                    'icon' => 'fas fa-fw fa-warehouse'
                ],
                [
                    'text' => 'Parametros Generales',
                    'route' => 'sistemaparametros.index',
                    'can' => 'sistemaparametros.index',
                    'icon' => 'fas fa-fw fa-sliders-h'
                ],

            ],
        ],
        [
            'text' => 'Config. Sistema',
            'icon' => 'fas fa-fw fa-cog',
            'submenu' => [
                [
                    'text' => 'Usuarios Sistema',
                    'url' => 'admin/users',
                    'can' => 'users.index',
                    'icon' => 'fas fa-fw fa-user-shield'
                ],
                [
                    'text' => 'Roles y Permisos',
                    'url' => 'admin/roles',
                    'can' => 'admin.roles.index',
                    'icon' => 'fas fa-fw fa-shield-alt',
                ],
            ]
        ]

    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
        App\MenuFilters\SubmenuPermissionFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'plugins/datatables/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'plugins/datatables-bs4/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'plugins/datatables-responsive/js/dataTables.responsive.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'plugins/datatables-responsive/js/responsive.bootstrap4.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'plugins/datatables-buttons/js/dataTables.buttons.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'plugins/datatables-buttons/js/buttons.bootstrap4.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'plugins/jszip/jszip.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'plugins/pdfmake/pdfmake.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'plugins/pdfmake/vfs_fonts.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'plugins/datatables-buttons/js/buttons.html5.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'plugins/datatables-buttons/js/buttons.print.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'plugins/datatables-buttons/js/buttons.colVis.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'plugins/datatables-bs4/css/dataTables.bootstrap4.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'plugins/datatables-responsive/css/responsive.bootstrap4.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'plugins/datatables-buttons/css/buttons.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'plugins/select2/js/select2.full.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'plugins/select2/css/select2.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css',
                ],
            ],
        ],
        'LightGallery' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'plugins/ekko-lightbox/ekko-lightbox.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'plugins/ekko-lightbox/ekko-lightbox.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@11',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
        'OpenStreetMap' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//unpkg.com/leaflet@1.9.4/dist/leaflet.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//unpkg.com/leaflet@1.9.4/dist/leaflet.js',
                ],
            ],
        ],
        'LightBox' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'plugins/light-box/lightbox.css',
                ]
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => true,
];
