<?php

require_once 'config.php';

$message = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = trim($_POST['email']);

    $stmt = $connect->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if($user){
        $token = bin2hex(random_bytes(50));
        $expire = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $stmt = $connect->prepare("UPDATE users SET reset_token = ?, token_expiry = ? WHERE email = ?");
        $stmt->bind_param('sss', $token, $expire, $email);
        $stmt->execute();

        $resetLink = "http://localhost:8080/reset-password.php?token=$token";

        $message = "A password reset link has been sent to your email. Please check your inbox.";
    }else {
        $message = "No user found with this email address.";
    }
}

?>

<!doctype html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Forgot Password</title>
  </head>
  <body>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        
        <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" method="POST" action="forgot-password.php">
               <?php if($message): ?>
                    <p class="text-green-500 text-xs italic"><?= $message ?></p>
                <?php endif; ?> 
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Email
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" name="email" type="email" placeholder="email" />
                <?php if(!empty($error['email'])): ?>
                    <p class="text-red-500 text-xs italic"><?= $error['email'] ?></p>
                <?php endif; ?>
            </div>

            
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="submit">
                    Reset Password
                </button>
            </div>
        </form>
        
    </div>

  </body>
</html>