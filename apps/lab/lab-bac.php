<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: /index.php");
    exit();
}

require(__DIR__ . '/backend/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
    $username = $_SESSION['username'];
    $sql = "SELECT isadmin FROM users WHERE username = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($isadmin_db);
    $stmt->fetch();
    $stmt->close();

    setcookie("isAdmin", $isadmin_db, time() + 3600, "/");

    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Broken Access Control - Vulnarena Lab</title>
<link rel="stylesheet" href="/style/main.css?b=3" />
<link rel="stylesheet" href="/style/bac.css?b=3" />
</head>
<body>
<nav class="navbar">
    <h1>
        <a href="/menu.php" style="display: flex; align-items: center; gap: 8px; color: #f8fafc; text-decoration: none; animation: outlineRGB 3s infinite linear;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
            Vulnarena Lab
        </a>
    </h1>
    <form method="POST" style="margin: 0;">
        <button type="submit" class="reset-btn" name="reset">RESET</button>
    </form>
</nav>

<h2 class="page-title">Broken Access Control</h2>

<div class="container">
    <div class="content">
        <?php
            if(!isset($_COOKIE['isAdmin']) || $_COOKIE['isAdmin'] != 1){
                echo '
                    <div style="display:flex;flex-direction:column;align-items:center;gap:10px;padding:20px;">
                        <span style="font-size:2.5rem;">🔒</span>
                        <span style="font-size:1.15rem;font-weight:600;color:#ef4444;margin-bottom:2px;">
                            Akses Ditolak
                        </span>
                        <span style="color:#f1f5f9;font-size:1rem;">
                            Anda tidak memiliki izin untuk mengakses halaman ini.
                        </span>
                    </div>
                    ';
            }
            else{
                echo '
                <div style="padding:20px 24px;border-radius:12px;">
                    <p style="font-size:1.15rem;font-weight:600;margin-bottom:8px;">
                        Selamat datang, <span style="color:#facc15;">' . htmlspecialchars($_SESSION['nickname']) . '</span>!
                    </p>
                    <p style="margin-bottom:0;">
                        <span style="color:#38bdf8;">🛡️ Halaman Admin</span>
                        &mdash; <span style="color:#cbd5e1;">Hanya pengguna dengan hak akses <b>administrator</b> yang dapat membuka halaman ini.</span>
                    </p>
                    <hr style="border:1px solid #334155;margin-top:18px;">
                </div>
                ';

                $query = "SELECT user_id, username, nickname FROM users";
                $result = $db->query($query);

                if ($result && $result->num_rows > 0) {
                    echo "<div class='table-container'><table class='custom-table'>";
                    echo "<tr><th>ID</th><th>Username</th><th>Nickname</th></tr>";
                    while($row = $result->fetch_assoc()){
                    echo "<tr> 
                        <td>" .$row['user_id'] . "</td>
                        <td>" . htmlspecialchars($row['username']) . "</td>
                        <td>" . htmlspecialchars($row['nickname']) . "</td>
                        </tr>";
                    }
                    echo "</table></div>";
                } else {
                    echo "<p>No users found.</p>";
                }
                $db->close();
            }
        ?>
    </div>
</div>
</body>
</html>