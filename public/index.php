<?php
declare(strict_types=1);
session_start();
const INCLUDES_DIR   = __DIR__ . '/../includes';
const ROUTE_DIR      = __DIR__ . '/../routes';
//const CONTROLLER_DIR = __DIR__ . '/../controllers';  // เพิ่ม
const TEMPLATES_DIR  = __DIR__ . '/../templates';
const DATABASES_DIR  = __DIR__ . '/../databases';

require_once DATABASES_DIR . '/database.php';
require_once INCLUDES_DIR  . '/router.php';
require_once INCLUDES_DIR  . '/view.php';
require_once INCLUDES_DIR  . '/helper/helper.php';
require_once INCLUDES_DIR  . '/helper/otp-helper.php';
require_once INCLUDES_DIR  . '/bootstrap.php';
require_once INCLUDES_DIR  . '/Enum.php';

$connection = db_connect();

// ลบ require controller ออกทั้งหมด router จัดการเอง

const PUBLIC_ROUTES = [
    '',               // เผื่อไว้สำหรับหน้าแรกสุด
    'events/index',   // สำคัญมาก! เพราะหน้าแรกถูก resolve เป็นตัวนี้
    'events/detail',  // หน้าที่คุณต้องการให้คนนอกดูได้
    'users/login',
    'users/register'
];
const ALLOW_METHODS  = ['GET', 'POST'];

dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);