<?php
require_once __DIR__ . "/../../middleware/securityHeader.php";
require_once __DIR__ . "/../../middleware/middleware.php";
$middleware = new middleware();

// Soal lengkap
$questions = [
  [
    "id" => "q1",
    "question" => "Apa perbedaan utama antara HTTP dan HTTPS?",
    "options" => [
      "a" => "HTTPS lebih cepat dari HTTP",
      "b" => "HTTP menggunakan SSL/TLS, HTTPS tidak",
      "c" => "HTTPS menggunakan enkripsi, HTTP tidak",
      "d" => "HTTP hanya untuk API, HTTPS untuk website"
    ],
    "correctAnswer" => "c",
    "explanation" => "HTTPS mengenkripsi komunikasi menggunakan SSL/TLS, sedangkan HTTP tidak menyediakan enkripsi."
  ],
  [
    "id" => "q2",
    "question" => "Fungsi dari cookie dalam konteks web adalah:",
    "options" => [
      "a" => "Menyimpan data sementara di server",
      "b" => "Menghapus cache browser",
      "c" => "Menyimpan data sesi pengguna di browser",
      "d" => "Menyimpan kredensial pengguna secara terenkripsi di server"
    ],
    "correctAnswer" => "c",
    "explanation" => "Cookie menyimpan data sesi di browser pengguna seperti session ID."
  ],
  [
  "id" => "q3",
  "question" => "Apa tujuan utama dari Red Team Operations?",
  "options" => [
    "a" => "Menjaga infrastruktur dari serangan DDoS",
    "b" => "Menguji ketahanan sistem dan mengidentifikasi celah keamanan",
    "c" => "Melakukan backup rutin terhadap data",
    "d" => "Menambal sistem operasi dengan patch terbaru"
  ],
  "correctAnswer" => "b",
  "explanation" => "Tujuan utama Red Team Operations adalah untuk mengidentifikasi dan mengeksploitasi celah keamanan demi menguji ketahanan sistem dan kesiapan Blue Team."
  ],
  [
    "id" => "q4",
    "question" => "Apa yang dimaksud dengan tahap 'Persistence' dalam Red Team Engagement?",
    "options" => [
      "a" => "Mengumpulkan data intelijen dari sumber terbuka",
      "b" => "Menjalankan exploit satu kali",
      "c" => "Menanamkan akses jangka panjang ke sistem target",
      "d" => "Melakukan penghapusan jejak (clean-up)"
    ],
    "correctAnswer" => "c",
    "explanation" => "Persistence adalah tahap di mana Red Team berusaha mempertahankan akses jangka panjang di sistem target agar bisa kembali di kemudian hari."
  ],
  [
    "id" => "q5",
    "question" => "Apa tugas utama dari Blue Team dalam konteks keamanan siber?",
    "options" => [
      "a" => "Melakukan penetrasi ke sistem untuk mencari kelemahan",
      "b" => "Menganalisis log dan merespon insiden keamanan",
      "c" => "Mengembangkan exploit dan malware baru",
      "d" => "Melakukan rekayasa sosial terhadap pengguna internal"
    ],
    "correctAnswer" => "b",
    "explanation" => "Blue Team bertugas untuk mendeteksi, merespon, dan memitigasi insiden keamanan melalui analisis log, monitoring jaringan, dan respon insiden."
  ],
  [
    "id" => "q6",
    "question" => "Apa manfaat dari pendekatan Purple Teaming dalam keamanan siber?",
    "options" => [
      "a" => "Meningkatkan efisiensi kerja tim pengembang",
      "b" => "Menghindari penggunaan alat-alat keamanan yang mahal",
      "c" => "Kolaborasi antara Red Team dan Blue Team untuk menguatkan pertahanan",
      "d" => "Mengurangi kebutuhan akan dokumentasi insiden"
    ],
    "correctAnswer" => "c",
    "explanation" => "Purple Teaming adalah kolaborasi antara Red Team dan Blue Team untuk menguji sistem secara langsung dan mempercepat penguatan pertahanan organisasi."
  ],
  [
    "id" => "q7",
    "question" => "Apa fungsi utama dari tag <a> dalam HTML?",
    "options" => [
      "a" => "Menambahkan gambar ke halaman",
      "b" => "Membuat tautan ke halaman lain atau URL",
      "c" => "Menentukan ukuran teks",
      "d" => "Membuat daftar bullet"
    ],
    "correctAnswer" => "b",
    "explanation" => "Tag <a> digunakan untuk membuat hyperlink yang mengarah ke halaman lain atau URL tertentu."
  ],
  [
    "id" => "q8",
    "question" => "Mengapa memahami HTML penting sebelum mempelajari CSS dan JavaScript?",
    "options" => [
      "a" => "Karena HTML lebih sulit dari CSS dan JavaScript",
      "b" => "Karena HTML digunakan untuk styling dan animasi",
      "c" => "Karena HTML membentuk struktur dasar halaman web yang digunakan oleh CSS dan JavaScript",
      "d" => "Karena HTML menentukan bagaimana server merespon permintaan pengguna"
    ],
    "correctAnswer" => "c",
    "explanation" => "HTML membentuk struktur dasar halaman web. CSS dan JavaScript bekerja di atas struktur ini untuk menambahkan gaya dan interaktivitas, sehingga pemahaman HTML sangat penting sebagai fondasi."
  ],
  [
    "id" => "q9",
    "question" => "Apa tujuan utama dari fungsi `htmlspecialchars()` dalam konteks mencegah XSS?",
    "options" => [
      "a" => "Menghapus semua tag HTML dari input",
      "b" => "Menjalankan script dari user dengan aman",
      "c" => "Mengubah karakter berbahaya menjadi entitas teks",
      "d" => "Memfilter angka dari input"
    ],
    "correctAnswer" => "c",
    "explanation" => "Fungsi `htmlspecialchars()` mengubah karakter seperti < dan > menjadi entitas teks, sehingga script tidak dijalankan di browser."
  ],
  [
    "id" => "q10",
    "question" => "Manakah dari pilihan berikut yang merupakan teknik perlindungan terhadap serangan XSS?",
    "options" => [
      "a" => "Menambahkan lebih banyak tag HTML",
      "b" => "Menggunakan Content-Security-Policy (CSP)",
      "c" => "Mematikan JavaScript di browser user",
      "d" => "Mengizinkan semua input dari user"
    ],
    "correctAnswer" => "b",
    "explanation" => "Content-Security-Policy (CSP) membatasi sumber script yang dapat dijalankan di browser dan membantu mencegah XSS."
  ],
  [
    "id" => "q11",
    "question" => "Apa tujuan dari penggunaan fungsi `realpath()` dalam mencegah Path Traversal?",
    "options" => [
      "a" => "Menampilkan isi file secara lengkap",
      "b" => "Menghindari duplikasi file di server",
      "c" => "Memastikan file berada dalam direktori yang diizinkan",
      "d" => "Menghapus file dari direktori"
    ],
    "correctAnswer" => "c",
    "explanation" => "Fungsi `realpath()` menghasilkan path absolut yang dapat dicek apakah masih berada dalam direktori yang diizinkan, sehingga mencegah akses file di luar folder."
  ],
  [
    "id" => "q12",
    "question" => "Manakah contoh serangan Path Traversal yang benar?",
    "options" => [
      "a" => "?file=config.php",
      "b" => "?file=../../../../../etc/passwd",
      "c" => "?file=index.html",
      "d" => "?file=/var/www/files/"
    ],
    "correctAnswer" => "b",
    "explanation" => "Payload `../../../../../etc/passwd` digunakan untuk mencoba mengakses file sistem yang berada di luar direktori web server (contoh khas serangan Path Traversal)."
  ],
  [
    "id" => "q13",
    "question" => "Mengapa query berikut rentan terhadap serangan SQL Injection?\n`SELECT * FROM users WHERE id = '$id'`",
    "options" => [
      "a" => "Karena menggunakan fungsi mysqli_query",
      "b" => "Karena query dijalankan dalam mode debug",
      "c" => "Karena input user langsung dimasukkan tanpa validasi",
      "d" => "Karena tidak ada koneksi ke database"
    ],
    "correctAnswer" => "c",
    "explanation" => "Query ini rentan karena memasukkan input user secara langsung tanpa filter atau proteksi, memungkinkan penyerang menyisipkan perintah SQL berbahaya."
  ],
  [
    "id" => "q14",
    "question" => "Manakah cara paling aman untuk menangani input user pada query SQL?",
    "options" => [
      "a" => "Menghapus karakter tanda kutip dari input",
      "b" => "Menggunakan URL encode",
      "c" => "Menggunakan prepared statement dengan bind_param",
      "d" => "Membatasi panjang input menjadi 10 karakter"
    ],
    "correctAnswer" => "c",
    "explanation" => "Prepared statement dengan `bind_param()` mencegah input user dieksekusi sebagai perintah SQL, sehingga mencegah SQL Injection secara efektif."
  ],
  [
    "id" => "q15",
    "question" => "Apa yang membedakan Reflected Command Injection dengan Blind Command Injection?",
    "options" => [
      "a" => "Reflected menghasilkan error, sedangkan Blind tidak",
      "b" => "Reflected menampilkan hasil langsung ke user, Blind tidak menampilkan hasil langsung",
      "c" => "Reflected hanya terjadi di browser lama",
      "d" => "Blind hanya bisa dilakukan secara lokal"
    ],
    "correctAnswer" => "b",
    "explanation" => "Reflected Command Injection menampilkan hasil injeksi secara langsung di browser, sedangkan Blind Command Injection tidak menampilkan hasilnya secara langsung dan membutuhkan cara lain untuk verifikasi."
  ],
  [
    "id" => "q16",
    "question" => "Manakah metode paling aman untuk mencegah Command Injection pada input user?",
    "options" => [
      "a" => "Menghapus semua spasi dari input",
      "b" => "Menggunakan basename() saja",
      "c" => "Menggunakan escapeshellarg() untuk memfilter input",
      "d" => "Menampilkan pesan error ke user"
    ],
    "correctAnswer" => "c",
    "explanation" => "Fungsi escapeshellarg() memastikan bahwa input user tidak dapat memecah perintah dan menyisipkan perintah baru, sehingga mencegah command injection."
  ],
  [
    "id" => "q17",
    "question" => "Apa yang dimaksud dengan Insecure Direct Object Reference (IDOR)?",
    "options" => [
      "a" => "User dapat melihat objek database langsung dari backend",
      "b" => "User bisa langsung mengakses data milik user lain dengan mengganti parameter",
      "c" => "Server memberikan akses root tanpa login",
      "d" => "Kode aplikasi memiliki terlalu banyak objek"
    ],
    "correctAnswer" => "b",
    "explanation" => "IDOR adalah celah di mana user bisa mengganti parameter seperti ID di URL untuk mengakses data milik orang lain tanpa otorisasi."
  ],
  [
    "id" => "q18",
    "question" => "Apa solusi terbaik untuk mencegah Broken Access Control?",
    "options" => [
      "a" => "Menyembunyikan URL admin dari user",
      "b" => "Menggunakan JavaScript untuk memfilter role",
      "c" => "Selalu validasi role dan hak akses di server-side",
      "d" => "Menonaktifkan semua fitur admin"
    ],
    "correctAnswer" => "c",
    "explanation" => "Validasi akses harus dilakukan di sisi server karena sisi klien bisa dimanipulasi dengan mudah oleh penyerang."
  ],
  [
    "id" => "q19",
    "question" => "Mengapa penting untuk menggunakan fungsi seperti `htmlspecialchars()`, `prepared statements`, dan `realpath()` dalam pengembangan web yang aman?",
    "options" => [
      "a" => "Untuk membuat website terlihat lebih modern",
      "b" => "Untuk mencegah celah keamanan umum seperti XSS, SQL Injection, dan Path Traversal",
      "c" => "Agar website lebih cepat diakses pengguna",
      "d" => "Agar halaman dapat ditampilkan di semua jenis browser"
    ],
    "correctAnswer" => "b",
    "explanation" => "Fungsi-fungsi tersebut digunakan untuk menyaring atau membatasi input agar tidak dimanfaatkan untuk serangan seperti XSS, SQLi, dan Path Traversal."
  ],
  [
    "id" => "q20",
    "question" => "Apa manfaat melakukan kolaborasi antara Red Team dan Blue Team dalam bentuk Purple Teaming?",
    "options" => [
      "a" => "Mengurangi biaya pelatihan tim keamanan",
      "b" => "Meningkatkan estetika dashboard keamanan",
      "c" => "Menggabungkan simulasi serangan dan pertahanan secara langsung untuk memperkuat sistem",
      "d" => "Meningkatkan jumlah alat keamanan yang digunakan"
    ],
    "correctAnswer" => "c",
    "explanation" => "Purple Teaming memungkinkan Red dan Blue Team bekerja sama dalam waktu nyata untuk menguji, mendeteksi, dan memperbaiki kelemahan sistem secara lebih efisien."
  ]

    // Tambahkan sampai q20
];

