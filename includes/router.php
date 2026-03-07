<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| NORMALIZER
|--------------------------------------------------------------------------
*/
function normalizeUri(string $uri): string
{
    $uri = parse_url($uri, PHP_URL_PATH);
    return strtolower(trim($uri, '/'));
}

function notFound(): void
{
    http_response_code(404);
    renderView('404');
    exit;
}

/*
|--------------------------------------------------------------------------
| DYNAMIC ROUTE RESOLVER
|
| URI Pattern → { scope, action, id }
|
|  /                        → scope=events,  action=index,    id=null
|  /events                   → scope=events,  action=index,    id=null
|  /events/create            → scope=events,  action=create,   id=null
|  /events/6/detail          → scope=events,  action=detail,   id=6
|  /events/6/edit            → scope=events,  action=edit,     id=6
|  /events/6/register        → scope=events,  action=register, id=6
|  /users/login              → scope=users,   action=login,    id=null
|  /users/profile            → scope=users,   action=profile,  id=null
|--------------------------------------------------------------------------
*/
function resolveRoute(string $uri): ?array
{
    // Root → default
    if ($uri === '') {
        return ['scope' => 'events', 'action' => 'index', 'id' => null];
    }

    $segments = explode('/', $uri);
    $count    = count($segments);

    $scope = $segments[0]; // เช่น events, users

    // /scope  → index
    if ($count === 1) {
        return ['scope' => $scope, 'action' => 'index', 'id' => null];
    }

    // /scope/{action}  เช่น events/create, users/login
    if ($count === 2 && !is_numeric($segments[1])) {
        return ['scope' => $scope, 'action' => $segments[1], 'id' => null];
    }

    // /scope/{id}/{action}  เช่น events/6/edit, events/6/detail
    if ($count === 3 && is_numeric($segments[1])) {
        return ['scope' => $scope, 'action' => $segments[2], 'id' => (int)$segments[1]];
    }

    return null;
}

/*
|--------------------------------------------------------------------------
| AUTH CHECK
|--------------------------------------------------------------------------
*/
function isPublicRoute(string $uri): bool
{
    return in_array($uri, PUBLIC_ROUTES, true);
}

/*
|--------------------------------------------------------------------------
| DISPATCHER
|--------------------------------------------------------------------------
*/
function dispatch(string $uri, string $method): void
{
    $uri    = normalizeUri($uri);
    $method = strtoupper($method);
    
    $route = resolveRoute($uri);

    if (!$route) {
        notFound();
    }

    if (!in_array($method, ALLOW_METHODS, true)) {
        notFound();
    }

    $currentRoutePath = $route['scope'] . '/' .$route['action'];

    if($uri === ' '){$currentRoutePath = '';}

    // 🔐 Auth Check ทุกคนวิ่งผ่านที่นี้ เราไม่ต้อง require Auth
        
    if (!isPublicRoute($currentRoutePath) && empty($_SESSION['user_id'])) {
        header('Location: /users/login');
        exit;
    }

   

    ['scope' => $scope, 'action' => $action, 'id' => $id] = $route;

    // โหลดไฟล์ controllers/{scope}/{action}.php
    $file = ROUTE_DIR . "/{$scope}/{$action}.php";

    if (!file_exists($file)) {
        notFound();
    }

    // ส่ง context เข้าไปให้ไฟล์ action ใช้งานได้เลย
    $context = [
        'method' => $method,
        'id'     => $id,
        'scope'  => $scope,
        'action' => $action,
    ];

    /* var_dump($context); // Debug: ดู context ที่ส่งไป */

    require $file; // ไฟล์รับ $context แล้วทำงานได้เลย
}