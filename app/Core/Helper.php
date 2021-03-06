<?php

namespace Core;

class Helper
{
    public static function redirect($path)
    {
        $protocol = '';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $protocol = 'https';
        } else {
            $protocol = 'http';
        }


        $server_host = $protocol . '://' . $_SERVER['HTTP_HOST'];
        $url = $server_host . Route::getBP() . $path;


        header("Location: $url");
    }

    public static function redirectDownload($path)
    {
        header('Content-type: application/xml');
        header('Content-Disposition: attachment; filename="products.xml"');

        readfile($path);
    }

    public static function getCustomer()
    {
        if (!empty($_SESSION['id'])) {
            return self::getModel('customer')->initCollection()
                ->filter(array('customer_id' => $_SESSION['id']))
                ->getCollection()
                ->selectFirst();
        } else {
            return null;
        }
    }

    public static function getModel($name)
    {
        $name = '\\Models\\' . ucfirst($name);
        $model = new $name();
        return $model;
    }

    public static function inBasket($id)
    {
        if (isset($_SESSION['products']['basket']['id'])) {
            if (array_key_exists($id, $_SESSION['products']['basket']['id'])) {
                return 1;
            }
        }

        return 0;
    }

    public static function isAdmin()
    {
        if (self::getCustomer()) {
            if (self::getCustomer()['admin_role'] === 1) {
                return 1;
            }
        }

        return 0;
    }
}