// Handle GET: kirim soal
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

  // cek session
    if ($middleware->isSessionExpired()) {
    session_unset();
    session_destroy();
    $middleware->errorResponse(401, "Session expired");
    exit;
  }
  if (!$middleware->isAuthCheck()) {
    $middleware->errorResponse(401, "Login diperlukan untuk melihat soal");
    exit;
  }
  // Pastikan hanya JSON yang diterima
  header("Content-Type: application/json");
  // Nonaktifkan MIME sniffing untuk keamanan
  header("X-Content-Type-Options: nosniff");
  //nonaktifkan cache
  header("Cache-Control: no-cache, no-store, must-revalidate");
  header("Pragma: no-cache");
  header("Expires: 0");

  // Hapus jawaban & penjelasan dari respon GET
  $cleaned = array_map(function ($q) {
    return [
      "id" => $q["id"],
      "question" => $q["question"],
      "options" => $q["options"]
    ];
  }, $questions);

  echo json_encode([
    "success" => true,
    "questions" => $cleaned
  ]);
  exit;
}

// Handle POST: proses jawaban dan kirim detail
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Cek apakah session sudah expired
  if ($middleware->isSessionExpired()) {
    session_unset();
    session_destroy();
    $middleware->errorResponse(401, "Session expired");
    exit;
  }
  // Pastikan hanya JSON yang diterima
  if (!$middleware->isJson()) {
    $middleware->errorResponse(415, "Hanya permintaan dengan format JSON yang diterima");
    exit;
  }
  // pastikan user sudah login
  if (!$middleware->isAuthCheck()) {
    $middleware->errorResponse(401, "Akses ditolak: silakan login terlebih dahulu");
    exit;
  }

  $inputRaw = file_get_contents("php://input");
  $input = json_decode($inputRaw, true);
