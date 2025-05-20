<?php

namespace App\Controllers;


class AdminPanel extends BaseController
{
    public function menu()
    {
        return view('admin/menu');
    }
}