<?php
/**
 * Created by PhpStorm.
 * User: Oskar
 * Date: 2016-01-03
 * Time: 13:26
 */

namespace market\Http\Controllers\Markets\buy;


use market\helper\markets\buy;
use market\helper\markets\sell;
use market\Http\Controllers\Markets\baseDeleteController;

class deleteController extends baseDeleteController
{
    public function __construct()
    {
        $this->marketHelper = new buy();
    }
}