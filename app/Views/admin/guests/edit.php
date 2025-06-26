<?= $this->include('admin/layout/header') ?>

<h2>Edit Guest</h2>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <div><?= esc($error) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="post" action="<?= site_url('admin/guests/update/' . $guest['id']) ?>">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label>First Name</label>
        <input type="text" name="first_name" class="form-control" value="<?= esc($guest['first_name']) ?>">
    </div>
    <div class="mb-3">
        <label>Last Name</label>
        <input type="text" name="last_name" class="form-control" value="<?= esc($guest['last_name']) ?>">
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?= esc($guest['email']) ?>">
    </div>
    <div class="mb-3">
        <label>Phone</label>
        <input type="text" name="phone" class="form-control" value="<?= esc($guest['phone']) ?>">
    </div>
    <div class="mb-3">
        <label>Room Number</label>
        <input type="text" name="room_number" class="form-control" value="<?= esc($guest['room_number']) ?>">
    </div>
    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="reserved" <?= $guest['status'] == 'reserved' ? 'selected' : '' ?>>Reserved</option>
            <option value="checked_in" <?= $guest['status'] == 'checked_in' ? 'selected' : '' ?>>Checked In</option>
            <option value="checked_out" <?= $guest['status'] == 'checked_out' ? 'selected' : '' ?>>Checked Out</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Update Guest</button>
    <a href="<?= site_url('admin/guests') ?>" class="btn btn-secondary">Cancel</a>
</form>
<?= $this->include('admin/layout/footer') ?>
