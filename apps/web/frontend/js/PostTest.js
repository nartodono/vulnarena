let questions = [];
// fungsi untuk mengambil soal dari server
async function fetchQuestions() {
  try {
    const res = await fetch("https://api.vulnarena.space/post-test/result", {
      method: "GET",
      credentials: "include"
    });

    const data = await res.json();

    if (!res.ok || data.success === false || !Array.isArray(data.questions)) {
      throw new Error(data.message || "Gagal memuat soal.");
    }

    questions = data.questions;
    initQuiz();
  } catch (err) {
    showPopup("Kesalahan", err.message || "Gagal mengambil soal.");
  }
}
// fungsi untuk mengacak array
function shuffleArray(array) {
  for (let i = array.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [array[i], array[j]] = [array[j], array[i]];
  }
}
// fungsi untuk menginisialisasi kuis
function initQuiz() {
  shuffleArray(questions);
  questions.forEach(q => {
    const entries = Object.entries(q.options);
    shuffleArray(entries);
    q.shuffledOptions = entries;
  });
  renderQuestions();
}
// fungsi untuk menampilkan pertanyaan dan opsi jawaban
const quizContainer = document.getElementById("quizContainer");

function renderQuestions() {
  quizContainer.innerHTML = "";
  questions.forEach((q, index) => {
    const div = document.createElement("div");
    div.classList.add("mb-6");

    const optionsHtml = q.shuffledOptions.map(([key, value]) => {
      const isSelected = q.userAnswer === key;
      const isCorrect = q.correctAnswer === key;

      let mark = "";
      let colorClass = "text-white";

      if (q.userAnswer !== undefined) {
        if (isCorrect && isSelected) {
          mark = "✅";
          colorClass = "text-green-400 font-semibold";
        } else if (isSelected && !isCorrect) {
          mark = "❌";
          colorClass = "text-red-400 font-semibold";
        } else if (isCorrect) {
          colorClass = "text-green-300";
        }
      }

      return `
        <label class="block ${colorClass}">
          <input type="radio" name="${q.id}" value="${key}" class="mr-2"
            ${isSelected ? "checked" : ""} ${q.userAnswer !== undefined ? "disabled" : ""}>
          ${mark} ${value}
        </label>
      `;
    }).join("");
// Generate HTML untuk setiap soal
    let extraInfo = "";
    if (q.userAnswer !== undefined && q.correctAnswer !== undefined) {
      const userText = q.options[q.userAnswer] || "(Tidak dijawab)";
      const correctText = q.options[q.correctAnswer] || "(Tidak tersedia)";
      extraInfo = `
        <p class="mt-2 text-sm text-gray-300">
          Jawaban Anda: <span class="font-medium ${q.isCorrect ? "text-green-400" : "text-red-400"}">${userText}</span><br/>
          Jawaban Benar: <span class="font-medium text-green-400">${correctText}</span>
        </p>
      `;
    }

    div.innerHTML = `
      <p class="font-semibold text-white">${index + 1}. ${q.question}</p>
      ${optionsHtml}
      ${extraInfo}
    `;

    quizContainer.appendChild(div);
  });
}
// Event listener untuk mengirim jawaban
document.getElementById("quizForm").addEventListener("submit", async function (e) {
  e.preventDefault();
//  Ambil jawaban pengguna
  const userAnswers = {};
  questions.forEach((q) => {
    const selected = document.querySelector(`input[name="${q.id}"]:checked`);
    userAnswers[q.id] = selected ? selected.value : null;
  });
// Cek apakah ada soal yang belum dijawab
  const unanswered = questions.filter(q => !userAnswers[q.id]);
  if (unanswered.length > 0) {
    const list = unanswered.map((q, i) => `• Soal ${questions.indexOf(q) + 1}`).join("<br/>");
    showPopup("Perhatian", `Masih ada soal yang belum dijawab:<br/><br/>${list}`);
    return;
  }
// Validasi jawaban
  const url = "https://api.vulnarena.space/post-test/result";
  const headers = { "Content-Type": "application/json" };
  const body = JSON.stringify({ answers: userAnswers });
// Mengirim jawaban ke server
  try {
    const res = await fetch(url, {
      method: "POST",
      headers,
      body,
      credentials: "include"
    });
// Cek apakah respons valid
    const contentType = res.headers.get("Content-Type") || "";
    if (!contentType.includes("application/json")) {
      throw new Error("Server tidak mengembalikan format JSON yang valid.");
    }

    const data = await res.json();
// Cek apakah respons mengandung kesalahan
    if (!res.ok || data.success === false) {
      throw new Error(data.message || "Terjadi kesalahan dari server.");
    }

    const percent = (data.score / data.total) * 100;
    let color = "text-red-500";
    let message = "Jangan menyerah! Yuk belajar lagi.";

    if (percent >= 80) {
      color = "text-green-500";
      message = "Hebat! Kamu menguasai materi dengan baik.";
    } else if (percent >= 50) {
      color = "text-yellow-400";
      message = "Lumayan, tapi masih bisa ditingkatkan!";
    }
// Tampilkan hasil
    const resultEl = document.getElementById("result");
    resultEl.className = `mt-8 text-xl font-bold ${color} text-center`;
    resultEl.innerHTML = `Skor Anda: ${data.score} / ${data.total} (${data.score * 5} poin)<br/><span class='text-sm font-medium block mt-2'>${message}</span>`;

    document.getElementById("submitBtn").classList.add("hidden");
    document.getElementById("showBtn").classList.remove("hidden");

    // Simpan jawaban dan benar/salah
    questions.forEach(q => {
      const full = data.details.find(d => d.id === q.id);
      if (full) {
        q.correctAnswer = Object.entries(q.options).find(([k, v]) => v === full.correct)?.[0];
        q.explanation = full.explanation;
      }
    });

    questions.forEach(q => {
      const selected = document.querySelector(`input[name="${q.id}"]:checked`);
      q.userAnswer = selected ? selected.value : null;
      q.isCorrect = q.userAnswer === q.correctAnswer;
    });

    renderQuestions();

  } catch (err) {
    showPopup("Kesalahan", err.message || "Terjadi kesalahan tidak terduga.");
  }
});
// Event listener untuk tombol reset
document.getElementById("resetQuizBtn").addEventListener("click", () => {
  document.getElementById("submitBtn").classList.remove("hidden");

  questions.forEach(q => {
    q.userAnswer = undefined;
    q.isCorrect = undefined;
    q.correctAnswer = undefined;
    q.explanation = undefined;
    const entries = Object.entries(q.options);
    shuffleArray(entries);
    q.shuffledOptions = entries;
  });

  document.getElementById("result").textContent = "";
  renderQuestions();

  document.getElementById("showBtn").classList.add("hidden");
  document.getElementById("popupContent").innerHTML = "";
  document.getElementById("popupModal").classList.add("hidden");
});
// Event listener untuk menampilkan jawaban dan penjelasan
document.getElementById("showBtn").addEventListener("click", () => {
  const combined = questions.map((q, i) => {
    return `
      <div class="bg-gray-800 rounded-lg p-4 mb-4">
        <p class="font-semibold text-cyan-300">${i + 1}. ${q.question}</p>
        <p class="mt-2"><span class="text-green-400 font-medium"><br/>✅ Jawaban:</span><br/><br/> ${q.options[q.correctAnswer]}</p>
        <p class="mt-2"><span class="text-indigo-400 font-medium"><br/>📘 Penjelasan:</span> <br/><br/>${q.explanation || "Tidak ada penjelasan."}</p>
      </div>
    `;
  }).join("");
  showPopup("Jawaban dan Penjelasan", combined);
});
// Event listener untuk menampilkan skor
document.getElementById("showScoreBtn")?.addEventListener("click", () => {
  const score = document.getElementById("result").textContent.match(/(\d+) \/ (\d+)/);
  if (score) {
    showPopup("Skor Anda", `Skor Anda: ${score[1]} / ${score[2]} (${score[1] * 5} poin)`);
  } else {
    showPopup("Info", "Anda belum mengerjakan kuis.");
  }
});

function showPopup(title, content) {
  document.getElementById("popupTitle").textContent = title;
  document.getElementById("popupContent").innerHTML = content;
  document.getElementById("popupModal").classList.remove("hidden");
}
// Event listener untuk menutup popup
document.getElementById("popupCloseBtn").addEventListener("click", () => {
  document.getElementById("popupModal").classList.add("hidden");
});

// Inisialisasi kuis dengan mengambil soal dari server
fetchQuestions();
