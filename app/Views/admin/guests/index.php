<?= $this->include('admin/layout/header') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Guests Management</h2>
    <a href="<?= site_url('admin/guests/create'); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Guest
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Room</th>
                        <th>Status</th>
                        <th>Requests</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($guests as $guest): ?>
                    <tr>
                        <td><?= $guest['id'] ?></td>
                        <td><?= $guest['first_name'] . ' ' . $guest['last_name'] ?></td>
                        <td><?= $guest['email'] ?></td>
                        <td><?= $guest['phone'] ?></td>
                        <td><?= $guest['room_number'] ?></td>
                        <td>
                            <span class="badge bg-<?= $guest['status'] == 'checked_in' ? 'success' : ($guest['status'] == 'reserved' ? 'warning' : 'secondary') ?>">
                                <?= ucfirst(str_replace('_', ' ', $guest['status'])) ?>
                            </span>
                        </td>
                        <td><?= $guest['check_in_date'] ? date('M j, Y', strtotime($guest['check_in_date'])) : '-' ?></td>
                        <td>
                            <a href="<?= site_url('admin/guests/edit/' . $guest['id']) ?>" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= site_url('admin/guests/delete/' . $guest['id']) ?>" class="btn btn-sm btn-outline-danger" 
                               onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button><a href="<?= site_url('/home') ?>">back</a></button>
        </div>
    </div>
</div>

<?= $this->include('admin/layout/footer') ?>