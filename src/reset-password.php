<?php

require_once 'config.php';
$token = isset($_GET['token']) ?? '';
$message = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $token = trim($_POST['token']);
    $newPassword = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    $stmt = $connect->prepare("SELECT *FROM users WHERE reset_token = ? AND token_expiry > NOW()");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if($user){
        $stmt = $connect->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE id = ?");
        $stmt->bind_param('si', $newPassword, $user['id']);
        $stmt->execute();
        $message = "Password reset successfully! <a href='login.php'>Login</a>";
    }else {
        $message = "Invalid or expired token.";
    }


}

?>

<!doctype html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Reset Password</title>
  </head>
  <body>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <?php if ($token) : ?>
        <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" method="POST" action="reset-password.php">
               <?php if($message): ?>
                    <p class="text-green-500 text-xs italic"><?= $message ?></p>
                <?php endif; ?> 
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Password
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" name="password" type="password" placeholder="password" />
                <?php if(!empty($error['password'])): ?>
                    <p class="text-red-500 text-xs italic"><?= $error['password'] ?></p>
                <?php endif; ?>
            </div>

            
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="submit">
                    Reset Password
                </button>
            </div>
        </form>
        <?php else: ?>
            <p>Invalid token</p>
        <?php endif; ?>
        
    </div>

  </body>
</html>