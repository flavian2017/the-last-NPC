const second = document.getElementById('second');
const millisecond = document.getElementById('millisecond');
const levelTag = document.getElementById('levelTag');
const buttonStart = document.getElementById('button-start');
const canvas = document.getElementById('gameCanvas');
const selectCharacters = document.querySelectorAll('.select-char');
const ctx = canvas.getContext('2d');
const mapImage = new Image()
const transparentImg = new Image()
mapImage.src = "../maps/level_1_map.png";
transparentImg.src = "../images/transparent.png";

let level = 1;
let newPts = 0;
let toggle = true;

document.addEventListener('contextmenu', ()=>{
    clearInterval(stopWatchmilli);

})

selectCharacters.forEach(function(currentBtn, i){
    var charName = document.getElementById('charName');
    var charDesc = document.getElementById('charDesc');
    currentBtn.addEventListener('click',()=>{
        switch(i){
            case 0: canvas.style['cursor'] = "url('../characters/mouse-icon/npc.png'), auto";
                    charName.innerHTML = 'creeper';
                    break;
            case 1: canvas.style['cursor'] = "url('../characters/mouse-icon/simplePC.png'), auto"; 
                    charName.innerHTML = 'SimplePC';
                    break;
            case 2: canvas.style['cursor'] = "url('../characters/mouse-icon/hawksEye.png'), auto"; 
                    charName.innerHTML = 'hawkTueye';
                    break;
            case 3: canvas.style['cursor'] = "url('../characters/mouse-icon/armorPC.png'), auto"; 
                    charName.innerHTML = 'armorPC';
                    break;
            case 4: canvas.style['cursor'] = "url('../characters/mouse-icon/snapy_c.png'), auto"; 
                    charName.innerHTML = 'snappy'; 
                    break;
        }
    })
})

mapImage.onload = () => {
    // console.log('Map image loaded');
    canvas.width = mapImage.width ;
    canvas.height = mapImage.height ;
    ctx.drawImage(mapImage, 0, 0);
};

buttonStart.addEventListener('click', ()=> {
    buttonStart.classList.toggle('hidden');

    if(toggle){
        second.innerText = '00';
        millisecond.innerText = '00';

        ctx.clearRect(0,0,canvas.width, canvas.height);
        ctx.drawImage(transparentImg, 0, 0);
        ctx.drawImage(mapImage, 0, 0);
        canvas.addEventListener('mousemove', gameEvent);
        let sec = 0;
        let millisec = 0;
        stopWatchmilli = setInterval(() => {
            if(millisec === 99){
                millisec = 0;
                    second.innerText = convert(++sec);
            }
            else{
                millisecond.innerText = convert(++millisec);
            }
        },9);
    }
    else{
        toggle = !toggle ;
        canvas.removeEventListener('mousemove', gameEvent);
    }
})


function loadLevel(level){
    switch(level){
        case 1: mapImage.src = '../maps/level_1_map.png'; break;
        case 2: mapImage.src = '../maps/level_2_map.png'; break;
        case 3: mapImage.src = '../maps/level_3_map.png'; break;
        case 4: mapImage.src = '../maps/level_4_map.png'; break;
        case 5: mapImage.src = '../maps/level_6_map.png'; break;
    }
}

function convert(n){
    n = String(n);
    if(n.length == 1)
        return '0' + n;
    else
        return n;
}

