<?php
$className = '\app\model\\'.ucfirst($this->link).'Model';
$model = new $className();
$count = $model->getItemsCount()['COUNT'];
$pages = ceil($count / 10);
if (isset($this->page) && $pages > 1):
?>
<nav aria-label="Stránkování">
    <ul class="pagination">
        <?php

        if ($this->page != 0) {
            echo '<li class="page-item"><a class="page-link"';
            echo ' href="/~novp19/'.$this->link.'/'.($this->page-1).'">Předchozí</a></li>';
        }

        for($i = 0; $i < $pages; $i++) {
            echo '<li class="page-item"><a class="page-link"';
            echo ' href="/~novp19/'.$this->link.'/'.($i).'">'.($i+1).'</a></li>';
        }


        if (($pages - $this->page) != 1) {
            echo '<li class="page-item"><a class="page-link"';
            echo ' href="/~novp19/'.$this->link.'/'.($this->page+1).'">Další</a></li>';
        }

        ?>
    </ul>
</nav>
<?php endif; ?>
