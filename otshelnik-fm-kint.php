<?php

/*
    MU Plugin Name: Otshelnik-Fm Kint
    Plugin URI: https://codeseller.ru/products/otshelnik-fm-kint/
    Description: PHP дебаг с помощью Kint и еще несколько возможностей
    Version: 1.0.0
    Author: Otshelnik-Fm
    Author URI: http://across-ocean.otshelnik-fm.ru/
    License: MIT
*/

/*
    Original idea:
        Author: Rokas Šleinius (raveren)
        https://github.com/kint-php/kint
        Kint: debugging helper for PHP developers

    
    Integrations:
        Author WordPress mu-plugin: Otshelnik-Fm
        http://across-ocean.otshelnik-fm.ru/
    
    
    Install:
        /wp-content/mu-plugins/otshelnik-fm-kint.php
        and
        /wp-content/mu-plugins/ot-fm-kint-resource/
    
    
    Usage:
        d($var);       // распечатает переменную
        ddd($var);     // распечатает ее и остановит выполнение. Эквивалент d();die;
        !d($var);      // сразу отобразит объект/переменную в раскрытом виде
        d(1);          // сделает трассировку стека вызовов
        s($var);       // выведет переменную в печатном виде
        sd($var);      // выведет переменную в печатном виде и остановит. Эквивалент s();die;
        
        Kint::enabled($_SERVER['REMOTE_ADDR'] === 'ваш-ip'); // перед вызовом - позволит видеть вывод только вашему айпишнику
        Больше методов отдадки описано в репозитории библиотеки: https://github.com/kint-php/kint
        
        Я добавил тройку своих функций:
        vd($var);      // (мой var_dump) - удобный дебаг вместо print_r или var_dump
        vdd($var);     // аналог vd, но с die; на конце. Когда нужно остановить дальнейшую работу
        vda($var);     // (var_dump admin) - вывод на экран для админа
        vdl($var);     // (var_dump log) - пишем в логи сервера. Когда выводить на экран нам нельзя (или это дебаг ajax запроса например).
*/

// подключаем библиотеку
require WPMU_PLUGIN_DIR.'/ot-fm-kint-resource/kint/Kint.class.php';


// если нужен вывод только для вашего ip - раскомментируйте и впишите свой ip
//Kint::enabled($_SERVER['REMOTE_ADDR'] === '88.108.38.168');

// Темы: aante-light - светлая, original - по умолчанию, solarized - золотистая, solarized-dark - тёмная
Kint::$theme = 'original';



/*
    Ниже мои 4 функции: Свой велосипед - и ездить приятно :)
    когда нужен простой вывод на печать
    когда я хочу загнать результат отладки в логи сервера
    когда я залогинен и админ - чтобы другие авторы и гости мои дебаги не видели
*/


// vd (мой var_dump) - удобный дебаг вместо print_r или var_dump
if(!function_exists('vd')){
	function vd($var){
        echo '<pre>';
        if (!empty($var)){
            print_r($var);
        } else {
            var_dump($var);
        }
        echo '</pre>';
	}
}

// vda (var_dump admin) - вывод на экран для админа
if(!function_exists('vda')){
    function vda($var){
        if( current_user_can('manage_options') ){
            vd($var);
        }
    }
}

// vdd - аналог vd, но с die; на конце. Когда нужно остановить дальнейшую работу
if(!function_exists('vdd')){
    function vdd($var){
        vd($var);
        die;
    }
}

// vdl (var_dump log) - пишем в логи сервера. Когда выводить на экран нам нельзя (или это дебаг ajax запроса например).
if(!function_exists('vdl') ){
    function vdl($var){
        error_log(print_r($var, true));
    }
}


