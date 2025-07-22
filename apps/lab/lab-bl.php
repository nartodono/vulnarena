<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: /index.php");
    exit();
}

if(!isset($_SESSION['balance'])){
    $_SESSION['balance'] = 1500000; // Set initial balance
}

$success = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['reset']) && $_POST['reset'] === 'true') {
        unset($_SESSION['balance']);
        header("Location: /lab-bl.php");
        exit();
    }
    else if(isset($_POST['grand_total'])) {
        $total = intval($_POST['grand_total']);
        $balance = intval($_SESSION['balance']);
        if($total == 0){
            $success = null;
        }
        else if($balance < $total) {
            $success = false;
        }
        else{
            $_SESSION['balance'] = $balance - $total;
            $success = true;
        }
    }
    else{
        http_response_code(400);
        echo "Invalid request.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Business Logic Vulnerability - Vulnarena Lab</title>
<link rel="stylesheet" href="/style/main.css?b=6">
<link rel="stylesheet" href="/style/bl.css?b=6">
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
    <form method="POST" action="/lab-bl.php" style="margin: 0;">
        <input type="hidden" name="reset" value="true">
        <button type="submit" class="reset-btn">RESET</button>
    </form>
</nav>

<h2 class="page-title">Business Logic Vulnerability</h2>

<div class="container">
    <div class="content">
        <?php
        $balance = $_SESSION['balance'];
        
        echo "<p>Saldo kamu: Rp <strong>" . number_format($balance, 0, ',', '.') . "</strong></p><hr>";
        ?>
        <form method="POST">
            <div class='product'>
                <p><strong>Tas Hermos</strong> - Rp 65.000.000 </p>
                <div>
                    <button type='button' onclick='decreaseQty(1)'>-</button>
                    <input type='number' name='qty[1]' id='qty_1' value='0' onchange='updateTotal()'>
                    <button type='button' onclick='increaseQty(1)'>+</button>
                </div>
            </div>

            <div class='product'>
                <p><strong>Iphine 17 Pro Min</strong> - Rp 15.999.000 </p>
                <div>
                    <button type='button' onclick='decreaseQty(2)'>-</button>
                    <input type='number' name='qty[2]' id='qty_2' value='0' onchange='updateTotal()'>
                    <button type='button' onclick='increaseQty(2)'>+</button>
                </div>
            </div>

            <div class='product'>
                <p><strong>TV Politren</strong> - Rp 6.499.000 </p>
                <div>
                    <button type='button' onclick='decreaseQty(3)'>-</button>
                    <input type='number' name='qty[3]' id='qty_3' value='0' onchange='updateTotal()'>
                    <button type='button' onclick='increaseQty(3)'>+</button>
                </div>
            </div>

            <div class='product'>
                <p><strong>Blazer Zarak</strong> - Rp 1.775.000 </p>
                <div>
                    <button type='button' onclick='decreaseQty(4)'>-</button>
                    <input type='number' name='qty[4]' id='qty_4' value='0' onchange='updateTotal()'>
                    <button type='button' onclick='increaseQty(4)'>+</button>
                </div>
            </div>

            <div class='product'>
                <p><strong>Sepatu adadus</strong> - Rp 950.000</p>
                <div>
                    <button type='button' onclick='decreaseQty(5)'>-</button>
                    <input type='number' name='qty[5]' id='qty_5' value='0' onchange='updateTotal()'>
                    <button type='button' onclick='increaseQty(5)'>+</button>
                </div>
            </div>

            <div class='product'>
                <p><strong>Dompet Krocodile</strong> - Rp 425.000</p>
                <div>
                    <button type='button' onclick='decreaseQty(6)'>-</button>
                    <input type='number' name='qty[6]' id='qty_6' value='0' onchange='updateTotal()'>
                    <button type='button' onclick='increaseQty(6)'>+</button>
                </div>
            </div>

            <hr>
            <p><strong>Total:</strong> Rp <span id="grandTotal">0</span></p>
            <input type="hidden" name="grand_total" id="grand_total_input" value="0">
            <button type="submit">Checkout</button>
        </form>
        <div>
            <?php
                if($success === true) {
                    echo "<p style='color: green;'>Pembayaran berhasil! </p>";
                } elseif(isset($success) && $success === false) {
                    echo "<p style='color: red;'>Saldo tidak cukup untuk melakukan pembelian.</p>";
                }
            ?>
        </div>
    </div>
</div>

<script>
function increaseQty(id) {
    let qtyInput = document.getElementById('qty_' + id);
    qtyInput.value = parseInt(qtyInput.value) + 1;
    updateTotal();
}

function decreaseQty(id) {
    let qtyInput = document.getElementById('qty_' + id);
    qtyInput.value = parseInt(qtyInput.value) - 1;
    updateTotal();
}

const hargaProduk = {
    1: 65000000,
    2: 15999000, 
    3: 6499000,
    4: 1775000,
    5: 950000,
    6: 425000
};

function updateTotal() {
    let total = 0;
 
    for (let id in hargaProduk) {
        let qty = parseInt(document.getElementById('qty_' + id).value) || 0;
        total += hargaProduk[id] * qty;
    }
    document.getElementById('grandTotal').textContent = total.toLocaleString('id-ID');
    document.getElementById('grand_total_input').value = total;
}
</script>
</body>
</html>