<?php

namespace Sharzon\Anecdotes;

require_once "../autoload.php";

if (User::isAuth()) {
    header("Location: /admin/review.php");
    exit;
}

if (isset($_POST['login']) && isset($_POST['pass'])) {
    $login = htmlspecialchars($_POST['login']);
    $pass = htmlspecialchars($_POST['pass']);

    $user = User::auth($login, $pass);
    if ($user) {
        header("Location: /admin/review.php");
        exit;
    } else {
        $wrong_pair = true;
    }
}

include "../partial/header.php";

if ($wrong_pair) {
    ?>
    <div class="alert alert-danger">
        You wrote wrong login and password pair
    </div>
    <?php
}
?>
    
<form action="/admin/login.php" method="post">
    <div class="form-group">
        <label for="login">Login</label>
        <input type="text" class="form-control" name="login">
    </div>
    <div class="form-group">
        <label for="pass">Password</label>
        <input type="password" class="form-control" name="pass">
    </div>
    <button type="submit">Sign in</button>
</form>


<?php include "../partial/footer.php"; ?>
