<?php
session_start();

$routes = [
  '/' => '../views/login.php',
  '/login' => '../views/login.php',
  '/logout' => '../controllers/LogoutController.php',
  '/dashboard' => '../views/dashboard.php',
  '/pos' => '../views/pos.php',
  '/products' => '../views/product.php',
  '/users' => '../views/users.php',
  '/report' => '../views/reports.php',
  '/report/preview' => '../views/report_preview.php',
  '/report/generate' => '../controllers/ReportController.php',
  '/product/add' => '../controllers/ProductController.php',
  '/cart/add' => '../controllers/POSController.php',
  '/user/add' => '../controllers/UsersController.php',
];

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$path = rtrim($path, '/');

if (isset($routes[$path])) {
  include $routes[$path];
} else {
  http_response_code(404);
  echo "404 - Page not found";
}