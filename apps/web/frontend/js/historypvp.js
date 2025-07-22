import { getPvpHistory } from "./module.js?a=14";

let realHistoryData = [];
let currentPage = 1;
let rowsPerPage = 10;

const tableBody = document.getElementById("tableBody");
const pagination = document.getElementById("pagination");
const rowsPerPageSelect = document.getElementById("rowsPerPage");

rowsPerPage = parseInt(rowsPerPageSelect.value);

async function loadAndRenderHistory() {
    tableBody.innerHTML = `<tr><td colspan="4" class="py-4 text-center text-gray-400">Loading...</td></tr>`;

    const data = await getPvpHistory();
    // Debug response

    if (!data || !Array.isArray(data.history) || data.history.length === 0) {
        tableBody.innerHTML = `<tr><td colspan="4" class="py-4 text-center text-gray-400">Tidak ada data</td></tr>`;
        pagination.innerHTML = "";
        return;
    }

    // Mapping data
    realHistoryData = data.history.map(match => {
        // Konversi result ke Bahasa Indonesia
        let resultText = "";
        if (match.result === "Win") resultText = "Menang";
        else if (match.result === "Lose") resultText = "Kalah";
        else if (match.result === "Draw") resultText = "Seri";
        else resultText = match.result; // fallback

        // Kelas warna untuk result
        const resultClass =
            match.result === "Win" ? "text-green-400"
        : match.result === "Lose" ? "text-red-400"
        : "text-gray-400";

        // ... kode lain seperti mmr_before, mmr_after, dst.
        const mmr_before = match.mmr_before;
        const mmr_gain = match.mmr_gain;
        let mmr_after = mmr_before + mmr_gain;
        mmr_after = Math.max(0, mmr_after);
        const deltaText = mmr_gain > 0 ? `+${mmr_gain}` : `${mmr_gain}`;
        const mmrClass = mmr_gain > 0 ? "text-green-400" : "text-red-400";
        const matchTime = new Date(match.played_at * 1000).toLocaleString('id-ID', {
            day: '2-digit', month: 'short', year: 'numeric',
            hour: '2-digit', minute: '2-digit'
        });
        const mmrDisplay = `${mmr_before} → ${mmr_after} <span class="${mmrClass}">(${deltaText})</span>`;

        return {
            match_id: match.match_id,
            mmr_before,
            mmr_after,
            deltaText,
            mmrClass,
            result: resultText,      // Tampilkan result versi Bahasa Indo!
            resultClass,
            matchTime,
            score: match.score,
            mmrDisplay
        };
    });

    renderTable(currentPage);
}

function renderTable(page = 1) {
    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const pageData = realHistoryData.slice(start, end);


    if (pageData.length === 0) {
        tableBody.innerHTML = `<tr><td colspan="4" class="py-4 text-center text-gray-400">Tidak ada data</td></tr>`;
        return;
    }

    tableBody.innerHTML = pageData.map(row => `
        <tr>
            <td class="px-4 py-4 text-blue-400 font-mono">
                ${row.match_id}
            </td>
            <td class="px-4 py-4 ${row.resultClass}">${row.result}</td>
            <td class="px-4 py-4 text-center">
                ${row.mmrDisplay}
            </td>
            <td class="px-4 py-4 text-sm text-gray-400">${row.matchTime}</td>
        </tr>
    `).join("");

    renderPagination();
}

function renderPagination() {
    const pageCount = Math.ceil(realHistoryData.length / rowsPerPage);
    pagination.innerHTML = "";

    for (let i = 1; i <= pageCount; i++) {
        const btn = document.createElement("button");
        btn.textContent = i;
        btn.className = `px-3 py-1 rounded ${i === currentPage ? 'bg-indigo-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600'}`;
        btn.addEventListener("click", () => {
            currentPage = i;
            renderTable(currentPage);
        });
        pagination.appendChild(btn);
    }
}

rowsPerPageSelect.addEventListener("change", () => {
    rowsPerPage = parseInt(rowsPerPageSelect.value);
    currentPage = 1;
    renderTable(currentPage);
});

document.addEventListener("DOMContentLoaded", loadAndRenderHistory);
