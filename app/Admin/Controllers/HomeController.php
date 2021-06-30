<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Web\Tools\Web\Tools\Web\Dashboard\Web\Dashboard\Web\Auth\Controller;
use Dcat\Admin\Layout\Content;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        return $content->header('GDCN Admin');
    }
}
