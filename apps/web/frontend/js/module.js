export async function checkAuth(){
    try{
        const response = await fetch("https://api.vulnarena.space/auth/check",{
            method: "GET",
            credentials: "include"
        });

        const result = await response.json();

        if(result.success){
            return true;
        }
        else if (result.message?.trim().toLowerCase() === "session expired") {
                window.location.href = "/account";
        }
        else{
            return false;
        }
    }
    catch (err){
        console.error(err);
        return false;
    }
}

export async function getUserInfo() {
    try{
        const response = await fetch('https://api.vulnarena.space/user', {
            method: 'GET',
            credentials: "include",
            headers: {
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();

        if (result.success) {
            // Rebuild/update sessionStorage info
            const userInfo = {
                user_id: result.data.user_id,
                username: result.data.username,
                email: result.data.email,
                nickname: result.data.nickname,
                age: result.data.age,
                gender: result.data.gender,
                bio: result.data.bio
            };
            sessionStorage.setItem('user_info', JSON.stringify(userInfo));
            return userInfo;
        }
        else if (result.message?.trim().toLowerCase() === "session expired") {
                window.location.href = "/account";
        }
        else{
            throw new Error("Failed to get user info");
        }
    }
    catch (err){
        console.error("Error while fetching user info: ", err);
        return null;
    }    
}

// get cookie
export function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
}

// get lab info
export async function getLabInfo(){
    try{
        const response = await fetch('https://api.vulnarena.space/lab/status', {
            method: 'GET',
            credentials: "include",
            headers: {
                'Accept': 'application/json',
                'X-CSRF-Token' : getCookie("csrf_token") || ""
            }
        });
        
        const result = await response.json();

        if (result.success && result.active) {
            const labInfo = {
                subdomain: result.subdomain,
                createtime: result.createtime,
                expiredtime: result.expiredtime,
            };
            return labInfo;
        }
        else if (result.success && !result.active) {
            return false;
        }
        else if (result.message?.trim().toLowerCase() === "session expired") {
                window.location.href = "/account";
        }
        else{
            return null;
        }
    }
    catch (err){
        console.error("Error while fetching lab info: ", err);
        return null;
    }
}

// get user pvp info/stat
export async function getPvpInfo() {
    try{
        const response = await fetch('https://api.vulnarena.space/user/pvp/stat', {
            method: 'GET',
            credentials: "include",
            headers: {
                'Accept': 'application/json',
                'X-CSRF-Token' : getCookie("csrf_token") || ""
            }
        });

        const result = await response.json();

        if(result.success){
            const userPvpInfo = {
                current_mmr: result.data.mmr,
                highest_mmr: result.data.highest_mmr,
                match_played: result.data.match_played,
                match_win: result.data.match_win,
                match_lost: result.data.match_lost
            };
            return userPvpInfo;
        }
        else if (result.message?.trim().toLowerCase() === "session expired") {
                window.location.href = "/account";
        }
        else{
            throw new Error("Failed to get user info");
        }
    }
    catch (err){
        console.error("Error while fetching lab info: ", err);
        return null;
    }
}

// get user pvp match history 
export async function getPvpHistory(){
        try{
        const response = await fetch('https://api.vulnarena.space/user/pvp/history', {
            method: 'GET',
            credentials: "include",
            headers: {
                'Accept': 'application/json',
            }
        });

        const result = await response.json();

        if(result.success){
            const user_id = result.user_id;
            const count = result.count;
            const history = result.data;

            // history.forEach((match, i) => {
            //     console.log(`Match ke-${i+1}:`, match);
            // });

            return {
                user_id,
                count,
                history
            };
        }
        else if (result.message?.trim().toLowerCase() === "session expired") {
                window.location.href = "/account";
        }
        else{
            throw new Error("Failed to get user info");
        }  
    }
    catch (err){
        console.error("Error while fetching lab info: ", err);
        return null;
    }
};
