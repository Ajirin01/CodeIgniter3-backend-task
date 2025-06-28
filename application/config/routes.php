<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'blog';
$route['blog/filter_posts'] = 'blog/filter_posts';
$route['blog/create'] = 'blog/create';
$route['blog/delete/(:num)'] = 'blog/delete/$1';
$route['blog/edit/(:num)'] = 'blog/edit/$1';


$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
