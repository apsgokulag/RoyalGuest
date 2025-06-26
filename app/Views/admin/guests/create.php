<?= $this->include('admin/layout/header') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Add New Guest</h2>
    <a href="<?= site_url('admin/guests'); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Guests
    </a>
</div>

<div class="card">
    <div class="card-body">
    <form action="<?= site_url('admin/guests/store') ?>" method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name *</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name *</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="room_number" class="form-label">Room Number</label>
                        <input type="text" class="form-control" id="room_number" name="room_number">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="check_in_date" class="form-label">Check-in Date</label>
                        <input type="date" class="form-control" id="check_in_date" name="check_in_date">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="check_out_date" class="form-label">Check-out Date</label>
                        <input type="date" class="form-control" id="check_out_date" name="check_out_date">
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status">
                    <option value="reserved">Reserved</option>
                    <option value="checked_in">Checked In</option>
                    <option value="checked_out">Checked Out</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Guest
            </button>
        </form>
    </div>
</div>

<?= $this->include('admin/layout/footer') ?>