<?php
/**
 * Created by PhpStorm.
 * User: YoloSwaggerson
 * Date: 11.03.2017
 * Time: 2:20
 */
spl_autoload_register(function ($className) {
    // Получаем путь к файлу из имени класса
    $filename = strtolower($className) . '.php';
    // определяем класс и находим для него путь
    $expArr = explode('_', $className);
    if(empty($expArr[1]) OR $expArr[1] == 'Base')
        $folder = 'classes';
    else
    {
        switch(strtolower($expArr[0]))
        {
            case 'controller':
                $folder = 'controllers';
                break;

            case 'model':
                $folder = 'models';
                break;

            default:
                $folder = 'classes';
                break;
        }
    }
    // путь до класса
    $file = SITE_PATH . $folder . DS . $filename;
    // проверяем наличие файла
    if (file_exists($file))
        include ($file);

    // подключаем файл с классом
});