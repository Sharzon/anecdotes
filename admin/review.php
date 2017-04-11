<?php

namespace Sharzon\Anecdotes;

require_once "../autoload.php";

if (!User::isAuth()) {
    header("Location: /admin/login.php");
    exit;
}

if (isset($_GET['success']) && isset($_GET['type'])) {
    $id = intval($_GET['success']);
    $anecdote = Anecdote::getById($id);
    $anecdote->changeType(intval($_GET['type']));
    $anecdote->accept();
}

if (isset($_GET['decline'])) {
    $id = intval($_GET['decline']);
    $anecdote = Anecdote::getById($id);
    $anecdote->decline();
}

include "../partial/header.php";

?>

<div class="container">
    <div class="col-lg-6 col-lg-offset-3">
        <?php

        $anecdotes = Anecdote::getAllNotAccepted();

        if (count($anecdotes) == 0) {
            ?>
            <h2>There is no any anecdote for review</h2>
            <?php
        } else {
            $types = AnecdoteType::getAll();
        }

        foreach ($anecdotes as $anecdote) {
            ?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php echo $anecdote->getText(); ?>
                </div>
                <div class="panel-footer">
                    <form action="/admin/review.php">
                        <button type="submit" 
                                class="btn btn-success"
                                name="success"
                                value="<?php echo $anecdote->getId(); ?>">Accept</button>
                        <!-- <a href="/admin/review.php?success=<?php echo $anecdote->getId(); ?>" 
                           class="btn btn-success">Accept</a> -->
                        <select name="type">
                            <?php

                            foreach ($types as $type) {
                                if ($anecdote->getType() == $type) {
                                    echo '<option value="'.$type->getId().'" selected>';
                                } else {
                                    echo '<option value="'.$type->getId().'">';
                                }
                                echo $type->getName();
                                echo '</option>';
                            }

                            ?>
                        </select>
                        <button type="submit" 
                                class="btn btn-danger pull-right"
                                name="decline"
                                value="<?php echo $anecdote->getId(); ?>">Decline</button>
                        <!-- <a href="/admin/review.php?decline=<?php echo $anecdote->getId(); ?>" 
                           class="btn btn-danger"></a> -->
                    </form>
                </div>
            </div>
            <?php
        }

        ?>
    </div>
</div>

<?php include "../partial/footer.php"; ?>