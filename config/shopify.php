<?php
/**
 * Created by PhpStorm.
 * User: nhockizi
 * Date: 4/1/20
 * Time: 18:24
 */

return [
    'client_id'     => env('SPF_API_KEY', null),
    'client_secret' => env('SPF_SECRET_KEY', null),
    'scopes'        => ['read_products', 'read_themes', 'write_script_tags'],
    'redirect_url'  => env('SPF_REDIRECT_URL', null),
];
