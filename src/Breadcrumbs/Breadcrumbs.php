<?php

namespace Glissmedia\Breadcrumbs;

use Illuminate\Support\Facades\Request;

class Breadcrumbs {

    protected $dir;

    public function __construct()
    {
        $this->dir = realpath(base_path()) . '/resources/views/vendor/breadcrumbs/';
    }

    public function render($data=false, $path=false)
    {

        if(empty($data)){return false;}

        $html = $this->getHtml($data, $path);
        return $html;

    }

    protected function getHtml($data, $template=false)
    {

        $template = !empty($template)
            ? $template
            : config('breadcrumbs.template');

        $host = Request::server('REQUEST_SCHEME') . '://' . Request::getHost();

        $breadcrumbs = array();

        $i = 0;
        if(config('breadcrumbs.first.enable') === true) {
            $breadcrumbs[0]['link'] = $host;
            $breadcrumbs[0]['title'] = !empty(config('breadcrumbs.first.content'))
                ? config('breadcrumbs.first.content')
                : 'Home';
            $breadcrumbs[0]['class'] = !empty(config('breadcrumbs.first.class'))
                ? config('breadcrumbs.first.class')
                : '';
            $i = 1;
        }

        foreach($data as $key=>$b){

            $breadcrumbs[$i]['link'] = $i == 0 ? $host . $key : $breadcrumbs[$i - 1]['link'] . $key;
            $breadcrumbs[$i]['title'] = $b;
            $breadcrumbs[$i]['class'] = '';

            $i++;
        }


        $path = $this->dir . '/' . $template . '.blade.php';
        return view()->file($path, [
            'data' => $breadcrumbs,
            'separator' => config('breadcrumbs.separator'),
        ]);


    }

}