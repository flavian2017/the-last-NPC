const mapContainer = document.getElementById('map-info');
const mapButton = document.getElementById('map-btn');
const charButton = document.getElementById('char-btn');
const charContainer = document.getElementById('char-info');

mapToggle = true;
mapButton.addEventListener('click', ()=>{
    if(mapToggle)
        mapContainer.style['display'] = 'block';
    else
        mapContainer.style['display'] = 'none';
    mapToggle = !mapToggle;
})

charToggle = true;
charButton.addEventListener('click', ()=>{
    if(charToggle)
        charContainer.style['display'] = 'block';
    else
        charContainer.style['display'] = 'none';
    charToggle = !charToggle;
})