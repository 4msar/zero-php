<?php view()->load('layouts.header') ?>
<div class="all-center">
    <div class="erorr-page text-center">
        <h1 class="title"><?= $code ?? 404 ?></h1>
        <h2 class="quote"><?= $message ?? 'Page not found.' ?></h2>
        <div class="explanation">
            <small>Back to <a href='/'>Home</a>.</small>
        </div>
    </div>
</div>
<?php view()->load('layouts.footer') ?>