function gameEvent (event) {

    const rect = canvas.getBoundingClientRect();
        const x = event.clientX - rect.left;
        const y = event.clientY - rect.top;

        const pixel = ctx.getImageData(x, y, 1, 1).data;
        // console.log(`Pixel data: ${pixel}`);


        if(level == 5){
            if(pixel[3] == 229) {
                level++;

                sendToServer(level);
                
                loadLevel(level);
                updateMapTable(level);

                levelTag.innerHTML = `LEVEL ${level}`;
                clearInterval(stopWatchmilli);
                buttonStart.classList.toggle('hidden');
                canvas.removeEventListener('mousemove', gameEvent);
            }
            else if (pixel[0] < 30 && pixel[1] < 30 && pixel[2] < 30) {
            }
            else {
                ctx.fillStyle = 'red';
                ctx.beginPath();
                ctx.arc(x, y, 3, 0, Math.PI * 2);
                ctx.fill();
                clearInterval(stopWatchmilli);
                buttonStart.classList.toggle('hidden');
                canvas.removeEventListener('mousemove', gameEvent);
            }
        }
        else{
            if(pixel[3] == 229) {
                level++;
                levelTag.innerHTML = `LEVEL ${level}`;

                sendToServer(level);   
                
                loadLevel(level);
                updateMapTable(level);

                levelTag.innerHTML = `LEVEL ${level}`;
                clearInterval(stopWatchmilli);
                buttonStart.classList.toggle('hidden');
                canvas.removeEventListener('mousemove', gameEvent);
            }
            else if (pixel[0] >= 200 && pixel[1] >= 200 && pixel[2] >= 200) {
            }
            else {
                ctx.fillStyle = 'red';
                ctx.beginPath();
                ctx.arc(x, y, 3, 0, Math.PI * 2);
                ctx.fill();
                clearInterval(stopWatchmilli);
                buttonStart.classList.toggle('hidden');
                canvas.removeEventListener('mousemove', gameEvent);
            }
        }
};

function assignPts(sec){
    if(sec < 4)
        return 100;
    else if (sec < 6)
        return 93;
    else if (sec < 8)
        return 88;
    else if (sec < 10)
        return 80;
    else if (sec < 15)
        return 60;
    else if (sec < 20)
        return 50;
    else if (sec < 30)
        return 40;
    else if (sec < 50)
        return 30;
    else return 10;
}

function sendToServer(level){
    level--;
    var url = '../db/add_record.php';
    var formData = new FormData();
    formData.append('id', document.getElementById('id').innerHTML);
    formData.append('mapId', level);
    var currentPts = parseFloat(document.getElementById('score').innerHTML);
    newPts = newPts + assignPts(parseInt(second.innerHTML));
    var pts = newPts > currentPts ? newPts : currentPts;
    document.getElementById('score').innerHTML = pts;
    var rank = '';
    if(pts >=320){
        rank = 'Grandmaster';
    }
    else if(pts >=190){
        rank = 'elite';
    }
    else if(pts >=100){
        rank = 'brown belt';
    }
    document.getElementById('rank').innerHTML = rank;

    var time = parseInt(second.innerHTML)*100+ parseInt(millisecond.innerHTML);
    formData.append('time', time);
    formData.append('pts', pts);
    
    fetch(url, { method: 'POST', body: formData })
    .then(function (response) {
        return response.text();
    })
    .then(function () {
        updateOverallTable();

    });
}

function updateOverallTable(){
    fetch('../db/fetch_data.php')
    .then(response => response.json())
    .then(data => {
        displayData(data);
    })
    .catch(error => console.error('Error:', error));
}

function displayData(data) {
const tableBody = document.getElementById('table-body');
tableBody.innerHTML = '';
data.forEach(item => {
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>${item.id}</td>
        <td>${item.username}</td>
        <td>${item.points}</td>
        <td>${item.rank}</td>
    `;
    tableBody.appendChild(row);
});
}


function updateMapTable(level){
    var formData = new FormData();
    formData.append('level', level);
    var url = '../db/fetch_map_data.php';
    fetch(url, { method: 'POST', body: formData })    
    .then(response => response.json())
    .then(data => {
        displayMapTableData(data);
    })
    .catch(error => console.error('Error:', error));
}

function displayMapTableData(data) {
const tableBody = document.getElementById('table1-body');
tableBody.innerHTML = '';
data.forEach(item => {
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>${item.playerId}</td>
        <td>${item.username}</td>
        <td>${item.lowest_time}</td>
        <td>${item.rank}</td>
    `;
    tableBody.appendChild(row);
});
}