<?php

session_start();
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once 'config.php';

$sql = "SELECT COUNT(*) as total FROM users";
$result = $connect->query($sql);
$total_users = $result->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <div class="w-64 bg-white border-r">
        <div class="p-6 font-bold text-xl border-b">Admin Panel</div>
        <ul class="p-4 space-y-2">
            <li><a href="#" class="block p-2 rounded hover:bg-gray-200">Dashboard</a></li>
            <li><a href="#" class="block p-2 rounded hover:bg-gray-200">Users</a></li>
            <li><a href="#" class="block p-2 rounded hover:bg-gray-200">Settings</a></li>
            <li><a href="logout.php" class="block p-2 rounded text-red-500 hover:bg-red-100">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8">
        <h1 class="text-3xl font-bold mb-6">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded shadow">
                <h2 class="text-xl font-semibold">Total Users</h2>
                <p class="text-2xl mt-2"><?php echo $total_users?></p>
            </div>

            <div class="bg-white p-6 rounded shadow">
                <h2 class="text-xl font-semibold">Pending Tasks</h2>
                <p class="text-2xl mt-2">5</p>
            </div>

            <div class="bg-white p-6 rounded shadow">
                <h2 class="text-xl font-semibold">Reports</h2>
                <p class="text-2xl mt-2">12</p>
            </div>
        </div>
    </div>
</div>

</body>
</html>