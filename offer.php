<?php

namespace Sharzon\Anecdotes;

require_once "autoload.php";

if (isset($_POST['text']) && isset($_POST['type'])) {
    $text = $_POST['text'];
    $type = AnecdoteType::getById($_POST['type']);

    if (Anecdote::create($text, $type)) {
        header('Location: /offer_completed.php');
        exit;
    }
}


require "partial/header.php";

?>
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <?php

            if (isset($_POST['text']) && isset($_POST['type'])) {
                ?>
                <div class="alert alert-danger">
                    <strong>Your anecdote haven't been saved.</strong> Something went wrong. Try again later.
                </div>
                <?php
            }
            ?>

            <h2>Anecdote</h2>
            <form action="" method="post">
                <div class="form-group">
                    <label for="text">Anecdote text</label>
                    <textarea name="text" 
                              placeholder="Anecdote"
                              class="form-control"
                              rows="6"></textarea>
                </div>
                <div class="form-group">
                    <label for="type">Type</label>
                    <select name="type"
                            class="form-control">
                        <?php

                        $types = AnecdoteType::getAll();

                        foreach ($types as $type) {
                            echo '<option value="'.$type->getId().'">';
                            echo $type->getName();
                            echo '</option>';
                        }

                        ?>
                    </select>
                </div>
                <button type="submit">Offer</button>
            </form>
        </div>
    </div>