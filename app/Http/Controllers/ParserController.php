<?php

namespace App\Http\Controllers;

use App\Core\Sites;

class ParserController extends Controller
{
    public function parse()
    {
        (new Sites\Drom('Mitsubishi', 'Lancer'))->handle();
    }
}
