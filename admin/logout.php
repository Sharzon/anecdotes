<?php

namespace Sharzon\Anecdotes;

require_once "../autoload.php";

User::logout();

header('Location: /');
exit;
