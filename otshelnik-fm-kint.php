<?php

/*
    MU Plugin Name: Otshelnik-Fm Kint
    Plugin URI: https://codeseller.ru/products/otshelnik-fm-kint/
    Description: PHP дебаг с помощью Kint и еще несколько возможностей
    Version: 2.0.0
    Author: Otshelnik-Fm
    Author URI: https://otshelnik-fm.ru/
    License: MIT
*/

/*

╔═╗╔╦╗╔═╗╔╦╗
║ ║ ║ ╠╣ ║║║ https://otshelnik-fm.ru
╚═╝ ╩ ╚  ╩ ╩

*/


/*
    Original idea:
        Author: Rokas Šleinius (raveren) https://github.com/kint-php/kint

    Integrations:
        Author WordPress mu-plugin: Otshelnik-Fm https://otshelnik-fm.ru/

*/


// подключаем библиотеку v3.0 https://github.com/kint-php/kint/releases
// require __DIR__.'/ot-fm-kint-resource/kint.phar';


// Темы: original.css (default), solarized.css, solarized-dark.css, aante-light.css
//Kint\Renderer\RichRenderer::$theme = 'original.css';



/*
    Ниже мои 5 функций: Свой велосипед - и ездить приятно :)
    когда нужен простой вывод на печать
    когда я хочу загнать результат отладки в логи сервера
    когда я залогинен и админ - чтобы другие авторы и гости мои дебаги не видели
    когда ajax продебажить надо
*/


// vd (мой var_dump) - удобный дебаг вместо print_r или var_dump
if( !function_exists('vd') ){
    function vd($var, $fixed = false){
        $pre_style = '';
        $det_style = '';
        if($fixed){
            $det_style = 'style="position:fixed;top:45px;left:20px;z-index:2000;background-color:#e6decf;padding:10px 8px;line-height:normal;"';
            $pre_style = 'style="height:calc(85vh - 50px);line-height: normal;margin: 10px 0 0;"';
        }

        echo '<details open '.$det_style.'><pre '.$pre_style.'>';
        if (!empty($var)){
            print_r($var);
        } else {
            var_dump($var);
        }
        echo '</pre></details>';
    }
}

// vda (var_dump admin) - вывод на экран для админа
if( !function_exists('vda') ){
    function vda($var, $fixed = false){
        if( current_user_can('manage_options') ){
            vd($var, $fixed);
        }
    }
}

// vdd - аналог vd, но с die; на конце. Когда нужно остановить дальнейшую работу
if( !function_exists('vdd') ){
    function vdd($var){
        vd($var);
        die;
    }
}

// vdl (var_dump log) - пишем в логи сервера. Когда выводить на экран нам нельзя (или это дебаг ajax запроса например).
if( !function_exists('vdl') ){
    function vdl($var){
        error_log( print_r($var, true) );
    }
}


// vdx (var_dump XHR) - для дебага ajax (смотри приходящие данные POST в вкладке XHR браузера) Наглядно: https://yadi.sk/i/CPGuKgwmSQTEKg
if( !function_exists('vdx') ){
    function vdx($var){
        if( defined('DOING_AJAX') && DOING_AJAX ){
            if( is_array($var) ){
                $var['data_type'] = gettype($var);
            }
            if( is_object($var) ){
                $var->data_type = gettype($var);
            }
            else if (is_string($var) || is_int($var) || is_float($var) || is_bool($var) ){
                $var .= ' | data_type: '.gettype($var);
            }
            else if (NULL === $var){
                $var = 'NULL';
                $var .= ' | data_type: NULL';
            }

            wp_send_json_error($var);
        }
    }
}

