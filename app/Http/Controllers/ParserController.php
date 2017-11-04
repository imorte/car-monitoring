<?php

namespace App\Http\Controllers;


use App\Core\InitParse;

class ParserController extends Controller
{
    public function parse()
    {
        InitParse::parse();
    }
}
