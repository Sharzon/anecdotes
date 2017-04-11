<?php

namespace Sharzon\Anecdotes;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Anecdotes</title>
    <base href="http://localhost:8000">
    <link rel="stylesheet" 
          href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" 
          href="/node_modules/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" 
          href="/css/style.css">
</head>
<body>

<div class="container">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="/">Anecdotes</a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <?php

                    if (User::isAuth()) {
                        ?>
                        <li><a href="/admin/review.php">Review</a></li>
                        <li><a href="/admin/types.php">Anecdote types</a></li>
                        <li><a href="/admin/logout.php">Logout</a></li>
                        <?php
                    } else {
                        ?>
                        <li><a href="offer.php">Offer an anecdote</a>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>