<?php
use App\Models\GuestModel;
use App\Models\ServiceRequestModel;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Hotel Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Welcome, <?= session()->get('username'); ?>!</h2>
        <a href="<?= site_url('logout'); ?>" class="btn btn-danger">Logout</a>
    </div>

    <!-- Guests Table -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Guest List</div>
        <button><a href="<?= site_url('admin/guests/create'); ?>" class="btn btn-light btn-sm">Create Guest</a></button>
        <button><a href="<?= site_url('admin/guests'); ?>" class="btn btn-light btn-sm">Manage Guest</a></button>
        <div class="card-body">
            <?php if (!empty($guests)): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Name</th>
                            <th>Room</th>
                            <th>Email</th>
                            <th>Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($guests as $guest): ?>
                            <tr>
                                <td><?= esc($guest['id']); ?></td>
                                <td><?= esc(GuestModel::getGuestName($guest['id'])); ?></td>
                                <td><?= esc(GuestModel::getGuestRoom($guest['id'])); ?></td>
                                <td><?= esc($guest['email']); ?></td>
                                <td><?= esc($guest['phone']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No guests found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Service Requests Table -->
    <div class="card">
        <div class="card-header bg-success text-white">Service Requests</div>
        <div class="card-body">
                    <button><a href="<?= site_url('admin/requests/create'); ?>" class="btn btn-light btn-sm">Create Service</a></button>
            <?php if (!empty($requests)): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Guest</th>
                            <th>Request</th>
                            <th>Status</th>
                            <th>Update Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $request): ?>
                            <tr>
                                <td><?= esc($request['id']); ?></td>
                                <td><?= esc(GuestModel::getGuestName($request['guest_id'])); ?></td>
                                <td><?= esc(ServiceRequestModel::getService($guest['id'])); ?></td>
                                <td><?= esc(ServiceRequestModel::getServiceStatus($guest['id'])); ?></td>
                                <td>
                                    <form action="<?= site_url('admin/update-status/' . $request['id']); ?>" method="post" class="d-flex">
                                        <select name="status" class="form-select form-select-sm me-2">
                                            <option <?= $request['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                            <option <?= $request['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                            <option <?= $request['status'] == 'Done' ? 'selected' : '' ?>>Done</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No service requests found.</p>
            <?php endif; ?>
        </div>
    </div>

</div>
</body>
</html>
