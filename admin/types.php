<?php

namespace Sharzon\Anecdotes;

require_once "../autoload.php";

if (!User::isAuth()) {
    header("Location: /admin/login.php");
    exit;
}

if (isset($_POST['name'])) {
    AnecdoteType::create($_POST['name']);

    header("Location: /admin/types.php");
    exit;
}

if (isset($_POST['save']) && isset($_POST['subst_name'])) {
    $type = AnecdoteType::getById($_POST['save']);

    $type->rename($_POST['subst_name']);

    header("Location: /admin/types.php");
    exit;
}

if (isset($_POST['remove']) && isset($_POST['subst_type'])) {
    $type = AnecdoteType::getById($_POST['remove']);

    $type->remove($_POST['subst_type']);

    header("Location: /admin/types.php");
    exit;
}

include "../partial/header.php";

?>

<div class="container">
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">New anecdote type</div>
                <div class="panel-body">
                    <form action="/admin/types.php" method="post">
                        <div class="form-group">
                            <label for="name">Type name</label>
                            <input type="text" name="name" class="form-control">
                        </div>
                        <button class="btn btn-default" type="submit">Create</button>
                    </form>
                </div>
            </div>
            <?php

            $types = AnecdoteType::getAll();

            foreach ($types as $type) {
                ?>
                <form method="post" action="/admin/types.php">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <input type="text" 
                                   name="subst_name"
                                   class="form-control"
                                   value="<?php echo $type->getName(); ?>">
                        </div>
                        <div class="panel-footer form-inline">
                            <button type="submit" 
                                    name="save" 
                                    class="btn btn-success"
                                    value="<?php echo $type->getId(); ?>">
                                    Save
                            </button>
                            <div class="pull-right">
                                <select name="subst_type" 
                                        class="form-control">
                                    <?php

                                    foreach ($types as $sel_type) {
                                        if ($sel_type === $type) {
                                            echo '<option value="'.$sel_type->getId().'" disabled>';
                                        } else {
                                            echo '<option value="'.$sel_type->getId()   .'">';
                                        }
                                            echo $sel_type->getName();
                                            echo '</option>';
                                    }
                                    ?>
                                </select>
                                <button type="submit" 
                                    name="remove"
                                    class="btn btn-danger"
                                    value="<?php echo $type->getId(); ?>">
                                    Remove
                                </button>
                            </div>
                        </div>    

                    </div>
                </form>
                <?php
            }
            ?>
        </div>
    </div>
</div>

<?php include "../partial/footer.php"; ?>