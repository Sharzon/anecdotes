<?php

namespace Sharzon\Anecdotes;

require_once "../autoload.php";

if (User::isAuth()) {
    header("Location: /admin/review.php");
} else {
    header("Location: /admin/login.php");
}
exit;
