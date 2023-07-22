<div class="az-content pd-y-20 pd-lg-y-30 pd-xl-y-40">
    <div class="container">
        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
            <div class="az-content-breadcrumb">
                <span>Home</span> <span>Users</span>
            </div>
            <div class="d-flex justify-content-between align-items-start">
                <h2 class="az-content-title">Users List</h2>
                <?php if (auth()->role('admin')): ?>
                    <button type="button" data-toggle="modal" data-target="#addNew" class="btn btn-sm btn-primary">Add New</button>
                <?php endif; ?>
            </div>
            
            <?php view()->load('partials.message') ?>

            <div class="table-responsive">
                <table class="table table-bordered mg-b-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $item): ?>
                            <tr>
                                <td><?= $item->name ?></td>
                                <td><?= $item->email ?></td>
                                <td><?= $item->role ?></td>
                                <td>
                                    <?php if (auth()->role('admin')): ?>
                                        <form onsubmit="return confirm('Are you sure?')" action="<?= url("users/{$item->id}/delete") ?>" method="POST">
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

            <div class="ht-40"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= url('users') ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name" class="control-label">Enter Name</label>
                        <input id="name" class="form-control" placeholder="Name" name="name" type="text">
                    </div>
                    <div class="form-group">
                        <label for="email" class="control-label">Enter Email</label>
                        <input id="email" class="form-control" placeholder="Email" name="email" type="email">
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label">Password</label>
                        <input id="password" class="form-control" placeholder="Password" name="password" type="password">
                    </div>
                    <div class="form-group">
                        <label for="role" class="control-label">Role</label>
                        <select class="form-control" name="role" id="role">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>