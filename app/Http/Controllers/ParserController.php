<?php

namespace App\Http\Controllers;

use App\Core\Sites;

class ParserController extends Controller
{
    public function parse()
    {
        $drom = new Sites\Drom('Mitsubishi', 'Lancer');
        $drom->handle();
    }
}
