<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('QR Code Scanner - AATC Visitor Management') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <!-- QR Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .scanner-container {
            max-width: 600px;
            margin: auto;
            padding: 3rem 1rem;
            background-color: white;
            border-radius: 1.5rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            margin-top: 5rem;
            text-align: center;
        }
        .scanner-title {
            color: #4361ee;
            font-weight: 800;
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
        }
        #reader {
            width: 100%;
            max-width: 400px;
            margin: 0 auto 1.5rem;
        }
        #result {
            font-size: 1rem;
            color: #212529;
            background-color: #e9f7ef;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-top: 1rem;
            min-height: 60px;
            word-wrap: break-word;
        }
        .footer {
            text-align: center;
            margin-top: auto;
            padding: 1rem;
            font-size: 0.875rem;
            color: #6c757d;
        }
        .logo {
            width: 60px;
            margin-bottom: 1rem;
        }
        .visitor-info {
            margin-top: 1rem;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 0.5rem;
        }
        .language-switcher {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 10;
        }
        @media (max-width: 768px) {
            .language-switcher {
                position: static;
                margin-bottom: 1rem;
                display: flex;
                justify-content: flex-end;
            }
        }
    </style>
</head>
<body>

    <div class="language-switcher">
        @include('partials.language_switcher')
    </div>

<div class="scanner-container">
    <img src="{{ asset('assets/logo-green-yellow.png') }}" alt="AATC Logo" class="logo">
    <div class="scanner-title">{{ __('Scan Visitor QR Code') }}</div>

    <div class="mb-3">
        <input type="text" id="manualCode" class="form-control" placeholder="{{ __('Enter Unique Code Manually') }}">
        <button id="manualSearchBtn" class="btn btn-outline-primary mt-2">{{ __('Search') }}</button>
    </div>

    <div id="reader"></div>
    <div id="result">{{ __('Waiting for scan...') }}</div>
    <div id="visitorInfo" class="visitor-info" style="display: none;"></div>
    <button id="checkinBtn" class="btn btn-success mt-3" style="display: none;">{{ __('Check-In Visitor') }}</button>
</div>

<div class="mt-3 text-center">
    <button id="notifyBtn" class="btn btn-primary" style="display: none;">{{ __('Notify Host') }}</button>
</div>

<div class="footer">
    &copy; {{ date('Y') }} {{__("AATC Visitor Management System")}}
</div>

<script>
    let currentVisitorData = null;

    function onScanSuccess(decodedText, decodedResult) {
        html5QrcodeScanner.clear().then(() => {
            console.log("QR Scanner stopped");
        }).catch(err => console.error("Failed to stop scanner", err));

        document.getElementById("result").innerHTML = "Processing QR code...";

        fetch("{{ route('gate.scanner.verify') }}", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "qr_data=" + encodeURIComponent(decodedText),
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "FOUND") {
                currentVisitorData = data;
                displayVisitorInfo(data);
                document.getElementById("checkinBtn").style.display = "inline-block";
                document.getElementById("notifyBtn").style.display = "inline-block";
            } else {
                document.getElementById("result").innerHTML = data.message;
                hideVisitorActions();
            }
        })
        .catch(() => {
            document.getElementById("result").innerHTML = "Error processing request";
        });
    }

    function handleManualSearch() {
        const code = document.getElementById("manualCode").value.trim();
        if (!code) return alert("Please enter a valid code.");

        document.getElementById("result").innerHTML = "Searching...";

        fetch("{{ route('gate.scanner.search') }}", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "code=" + encodeURIComponent(code),
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "FOUND") {
                currentVisitorData = data.visitor;
                displayVisitorInfo(data.visitor);
                document.getElementById("checkinBtn").style.display = "inline-block";
                document.getElementById("notifyBtn").style.display = "inline-block";
            } else {
                document.getElementById("result").innerHTML = data.message;
                hideVisitorActions();
            }
        })
        .catch(() => {
            document.getElementById("result").innerHTML = "Error occurred while searching.";
        });
    }

    function displayVisitorInfo(data) {
        const visitorInfoDiv = document.getElementById("visitorInfo");
        visitorInfoDiv.style.display = "block";
        visitorInfoDiv.innerHTML = `
            <h5>{{ __('Visitor Information') }}</h5>
            <p><strong>{{ __('Name') }}:</strong> ${data.visitor_name}</p>
            <p><strong>{{ __('Company') }}:</strong> ${data.company}</p>
            <p><strong>{{ __('Host') }}:</strong> ${data.host_name}</p>
            <p><strong>{{ __('Purpose') }}:</strong> ${data.purpose}</p>
        `;
        document.getElementById("result").innerHTML = "{{ __('Visitor verified successfully!') }}";
    }

    function hideVisitorActions() {
        document.getElementById("visitorInfo").style.display = "none";
        document.getElementById("checkinBtn").style.display = "none";
        document.getElementById("notifyBtn").style.display = "none";
    }

    function checkInVisitor() {
        if (!currentVisitorData) return;

        fetch("{{ route('gate.scanner.checkin') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                visitor_id: currentVisitorData.visitor_id,
                qr_data: currentVisitorData.qr_data
            }),
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                document.getElementById("checkinBtn").style.display = "none";
            }
        })
        .catch(() => {
            alert("Error during check-in");
        });
    }

    function notifyHost() {
        if (!currentVisitorData) return;

        const btn = document.getElementById("notifyBtn");
        btn.disabled = true;
        btn.textContent = "{{ __('Sending...') }}";

        fetch("{{ route('gate.scanner.notify') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                visitor_id: currentVisitorData.visitor_id,
                staff_id: currentVisitorData.staff_id
            }),
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                btn.textContent = "{{ __('Host Notified') }}";
            } else {
                btn.disabled = false;
                btn.textContent = "{{ __('Notify Host') }}";
            }
        })
        .catch(() => {
            alert("Error notifying host");
            btn.disabled = false;
            btn.textContent = "{{ __('Notify Host') }}";
        });
    }

    const html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
    html5QrcodeScanner.render(onScanSuccess);

    document.getElementById("manualSearchBtn").addEventListener("click", handleManualSearch);
    document.getElementById("checkinBtn").addEventListener("click", checkInVisitor);
    document.getElementById("notifyBtn").addEventListener("click", notifyHost);
</script>

</body>
</html>
