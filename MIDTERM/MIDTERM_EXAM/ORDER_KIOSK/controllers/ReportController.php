<?php
session_start();
require_once '../models/Order.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $start = $_POST['start'] ?? null;
  $end = $_POST['end'] ?? null;

  if (!$start || !$end || strtotime($start) > strtotime($end)) {
    $_SESSION['error'] = "Invalid date range.";
    header('Location: ../views/reports.php');
    exit();
  }

  $_SESSION['report_data'] = [
    'orders' => Order::filter($start, $end),
    'total' => Order::total($start, $end),
    'start' => $start,
    'end' => $end
  ];

  header('Location: ../views/report_preview.php');
  exit();
}
?>