<?php

require_once 'config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])){
    $name = trim($_POST['name']) ?? '';
    $phone = trim($_POST['phone']) ?? '';
    $email = trim($_POST['email']) ?? '';
    $password = trim($_POST['password']) ?? '';
    $created_at = date('Y-m-d H:i:s');
    $confirm_password = trim($_POST['confirm_password']) ?? '';

   
    $error = [];

    // Validate required fields
    if(empty($name)){
        $error['name'] = "Name is required.";
    }

    if(empty($phone)){
        $error['phone'] = "Phone is required.";
    }

    if(empty($email)){
        $error['email'] = "Email is required.";
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error['email'] = "Invalid email format.";
    }

    if(empty($password)){
        $error['password'] = "Password is required.";
    }elseif(strlen($password) < 6){
        $error['password'] = "Password must be at least 6 characters.";
    }

    if(empty($confirm_password)){
        $error['confirm_password'] = "Confirm Password is required.";
    }elseif($password !== $confirm_password){
        $error['confirm_password'] = "Passwords do not match.";
    }

    if(empty($error)){
        //Hash the password
        $hash_password = password_hash($password, PASSWORD_BCRYPT);

        $created_at = date('Y-m-d H:i:s');
        // Prepare SQL statement
        $sql = $connect->prepare("INSERT INTO users (name, phone, email, password, created_at) VALUES (?, ?, ?, ?, ?)");
        $sql->bind_param("sssss", $name, $phone, $email, $hash_password, $created_at);

        if ($sql->execute()) {
            echo "<p class='text-green-600'>✅ Registration successful!</p>";
            // Reset form values
            $name = $phone = $email = '';
        } else {
            $error['email'] = '❌ Email already exists or DB error.';
        }

        $sql->close();
    }
}

?>

<!doctype html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  </head>
  <body>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" method="POST" action="index.php">
                <a href="register-list.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline float-right mb-5">
                    User List
                </a>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    Name
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" type="text" placeholder="Full name" />

                <?php if (!empty($error['name'])): ?>
                    <p class="text-red-500 text-xs italic"><?= $error['name'] ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="phone">
                    Phone
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="phone" name="phone" type="tel" placeholder="phone" />
                <?php if(!empty($error['phone'])): ?>
                    <p class="text-red-500 text-xs italic"><?= $error['phone'] ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Email
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" name="email" type="email" placeholder="email" />
                <?php if(!empty($error['email'])): ?>
                    <p class="text-red-500 text-xs italic"><?= $error['email'] ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Password
                </label>
                <input class="shadow appearance-none border border-red-500 rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" name="password" type="password" placeholder="******************" />
                <?php if(!empty($error['password'])): ?>
                    <p class="text-red-500 text-xs italic"><?= $error['password'] ?></p>
                <?php endif; ?>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm_password">
                    Confirm Password
                </label>
                <input class="shadow appearance-none border border-red-500 rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="confirm_password" name="confirm_password" type="password" placeholder="******************" />
                <?php if(!empty($error['confirm_password'])): ?>
                    <p class="text-red-500 text-xs italic"><?= $error['confirm_password'] ?></p>
                <?php endif; ?>
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="submit">
                    Sign Up
                </button>
                <a href="login.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Already have an account?
                </a>
            </div>
        </form>
    </div>

  </body>
</html>