<?php

namespace Sharzon\Anecdotes;

require_once "autoload.php";

include "partial/header.php";

$page = isset($_GET['page']) ? $_GET['page'] : 1;

?>

    <div class="row">
        <div class="col-lg-6 col-lg-offset-3">
            <?php

            unset($type);
            if (isset($_GET['type']) && is_numeric($_GET['type'])) {
                $type = AnecdoteType::getById($_GET['type']);
                $anecdotes = Anecdote::getPageAcceptedByType($type, $page);

                $pages_count = Anecdote::pagesCountByType($type->getId());
            } else {
                $anecdotes = Anecdote::getPageAccepted($page);

                $pages_count = Anecdote::pagesCount();
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
            
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php

                    if ($page > 1) {
                        $link = '?page='.($page-1);
                        if (isset($type)) {
                            $link .= '&type='.$type->getId();
                        }
                        ?>
                        <li>
                            <a href="<?php echo $link; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php
                    }
                    
                    for ($i = 1; $i <= $pages_count; $i++) {
                        $link = '?page='.$i;
                        if (isset($type)) {
                            $link .= '&type='.$type->getId();
                        }

                        if ($i == $page) {
                            echo '<li class="active">';
                            echo '<a href="'.$link.'">';
                            echo $i.' <span class="sr-only">(current)</span>';
                        } else {
                            echo '<li>';
                            echo '<a href="'.$link.'">'.$i;
                        }
                        echo '</a></li>';
                    }
                    ?>
                    <!-- <li><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li> -->

                    <?php

                    if ($page < $pages_count) {
                        $link = '?page='.($page+1);
                        if (isset($type)) {
                            $link .= '&type='.$type->getId();
                        }
                        ?>
                        <li>
                            <a href="<?php echo $link; ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </nav>

        </div>
    </div>

    

<?php

include "partial/footer.php";

?>