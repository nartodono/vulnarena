import { getPvpInfo, getUserInfo } from "./module.js?v=70";

const userNickname = document.getElementById('userNickname');
const userCurrentMmr = document.getElementById('userCurrentMmr');
const userHighestMmr = document.getElementById('userHighestMmr');
const userTotalPlayed = document.getElementById('userTotalPlayed');
const userTotalWin = document.getElementById('userTotalWin');
const userTotalLost = document.getElementById('userTotalLost');

async function loadPvpInfo() {

    let user = null;
    const raw = sessionStorage.getItem("user_info");
    try {
        if (raw) user = JSON.parse(raw);
    } catch (err) {
        console.error("Failed to parse session data:", err);
    }
    if (!user) {
        try {
            user = await getUserInfo();
        } catch (err) {
            console.error("Error:", err);
            alert("Failed to fetch user info.");
            return;
        }
    }

    let userPvp = null;
    try{
        userPvp = await getPvpInfo();
    }
    catch (error) {
        console.error("Error:", error);
        alert("Failed to fetch user pvp info.");
        return;
    }
    
    userNickname.textContent = user.nickname;
    userCurrentMmr.textContent = userPvp.current_mmr;
    userHighestMmr.textContent = userPvp.highest_mmr;
    userTotalPlayed.textContent = userPvp.match_played;
    userTotalWin.textContent = userPvp.match_win;
    userTotalLost.textContent = userPvp.match_lost;
};

loadPvpInfo();