<?php
namespace controller;
use core as c;

class Home extends Base
{
    public function run()
    {

        $output = <<< EOF
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58148 [200]: /
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58149 [200]: /www/vendor/bootstrap/css/bootstrap.min.css
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58150 [200]: /www/vendor/bootstrap/css/bootstrap-theme.min.css
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58151 [200]: /www/vendor/jquery/jquery.min.js
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58152 [200]: /www/application/css/home.css
[Wed Aug 13 16:39:34 2014] 127.0.0.1:58153 [200]: /www/application/img/favicon.ico
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58148 [200]: /
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58149 [200]: /www/vendor/bootstrap/css/bootstrap.min.css
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58150 [200]: /www/vendor/bootstrap/css/bootstrap-theme.min.css
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58151 [200]: /www/vendor/jquery/jquery.min.js
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58152 [200]: /www/application/css/home.css
[Wed Aug 13 16:39:34 2014] 127.0.0.1:58153 [200]: /www/application/img/favicon.ico[Wed Aug 13 16:39:33 2014] 127.0.0.1:58148 [200]: /
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58149 [200]: /www/vendor/bootstrap/css/bootstrap.min.css
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58150 [200]: /www/vendor/bootstrap/css/bootstrap-theme.min.css
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58151 [200]: /www/vendor/jquery/jquery.min.js
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58152 [200]: /www/application/css/home.css
[Wed Aug 13 16:39:34 2014] 127.0.0.1:58153 [200]: /www/application/img/favicon.ico
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58148 [200]: /
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58149 [200]: /www/vendor/bootstrap/css/bootstrap.min.css
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58150 [200]: /www/vendor/bootstrap/css/bootstrap-theme.min.css
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58151 [200]: /www/vendor/jquery/jquery.min.js
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58152 [200]: /www/application/css/home.css
[Wed Aug 13 16:39:34 2014] 127.0.0.1:58153 [200]: /www/application/img/favicon.ico
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58148 [200]: /
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58149 [200]: /www/vendor/bootstrap/css/bootstrap.min.css
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58150 [200]: /www/vendor/bootstrap/css/bootstrap-theme.min.css
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58151 [200]: /www/vendor/jquery/jquery.min.js
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58152 [200]: /www/application/css/home.css
[Wed Aug 13 16:39:34 2014] 127.0.0.1:58153 [200]: /www/application/img/favicon.ico[Wed Aug 13 16:39:33 2014] 127.0.0.1:58148 [200]: /
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58149 [200]: /www/vendor/bootstrap/css/bootstrap.min.css
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58150 [200]: /www/vendor/bootstrap/css/bootstrap-theme.min.css
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58151 [200]: /www/vendor/jquery/jquery.min.js
[Wed Aug 13 16:39:33 2014] 127.0.0.1:58152 [200]: /www/application/css/home.css
[Wed Aug 13 16:39:34 2014] 127.0.0.1:58153 [200]: /www/application/img/favicon.ico
EOF;
        $aConfig = c\Config::get('repos');
        $aData = [
            'title' => 'Just Merge',
            'output' => $output,
            'repos' => $aConfig['data']
        ];

        return $aData;
    }

    protected function getBody()
    {
        return 'Home';
    }

}

# end of this file
