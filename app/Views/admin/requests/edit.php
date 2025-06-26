<div class="container mt-4">
    <h2>Edit Service Request Status</h2>

    <form method="post" action="<?= site_url('admin/requests/update/' . $request['id']) ?>">
        <?= csrf_field() ?>

        <div class="form-group">
            <label>Current Status:</label>
            <select name="status" class="form-control" required>
                <option value="pending" <?= $request['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="in_progress" <?= $request['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                <option value="completed" <?= $request['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                <option value="cancelled" <?= $request['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>
        <br>
        <button type="submit" class="btn btn-success">Update Status</button>
        <a href="<?= site_url('admin/requests') ?>" class="btn btn-secondary">Back</a>
    </form>
</div>