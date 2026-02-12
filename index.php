<?php
declare(strict_types=1);

require_once __DIR__ . '/database.php';
send_security_headers();
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BedadungPoint</title>
    <style>
        :root {
            --jember-green: #1b5e20;
            --jember-green-dark: #124217;
            --gold: #d4af37;
            --surface: #ffffff;
            --surface-soft: #f7fbf7;
            --text: #153015;
            --success: #166534;
            --warning: #9a6700;
            --danger: #b42318;
            --shadow: 0 12px 30px rgba(18, 66, 23, 0.14);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
            background:
                radial-gradient(120% 140% at 90% -20%, rgba(212, 175, 55, 0.26), transparent 55%),
                linear-gradient(150deg, #f6fff5 0%, #fdfcf6 44%, #f7fbf7 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            width: min(1040px, 92vw);
            margin: 0 auto;
            padding: 24px 0 44px;
            flex: 1 0 auto;
        }

        header {
            background: linear-gradient(120deg, var(--jember-green-dark), var(--jember-green));
            color: #fff;
            border-radius: 18px;
            padding: 22px 24px 24px;
            box-shadow: var(--shadow);
            border: 2px solid rgba(212, 175, 55, 0.32);
        }

        .header-identity {
            margin: 0;
            font-size: clamp(1.1rem, 2.8vw, 1.65rem);
            letter-spacing: 0.5px;
            color: #fff8cf;
            font-weight: 800;
            text-transform: uppercase;
        }

        header h1 {
            margin: 6px 0 0;
            font-size: clamp(1.05rem, 2.2vw, 1.45rem);
            letter-spacing: 0.2px;
            color: #ffffff;
        }

        header p {
            margin: 6px 0 0;
            color: #fcecb8;
            font-size: 0.95rem;
        }

        .panel {
            margin-top: 16px;
            background: var(--surface);
            border-radius: 16px;
            border: 1px solid #dde8d8;
            box-shadow: var(--shadow);
            padding: 18px;
        }

        .panel h2 {
            margin: 0 0 12px;
            font-size: 1.1rem;
            color: var(--jember-green-dark);
        }

        .muted {
            margin: 0;
            color: #567156;
            font-size: 0.9rem;
        }

        .converter-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
            margin-top: 12px;
        }

        @media (min-width: 920px) {
            .converter-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        .converter-box {
            border: 1px solid #d6e4d0;
            border-radius: 12px;
            background: var(--surface-soft);
            padding: 12px;
        }

        .map-wrapper {
            margin-top: 12px;
            border: 1px solid #d6e4d0;
            border-radius: 12px;
            background: var(--surface-soft);
            padding: 12px;
        }

        .map-toolbar {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 10px;
        }

        .map-toolbar label {
            margin: 0;
        }

        .map-toolbar select {
            border: 1px solid #c8d8c8;
            border-radius: 10px;
            padding: 8px 10px;
            background: #fff;
            font-size: 0.9rem;
            color: #233823;
        }

        .map-hint {
            font-size: 0.82rem;
            color: #4b6a4b;
        }

        .map-canvas {
            width: 100%;
            min-height: 420px;
            border: 2px solid rgba(212, 175, 55, 0.42);
            border-radius: 12px;
            overflow: hidden;
            background:
                radial-gradient(circle at 20% 20%, rgba(27, 94, 32, 0.15), transparent 52%),
                #e5efe4;
        }

        .map-canvas.map-notice-mode {
            display: grid;
            place-items: center;
            padding: 18px;
            text-align: center;
        }

        .map-notice {
            max-width: 560px;
            color: #573f00;
            background: #fff5d5;
            border: 1px solid #ead48c;
            border-radius: 10px;
            padding: 12px 14px;
            line-height: 1.45;
        }

        .map-legend {
            margin-top: 10px;
            border: 1px dashed #c7d9be;
            border-radius: 10px;
            background: #fbfff8;
            padding: 10px 12px;
        }

        .map-legend-title {
            margin: 0 0 6px;
            font-size: 0.9rem;
            color: #1f3a1f;
            font-weight: 700;
        }

        .map-legend-grid {
            display: grid;
            grid-template-columns: 110px 1fr;
            row-gap: 4px;
            column-gap: 8px;
            font-size: 0.87rem;
            color: #264326;
        }

        @media (max-width: 540px) {
            .map-canvas {
                min-height: 320px;
            }

            .map-legend-grid {
                grid-template-columns: 1fr;
                row-gap: 2px;
            }
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.88rem;
            color: #325a32;
            font-weight: 600;
        }

        input[type="text"] {
            width: 100%;
            border: 1px solid #c8d8c8;
            border-radius: 10px;
            background: #fff;
            padding: 11px 12px;
            font-size: 0.95rem;
        }

        input:focus {
            outline: 2px solid rgba(212, 175, 55, 0.48);
            border-color: var(--gold);
        }

        .converter-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            border: 0;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
            padding: 11px 14px;
            font-size: 0.93rem;
            color: #203015;
            background: linear-gradient(120deg, #f4df97, #e4c65b);
            border: 1px solid #d4b14f;
        }

        .btn:hover {
            filter: brightness(1.03);
        }

        .alert {
            border-radius: 10px;
            padding: 11px 12px;
            margin-top: 12px;
            font-size: 0.92rem;
        }

        .alert-success {
            background: #e8f7ec;
            border: 1px solid #b7e0c4;
            color: var(--success);
        }

        .alert-error {
            background: #fdeceb;
            border: 1px solid #f5c4c0;
            color: #8a1f17;
        }

        .alert-warning {
            background: #fff8e6;
            border: 1px solid #f0dfb3;
            color: var(--warning);
        }

        .converter-result {
            margin-top: 12px;
            border: 1px dashed #c7d9be;
            border-radius: 12px;
            background: #fbfff8;
            padding: 12px;
        }

        .converter-result pre {
            margin: 0;
            font-size: 0.91rem;
            line-height: 1.5;
            white-space: pre-wrap;
            word-break: break-word;
            color: #223722;
        }

        .hidden {
            display: none !important;
        }

        .copy-feedback {
            font-size: 0.84rem;
            color: #355535;
            align-self: center;
        }

        .site-footer {
            flex-shrink: 0;
            background: linear-gradient(120deg, var(--jember-green-dark), var(--jember-green));
            color: #eef7ef;
            border-top: 2px solid rgba(212, 175, 55, 0.3);
            margin-top: 10px;
        }

        .footer-inner {
            width: min(1040px, 92vw);
            margin: 0 auto;
            padding: 14px 0 18px;
            display: grid;
            gap: 6px;
            text-align: center;
            font-size: 0.92rem;
            line-height: 1.4;
        }

        .footer-line {
            margin: 0;
            color: #f8fce9;
        }

        .footer-muted {
            margin: 0;
            color: #dff0df;
            font-size: 0.86rem;
        }

        .footer-love {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
        }

        .love-icon {
            width: 16px;
            height: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #e74c3c;
            line-height: 1;
            transition: transform 0.2s ease, color 0.2s ease;
            transform-origin: center;
            cursor: pointer;
        }

        .love-icon svg {
            width: 16px;
            height: 16px;
            display: block;
            fill: currentColor;
        }

        .love-icon:hover {
            color: #c0392b;
            transform: scale(1.12);
        }

        .love-icon:focus-visible {
            outline: 2px solid rgba(231, 76, 60, 0.35);
            outline-offset: 2px;
            border-radius: 3px;
        }

        @media (max-width: 480px) {
            .footer-inner {
                padding: 12px 0 16px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <header>
        <p class="header-identity">BPS Kabupaten Jember</p>
        <h1>BedadungPoint - Presisi di Bumi Pandalungan</h1>
        <p>Konversi dua arah koordinat Decimal ↔ DMS dengan informasi alamat lokasi.</p>
    </header>

    <section class="panel" aria-label="Konverter koordinat decimal dan DMS">
        <h2>Konversi Koordinat Decimal ↔ DMS</h2>
        <p class="muted">Gunakan peta atau input teks untuk mengonversi koordinat secara dua arah.</p>

        <?php if (MAPS_API_KEY === ''): ?>
            <div class="alert alert-warning">
                MAPS_API_KEY belum diset. Konversi tetap berjalan, tetapi fitur peta dan pencarian alamat tidak aktif.
            </div>
        <?php endif; ?>

        <div class="map-wrapper">
            <div class="map-toolbar">
                <label for="map-view-select">Tampilan Peta:</label>
                <select id="map-view-select" aria-label="Pilih tampilan peta">
                    <option value="roadmap">Roadmap</option>
                    <option value="satellite">Satellite</option>
                    <option value="hybrid" selected>Hybrid</option>
                    <option value="terrain">Terrain</option>
                </select>
                <span class="map-hint">Street View aktif melalui kontrol Pegman di peta.</span>
            </div>

            <div id="map-canvas" class="map-canvas" aria-label="Peta interaktif lokasi"></div>

            <div class="map-legend" aria-live="polite">
                <p class="map-legend-title">Legenda Koordinat (Real-Time)</p>
                <div class="map-legend-grid">
                    <span>Latitude</span>
                    <span id="legend-lat">-</span>
                    <span>Longitude</span>
                    <span id="legend-lng">-</span>
                    <span>Zoom</span>
                    <span id="legend-zoom">-</span>
                    <span>Status</span>
                    <span id="legend-status">Peta siap digunakan.</span>
                </div>
            </div>
        </div>

        <div class="converter-grid">
            <div class="converter-box">
                <label for="decimal-source">Sumber Decimal (lat, lng)</label>
                <input
                    id="decimal-source"
                    type="text"
                    placeholder="-8.1050786039, 113.7262102605"
                    autocomplete="off"
                >
                <div class="converter-actions">
                    <button type="button" id="convert-decimal-to-dms" class="btn">
                        Decimal → DMS
                    </button>
                </div>
            </div>

            <div class="converter-box">
                <label for="dms-source">Sumber DMS (lat + lng)</label>
                <input
                    id="dms-source"
                    type="text"
                    placeholder="8°06'18.3&quot;S 113°43'34.4&quot;E"
                    autocomplete="off"
                >
                <div class="converter-actions">
                    <button type="button" id="convert-dms-to-decimal" class="btn">
                        DMS → Decimal
                    </button>
                </div>
            </div>
        </div>

        <div id="converter-status" class="alert alert-warning hidden" role="status" aria-live="polite"></div>

        <div id="converter-result" class="converter-result hidden">
            <pre id="converter-result-text"></pre>
            <div class="converter-actions">
                <button type="button" id="copy-result-btn" class="btn">Copy Hasil</button>
                <span id="copy-feedback" class="copy-feedback"></span>
            </div>
        </div>
    </section>
</div>

<footer class="site-footer" aria-label="Informasi aplikasi">
    <div class="footer-inner">
        <p class="footer-line">Developer Nanang Pamungkas</p>
        <p class="footer-muted">Version 1.0.0</p>
        <p class="footer-muted footer-love">
            Created with love
            <span class="love-icon" role="img" aria-label="love">
                <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54z"></path>
                </svg>
            </span>
        </p>
    </div>
</footer>

<script>
    window.BedadungPointConfig = {
        hasMapsApiKey: <?php echo MAPS_API_KEY !== '' ? 'true' : 'false'; ?>,
        defaultCenter: { lat: -8.1704, lng: 113.7022 }
    };
</script>
<script src="script.js"></script>
<?php if (MAPS_API_KEY !== ''): ?>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=<?php echo urlencode(MAPS_API_KEY); ?>&callback=initBedadungMap&language=id&region=ID"
        async
        defer
    ></script>
<?php endif; ?>
</body>
</html>
