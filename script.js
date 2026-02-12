(function () {
    "use strict";

    var config = window.BedadungPointConfig || {};
    var defaultCenter = config.defaultCenter || { lat: -8.1704, lng: 113.7022 };

    var converterElements = {};
    var mapElements = {};
    var converterLastResultText = "";

    var geocoder = null;
    var map = null;
    var marker = null;
    var mapReady = false;
    var isApplyingMapTypeFromSelect = false;

    function isValidLatLng(lat, lng) {
        return Number.isFinite(lat) && Number.isFinite(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180;
    }

    function formatDecimal(value) {
        return Number(value).toFixed(10).replace(/\.?0+$/, "");
    }

    function parseDecimalNumber(rawValue, label) {
        var text = String(rawValue || "").trim();
        if (text === "") {
            throw new Error(label + " kosong.");
        }

        if (!/^[+-]?\d+(\.\d+)?$/.test(text)) {
            throw new Error(label + " harus desimal bertitik. Contoh: -8.1551016781");
        }

        var value = Number(text);
        if (!Number.isFinite(value)) {
            throw new Error(label + " tidak valid.");
        }

        return value;
    }

    function validateDecimalRange(lat, lng) {
        if (lat < -90 || lat > 90) {
            throw new Error("Latitude desimal harus berada di rentang -90 sampai 90.");
        }
        if (lng < -180 || lng > 180) {
            throw new Error("Longitude desimal harus berada di rentang -180 sampai 180.");
        }
    }

    function parseDecimalPair(rawValue) {
        if (typeof rawValue !== "string" || rawValue.trim() === "") {
            throw new Error("Input decimal kosong. Gunakan format: lat, lng.");
        }

        var normalized = rawValue.trim().replace(/;/g, ",");
        var parts;

        if (normalized.indexOf(",") !== -1) {
            parts = normalized.split(",");
        } else {
            parts = normalized.split(/\s+/);
        }

        if (!Array.isArray(parts) || parts.length !== 2) {
            throw new Error("Format decimal tidak valid. Gunakan contoh: -8.1050786039, 113.7262102605");
        }

        var lat = parseDecimalNumber(parts[0], "Latitude");
        var lng = parseDecimalNumber(parts[1], "Longitude");
        validateDecimalRange(lat, lng);

        return { lat: lat, lng: lng };
    }

    function padTwoDigits(value) {
        return String(value).padStart(2, "0");
    }

    function formatSeconds(value) {
        var text = Number(value).toFixed(1);
        if (value < 10) {
            return "0" + text;
        }
        return text;
    }

    function decimalToDms(decimalValue, axis) {
        if (!Number.isFinite(decimalValue)) {
            throw new Error("Nilai decimal tidak valid.");
        }

        var maxDegree = axis === "lat" ? 90 : 180;
        if (decimalValue < -maxDegree || decimalValue > maxDegree) {
            throw new Error("Nilai decimal di luar rentang " + axis.toUpperCase() + ".");
        }

        var absolute = Math.abs(decimalValue);
        var degrees = Math.floor(absolute);
        var minuteFloat = (absolute - degrees) * 60;
        var minutes = Math.floor(minuteFloat);
        var secondFloat = (minuteFloat - minutes) * 60;
        var seconds = Number(secondFloat.toFixed(1));

        if (seconds >= 60) {
            seconds = 0;
            minutes += 1;
        }
        if (minutes >= 60) {
            minutes = 0;
            degrees += 1;
        }

        var direction;
        if (axis === "lat") {
            direction = decimalValue < 0 ? "S" : "N";
        } else {
            direction = decimalValue < 0 ? "W" : "E";
        }

        return degrees + "°" + padTwoDigits(minutes) + "'" + formatSeconds(seconds) + "\"" + direction;
    }

    function normalizeDmsText(value) {
        return String(value)
            .trim()
            .toUpperCase()
            .replace(/,/g, ".")
            .replace(/[º]/g, "°")
            .replace(/[′’]/g, "'")
            .replace(/[″”]/g, "\"");
    }

    function tokenizeDmsPair(rawValue) {
        var normalized = normalizeDmsText(rawValue);
        if (normalized === "") {
            throw new Error("Input DMS kosong. Gunakan format seperti: 8°06'18.3\"S 113°43'34.4\"E");
        }

        var tokens = normalized.match(/([+-]?\d[\d\s°'".:-]*[NSEW])/g);
        if (!tokens || tokens.length < 2) {
            throw new Error("Format DMS tidak valid. Pastikan ada arah N/S untuk latitude dan E/W untuk longitude.");
        }

        var latToken = "";
        var lngToken = "";

        tokens.forEach(function (token) {
            var cleanedToken = token.trim();
            var direction = cleanedToken.slice(-1);

            if (direction === "N" || direction === "S") {
                if (latToken !== "") {
                    throw new Error("Latitude DMS ditemukan lebih dari satu.");
                }
                latToken = cleanedToken;
                return;
            }

            if (direction === "E" || direction === "W") {
                if (lngToken !== "") {
                    throw new Error("Longitude DMS ditemukan lebih dari satu.");
                }
                lngToken = cleanedToken;
            }
        });

        if (latToken === "" || lngToken === "") {
            throw new Error("DMS harus memuat dua koordinat lengkap: latitude (N/S) dan longitude (E/W).");
        }

        return { latToken: latToken, lngToken: lngToken };
    }

    function parseSingleDmsCoordinate(dmsToken, axis) {
        var normalized = normalizeDmsText(dmsToken);
        var directionMatch = normalized.match(/[NSEW]$/);
        if (!directionMatch) {
            throw new Error("Koordinat DMS harus diakhiri arah mata angin (N, S, E, W).");
        }

        var direction = directionMatch[0];
        if (axis === "lat" && direction !== "N" && direction !== "S") {
            throw new Error("Latitude DMS harus menggunakan arah N atau S.");
        }
        if (axis === "lng" && direction !== "E" && direction !== "W") {
            throw new Error("Longitude DMS harus menggunakan arah E atau W.");
        }

        var numericSegments = normalized.replace(/[NSEW]/g, " ").match(/[-+]?\d+(?:\.\d+)?/g);
        if (!numericSegments || numericSegments.length === 0 || numericSegments.length > 3) {
            throw new Error("Format angka DMS tidak valid. Gunakan derajat, menit, detik.");
        }

        var degrees = Number(numericSegments[0]);
        var minutes = numericSegments.length > 1 ? Number(numericSegments[1]) : 0;
        var seconds = numericSegments.length > 2 ? Number(numericSegments[2]) : 0;

        if (!Number.isFinite(degrees) || !Number.isFinite(minutes) || !Number.isFinite(seconds)) {
            throw new Error("Nilai angka DMS tidak valid.");
        }
        if (minutes < 0 || seconds < 0) {
            throw new Error("Menit dan detik pada DMS tidak boleh negatif.");
        }
        if (minutes >= 60 || seconds >= 60) {
            throw new Error("Menit dan detik pada DMS harus kurang dari 60.");
        }

        var absoluteDegree = Math.abs(degrees);
        var maxDegree = axis === "lat" ? 90 : 180;
        if (absoluteDegree > maxDegree) {
            throw new Error("Derajat pada DMS di luar rentang " + axis.toUpperCase() + ".");
        }
        if (absoluteDegree === maxDegree && (minutes > 0 || seconds > 0)) {
            throw new Error("Nilai batas derajat maksimum tidak boleh memiliki menit/detik.");
        }

        var isNegativeDirection = direction === "S" || direction === "W";
        if (degrees < 0 && !isNegativeDirection) {
            throw new Error("Tanda minus bertentangan dengan arah mata angin.");
        }

        var decimal = absoluteDegree + (minutes / 60) + (seconds / 3600);
        decimal = isNegativeDirection ? -decimal : decimal;

        if (axis === "lat" && (decimal < -90 || decimal > 90)) {
            throw new Error("Hasil latitude DMS berada di luar rentang.");
        }
        if (axis === "lng" && (decimal < -180 || decimal > 180)) {
            throw new Error("Hasil longitude DMS berada di luar rentang.");
        }

        return decimal;
    }

    function setConverterStatus(type, message) {
        if (!converterElements.status) {
            return;
        }

        converterElements.status.className = "alert alert-" + type;
        converterElements.status.textContent = String(message);
        converterElements.status.classList.remove("hidden");
    }

    function hideConverterStatus() {
        if (!converterElements.status) {
            return;
        }

        converterElements.status.className = "alert alert-warning hidden";
        converterElements.status.textContent = "";
    }

    function setConverterResult(textValue) {
        if (!converterElements.result || !converterElements.resultText) {
            return;
        }

        converterElements.resultText.textContent = String(textValue);
        converterElements.result.classList.remove("hidden");
        converterLastResultText = String(textValue);

        if (converterElements.copyFeedback) {
            converterElements.copyFeedback.textContent = "";
        }
    }

    function hideConverterResult() {
        if (!converterElements.result) {
            return;
        }

        converterElements.result.classList.add("hidden");
        converterLastResultText = "";

        if (converterElements.copyFeedback) {
            converterElements.copyFeedback.textContent = "";
        }
    }

    function copyToClipboard(textValue) {
        if (navigator.clipboard && typeof navigator.clipboard.writeText === "function" && window.isSecureContext) {
            return navigator.clipboard.writeText(textValue);
        }

        return new Promise(function (resolve, reject) {
            var textarea = document.createElement("textarea");
            textarea.value = textValue;
            textarea.style.position = "fixed";
            textarea.style.left = "-9999px";
            textarea.style.top = "-9999px";
            document.body.appendChild(textarea);
            textarea.focus();
            textarea.select();

            try {
                var successful = document.execCommand("copy");
                document.body.removeChild(textarea);
                if (successful) {
                    resolve();
                } else {
                    reject(new Error("Browser menolak proses copy."));
                }
            } catch (error) {
                document.body.removeChild(textarea);
                reject(error);
            }
        });
    }

    function initGeocoder() {
        if (!config.hasMapsApiKey) {
            return null;
        }

        if (geocoder) {
            return geocoder;
        }

        if (window.google && window.google.maps && typeof window.google.maps.Geocoder === "function") {
            geocoder = new window.google.maps.Geocoder();
            return geocoder;
        }

        return null;
    }

    function waitForGeocoder(maxWaitMs) {
        return new Promise(function (resolve) {
            var startedAt = Date.now();

            function check() {
                var instance = initGeocoder();
                if (instance) {
                    resolve(instance);
                    return;
                }

                if (Date.now() - startedAt >= maxWaitMs) {
                    resolve(null);
                    return;
                }

                window.setTimeout(check, 150);
            }

            check();
        });
    }

    function getAddressFromCoordinates(lat, lng) {
        return new Promise(function (resolve) {
            waitForGeocoder(2200).then(function (geocoderInstance) {
                if (!geocoderInstance) {
                    resolve("Alamat tidak tersedia (Google Maps API belum siap).");
                    return;
                }

                geocoderInstance.geocode(
                    { location: { lat: lat, lng: lng } },
                    function (results, status) {
                        if (status !== "OK" || !Array.isArray(results) || results.length === 0) {
                            resolve("Alamat tidak ditemukan untuk koordinat ini.");
                            return;
                        }

                        var preferred = results.find(function (item) {
                            return typeof item.formatted_address === "string" && item.formatted_address.indexOf("+") !== -1;
                        });

                        var address = preferred && preferred.formatted_address
                            ? preferred.formatted_address
                            : (results[0].formatted_address || "Alamat tidak ditemukan untuk koordinat ini.");

                        resolve(address);
                    }
                );
            });
        });
    }

    function updateMapLegend(lat, lng, statusText) {
        if (mapElements.legendLat) {
            mapElements.legendLat.textContent = Number(lat).toFixed(7);
        }
        if (mapElements.legendLng) {
            mapElements.legendLng.textContent = Number(lng).toFixed(7);
        }
        if (mapElements.legendZoom && map) {
            mapElements.legendZoom.textContent = String(map.getZoom());
        }
        if (mapElements.legendStatus && typeof statusText === "string" && statusText !== "") {
            mapElements.legendStatus.textContent = statusText;
        }
    }

    function setDecimalInputFromCoordinates(lat, lng) {
        if (!converterElements.decimalInput) {
            return;
        }

        converterElements.decimalInput.value = formatDecimal(lat) + ", " + formatDecimal(lng);
    }

    function showMapNotice(message) {
        if (!mapElements.canvas) {
            return;
        }

        mapElements.canvas.classList.add("map-notice-mode");
        mapElements.canvas.innerHTML = '<div class="map-notice">' + String(message) + "</div>";

        if (mapElements.legendStatus) {
            mapElements.legendStatus.textContent = message;
        }
    }

    function normalizeMapType(value) {
        var supported = ["roadmap", "satellite", "hybrid", "terrain"];
        var type = String(value || "").toLowerCase();
        return supported.indexOf(type) !== -1 ? type : "hybrid";
    }

    function moveMarkerTo(lat, lng, centerMap, statusText) {
        if (!mapReady || !marker || !map || !isValidLatLng(lat, lng)) {
            return;
        }

        var nextPosition = { lat: lat, lng: lng };
        marker.setPosition(nextPosition);

        if (centerMap) {
            map.panTo(nextPosition);
        }

        setDecimalInputFromCoordinates(lat, lng);
        updateMapLegend(lat, lng, statusText || "Koordinat marker diperbarui.");
    }

    function bindMapInteractions() {
        if (!map || !marker) {
            return;
        }

        map.addListener("click", function (event) {
            var lat = event.latLng.lat();
            var lng = event.latLng.lng();
            moveMarkerTo(lat, lng, false, "Titik dipilih dari klik peta.");
        });

        marker.addListener("drag", function (event) {
            var lat = event.latLng.lat();
            var lng = event.latLng.lng();
            setDecimalInputFromCoordinates(lat, lng);
            updateMapLegend(lat, lng, "Marker sedang digeser...");
        });

        marker.addListener("dragend", function (event) {
            var lat = event.latLng.lat();
            var lng = event.latLng.lng();
            moveMarkerTo(lat, lng, false, "Marker selesai dipindahkan.");
        });

        map.addListener("zoom_changed", function () {
            if (!marker) {
                return;
            }

            var position = marker.getPosition();
            if (!position) {
                return;
            }

            updateMapLegend(position.lat(), position.lng(), "Zoom diperbarui.");
        });

        map.addListener("maptypeid_changed", function () {
            if (mapElements.viewSelect && !isApplyingMapTypeFromSelect) {
                mapElements.viewSelect.value = normalizeMapType(map.getMapTypeId());
            }

            if (!marker) {
                return;
            }

            var position = marker.getPosition();
            if (!position) {
                return;
            }

            updateMapLegend(position.lat(), position.lng(), "Mode peta: " + normalizeMapType(map.getMapTypeId()) + ".");
        });

        if (mapElements.viewSelect) {
            mapElements.viewSelect.addEventListener("change", function () {
                if (!map) {
                    return;
                }

                var nextType = normalizeMapType(mapElements.viewSelect.value);
                isApplyingMapTypeFromSelect = true;
                map.setMapTypeId(nextType);
                isApplyingMapTypeFromSelect = false;
            });
        }
    }

    function applyCoordinateToMap(lat, lng, statusText) {
        if (!isValidLatLng(lat, lng)) {
            return;
        }

        if (mapReady) {
            moveMarkerTo(lat, lng, true, statusText || "Koordinat diperbarui dari input.");
        } else {
            setDecimalInputFromCoordinates(lat, lng);
            updateMapLegend(lat, lng, statusText || "Koordinat siap. Peta menunggu inisialisasi.");
        }
    }

    function renderResultWithAddress(baseText, lat, lng) {
        var loadingText = baseText + "\nAlamat: Mencari alamat...";
        setConverterResult(loadingText);

        getAddressFromCoordinates(lat, lng)
            .then(function (address) {
                setConverterResult(baseText + "\nAlamat: " + address);
            })
            .catch(function () {
                setConverterResult(baseText + "\nAlamat: Gagal mengambil alamat.");
            });
    }

    function runDecimalToDmsConversion() {
        if (!converterElements.decimalInput || !converterElements.dmsInput) {
            return;
        }

        try {
            hideConverterStatus();

            var decimalPair = parseDecimalPair(converterElements.decimalInput.value);
            var latDms = decimalToDms(decimalPair.lat, "lat");
            var lngDms = decimalToDms(decimalPair.lng, "lng");
            var combinedDms = latDms + " " + lngDms;
            converterElements.dmsInput.value = combinedDms;

            applyCoordinateToMap(decimalPair.lat, decimalPair.lng, "Koordinat dari konversi Decimal → DMS.");

            var baseText =
                "Mode: Decimal → DMS\n" +
                "Input Decimal: " + formatDecimal(decimalPair.lat) + ", " + formatDecimal(decimalPair.lng) + "\n" +
                "Hasil DMS: " + combinedDms;

            renderResultWithAddress(baseText, decimalPair.lat, decimalPair.lng);
            setConverterStatus("success", "Konversi decimal ke DMS berhasil.");
        } catch (error) {
            hideConverterResult();
            setConverterStatus("error", error instanceof Error ? error.message : "Terjadi kesalahan pada konversi decimal.");
        }
    }

    function runDmsToDecimalConversion() {
        if (!converterElements.decimalInput || !converterElements.dmsInput) {
            return;
        }

        try {
            hideConverterStatus();

            var pairTokens = tokenizeDmsPair(converterElements.dmsInput.value);
            var latDecimal = parseSingleDmsCoordinate(pairTokens.latToken, "lat");
            var lngDecimal = parseSingleDmsCoordinate(pairTokens.lngToken, "lng");
            validateDecimalRange(latDecimal, lngDecimal);

            var combinedDecimal = formatDecimal(latDecimal) + ", " + formatDecimal(lngDecimal);
            converterElements.decimalInput.value = combinedDecimal;
            applyCoordinateToMap(latDecimal, lngDecimal, "Koordinat dari konversi DMS → Decimal.");

            var baseText =
                "Mode: DMS → Decimal\n" +
                "Input DMS: " + pairTokens.latToken + " " + pairTokens.lngToken + "\n" +
                "Hasil Decimal: " + combinedDecimal;

            renderResultWithAddress(baseText, latDecimal, lngDecimal);
            setConverterStatus("success", "Konversi DMS ke decimal berhasil.");
        } catch (error) {
            hideConverterResult();
            setConverterStatus("error", error instanceof Error ? error.message : "Terjadi kesalahan pada konversi DMS.");
        }
    }

    function bindConverterCopyAction() {
        if (!converterElements.copyButton) {
            return;
        }

        converterElements.copyButton.addEventListener("click", function () {
            if (!converterLastResultText) {
                setConverterStatus("warning", "Belum ada hasil konversi yang bisa disalin.");
                return;
            }

            copyToClipboard(converterLastResultText)
                .then(function () {
                    if (converterElements.copyFeedback) {
                        converterElements.copyFeedback.textContent = "Hasil berhasil disalin.";
                    }
                })
                .catch(function () {
                    if (converterElements.copyFeedback) {
                        converterElements.copyFeedback.textContent = "Copy otomatis gagal. Silakan salin manual dari hasil.";
                    }
                });
        });
    }

    function bindDecimalInputToMap() {
        if (!converterElements.decimalInput) {
            return;
        }

        var applyFromInput = function () {
            try {
                var pair = parseDecimalPair(converterElements.decimalInput.value);
                applyCoordinateToMap(pair.lat, pair.lng, "Koordinat dari input decimal.");
            } catch (error) {
                // Ignore while user typing invalid interim format.
            }
        };

        converterElements.decimalInput.addEventListener("blur", applyFromInput);
        converterElements.decimalInput.addEventListener("change", applyFromInput);
    }

    function setupCoordinateConverter() {
        converterElements.decimalInput = document.getElementById("decimal-source");
        converterElements.dmsInput = document.getElementById("dms-source");
        converterElements.toDmsButton = document.getElementById("convert-decimal-to-dms");
        converterElements.toDecimalButton = document.getElementById("convert-dms-to-decimal");
        converterElements.result = document.getElementById("converter-result");
        converterElements.resultText = document.getElementById("converter-result-text");
        converterElements.status = document.getElementById("converter-status");
        converterElements.copyButton = document.getElementById("copy-result-btn");
        converterElements.copyFeedback = document.getElementById("copy-feedback");

        if (!converterElements.decimalInput || !converterElements.dmsInput) {
            return;
        }

        if (converterElements.toDmsButton) {
            converterElements.toDmsButton.addEventListener("click", runDecimalToDmsConversion);
        }
        if (converterElements.toDecimalButton) {
            converterElements.toDecimalButton.addEventListener("click", runDmsToDecimalConversion);
        }

        bindConverterCopyAction();
        bindDecimalInputToMap();
    }

    function setupMapDomReferences() {
        mapElements.canvas = document.getElementById("map-canvas");
        mapElements.viewSelect = document.getElementById("map-view-select");
        mapElements.legendLat = document.getElementById("legend-lat");
        mapElements.legendLng = document.getElementById("legend-lng");
        mapElements.legendZoom = document.getElementById("legend-zoom");
        mapElements.legendStatus = document.getElementById("legend-status");
    }

    function initializeMap() {
        if (mapReady) {
            return;
        }

        if (!mapElements.canvas) {
            setupMapDomReferences();
        }

        if (!mapElements.canvas) {
            return;
        }

        if (!config.hasMapsApiKey) {
            showMapNotice("MAPS_API_KEY belum diset. Peta interaktif dinonaktifkan.");
            return;
        }

        if (!window.google || !window.google.maps) {
            showMapNotice("Google Maps belum siap. Periksa koneksi internet atau API key.");
            return;
        }

        mapElements.canvas.classList.remove("map-notice-mode");
        mapElements.canvas.innerHTML = "";

        var initialType = mapElements.viewSelect ? normalizeMapType(mapElements.viewSelect.value) : "hybrid";

        map = new window.google.maps.Map(mapElements.canvas, {
            center: defaultCenter,
            zoom: 14,
            mapTypeId: initialType,
            mapTypeControl: true,
            mapTypeControlOptions: {
                style: window.google.maps.MapTypeControlStyle.DEFAULT,
                mapTypeIds: ["roadmap", "satellite", "hybrid", "terrain"]
            },
            streetViewControl: true,
            zoomControl: true,
            fullscreenControl: true,
            scaleControl: true,
            gestureHandling: "cooperative"
        });

        marker = new window.google.maps.Marker({
            position: defaultCenter,
            map: map,
            draggable: true,
            animation: window.google.maps.Animation.DROP,
            title: "Titik koordinat aktif"
        });

        var initialCoordinate = defaultCenter;
        if (converterElements.decimalInput) {
            try {
                initialCoordinate = parseDecimalPair(converterElements.decimalInput.value);
            } catch (error) {
                initialCoordinate = defaultCenter;
            }
        }

        mapReady = true;
        initGeocoder();
        bindMapInteractions();
        moveMarkerTo(
            initialCoordinate.lat,
            initialCoordinate.lng,
            true,
            "Peta siap. Klik peta atau geser marker untuk memilih titik."
        );
    }

    window.initBedadungMap = function () {
        initializeMap();
    };

    document.addEventListener("DOMContentLoaded", function () {
        setupCoordinateConverter();
        setupMapDomReferences();

        if (!config.hasMapsApiKey) {
            showMapNotice("MAPS_API_KEY belum diset. Peta interaktif dan alamat otomatis dinonaktifkan.");
            return;
        }

        if (window.google && window.google.maps) {
            initializeMap();
            return;
        }

        if (!window.google || !window.google.maps) {
            showMapNotice("Menunggu Google Maps API...");
        }
    });
})();
