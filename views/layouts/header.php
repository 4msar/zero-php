<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Meta -->
  <meta name="description" content="Zero PHP MVC framework">
  <meta name="author" content="BootstrapDash">

  <title><?= APP_NAME ?></title>
  <link rel="icon" href="/favicon.ico" />

  <!-- Environment: <?= $GLOBALS['environment'] ?? 'staging' ?> -->
</head>

<body>

  <?php if (auth()->check()) : ?>
    <?php view()->load('layouts.navbar') ?>
  <?php endif ?>