// Cek apakah input valid
  if (!is_array($input) || !isset($input['answers']) || !is_array($input['answers'])) {
    $middleware->errorResponse(400, "Format JSON tidak valid atau jawaban tidak sesuai");
    exit;
  }
// Cek apakah jawaban sesuai dengan soal yang ada
  $answers = $input['answers'];
  $score = 0;
  $details = [];
// Validasi jawaban
  foreach ($questions as $q) {
    $id = $q['id'];
    $correctKey = $q['correctAnswer'];
    $isCorrect = isset($answers[$id]) && $answers[$id] === $correctKey;
    if ($isCorrect) $score++;
// Simpan detail jawaban
    $details[] = [
      "id" => $id,
      "question" => $q['question'],
      "correct" => $q['options'][$correctKey],
      "explanation" => $q['explanation']
    ];
  }

  // Pastikan hanya JSON yang diterima
  header("Content-Type: application/json");
  // Nonaktifkan MIME sniffing untuk keamanan
  header("X-Content-Type-Options: nosniff");
  //nonaktifkan cache
  header("Cache-Control: no-cache, no-store, must-revalidate");
  header("Pragma: no-cache");
  header("Expires: 0");

  echo json_encode([
    "success" => true,
    "score" => $score,
    "total" => count($questions),
    "details" => $details
  ]);
  exit;
}

// Jika method tidak dikenali
$middleware->errorResponse(405, "Method tidak diizinkan");
exit;
