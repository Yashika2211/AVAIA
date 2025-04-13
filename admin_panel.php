<?php
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.html');
    exit();
}

$db = new PDO('mysql:host=localhost;dbname=AVAIA', 'root', '');

if (isset($_POST['delete_confession'])) {
    $stmt = $db->prepare('DELETE FROM confessions WHERE id = ?');
    $stmt->execute([$_POST['confession_id']]);
}

$stmt = $db->query('SELECT * FROM confessions ORDER BY timestamp DESC');
$confessions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AVAIA - Admin Panel</title>
    <style>
        body {
            background: linear-gradient(45deg, #000000, #2d0036);
            color: #fff;
            font-family: 'Arial', sans-serif;
            margin: 0;
            min-height: 100vh;
            padding: 2rem;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .confession-table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ff69b4;
        }

        th {
            background: rgba(255, 105, 180, 0.3);
        }

        .delete-btn {
            background: #ff0055;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .delete-btn:hover {
            background: #ff0077;
            transform: scale(1.05);
        }

        .logout-btn {
            background: #800080;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: #a000a0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="admin-header">
            <h1>Admin Panel</h1>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
        
        <table class="confession-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Content</th>
                    <th>Tag</th>
                    <th>Votes</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($confessions as $confession): ?>
                <tr>
                    <td><?= $confession['id'] ?></td>
                    <td><?= htmlspecialchars($confession['content']) ?></td>
                    <td><?= htmlspecialchars($confession['tag']) ?></td>
                    <td>👍 <?= $confession['upvotes'] ?> | 👎 <?= $confession['downvotes'] ?></td>
                    <td><?= $confession['timestamp'] ?></td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="confession_id" value="<?= $confession['id'] ?>">
                            <button type="submit" name="delete_confession" class="delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>