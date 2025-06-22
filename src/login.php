<?php

session_start();
require_once 'config.php';

$error = [];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if(empty($email)){
        $error['email'] = 'Email is requuired';
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error['email'] = 'Invalid email format';
    }

    if(empty($password)){
        $error['password'] = 'Password is required';
    }elseif(strlen($password) < 6){
        $error['password'] = 'Password must be at least 6 characters';
    }

    if(empty($error)){
        $sql = 'SELECT * FROM users WHERE email = ?';
        $stmt = $connect->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if($user){
            if(password_verify($password, $user['password'])){
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['name'];
                header('Location: dashboard.php');
                exit;
            }else{
                $error['password'] = 'Incorrect password';
            }
        }else{
            $error['email'] = 'No user found with this email';
        }
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
        <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" method="POST" action="login.php">
                
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
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="submit">
                    Sign In
                </button>
                <a href="index.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Do not have an account?
                </a>
            </div>
        </form>
    </div>

  </body>
</html>