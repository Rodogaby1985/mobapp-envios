<?php
/**
* Plugin Name: MOBAPP ENVÍOS
* Plugin URI: https://mobappexpress.com
* Description: Envíos cotizados Correo Argentino, Andreani, OCA y Urbano
* Version: 2.0.0
* Author: Emanuel Loto
* Author URI: http://localhost/wordpress
* Requires at least: 4.0
* Tested up to: 6.3
*
* Text Domain: MOBAPP ENVÍOS
* Domain path: /languages/
*
*/
defined( "ABSPATH" ) or die( "¡sin trampas!" );

//////////////////////////////////////registro cronjob diario///////////////////////////////////////
add_action( 'wp', 'mobapp_setup_schedule' ); 

function mobapp_setup_schedule() {
    if ( ! wp_next_scheduled( 'mobapp_daily_event' ) ) {
        wp_schedule_event( time(), 'hourly', 'mobapp_daily_event');
    }
}

add_action( 'mobapp_daily_event', 'mobapp_do_this_daily' );

function mobapp_do_this_daily() {
    ////////////////////////CA DOM////////////////////
    $url_ca_dom = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vT6l-Z2nmlhlTRQp5Aaki1Mwpao8XKHrSTRllymp8UiUP7dZ20hVitvqSvRl72GwDnXsGh9P31mq0vi/pub?gid=0&single=true&output=csv';
    $csv_data_ca_dom = file_get_contents_curl($url_ca_dom);
    if ($csv_data_ca_dom) set_transient('datos_csv_ca_dom', $csv_data_ca_dom, DAY_IN_SECONDS); //guardo la info 24hs
    ////////////////////////CA SUC////////////////////
    $url_ca_suc = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vT6l-Z2nmlhlTRQp5Aaki1Mwpao8XKHrSTRllymp8UiUP7dZ20hVitvqSvRl72GwDnXsGh9P31mq0vi/pub?gid=1897873008&single=true&output=csv';
    $csv_data_ca_suc = file_get_contents_curl($url_ca_suc);
    if ($csv_data_ca_suc) set_transient('datos_csv_ca_suc', $csv_data_ca_suc, DAY_IN_SECONDS); //guardo la info 24hs
    ////////////////////////ANDREANI DOM////////////////////
    $url_andreani_dom = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vT6l-Z2nmlhlTRQp5Aaki1Mwpao8XKHrSTRllymp8UiUP7dZ20hVitvqSvRl72GwDnXsGh9P31mq0vi/pub?gid=506491561&single=true&output=csv';
    $csv_data_andreani_dom = file_get_contents_curl($url_andreani_dom);
    if ($csv_data_andreani_dom) set_transient('datos_csv_andreani_dom', $csv_data_andreani_dom, DAY_IN_SECONDS); //guardo la info 24hs
    ////////////////////////ANDREANI SUC////////////////////
    $url_andreani_suc = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vT6l-Z2nmlhlTRQp5Aaki1Mwpao8XKHrSTRllymp8UiUP7dZ20hVitvqSvRl72GwDnXsGh9P31mq0vi/pub?gid=2067361143&single=true&output=csv';
    $csv_data_andreani_suc = file_get_contents_curl($url_andreani_suc);
    if ($csv_data_andreani_suc) set_transient('datos_csv_andreani_suc', $csv_data_andreani_suc, DAY_IN_SECONDS); //guardo la info 24hs
    ////////////////////////OCA DOM////////////////////
    $url_oca_dom = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vT6l-Z2nmlhlTRQp5Aaki1Mwpao8XKHrSTRllymp8UiUP7dZ20hVitvqSvRl72GwDnXsGh9P31mq0vi/pub?gid=98567282&single=true&output=csv';
    $csv_data_oca_dom = file_get_contents_curl($url_oca_dom);
    if ($csv_data_oca_dom) set_transient('datos_csv_oca_dom', $csv_data_oca_dom, DAY_IN_SECONDS); //guardo la info 24hs
    ////////////////////////OCA SUC////////////////////
    $url_oca_suc = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vT6l-Z2nmlhlTRQp5Aaki1Mwpao8XKHrSTRllymp8UiUP7dZ20hVitvqSvRl72GwDnXsGh9P31mq0vi/pub?gid=1766360152&single=true&output=csv';
    $csv_data_oca_suc = file_get_contents_curl($url_oca_suc);
    if ($csv_data_oca_suc) set_transient('datos_csv_oca_suc', $csv_data_oca_suc, DAY_IN_SECONDS); //guardo la info 24hs
    ////////////////////////URBANO DOM////////////////////
    $url_urbano_dom = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vT6l-Z2nmlhlTRQp5Aaki1Mwpao8XKHrSTRllymp8UiUP7dZ20hVitvqSvRl72GwDnXsGh9P31mq0vi/pub?gid=1666417641&single=true&output=csv';
    $csv_data_urbano_dom = file_get_contents_curl($url_urbano_dom);
    if ($csv_data_urbano_dom) set_transient('datos_csv_urbano_dom', $csv_data_urbano_dom, DAY_IN_SECONDS); //guardo la info 24hs
}
/////////////////////// Eliminar el cron job al desactivar el plugin ////////////////////
function eliminar_cron_diario() {
    $timestamp = wp_next_scheduled('mobapp_daily_event');
    wp_unschedule_event($timestamp, 'mobapp_daily_event');
}
register_deactivation_hook(__FILE__, 'eliminar_cron_diario');
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function file_get_contents_curl($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

function ocultar_envios($rates, $package) {
    
    $zonas = array();                                           //
    $delivery_zones = WC_Shipping_Zones::get_zones();           //
    foreach ((array) $delivery_zones as $key => $the_zone ) {   //
        $zonas[] = $the_zone['zone_name'];                      //
    }                                                           //
    
    $zona_del_carrito = WC_Shipping_Zones::get_zone_matching_package( $package );
    $zona_del_carrito_nombre = $zona_del_carrito->get_zone_name();
    $correos =  WC()->shipping->get_shipping_methods();
   
    foreach($correos as $correo){
        $id = $correo->id;
        $zonas_posiciones = $correo->get_option('ocultar_para_zonas');
        if($zonas_posiciones == null)
        {
            continue;
        }
        if(is_array($zonas_posiciones)){
            foreach($zonas_posiciones as $zona_pos){
                if($zonas[(int)$zona_pos] == $zona_del_carrito_nombre){
                    unset($rates[$id]);
                }
                else{
                    isset($rates[$id]);
                }
            }
        }
        else{
            if($zonas[(int)$zonas_posiciones] == $zona_del_carrito_nombre){
                unset($rates[$id]);
                break;
            }
            else{
                isset($rates[$id]);
            }
        }
    }
    return $rates;
}
add_filter('woocommerce_package_rates', 'ocultar_envios', 10, 2);

/*/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
// Todas las clases de métodos de envío ahora incluyen la opción de destacado y tooltip CSS
function mobapp_add_common_fields(&$form_fields) {
    $form_fields['featured'] = array(
        'title'   => esc_html__('Destacar este método', 'mobapp-envios'),
        'type'    => 'checkbox',
        'label'   => esc_html__('Mostrar como método recomendado', 'mobapp-envios'),
        'default' => 'no'
    );
    $form_fields['featured_text'] = array(
        'title'       => esc_html__('Leyenda descriptiva destacada', 'mobapp-envios'),
        'type'        => 'text',
        'description' => esc_html__('Leyenda que se mostrará al pasar el mouse sobre el icono de información.', 'mobapp-envios'),
        'default'     => '',
        'desc_tip'    => true
    );
}

/* ======== Helper para añadir tooltip destacado ======== */
function mobapp_append_featured_tooltip(&$titulo, $method_object) {
    $featured = $method_object->get_option('featured');
    $featured_text = $method_object->get_option('featured_text');
    if ($featured === 'yes' && !empty($featured_text)) {
        static $tooltip_css_printed = false;
        if (!$tooltip_css_printed) {
            $tooltip_css = '<style>
            .mobapp-tooltip {
                position: relative; display: inline-block; cursor: pointer; margin-left: 6px; vertical-align: middle;
            }
            .mobapp-tooltip .mobapp-tooltiptext {
                visibility: hidden; width: 200px; background: #222; color: #fff; text-align: center; border-radius: 6px; padding: 8px 10px;
                position: absolute; z-index: 9999; bottom: 125%; left: 50%; transform: translateX(-50%);
                opacity: 0; transition: opacity 0.3s;
                font-size: 13px;
            }
            .mobapp-tooltip .mobapp-tooltiptext::after {
                content: ""; position: absolute; top: 100%; left: 50%; margin-left: -5px;
                border-width: 6px; border-style: solid; border-color: #222 transparent transparent transparent;
            }
            .mobapp-tooltip:hover .mobapp-tooltiptext, .mobapp-tooltip:focus .mobapp-tooltiptext {
                visibility: visible; opacity: 1;
            }
            </style>';
            echo $tooltip_css;
            $tooltip_css_printed = true;
        }
        $info_icon = '<span class="mobapp-tooltip" tabindex="0" aria-label="Más información">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" style="vertical-align:middle;" viewBox="0 0 24 24" fill="#0073aa"><circle cx="12" cy="12" r="10" stroke="#0073aa" stroke-width="2" fill="#fff"/><text x="12" y="16" text-anchor="middle" fill="#0073aa" font-size="13" font-family="Arial" dy="0.3em">i</text></svg>
            <span class="mobapp-tooltiptext">'.esc_html($featured_text).'</span>
        </span>';
        $titulo .= $info_icon;
    }
}
/*/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/

add_action( 'woocommerce_shipping_init', 'mobapp_correoargentino_domicilio_envios_init' );
function mobapp_correoargentino_domicilio_envios_init() {
    if ( ! class_exists( 'WC_MOBAPP_CORREOARGENTINO_DOMICILIO_ENVIOS' ) ) {
        class WC_MOBAPP_CORREOARGENTINO_DOMICILIO_ENVIOS extends WC_Shipping_Method{
            public function __construct(){
                $this->id                 = 'mobapp-correoargentino-domicilio-envios';
                $this->method_title       = __( 'CORREO ARGENTINO DOMICILIO');
                $this->method_description = __( 'Cotiza tus envíos con MOBAPP ENVÍOS' );
                $this->init(); 
                $this->enabled            = $this->get_option( 'enabled' );
                $this->title              = "CORREO ARGENTINO DOMICILIO";               
            }
            public function init(){
                $this->init_form_fields(); 
                add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
            }
            public function calculate_shipping( $package = Array() ){
                $csv_data = get_transient('datos_csv_ca_dom'); //TOMO INFO TEMPORAL
                $provincia = $package[ 'destination' ][ 'state' ];
                $peso_carrito = WC()->cart->get_cart_contents_weight();
                $cost = '0';
                $titulo = $this->get_option('title');
                $asd = $this->get_option('ocultar_para_zonas');
                if ($csv_data) {
                    $filas = explode("\n", $csv_data);
                    foreach ($filas as $fila) {
                        $columnas = str_getcsv($fila);
                        $titulo_tabla = $columnas[0];
                        $codigo = $columnas[2];
                        $peso_min = floatval($columnas[3]);
                        $peso_max = floatval($columnas[4]);
                        $precio = $columnas[5];
                        if ( $provincia == $codigo && $peso_carrito <= $peso_max && $peso_carrito > $peso_min){
                            $titulo = $titulo_tabla;
                            $cost = $precio;
                        }
                    }
                }
                else{
                    echo 'No se pudo leer csv';
                }
                mobapp_append_featured_tooltip($titulo, $this);
                $this->add_rate( array(
                    'id'     => $this->id,
                    'label'  => $titulo,
                    'cost'   => $cost
                ));
                $this->add_rate( $package ); // Aplicar precio
            }
            public function init_form_fields() {
                $zonas = array();                                           //
                $delivery_zones = WC_Shipping_Zones::get_zones();           //
                foreach ((array) $delivery_zones as $key => $the_zone ) {   //
                    $zonas[] = $the_zone['zone_name'];                      //
                }                                                           //
                $form_fields = array(
                  'enabled' => array(
                     'title'   => esc_html__('Activar/Desactivar', 'mobapp-correoargentino-domicilio-envios' ),
                     'type'    => 'checkbox',
                     'label'   => esc_html__('Activar método de envío', 'mobapp-correoargentino-domicilio-envios'  ),
                     'default' => 'yes'
                  ),
                  'title' => array(
                     'title'       => esc_html__('Título del envío en caso de peso excedente', 'mobapp-correoargentino-domicilio-envios' ),
                     'type'        => 'text',
                     'description' => esc_html__('Ingresar título del envío', 'mobapp-correoargentino-domicilio-envios'  ),
                     'default'     => esc_html__('A COTIZAR - PESO SUPERIOR A 30KG - CORREO ARGENTINO A DOMICILIO', 'mobapp-correoargentino-domicilio-envios' ),
                     'desc_tip'    => true
                  ),
                  'ocultar_para_zonas' => array(
                    'title'             => __( 'Ocultar envío para zonas específicas', 'mobapp-correoargentino-domicilio-envios' ),
                    'type'              => 'multiselect',
                    'class'             => 'wc-enhanced-select',
                    'css'               => 'width: 400px;',
                    'default'           => '',
                    'description'       => __( 'Seleccionar zonas en las que se ocultará este envío', 'mobapp-correoargentino-domicilio-envios' ),
                    'options'           => $zonas,
                    'desc_tip'          => true,
                    'custom_attributes' => array(
                        'data-placeholder' => __( 'Seleccionar zonas', 'mobapp-correoargentino-domicilio-envios' )
                    ),
                 )
                );
                mobapp_add_common_fields($form_fields);
                $this->form_fields = $form_fields;
            }
        }   
    }
}
add_filter('woocommerce_shipping_methods','agregar_mobapp_correoargentino_domicilio_envios_method');
function agregar_mobapp_correoargentino_domicilio_envios_method( $methods ){
    $methods['mobapp-correoargentino-domicilio-envios'] = 'WC_MOBAPP_CORREOARGENTINO_DOMICILIO_ENVIOS';
    return $methods;
}
/*//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/

add_action( 'woocommerce_shipping_init', 'mobapp_correoargentino_sucursal_envios_init' );
function mobapp_correoargentino_sucursal_envios_init() {
    if ( ! class_exists( 'WC_MOBAPP_CORREOARGENTINO_SUCURSAL_ENVIOS' ) ) {
        class WC_MOBAPP_CORREOARGENTINO_SUCURSAL_ENVIOS extends WC_Shipping_Method{
            public function __construct(){
                $this->id                 = 'mobapp-correoargentino-sucursal-envios';
                $this->method_title       = __( 'CORREO ARGENTINO SUCURSAL');
                $this->method_description = __( 'Cotiza tus envíos con MOBAPP ENVÍOS' ); 
                $this->init();
                $this->enabled            = $this->get_option( 'enabled' );
                $this->title              = "CORREO ARGENTINO SUCURSAL";
            }
            public function init(){
                $this->init_form_fields(); 
                add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
            }
            public function calculate_shipping( $package = Array() ){
                $csv_data = get_transient('datos_csv_ca_suc'); //TOMO INFO TEMPORAL
                $provincia = $package[ 'destination' ][ 'state' ];
                $peso_carrito = WC()->cart->get_cart_contents_weight();
                $cost = '0';
                $titulo = $this->get_option('title');
                if ($csv_data) {
                    $filas = explode("\n", $csv_data);
                    foreach ($filas as $fila) {
                        $columnas = str_getcsv($fila);
                        $titulo_tabla = $columnas[0];
                        $codigo = $columnas[2];
                        $peso_min = floatval($columnas[3]);
                        $peso_max = floatval($columnas[4]);
                        $precio = $columnas[5];
                        if ( $provincia == $codigo && $peso_carrito <= $peso_max && $peso_carrito > $peso_min){
                            $titulo = $titulo_tabla;
                            $cost = $precio;
                        }
                    }
                }
                else{
                    echo 'No se pudo leer csv';
                }
                mobapp_append_featured_tooltip($titulo, $this);
                $this->add_rate( array(
                    'id'     => $this->id,
                    'label'  => $titulo,
                    'cost'   => $cost
                ));
                $this->add_rate( $package ); // Aplicar precio
            }
            public function init_form_fields() {
                $zonas = array();                                           //
                $delivery_zones = WC_Shipping_Zones::get_zones();           //
                foreach ((array) $delivery_zones as $key => $the_zone ) {   //
                    $zonas[] = $the_zone['zone_name'];                      //
                }                                                           //
                $form_fields = array(
                  'enabled' => array(
                     'title'   => esc_html__('Activar/Desactivar', 'mobapp-correoargentino-sucursal-envios' ),
                     'type'    => 'checkbox',
                     'label'   => esc_html__('Activar método de envío', 'mobapp-correoargentino-sucursal-envios'  ),
                     'default' => 'yes'
                  ),
                  'title' => array(
                     'title'       => esc_html__('Título del envío en caso de peso excedente', 'mobapp-correoargentino-sucursal-envios' ),
                     'type'        => 'text',
                     'description' => esc_html__('Ingresar título del envío', 'mobapp-correoargentino-sucursal-envios'  ),
                     'default'     => esc_html__('A COTIZAR - PESO SUPERIOR A 30KG - CORREO ARGENTINO A SUCURSAL', 'mobapp-correoargentino-sucursal-envios' ),
                     'desc_tip'    => true
                  ),
                   'ocultar_para_zonas' => array(
                    'title'             => __( 'Ocultar envío para zonas específicas', 'mobapp-correoargentino-sucursal-envios' ),
                    'type'              => 'multiselect',
                    'class'             => 'wc-enhanced-select',
                    'css'               => 'width: 400px;',
                    'default'           => '',
                    'description'       => __( 'Seleccionar zonas en las que se ocultará este envío', 'mobapp-correoargentino-sucursal-envios' ),
                    'options'           => $zonas,
                    'desc_tip'          => true,
                    'custom_attributes' => array(
                        'data-placeholder' => __( 'Seleccionar zonas', 'mobapp-correoargentino-sucursal-envios' )
                    ),
                 )
                );
                mobapp_add_common_fields($form_fields);
                $this->form_fields = $form_fields;
            }
        }   
    }
}
add_filter('woocommerce_shipping_methods','agregar_mobapp_correoargentino_sucursal_envios_method');
function agregar_mobapp_correoargentino_sucursal_envios_method( $methods ){
    $methods['mobapp-correoargentino-sucursal-envios'] = 'WC_MOBAPP_CORREOARGENTINO_SUCURSAL_ENVIOS';
    return $methods;
}
/*//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/

add_action( 'woocommerce_shipping_init', 'mobapp_andreani_domicilio_envios_init' );
function mobapp_andreani_domicilio_envios_init() {
    if ( ! class_exists( 'WC_MOBAPP_ANDREANI_DOMICILIO_ENVIOS' ) ) {
        class WC_MOBAPP_ANDREANI_DOMICILIO_ENVIOS extends WC_Shipping_Method{
            public function __construct(){
                $this->id                 = 'mobapp-andreani-domicilio-envios';
                $this->method_title       = __( 'ANDREANI DOMICILIO');
                $this->method_description = __( 'Cotiza tus envíos con MOBAPP ENVÍOS' ); 
                $this->init();
                $this->enabled            = $this->get_option( 'enabled' );
                $this->title              = "ANDREANI DOMICILIO";
            }
            public function init(){
                $this->init_form_fields(); 
                add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
            }
            public function calculate_shipping( $package = Array() ){
                $csv_data = get_transient('datos_csv_andreani_dom'); //TOMO INFO TEMPORAL
                $provincia = $package[ 'destination' ][ 'state' ];
                $peso_carrito = WC()->cart->get_cart_contents_weight();
                $cost = '0';
                $titulo = $this->get_option('title');
                if ($csv_data) {
                    $filas = explode("\n", $csv_data);
                    foreach ($filas as $fila) {
                        $columnas = str_getcsv($fila);
                        $titulo_tabla = $columnas[0];
                        $codigo = $columnas[2];
                        $peso_min = floatval($columnas[3]);
                        $peso_max = floatval($columnas[4]);
                        $precio = $columnas[5];
                        if ( $provincia == $codigo && $peso_carrito <= $peso_max && $peso_carrito > $peso_min){
                            $titulo = $titulo_tabla;
                            $cost = $precio;
                        }
                    }
                }
                else{
                    echo 'No se pudo leer csv';
                }
                mobapp_append_featured_tooltip($titulo, $this);
                $this->add_rate( array(
                    'id'     => $this->id,
                    'label'  => $titulo,
                    'cost'   => $cost
                ));
                $this->add_rate( $package ); // Aplicar precio
            }
            public function init_form_fields() {
                $zonas = array();                                           //
                $delivery_zones = WC_Shipping_Zones::get_zones();           //
                foreach ((array) $delivery_zones as $key => $the_zone ) {   //
                    $zonas[] = $the_zone['zone_name'];                      //
                }                                                           //
                $form_fields = array(
                  'enabled' => array(
                     'title'   => esc_html__('Activar/Desactivar', 'mobapp-andreani-domicilio-envios' ),
                     'type'    => 'checkbox',
                     'label'   => esc_html__('Activar método de envío', 'mobapp-andreani-domicilio-envios'  ),
                     'default' => 'yes'
                  ),
                  'title' => array(
                     'title'       => esc_html__('Título del envío en caso de peso excedente', 'mobapp-andreani-domicilio-envios' ),
                     'type'        => 'text',
                     'description' => esc_html__('Ingresar título del envío', 'mobapp-andreani-domicilio-envios'  ),
                     'default'     => esc_html__('A COTIZAR - PESO SUPERIOR A 30KG - ANDREANI A DOMICILIO', 'mobapp-andreani-domicilio-envios' ),
                     'desc_tip'    => true
                  ),
                   'ocultar_para_zonas' => array(
                    'title'             => __( 'Ocultar envío para zonas específicas', 'mobapp-andreani-domicilio-envios' ),
                    'type'              => 'multiselect',
                    'class'             => 'wc-enhanced-select',
                    'css'               => 'width: 400px;',
                    'default'           => '',
                    'description'       => __( 'Seleccionar zonas en las que se ocultará este envío', 'mobapp-andreani-domicilio-envios' ),
                    'options'           => $zonas,
                    'desc_tip'          => true,
                    'custom_attributes' => array(
                        'data-placeholder' => __( 'Seleccionar zonas', 'mobapp-andreani-domicilio-envios' )
                    ),
                 )
                );
                mobapp_add_common_fields($form_fields);
                $this->form_fields = $form_fields;
            }
        }   
    }
}
add_filter('woocommerce_shipping_methods','agregar_mobapp_andreani_domicilio_envios_method');
function agregar_mobapp_andreani_domicilio_envios_method( $methods ){
    $methods['mobapp-andreani-domicilio-envios'] = 'WC_MOBAPP_ANDREANI_DOMICILIO_ENVIOS';
    return $methods;
}
/*//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/

add_action( 'woocommerce_shipping_init', 'mobapp_andreani_sucursal_envios_init' );
function mobapp_andreani_sucursal_envios_init() {
    if ( ! class_exists( 'WC_MOBAPP_ANDREANI_SUCURSAL_ENVIOS' ) ) {
        class WC_MOBAPP_ANDREANI_SUCURSAL_ENVIOS extends WC_Shipping_Method{
            public function __construct(){
                $this->id                 = 'mobapp-andreani-sucursal-envios';
                $this->method_title       = __( 'ANDREANI SUCURSAL');
                $this->method_description = __( 'Cotiza tus envíos con MOBAPP ENVÍOS' ); 
                $this->init();
                $this->enabled            = $this->get_option( 'enabled' );
                $this->title              = "ANDREANI SUCURSAL";
            }
            public function init(){
                $this->init_form_fields(); 
                add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
            }
            public function calculate_shipping( $package = Array() ){
                $csv_data = get_transient('datos_csv_andreani_suc'); //TOMO INFO TEMPORAL
                $provincia = $package[ 'destination' ][ 'state' ];
                $peso_carrito = WC()->cart->get_cart_contents_weight();
                $cost = '0';
                $titulo = $this->get_option('title');
                if ($csv_data) {
                    $filas = explode("\n", $csv_data);
                    foreach ($filas as $fila) {
                        $columnas = str_getcsv($fila);
                        $titulo_tabla = $columnas[0];
                        $codigo = $columnas[2];
                        $peso_min = floatval($columnas[3]);
                        $peso_max = floatval($columnas[4]);
                        $precio = $columnas[5];
                        if ( $provincia == $codigo && $peso_carrito <= $peso_max && $peso_carrito > $peso_min){
                            $titulo = $titulo_tabla;
                            $cost = $precio;
                        }
                    }
                }
                else{
                    echo 'No se pudo leer csv';
                }
                mobapp_append_featured_tooltip($titulo, $this);
                $this->add_rate( array(
                    'id'     => $this->id,
                    'label'  => $titulo,
                    'cost'   => $cost
                ));
                $this->add_rate( $package ); // Aplicar precio
            }
            public function init_form_fields() {
                $zonas = array();                                           //
                $delivery_zones = WC_Shipping_Zones::get_zones();           //
                foreach ((array) $delivery_zones as $key => $the_zone ) {   //
                    $zonas[] = $the_zone['zone_name'];                      //
                }                                                           //
                $form_fields = array(
                  'enabled' => array(
                     'title'   => esc_html__('Activar/Desactivar', 'mobapp-andreani-sucursal-envios' ),
                     'type'    => 'checkbox',
                     'label'   => esc_html__('Activar método de envío', 'mobapp-andreani-sucursal-envios'  ),
                     'default' => 'yes'
                  ),
                  'title' => array(
                     'title'       => esc_html__('Título del envío en caso de peso excedente', 'mobapp-andreani-sucursal-envios' ),
                     'type'        => 'text',
                     'description' => esc_html__('Ingresar título del envío', 'mobapp-andreani-sucursal-envios'  ),
                     'default'     => esc_html__('A COTIZAR - PESO SUPERIOR A 30KG - ANDREANI A SUCURSAL', 'mobapp-andreani-sucursal-envios' ),
                     'desc_tip'    => true
                  ),
                   'ocultar_para_zonas' => array(
                    'title'             => __( 'Ocultar envío para zonas específicas', 'mobapp-andreani-sucursal-envios' ),
                    'type'              => 'multiselect',
                    'class'             => 'wc-enhanced-select',
                    'css'               => 'width: 400px;',
                    'default'           => '',
                    'description'       => __( 'Seleccionar zonas en las que se ocultará este envío', 'mobapp-andreani-sucursal-envios' ),
                    'options'           => $zonas,
                    'desc_tip'          => true,
                    'custom_attributes' => array(
                        'data-placeholder' => __( 'Seleccionar zonas', 'mobapp-andreani-sucursal-envios' )
                    ),
                 )
                );
                mobapp_add_common_fields($form_fields);
                $this->form_fields = $form_fields;
            }
        }   
    }
}
add_filter('woocommerce_shipping_methods','agregar_mobapp_andreani_sucursal_envios_method');
function agregar_mobapp_andreani_sucursal_envios_method( $methods ){
    $methods['mobapp-andreani-sucursal-envios'] = 'WC_MOBAPP_ANDREANI_SUCURSAL_ENVIOS';
    return $methods;
}
/*//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/

add_action( 'woocommerce_shipping_init', 'mobapp_oca_domicilio_envios_init' );
function mobapp_oca_domicilio_envios_init() {
    if ( ! class_exists( 'WC_MOBAPP_OCA_DOMICILIO_ENVIOS' ) ) {
        class WC_MOBAPP_OCA_DOMICILIO_ENVIOS extends WC_Shipping_Method{
            public function __construct(){
                $this->id                 = 'mobapp-oca-domicilio-envios';
                $this->method_title       = __( 'OCA DOMICILIO');
                $this->method_description = __( 'Cotiza tus envíos con MOBAPP ENVÍOS' );
                $this->init(); 
                $this->enabled            = $this->get_option( 'enabled' );
                $this->title              = "OCA DOMICILIO";
            }
            public function init(){
                $this->init_form_fields(); 
                add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
            }
            public function calculate_shipping( $package = Array() ){
                $csv_data = get_transient('datos_csv_oca_dom'); //TOMO INFO TEMPORAL
                $provincia = $package[ 'destination' ][ 'state' ];
                $peso_carrito = WC()->cart->get_cart_contents_weight();
                $cost = '0';
                $titulo = $this->get_option('title');
                if ($csv_data) {
                    $filas = explode("\n", $csv_data);
                    foreach ($filas as $fila) {
                        $columnas = str_getcsv($fila);
                        $titulo_tabla = $columnas[0];
                        $codigo = $columnas[2];
                        $peso_min = floatval($columnas[3]);
                        $peso_max = floatval($columnas[4]);
                        $precio = $columnas[5];
                        if ( $provincia == $codigo && $peso_carrito <= $peso_max && $peso_carrito > $peso_min){
                            $titulo = $titulo_tabla;
                            $cost = $precio;
                        }
                    }
                }
                else{
                    echo 'No se pudo leer csv';
                }
                mobapp_append_featured_tooltip($titulo, $this);
                $this->add_rate( array(
                    'id'     => $this->id,
                    'label'  => $titulo,
                    'cost'   => $cost
                ));
                $this->add_rate( $package ); // Aplicar precio
            }
            public function init_form_fields() {
                $zonas = array();                                           //
                $delivery_zones = WC_Shipping_Zones::get_zones();           //
                foreach ((array) $delivery_zones as $key => $the_zone ) {   //
                    $zonas[] = $the_zone['zone_name'];                      //
                }                                                           //
                $form_fields = array(
                  'enabled' => array(
                     'title'   => esc_html__('Activar/Desactivar', 'mobapp-oca-domicilio-envios' ),
                     'type'    => 'checkbox',
                     'label'   => esc_html__('Activar método de envío', 'mobapp-oca-domicilio-envios'  ),
                     'default' => 'yes'
                  ),
                  'title' => array(
                     'title'       => esc_html__('Título del envío en caso de peso excedente', 'mobapp-oca-domicilio-envios' ),
                     'type'        => 'text',
                     'description' => esc_html__('Ingresar título del envío', 'mobapp-oca-domicilio-envios'  ),
                     'default'     => esc_html__('A COTIZAR - PESO SUPERIOR A 30KG - OCA A DOMICILIO', 'mobapp-oca-domicilio-envios' ),
                     'desc_tip'    => true
                  ),
                   'ocultar_para_zonas' => array(
                    'title'             => __( 'Ocultar envío para zonas específicas', 'mobapp-oca-domicilio-envios' ),
                    'type'              => 'multiselect',
                    'class'             => 'wc-enhanced-select',
                    'css'               => 'width: 400px;',
                    'default'           => '',
                    'description'       => __( 'Seleccionar zonas en las que se ocultará este envío', 'mobapp-oca-domicilio-envios' ),
                    'options'           => $zonas,
                    'desc_tip'          => true,
                    'custom_attributes' => array(
                        'data-placeholder' => __( 'Seleccionar zonas', 'mobapp-oca-domicilio-envios' )
                    ),
                 )
                );
                mobapp_add_common_fields($form_fields);
                $this->form_fields = $form_fields;
            }
        }   
    }
}
add_filter('woocommerce_shipping_methods','agregar_mobapp_oca_domicilio_envios_method');
function agregar_mobapp_oca_domicilio_envios_method( $methods ){
    $methods['mobapp-oca-domicilio-envios'] = 'WC_MOBAPP_OCA_DOMICILIO_ENVIOS';
    return $methods;
}
/*//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/

add_action( 'woocommerce_shipping_init', 'mobapp_oca_sucursal_envios_init' );
function mobapp_oca_sucursal_envios_init() {
    if ( ! class_exists( 'WC_MOBAPP_OCA_SUCURSAL_ENVIOS' ) ) {
        class WC_MOBAPP_OCA_SUCURSAL_ENVIOS extends WC_Shipping_Method{
            public function __construct(){
                $this->id                 = 'mobapp-oca-sucursal-envios';
                $this->method_title       = __( 'OCA SUCURSAL');
                $this->method_description = __( 'Cotiza tus envíos con MOBAPP ENVÍOS' ); 
                $this->init();
                $this->enabled            = $this->get_option( 'enabled' );
                $this->title              = "OCA SUCURSAL";
            }
            public function init(){
                $this->init_form_fields(); 
                add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
            }
            public function calculate_shipping( $package = Array() ){
                $csv_data = get_transient('datos_csv_oca_suc'); //TOMO INFO TEMPORAL
                $provincia = $package[ 'destination' ][ 'state' ];
                $peso_carrito = WC()->cart->get_cart_contents_weight();
                $cost = '0';
                $titulo = $this->get_option('title');
                if ($csv_data) {
                    $filas = explode("\n", $csv_data);
                    foreach ($filas as $fila) {
                        $columnas = str_getcsv($fila);
                        $titulo_tabla = $columnas[0];
                        $codigo = $columnas[2];
                        $peso_min = floatval($columnas[3]);
                        $peso_max = floatval($columnas[4]);
                        $precio = $columnas[5];
                        if ( $provincia == $codigo && $peso_carrito <= $peso_max && $peso_carrito > $peso_min){
                            $titulo = $titulo_tabla;
                            $cost = $precio;
                        }
                    }
                }
                else{
                    echo 'No se pudo leer csv';
                }
                mobapp_append_featured_tooltip($titulo, $this);
                $this->add_rate( array(
                    'id'     => $this->id,
                    'label'  => $titulo,
                    'cost'   => $cost
                ));
                $this->add_rate( $package ); // Aplicar precio
            }
            public function init_form_fields() {
                $zonas = array();                                           //
                $delivery_zones = WC_Shipping_Zones::get_zones();           //
                foreach ((array) $delivery_zones as $key => $the_zone ) {   //
                    $zonas[] = $the_zone['zone_name'];                      //
                }                                                           //
                $form_fields = array(
                  'enabled' => array(
                     'title'   => esc_html__('Activar/Desactivar', 'mobapp-oca-sucursal-envios' ),
                     'type'    => 'checkbox',
                     'label'   => esc_html__('Activar método de envío', 'mobapp-oca-sucursal-envios'  ),
                     'default' => 'yes'
                  ),
                  'title' => array(
                     'title'       => esc_html__('Título del envío en caso de peso excedente', 'mobapp-oca-sucursal-envios' ),
                     'type'        => 'text',
                     'description' => esc_html__('Ingresar título del envío', 'mobapp-oca-sucursal-envios'  ),
                     'default'     => esc_html__('A COTIZAR - PESO SUPERIOR A 30KG - OCA A SUCURSAL', 'mobapp-oca-sucursal-envios' ),
                     'desc_tip'    => true
                  ),
                   'ocultar_para_zonas' => array(
                    'title'             => __( 'Ocultar envío para zonas específicas', 'mobapp-oca-sucursal-envios' ),
                    'type'              => 'multiselect',
                    'class'             => 'wc-enhanced-select',
                    'css'               => 'width: 400px;',
                    'default'           => '',
                    'description'       => __( 'Seleccionar zonas en las que se ocultará este envío', 'mobapp-oca-sucursal-envios' ),
                    'options'           => $zonas,
                    'desc_tip'          => true,
                    'custom_attributes' => array(
                        'data-placeholder' => __( 'Seleccionar zonas', 'mobapp-oca-sucursal-envios' )
                    ),
                 )
                );
                mobapp_add_common_fields($form_fields);
                $this->form_fields = $form_fields;
            }
        }   
    }
}
add_filter('woocommerce_shipping_methods','agregar_mobapp_oca_sucursal_envios_method');
function agregar_mobapp_oca_sucursal_envios_method( $methods ){
    $methods['mobapp-oca-sucursal-envios'] = 'WC_MOBAPP_OCA_SUCURSAL_ENVIOS';
    return $methods;
}
/*//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/

add_action( 'woocommerce_shipping_init', 'mobapp_urbano_domicilio_envios_init' );
function mobapp_urbano_domicilio_envios_init() {
    if ( ! class_exists( 'WC_MOBAPP_URBANO_DOMICILIO_ENVIOS' ) ) {
        class WC_MOBAPP_URBANO_DOMICILIO_ENVIOS extends WC_Shipping_Method{
            public function __construct(){
                $this->id                 = 'mobapp-urbano-domicilio-envios';
                $this->method_title       = __( 'URBANO DOMICILIO');
                $this->method_description = __( 'Cotiza tus envíos con MOBAPP ENVÍOS' ); 
                $this->init();
                $this->enabled            = $this->get_option( 'enabled' );
                $this->title              = "URBANO DOMICILIO";
            }
            public function init(){
                $this->init_form_fields(); 
                add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
            }
            public function calculate_shipping( $package = Array() ){
                $csv_data = get_transient('datos_csv_urbano_dom'); //TOMO INFO TEMPORAL
                $provincia = $package[ 'destination' ][ 'state' ];
                $peso_carrito = WC()->cart->get_cart_contents_weight();
                $cost = '0';
                $titulo = $this->get_option('title');
                if ($csv_data) {
                    $filas = explode("\n", $csv_data);
                    foreach ($filas as $fila) {
                        $columnas = str_getcsv($fila);
                        $titulo_tabla = $columnas[0];
                        $codigo = $columnas[2];
                        $peso_min = floatval($columnas[3]);
                        $peso_max = floatval($columnas[4]);
                        $precio = $columnas[5];
                        if ( $provincia == $codigo && $peso_carrito <= $peso_max && $peso_carrito > $peso_min){
                            $titulo = $titulo_tabla;
                            $cost = $precio;
                        }
                    }
                }
                else{
                    echo 'No se pudo leer csv';
                }
                mobapp_append_featured_tooltip($titulo, $this);
                $this->add_rate( array(
                    'id'     => $this->id,
                    'label'  => $titulo,
                    'cost'   => $cost
                ));
                $this->add_rate( $package ); // Aplicar precio
            }
            public function init_form_fields() {
                $zonas = array();                                           //
                $delivery_zones = WC_Shipping_Zones::get_zones();           //
                foreach ((array) $delivery_zones as $key => $the_zone ) {   //
                    $zonas[] = $the_zone['zone_name'];                      //
                }                                                           //
                $form_fields = array(
                  'enabled' => array(
                     'title'   => esc_html__('Activar/Desactivar', 'mobapp-urbano-domicilio-envios' ),
                     'type'    => 'checkbox',
                     'label'   => esc_html__('Activar método de envío', 'mobapp-urbano-domicilio-envios'  ),
                     'default' => 'yes'
                  ),
                  'title' => array(
                     'title'       => esc_html__('Título del envío en caso de peso excedente', 'mobapp-urbano-domicilio-envios' ),
                     'type'        => 'text',
                     'description' => esc_html__('Ingresar título del envío', 'mobapp-urbano-domicilio-envios'  ),
                     'default'     => esc_html__('A COTIZAR - PESO SUPERIOR A 30KG - URBANO A DOMICILIO', 'mobapp-urbano-domicilio-envios' ),
                     'desc_tip'    => true
                  ),
                   'ocultar_para_zonas' => array(
                    'title'             => __( 'Ocultar envío para zonas específicas', 'mobapp-urbano-domicilio-envios' ),
                    'type'              => 'multiselect',
                    'class'             => 'wc-enhanced-select',
                    'css'               => 'width: 400px;',
                    'default'           => '',
                    'description'       => __( 'Seleccionar zonas en las que se ocultará este envío', 'mobapp-urbano-domicilio-envios' ),
                    'options'           => $zonas,
                    'desc_tip'          => true,
                    'custom_attributes' => array(
                        'data-placeholder' => __( 'Seleccionar zonas', 'mobapp-urbano-domicilio-envios' )
                    ),
                 )
                );
                mobapp_add_common_fields($form_fields);
                $this->form_fields = $form_fields;
            }
        }   
    }
}
add_filter('woocommerce_shipping_methods','agregar_mobapp_urbano_domicilio_envios_method');
function agregar_mobapp_urbano_domicilio_envios_method( $methods ){
    $methods['mobapp-urbano-domicilio-envios'] = 'WC_MOBAPP_URBANO_DOMICILIO_ENVIOS';
    return $methods;
}
/*//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
