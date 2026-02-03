    <!DOCTYPE html>
    <html lang="id">
    <head>
    <meta charset="UTF-8">
    <title>Scan QR Asset</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <script src="https://unpkg.com/html5-qrcode"></script>

    <style>
    html, body {
        margin: 0;
        padding: 0;
        background: black;
        height: 100%;
        overflow: hidden;
    }

    #qr-reader {
        position: fixed;
        inset: 0;
        width: 100vw;
        height: 100vh;
    }

    #qr-reader video {
        width: 100vw !important;
        height: 100vh !important;
        object-fit: cover;
    }

    .overlay {
        position: fixed;
        inset: 0;
        pointer-events: none;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .scan-box {
        width: 260px;
        height: 260px;
        border: 3px solid white;
        border-radius: 12px;
    }

    .close-btn {
        position: fixed;
        top: 16px;
        left: 16px;
        z-index: 1000;
        background: rgba(0,0,0,.6);
        color: white;
        border: none;
        padding: 10px 14px;
        font-size: 18px;
        border-radius: 10px;
    }
    </style>
    </head>

    <body>

    <button class="close-btn" onclick="goBack()">✕</button>

    <div id="qr-reader"></div>

    <div class="overlay">
        <div class="scan-box"></div>
    </div>

    <script>
    let qr;

    function goBack(){
        if(qr){
            qr.stop().finally(() => {
                window.location.href = "/assets-view";
            });
        } else {
            window.location.href = "/assets-view";
        }
    }

    window.onload = async function () {

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert("Perangkat tidak mendukung kamera.");
            goBack();
            return;
        }

        qr = new Html5Qrcode("qr-reader");

        try {
            await qr.start(
                { facingMode: { exact: "environment" } },
                { fps: 10 },
                decodedText => {
                    qr.stop();
                    window.location.href = "/assets-view/" + decodedText.trim();
                }
            );
        } catch (err) {
            alert(
                "Kamera tidak dapat diakses.\n\n" +
                "Pastikan:\n" +
                "• Menggunakan HTTPS\n" +
                "• Izin kamera diaktifkan\n" +
                "• Tidak membuka via 127.0.0.1"
            );
            goBack();
        }
    };
    </script>

    </body>
    </html>
