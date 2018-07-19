<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $seoTitle;

    protected $level = 1;

    protected function render(String $view = null, $data = [], $mergeData = []) {
        if (empty($data['seo']['title'])) {
            $data['seo']['title'] = $this->seoTitle;
        }
        return view($view, $data, $mergeData);
    }
}
