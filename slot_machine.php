<?php
session_start();
$name = $_GET['name'] ?? '';
$amount = $_GET['amount'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halal Slot Machine</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="slots">
        <div class="reel"></div>
        <div class="reel"></div>
        <div class="reel"></div>
    </div>

    <div id="debug" class="debug"></div>
    <button id="startSpin" class="startbutt"><h1>Putar Sekarang</button>
   
    <img style="position:fixed; left: 0; top: 0; height: 100vh; width: auto;" src="rollislami.png">

    <script>
        const debugEl = document.getElementById('debug'),
              startSpinButton = document.getElementById('startSpin'),
              iconMap = ["kaligrafi", "muslim", "lampu", "tasbih", "quran", "kupat", "2x", "muslimah", "masjid"],
              icon_width = 79,    
              icon_height = 79,    
              num_icons = 9,    
              time_per_icon = 100,
              indexes = [0, 0, 0];

        const roll = (reel, offset = 0) => {
            const delta = (offset + 2) * num_icons + Math.round(Math.random() * num_icons); 

            return new Promise((resolve, reject) => {
                const style = getComputedStyle(reel),
                      backgroundPositionY = parseFloat(style["background-position-y"]),
                      targetBackgroundPositionY = backgroundPositionY + delta * icon_height,
                      normTargetBackgroundPositionY = targetBackgroundPositionY % (num_icons * icon_height);

                setTimeout(() => { 
                    reel.style.transition = `background-position-y ${(8 + 1 * delta) * time_per_icon}ms cubic-bezier(.41,-0.01,.63,1.09)`;
                    reel.style.backgroundPositionY = `${backgroundPositionY + delta * icon_height}px`;
                }, offset * 150);

                setTimeout(() => {
                    reel.style.transition = `none`;
                    reel.style.backgroundPositionY = `${normTargetBackgroundPositionY}px`;
                    resolve(delta % num_icons);
                }, (8 + 1 * delta) * time_per_icon + offset * 150);
            });
        };

        function rollAll() {
            debugEl.textContent = 'rolling...';

            const reelsList = document.querySelectorAll('.slots > .reel');

            Promise.all([...reelsList].map((reel, i) => roll(reel, i)))
                .then((deltas) => {
                    deltas.forEach((delta, i) => indexes[i] = (indexes[i] + delta) % num_icons);
                    debugEl.textContent = indexes.map((i) => iconMap[i]).join(' - ');

                    let doubledAmount = <?= $amount ?>;
                    if (indexes[0] == indexes[1] && indexes[1] == indexes[2]) {
                        doubledAmount *= 2;
                        alert('SELAMAT ANDA MENANG!                                                                  Nominal Sedekah anda akan digandakan untuk anak yatim.');
                    } else {
                        alert('Maaf anda belum beruntung.                                                            Nominal Sedekah anda tetap akan disalurkan ke anak yatim.');
                    }
                    // Redirect back with doubled amount if won or original amount if not won
                    window.location.href = `insert_leaderboard.php?name=<?= $name ?>&amount=${doubledAmount}`;
                });
        }

        startSpinButton.addEventListener('click', rollAll);
    </script>
</body>
</html>
