<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

$db = new PDO('mysql:host=localhost;dbname=AVAIA', 'root', '');
$tag_filter = isset($_GET['tag']) ? $_GET['tag'] : '';

$query = 'SELECT * FROM confessions';
if ($tag_filter) {
    $query .= ' WHERE tag = ?';
}
$query .= ' ORDER BY timestamp DESC';

$stmt = $db->prepare($query);
if ($tag_filter) {
    $stmt->execute([$tag_filter]);
} else {
    $stmt->execute();
}
$confessions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AVAIA - Confessions</title>
    <!-- Add these links -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Quicksand:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        body {
            background: linear-gradient(45deg, #000000, #2d0036);
            color: #fff;
            font-family: 'Quicksand', sans-serif;
            margin: 0;
            min-height: 100vh;
            padding: 2rem;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text y="50" x="50" text-anchor="middle" fill="rgba(255,105,180,0.05)" style="font-size: 80px;">🌟</text></svg>') 0 0/150px 150px,
                url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text y="50" x="50" text-anchor="middle" fill="rgba(128,0,128,0.05)" style="font-size: 80px;">💭</text></svg>') 75px 75px/150px 150px;
            pointer-events: none;
            z-index: -1;
        }

        .logo {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.5rem;
            background: linear-gradient(45deg, #ff69b4, #800080);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .confession-form {
            background: rgba(0, 0, 0, 0.8);
            border: 2px solid rgba(255,105,180,0.3);
            padding: 2rem;
            border-radius: 20px;
            position: relative;
            overflow: hidden;
        }

        .confession-form::before {
            content: '✨';
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            animation: sparkle 2s linear infinite;
        }

        @keyframes sparkle {
            0% { transform: rotate(0deg) scale(1); }
            50% { transform: rotate(180deg) scale(1.2); }
            100% { transform: rotate(360deg) scale(1); }
        }

        .confession-card {
            background: rgba(0, 0, 0, 0.8);
            border: 1px solid rgba(255,105,180,0.2);
            padding: 2rem;
            border-radius: 15px;
            position: relative;
            transition: all 0.3s ease;
            animation: slideIn 0.5s ease-out;
        }

        .confession-card::after {
            content: '💌';
            position: absolute;
            bottom: 10px;
            right: 10px;
            font-size: 20px;
            opacity: 0.5;
        }

        @keyframes slideIn {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .submit-btn {
            background: linear-gradient(45deg, #ff69b4, #800080);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .submit-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(255,105,180,0.5);
        }

        .tag {
            background: linear-gradient(45deg, #ff69b4, #800080);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        select {
            background: rgba(0,0,0,0.6);
            border: 2px solid #ff69b4;
            color: white;
            padding: 10px;
            border-radius: 10px;
            margin: 10px 0;
            cursor: pointer;
        }

        textarea {
            background: rgba(0,0,0,0.6);
            border: 2px solid #ff69b4;
            color: white;
            padding: 15px;
            border-radius: 10px;
            min-height: 100px;
            font-family: 'Quicksand', sans-serif;
        }

        .vote-btn {
            background: rgba(255,105,180,0.1);
            border: 2px solid #ff69b4;
            padding: 8px 20px;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .vote-btn:hover {
            background: #ff69b4;
            transform: translateY(-2px);
        }

        /* Add cute decorative elements */
        .container::before {
            content: '🦄';
            position: fixed;
            top: 20px;
            left: 20px;
            font-size: 30px;
            animation: bounce 2s infinite;
        }

        .container::after {
            content: '🌈';
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 30px;
            animation: wave 3s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        @keyframes wave {
            0% { transform: rotate(0deg); }
            50% { transform: rotate(15deg); }
            100% { transform: rotate(0deg); }
        }
    </style>
</head>
<body>
    <!-- Add this right after the <body> tag -->
    <nav style="
        background: rgba(0, 0, 0, 0.8);
        padding: 1rem;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 0 20px rgba(255, 0, 255, 0.2);
    ">
        <div class="logo" style="font-size: 1.5rem; color: #ff69b4;">AVAIA</div>
        <div class="nav-links">
            <a href="confessions.php" style="color: #fff; text-decoration: none; margin: 0 1rem;">Home</a>
            <?php if ($_SESSION['is_admin']): ?>
                <a href="admin_panel.php" style="color: #fff; text-decoration: none; margin: 0 1rem;">Admin Panel</a>
            <?php endif; ?>
            <a href="logout.php" style="color: #ff69b4; text-decoration: none; margin: 0 1rem;">Logout</a>
        </div>
    </nav>
    <div class="container">
        <h1>Share Your Confession</h1>
        <form action="submit_confession.php" method="POST" class="confession-form">
            <textarea name="content" placeholder="Type your confession here..." required></textarea>
            <select name="tag" required>
                <option value="">Select a tag</option>
                <option value="love">Love</option>
                <option value="study">Study</option>
                <option value="friendship">Friendship</option>
                <option value="campus">Campus Life</option>
                <option value="other">Other</option>
            </select>
            <button type="submit">Submit Confession</button>
        </form>

        <div class="confessions-list">
            <?php foreach ($confessions as $confession): ?>
            <div class="confession-card">
                <span class="tag"><?= htmlspecialchars($confession['tag']) ?></span>
                <p><?= htmlspecialchars($confession['content']) ?></p>
                <div class="votes">
                    <button class="vote-btn" onclick="vote(<?= $confession['id'] ?>, 'up')">
                        👍 <?= $confession['upvotes'] ?>
                    </button>
                    <button class="vote-btn" onclick="vote(<?= $confession['id'] ?>, 'down')">
                        👎 <?= $confession['downvotes'] ?>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
    function vote(confessionId, voteType) {
        fetch(`vote.php?confession_id=${confessionId}&vote_type=${voteType}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
    }
    </script>
</body>
</html>