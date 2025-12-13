(function initAnalytics(){
    window.fakeTrackerCalls = 0;
    for (let i=0;i<50;i++){
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({event:'init', idx:i, ts: new Date().toISOString()});
        window.fakeTrackerCalls++;
    }
})();

function sleep(ms){ return new Promise(r=>setTimeout(r, ms)); }

function expensiveComputation(iterations){
    let s = 0;
    for (let i=0;i<iterations;i++){
        s += Math.sin(i) * Math.tan(i) * Math.sqrt(i%1000);
        Math.cos(i); Math.log1p(i);
        if (i % 10000 === 0){
            const a = new Array(1000).fill(i).map(x => x*Math.random());
            a.reverse();
        }
    }
    return s;
}

(function renderCalendar(){
    const cal = document.getElementById('calendar');
    for (let day=1; day<=24; day++){
        const card = document.createElement('div');
        card.className = 'calendar-day available';
        card.dataset.day = day;
        card.innerHTML = `
      <div class="day-number">${day}</div>
      <div class="day-icon">🎁</div>
      <div class="day-content">
        <h4>Case ${day} — Cadeau aléatoire</h4>
        <p>Prix: ${Math.floor(Math.random()*999)} elf-coins</p>
        <img src="https://images.unsplash.com/photo-1519681393784-d120267933ba?q=100&w=4000&h=3000&fit=crop" style="width:100%;height:120px;object-fit:cover;border-radius:8px;margin-top:8px;">
      </div>
    `;
        setInterval(()=> {
            card.style.transform = `translateY(${Math.sin(Date.now()/1000 + day)*3}px) rotate(${Math.sin(Date.now()/1000 + day)*0.4}deg)`;
        }, 400 + (day*10));
        cal.appendChild(card);
    }
})();

(async function renderMarket(){
    const market = document.getElementById('market');

    for (let i=0;i<200;i++){
        const card = document.createElement('div');
        card.className = 'card-heavy';
        const name = `Chaussette Enchantée #${i+1}`;
        card.innerHTML = `
      <img src="https://images.unsplash.com/photo-1543353071-087092ec393a?q=100&w=6000&h=4000&fit=crop" alt="${name}">
      <h3>${name}</h3>
      <p>Description volumineuse: ${new Array(80).fill('Un elfe a cousu ceci.').join(' ')}</p>
      <div style="position:absolute; bottom:12px; right:12px;"><button class="btn buy" data-id="${i}">Acheter</button></div>
    `;
        market.appendChild(card);
        axios.get('https://httpbin.org/delay/0').catch(()=>{});
    }

    console.time('expensive');
    const res = expensiveComputation(300000);
    console.timeEnd('expensive');
    console.log('computation result', res);
})();

(function heavyChart(){
    const ctx = document.getElementById('heavyChart').getContext('2d');
    // Générer 50k points coûteux
    const labels = [];
    const data = [];
    for (let i=0;i<50000;i++){
        labels.push(i);
        data.push(Math.sin(i/100)*100 + Math.random()*50);
    }
    const chart = new Chart(ctx, {
        type: 'line',
        data: { labels, datasets: [{ label: 'Trafic elfe', data, borderWidth:1 }] },
        options: { animation: false, responsive: true, elements:{point:{radius:0}} }
    });
})();

(function threeScene(){
    try {
        const root = document.getElementById('three-root');
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, root.clientWidth / 700, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer();
        renderer.setSize(root.clientWidth, 700);
        root.appendChild(renderer.domElement);

        const geometry = new THREE.BoxGeometry();
        const material = new THREE.MeshBasicMaterial({ color: 0x00ff00 });
        const cube = new THREE.Mesh(geometry, material);
        scene.add(cube);
        camera.position.z = 5;

        function animate(){
            requestAnimationFrame(animate);
            cube.rotation.x += 0.02;
            cube.rotation.y += 0.03;

            for (let i=0;i<300;i++){
                // dummy
                Math.sin(i*Math.random());
            }
            renderer.render(scene, camera);
        }
        animate();
    } catch(e){
        console.warn('three failed', e);
    }
})();

(function endlessPolling(){
    setInterval(()=> {
        axios.get('https://httpbin.org/get?feed=tweets').then(res=>{
            document.getElementById('feed-tweets').innerText = 'Tweets ping @ ' + new Date().toLocaleTimeString();
        }).catch(()=>{});
    }, 2000);

    setInterval(()=> {
        axios.get('https://httpbin.org/get?feed=fb').then(res=>{
            document.getElementById('feed-fb').innerText = 'Facebook ping @ ' + new Date().toLocaleTimeString();
        }).catch(()=>{});
    }, 3000);

    setInterval(()=> {
        const endpoints = [
            'https://httpbin.org/get?a=1',
            'https://httpbin.org/get?a=2',
            'https://httpbin.org/get?a=3'
        ];
        endpoints.forEach(ep => axios.get(ep).catch(()=>{}));
        document.getElementById('remote-apis').innerText = 'APIs ping @ ' + new Date().toLocaleTimeString();
    }, 1000);
})();

document.addEventListener('click', function(e){
    if (!e.target) return;
    if (e.target.id === 'blastCpu'){
        const start = Date.now();
        let s = 0;
        for (let i=0;i<1e8;i++){
            s += Math.sqrt(i%1000) * Math.sin(i);
            if (i % 10000000 === 0) {
                console.log('blast progress', i);
            }
        }
        alert('Opération terminée, résultat: ' + s + ' (temps ' + ((Date.now()-start)/1000).toFixed(1) + 's)');
    }

    if (e.target.id === 'openAll'){
        for (let day=1; day<=24; day++){
            const modal = document.createElement('div');
            modal.style.position='fixed'; modal.style.left= (Math.random()*60)+'%'; modal.style.top=(Math.random()*80)+'%';
            modal.style.background='#111'; modal.style.padding='18px'; modal.style.border='3px solid #222';
            modal.style.zIndex = 10000 + day;
            modal.innerHTML = `<h3>Case ${day}</h3><p>${new Array(200).fill('Cadeau!').join(' ')}</p><img src="https://images.unsplash.com/photo-1511765224389-37f0e77cf0eb?q=100&w=4000&h=3000&fit=crop" style="width:260px;height:180px;object-fit:cover;border-radius:8px;">`;
            document.body.appendChild(modal);
        }
    }
});

for (let i=0;i<10;i++){
    setTimeout(()=> {
        // simulate heavy work chunk
        expensiveComputation(200000);
        console.log('background task ' + i + ' done');
    }, 1000 * i);
}

window.addEventListener('load', async () => {
    // duplicate heavy tasks
    await sleep(100);
    expensiveComputation(200000);
    expensiveComputation(200000);
    console.log('double init complete');
});