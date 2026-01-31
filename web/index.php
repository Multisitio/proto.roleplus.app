<?php
const APP_CHARSET = 'UTF-8';
const APP_PATH = 'C:/xampp/htdocs/proto.roleplus.app/private/';
const CMD_PATH = '/usr/local/bin/';
const CORE_PATH = 'C:/xampp/htdocs/KumbiaPHP/core/';
const DOMAIN = 'http:///proto.roleplus.vh/';
const PRODUCTION = false;
const PUB_PATH = 'C:/xampp/htdocs/proto.roleplus.app/web/';
const PUBLIC_PATH = '/';
const VENDOR_PATH = 'C:/xampp/htdocs/vendor/';
$url = $_SERVER['PATH_INFO'] ?? '/';
require CORE_PATH.'kumbia/bootstrap.php';
