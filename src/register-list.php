<?php

require_once 'config.php';
// Fetch all users
    $sql = "Select * from users";

    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $users = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
    $stmt->close();
    $connect->close();

?>


<!doctype html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  </head>
  <body>
    <div class="flex flex-col items-center mt-10 space-y-4">
        <button onclick="window.location.href='index.php'" type="button" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-auto">
            Add User
        </button>
        <table class="table-auto w-3/4 border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Name</th>
                    <th class="px-4 py-2 border">Phone</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 border">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($users as $user) : ?>
                    
                <tr>
                    <td class="px-4 py-2 border"><?php echo $i++; ?></td>
                    <td class="px-4 py-2 border"><?php echo $user['name']?></td>
                    <td class="px-4 py-2 border"><?php echo $user['phone']?></td>
                    <td class="px-4 py-2 border"><?php echo $user['email']?></td>
                    <td class="px-4 py-2 border">
                        <a href="edit.php?id=<?php echo $user['id']; ?>" class="text-blue-500 hover:text-blue-700">Edit</a> |
                        <a href="delete.php?id=<?php echo $user['id']; ?>" class="text-red-500 hover:text-red-700 ml-2" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                    </th>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
  </body>
</html>