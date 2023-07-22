<?php
$total_pages = $total ?? 0;
$page = request('page', 1);
$URL = request()->path();
?>

<?php if ($total_pages > 1): ?>
<ul class="pagination mt-2">
    <?php if ($page > 1): ?>
    <li class="page-item prev"><a class="page-link" href="<?= $URL ?>?page=<?php echo $page - 1 ?>">Prev</a></li>
    <?php endif; ?>

    <?php if ($page > 3): ?>
    <li class="page-item start"><a class="page-link" href="<?= $URL ?>?page=1">1</a></li>
    <li class="page-item dots">...</li>
    <?php endif; ?>

    <?php if ($page - 1 > 0): ?><li class="page-item page"><a class="page-link" href="<?= $URL ?>?page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a></li><?php endif; ?>

    <li class="page-item current-page disabled"><a class="page-link" href="<?= $URL ?>?page=<?php echo $page ?>"><?php echo $page ?></a></li>

    <?php if ($page + 1 < $total_pages + 1): ?><li class="page-item page"><a class="page-link" href="<?= $URL ?>?page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a></li><?php endif; ?>

    <?php if ($page < $total_pages - 2): ?>
    <li class="page-item dots">...</li>
    <li class="page-item end"><a class="page-link" href="<?= $URL ?>?page=<?php echo $total_pages ?>"><?php echo $total_pages ?></a></li>
    <?php endif; ?>

    <?php if ($page < $total_pages): ?>
    <li class="page-item next"><a class="page-link" href="<?= $URL ?>?page=<?php echo $page + 1 ?>">Next</a></li>
    <?php endif; ?>
</ul>
<?php endif; ?>