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
</head>
<body>

<div class="container">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="/">Anecdotes</a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a href="#" 
                           class="dropdown-toggle" 
                           data-toggle="dropdown"
                           role="button"
                           aria-haspopup="true"
                           aria-expanded="false">Type <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="/">No type</a></li>
                            <li role="separator" class="divider"></li>
                            <?php

                            $types = AnecdoteType::getAll();
    
                            foreach ($types as $type) {
                                if (count(Anecdote::getAllAcceptedByType($type)) > 0) {
                                    echo '<li><a href="/?type='.$type->getId().'">';
                                    echo $type->getName();
                                    echo '</a></li>';
                                }
                            }
                            ?>
                        </ul>
                    </li>
                </ul>
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