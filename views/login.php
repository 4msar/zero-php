<div class="card">
    <h1 class="logo">
        <!-- <img class="logo-img" src="<?= assets('img/logo.svg') ?>" alt="Logo"> -->
        <?= APP_NAME ?>
    </h1>
    <div class="signin-header">
        <h2>Welcome back!</h2>
        <h4>Please sign in to continue</h4>
        <form action="" method="post">
            <div class="form-group">
                <label>Email</label>
                <input class="form-control" name="email" placeholder="Enter your email" type="email">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input class="form-control" name="password" placeholder="Enter your password" type="password">
            </div>

            <?php if ($error = flash('login-error')) : ?>
                <strong style="color: red;"><?= $error ?></strong>
            <?php endif ?>

            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
        </form>
    </div>
</div>