<?php
use App\Models\GuestModel;
?>

<!-- Custom Styles -->
 <?= $this->include('admin/layout/header') ?>
<style>
    body {
        background: #f4f7fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .container {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-top: 40px;
    }

    h2 {
        font-weight: 600;
        color: #343a40;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        font-weight: 500;
        padding: 8px 16px;
        border-radius: 6px;
        transition: 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .table th, .table td {
        vertical-align: middle !important;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f9ff;
        transition: 0.2s;
    }

    .badge {
        font-size: 0.85em;
        padding: 6px 12px;
        border-radius: 20px;
    }

    .btn-warning {
        font-size: 0.8em;
        padding: 5px 10px;
    }

    .alert-success {
        border-left: 5px solid #28a745;
    }

    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
        }
    }
</style>

<!-- Content -->
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Service Requests</h2>
        <a href="<?= site_url('admin/requests/create') ?>" class="btn btn-primary shadow-sm">+ New Request</a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Guest</th>
                    <th>Room No</th>
                    <th>Service Type</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($requests)): ?>
                    <?php foreach ($requests as $index => $request): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= esc(GuestModel::getGuestName($request['id'])) ?></td>
                            <td><?= esc(GuestModel::getGuestRoom($request['id'])) ?></td>
                            <td><?= esc($request['service_type']) ?></td>
                            <td>
                                <?php if ($request['priority'] === 'high'): ?>
                                    <span class="badge bg-danger">High</span>
                                <?php elseif ($request['priority'] === 'medium'): ?>
                                    <span class="badge bg-warning text-dark">Medium</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Low</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                    $statusColors = [
                                        'pending' => 'secondary',
                                        'in_progress' => 'warning text-dark',
                                        'completed' => 'success',
                                        'cancelled' => 'danger',
                                    ];
                                ?>
                                <span class="badge bg-<?= $statusColors[$request['status']] ?? 'light' ?>">
                                    <?= ucwords(str_replace('_', ' ', $request['status'])) ?>
                                </span>
                            </td>
                            <td><?= esc($request['assigned_user'] ?? 'Unassigned') ?></td>
                            <td><?= date('d M Y, h:i A', strtotime($request['created_at'])) ?></td>
                            <td>
                                <a href="<?= site_url('admin/requests/edit/' . $request['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted">No service requests found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <button><a href="<?= site_url('/home') ?>">back</a></button>
    </div>
</div>

<!-- Optional Bootstrap JS (if not already included globally) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
