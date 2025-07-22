<?PHP
require_once __DIR__ . "/../../middleware/securityHeader.php";
require_once __DIR__ . "/../../middleware/middleware.php";
$middleware = new middleware;

if(!$middleware->isPostMethod()){
    $middleware->errorResponse(405,"Only POST method is allowed");
    exit;
}

if(!$middleware->isJson()){
    $middleware->errorResponse(415, "Only application/json is accepted");
    exit;
}

if(!$middleware->isAuthCheck()){
    $middleware->errorResponse(401, "Unauthorized");
    exit;
}

if($middleware->isSessionExpired()){
    $middleware->errorResponse(401, "Session expired");
    exit;
}

if(!$middleware->isCsrf()){
    $middleware->errorResponse(403, "Invalid CSRF token");
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$newNickname = trim($input['nickname']);
$newAge = $input['age'];
$newGender = $input['gender'];
$newBio = $input['bio'];

$userId = $_SESSION['user_id'];
$db = require_once __DIR__ . "/../../database/db.php";
$query = "SELECT nickname, age, gender, bio FROM user_profile WHERE user_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

$currentNickname = $row['nickname'];
$currentAge = $row['age'];
$currentGender = $row['gender'];
$currentBio = $row['bio'];

// check user input before update db
if(trim($currentNickname) !== $newNickname){
    if(strlen($newNickname) < 6 || strlen($newNickname) > 12){
        $middleware->errorResponse(400, "New nickname cannot be less than 6 or more than 12 characters");
        exit;
    }

    $query = "UPDATE user_profile SET nickname = ? WHERE user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("si", $newNickname, $userId);
    $stmt->execute();
    $stmt->close();
}

if($currentAge !== $newAge){
    if(!is_int($newAge) || $newAge < 0 || $newAge > 99){
        $middleware->errorResponse(400, "Invalid age");
    exit;
    }

    $query = "UPDATE user_profile SET age = ? WHERE user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $newAge, $userId);
    $stmt->execute();
    $stmt->close();
}

if($currentGender !== $newGender){
    $validGenders = [0, 1, 2];
    if(!in_array($newGender, $validGenders)){
        $middleware->errorResponse(400, "Invalid gender");
        exit;
    }

    $query = "UPDATE user_profile SET gender = ? WHERE user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $newGender, $userId);
    $stmt->execute();
    $stmt->close();
}

// prevent different understanding of empty input vs null for php and db
$normalizedNewBio = $newBio === "" ? null : $newBio;
$normalizedCurrentBio = $currentBio === "" ? null : $currentBio;

if ($normalizedNewBio !== $normalizedCurrentBio) {
    if (!is_null($newBio) && strlen($newBio) > 250) {
        $middleware->errorResponse(400, "Bio too long");
        exit;
    }

    if (is_null($newBio) || $newBio === "") {
        $query = "UPDATE user_profile SET bio = NULL WHERE user_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $userId);
    } else {
        $query = "UPDATE user_profile SET bio = ? WHERE user_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("si", $newBio, $userId);
    }

    $stmt->execute();
    $stmt->close();
}

echo json_encode([
    "success" => true,
    "message" => "Profile updated successfully"
]);
exit;
?>