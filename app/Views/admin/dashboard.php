<div>
    <h1>
        jii
    </h1>
<div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Welcome, <?= session()->get('username'); ?>!</h2>
        <a href="<?= site_url('logout'); ?>" class="btn btn-danger">Logout</a>
    </div>
</div>