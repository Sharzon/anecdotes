<?php

namespace Sharzon\Anecdotes;

require_once "autoload.php";

include "partial/header.php";

?>

    <div class="row">
        <div class="col-lg-3" id="type-list">
            
            <ul class="list-unstyled">
                <?php

                $types = AnecdoteType::getAll();

                foreach ($types as $type) {
                    echo '<li><a href="?type='.$type->getId().'">'.$type->getName().'</a></li>';
                }

                ?>
            </ul>


        </div>
        <div class="col-lg-9">
            <?php

            if (isset($_GET['type']) && is_numeric($_GET['type'])) {
                $type = AnecdoteType::getById($_GET['type']);
                $anecdotes = Anecdote::getAllAcceptedByType($type);
            } else {
                $anecdotes = Anecdote::getAllAccepted();
            }

            foreach ($anecdotes as $anecdote) {
                ?>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <?php echo $anecdote->getText(); ?>
                    </div>
                </div>
                <?php
            }
            ?>
            
        </div>
    </div>



<?php

include "partial/footer.php";

?>