<div class="content pd-y-20 pd-lg-y-30 pd-xl-y-40">
    <div class="container">
        <div class="content-body pd-lg-l-40 d-flex flex-column">
            <div class="content-breadcrumb">
                <span>Home</span> <span>Settings</span>
            </div>
            <h2 class="content-title">Account Setting</h2>

            <div class="row">
                <div class="col-sm-8">
                    <?php view()->load('partials.message') ?>
                </div>
            </div>

            <div class="row row-sm">
                <div class="col-sm-6">
                    <form action="<?= url('/settings') ?>" method="post">
                        <h5>Update Profile</h5>
                        <div class="form-group">
                            <label for="name" class="control-label">User Name</label>
                            <input id="name" class="form-control" placeholder="Name" name="name" type="text" value="<?= auth()->user('name') ?>">
                        </div>
                        <div class="form-group">
                            <label for="email" class="control-label">Email Address</label>
                            <input id="email" class="form-control" placeholder="Email" name="email" type="email" value="<?= auth()->user('email') ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                <div class="col-sm-6">
                    <form action="<?= url('/settings/password') ?>" method="post">
                        <h5>Update Password</h5>
                        <div class="form-group">
                            <label for="old_password" class="control-label">Old Password</label>
                            <input id="old_password" class="form-control" placeholder="Old Password" name="old_password" type="password">
                        </div>
                        <div class="form-group">
                            <label for="password" class="control-label">New Password</label>
                            <input id="password" class="form-control" placeholder="New Password" name="password" type="password">
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>