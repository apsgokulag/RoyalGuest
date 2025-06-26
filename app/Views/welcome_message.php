<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hotel Guest Services</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="/assets/js/script.js" defer></script>
</head>
<style>
    body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(to right, #00b4db, #0083b0);
    color: #fff;
    display: flex;
    height: 100vh;
    align-items: center;
    justify-content: center;
    margin: 0;
}

.container {
    text-align: center;
    padding: 40px;
    background-color: rgba(255,255,255,0.1);
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
}

p {
    margin-bottom: 30px;
    font-size: 1.2rem;
}

.buttons .btn {
    text-decoration: none;
    padding: 12px 25px;
    font-size: 1rem;
    margin: 10px;
    border-radius: 6px;
    background-color: #fff;
    color: #0083b0;
    transition: 0.3s ease;
    display: inline-block;
}

.buttons .btn:hover {
    background-color: #005f73;
    color: #fff;
}

.btn-secondary {
    background-color: #004e64;
    color: #fff;
}
</style>
<body>
    <div class="container">
        <h1>Welcome to Hotel Guest Services</h1>
        <p>Manage guests, service requests, and streamline your hotel operations.</p>
        <div class="buttons">
            <a href="<?= base_url('/login') ?>" class="btn">Login</a>
            <a href="<?= base_url('/signup') ?>" class="btn btn-secondary">Signup</a>
        </div>
    </div>
</body>
</html>
<script>
    // Example: Add fade-in effect
document.addEventListener("DOMContentLoaded", function () {
    const container = document.querySelector('.container');
    container.style.opacity = 0;
    setTimeout(() => {
        container.style.transition = 'opacity 1s';
        container.style.opacity = 1;
    }, 100);
});

</script>