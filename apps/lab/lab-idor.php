<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: /index.php");
    exit();
}
require(__DIR__ . "/backend/conn.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>IDOR - Vulnarena Lab</title>
<link rel="stylesheet" href="/style/main.css?b=6">
<link rel="stylesheet" href="/style/idor.css?b=16">
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
    <form method="GET" action="/lab-idor.php" style="margin: 0;">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($_SESSION['user_id'] ?? ''); ?>">
        <button type="submit" class="reset-btn">RESET</button>
    </form>
</nav>

<h2 class="page-title">IDOR</h2>

<div class="container">
    <div class="content">
        <?php
            if(isset($_GET['id'])){
                $id = $_GET['id'];
                if(empty($id)){
                    echo "<p>Parameter ID tidak boleh kosong.</p>";
                    exit;
                }
                $query ="SELECT users.username, users.nickname, user_info.email, user_info.phone, user_info.address, user_info.birthdate, user_info.ktp, user_info.rekening
                        FROM users
                        LEFT JOIN user_info ON users.user_id = user_info.user_id
                        WHERE users.user_id = '$id';
                        ";
                $result = $db->query($query);

                if($result->num_rows > 0){
    $row = $result->fetch_assoc();
    echo '
<div class="profile-card">
  <h3>Profil Pengguna</h3>
  <div class="profile-row"><span class="profile-label">Nickname:</span> ' . htmlspecialchars($row['nickname']) . '</div>
  <div class="profile-row"><span class="profile-label">Username:</span> ' . htmlspecialchars($row['username']) . '</div>
  <div class="profile-row"><span class="profile-label">Email:</span> ' . htmlspecialchars($row['email']) . '</div>
  <div class="profile-row"><span class="profile-label">No. HP:</span> ' . htmlspecialchars($row['phone']) . '</div>
  <div class="profile-row"><span class="profile-label">Alamat:</span> ' . htmlspecialchars($row['address']) . '</div>
  <div class="profile-row"><span class="profile-label">Tanggal Lahir:</span> ' . htmlspecialchars($row['birthdate']) . '</div>
  <div class="profile-row"><span class="profile-label">No. KTP:</span> ' . htmlspecialchars($row['ktp']) . '</div>
  <div class="profile-row"><span class="profile-label">No. Rekening:</span> ' . htmlspecialchars($row['rekening']) . '</div>
</div>
';


    exit;
}
 else {
                    echo "<p>ID tidak ditemukan.</p>";
                    exit;
                }
            } else {
                echo "<p>Parameter ID tidak boleh kosong.</p>";
                exit;
            }
            $db->close();
        ?>
    </div>
</div>
</body>
</html>