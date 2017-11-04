<?php
/**
 * Created by PhpStorm.
 * User: ONLY. Digital Agency | Kirill
 * Date: 08/12/2017
 * Time: 15:01
 */

namespace App\Core;

class InitParse {
    public static function parse() {
        (new Sites\Drom('Mitsubishi', 'Lancer'))->handle();
        (new Sites\Drom('Ford', 'Mondeo'))->handle();
    }
}