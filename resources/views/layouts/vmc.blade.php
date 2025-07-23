<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'AATC VMS')</title>

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <meta name="apple-mobile-web-app-title" content="VMS">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0d9488',
                        secondary: '#f3f4f6',
                    }
                }
            }
        }
    </script>
    <style>
        .tab-content {
            transition: opacity 1s ease;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50">
    @yield('body')

    @livewireScripts
    <script>
        // Tab functionality
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });

            // Show selected tab content
            document.getElementById(tabName + '-tab').classList.remove('hidden');

            // Update tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                if (button.getAttribute('data-tab') === tabName) {
                    button.classList.add('border-[#fecd01]', 'text-[#007570]');
                    button.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                } else {
                    button.classList.remove('border-[#fecd01]', 'text-[#007570]');
                    button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                }
            });

            // Update URL
            history.pushState(null, null, '#' + tabName);
        }

        // Check for hash on page load
        window.addEventListener('load', function() {
            if (window.location.hash) {
                const tabName = window.location.hash.substring(1);
                if (['walk-in', 'staff-guest', 'history'].includes(tabName)) {
                    showTab(tabName);
                }
            }
        });

        // Modal functions
        function openModal() {
            document.getElementById('export-modal').classList.remove('hidden');
        }

        function closeExportModal() {
            document.getElementById('export-modal').classList.add('hidden');
        }

        // Show toast notification
        function showToast(message) {
            const toast = document.getElementById('toast');
            document.getElementById('toast-message').textContent = message;
            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 5000);
        }

        // CSRF Token setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Check in visitor
        function checkInVisitor(visitId) {
            if (confirm('Are you sure you want to check in this visitor?')) {
                fetch(`{{ route('reception.dashboard') }}/check-in/${visitId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.success);
                        location.reload();
                    } else {
                        alert(data.error || 'An error occurred');
                    }
                })
                .catch(error => {
                    console.error('Check-in error:', error);
                    alert('An error occurred while checking in the visitor');
                });
            }
        }

        // Check out visitor
        function checkOutVisitor(visitId) {
            if (confirm('Are you sure you want to check out this visitor?')) {
                fetch(`{{ route('reception.dashboard') }}/check-out/${visitId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.success);
                        location.reload();
                    } else {
                        alert(data.error || 'An error occurred');
                    }
                })
                .catch(error => {
                    console.error('Check-out error:', error);
                    alert('An error occurred while checking out the visitor');
                });
            }
        }

        // Form handling for walk-in guests
        document.addEventListener('DOMContentLoaded', function() {
            let guests = [{}]; // Array to store guest data
            let currentGuestIndex = 0;

            // Form elements
            const form = document.getElementById('walk-in-form');
            if (form) {
                const guestNameInput = document.getElementById('guest_name');
                const guestEmailInput = document.getElementById('guest_email');
                const guestPhoneInput = document.getElementById('guest_phone');
                const organizationInput = document.getElementById('organization');
                const visitReasonInput = document.getElementById('visit_reason');
                const visitDateInput = document.getElementById('visit_date');
                const visitTimeInput = document.getElementById('visit_time');
                const floorSelect = document.getElementById('floor');

                // Control elements
                const addGuestBtn = document.getElementById('add-guest');
                const removeGuestBtn = document.getElementById('remove-guest');
                const prevGuestBtn = document.getElementById('prev-guest');
                const nextGuestBtn = document.getElementById('next-guest');
                const guestNavigation = document.getElementById('guest-navigation');
                const guestCount = document.getElementById('guest-count');
                const guestIndicator = document.getElementById('guest-indicator');
                const submitCount = document.getElementById('submit-count');
                const downloadTemplateBtn = document.getElementById('download-template');
                const csvImportInput = document.getElementById('csv-import');

                // Summary elements
                const summaryName = document.getElementById('summary-name');
                const summaryEmail = document.getElementById('summary-email');
                const summaryDate = document.getElementById('summary-date');
                const summaryFloor = document.getElementById('summary-floor');

                // Update UI based on current state
                function updateUI() {
                    // Update counters
                    guestCount.textContent = `${guests.length} Guest${guests.length !== 1 ? 's' : ''}`;
                    guestIndicator.textContent = `Guest ${currentGuestIndex + 1} of ${guests.length}`;
                    submitCount.textContent = guests.length;

                    // Show/hide navigation
                    if (guests.length > 1) {
                        guestNavigation.classList.remove('hidden');
                        removeGuestBtn.classList.remove('hidden');
                    } else {
                        guestNavigation.classList.add('hidden');
                        removeGuestBtn.classList.add('hidden');
                    }

                    // Update navigation buttons
                    prevGuestBtn.disabled = currentGuestIndex === 0;
                    nextGuestBtn.disabled = currentGuestIndex === guests.length - 1;

                    // Update add button state
                    addGuestBtn.disabled = !isCurrentGuestComplete();
                }

                // Load guest data into form
                function loadGuestData() {
                    const guest = guests[currentGuestIndex] || {};

                    guestNameInput.value = guest.name || '';
                    guestEmailInput.value = guest.email || '';
                    guestPhoneInput.value = guest.phone || '';
                    organizationInput.value = guest.organization || '';
                    visitReasonInput.value = guest.reason || '';
                    visitDateInput.value = guest.date || '';
                    visitTimeInput.value = guest.time || '';
                    floorSelect.value = guest.floor || '';

                    updateSummary();
                }

                // Save current form data to guests array
                function saveCurrentGuestData() {
                    guests[currentGuestIndex] = {
                        name: guestNameInput.value,
                        email: guestEmailInput.value,
                        phone: guestPhoneInput.value,
                        organization: organizationInput.value,
                        reason: visitReasonInput.value,
                        date: visitDateInput.value,
                        time: visitTimeInput.value,
                        floor: floorSelect.value
                    };
                }

                // Check if current guest form is complete
                function isCurrentGuestComplete() {
                    const guest = guests[currentGuestIndex] || {};
                    return guest.name && guest.email && guest.phone && guest.reason && guest.date && guest.time && guest.floor;
                }

                // Update summary panel
                function updateSummary() {
                    const guest = guests[currentGuestIndex] || {};
                    summaryName.textContent = guest.name || 'Not specified';
                    summaryEmail.textContent = guest.email || 'Not specified';
                    summaryDate.textContent = guest.date || 'Not specified';
                    summaryFloor.textContent = guest.floor || 'Not specified';
                }

                // Show toast message
                function showToast(title, message, type = 'success') {
                    const toast = document.createElement('div');
                    toast.className = `p-4 mb-4 rounded-lg ${type === 'success' ? 'bg-green-100 border border-green-200 text-green-800' : 'bg-red-100 border border-red-200 text-red-800'}`;
                    toast.innerHTML = `
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium">${title}</h3>
                                <p class="text-sm mt-1">${message}</p>
                            </div>
                            <button type="button" class="ml-auto -mx-1.5 -my-1.5 rounded-lg p-1.5 hover:bg-gray-100" onclick="this.parentElement.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;

                    document.getElementById('message-container').appendChild(toast);

                    setTimeout(() => {
                        if (toast.parentElement) {
                            toast.remove();
                        }
                    }, 5000);
                }

                // Event listeners for form inputs
                [guestNameInput, guestEmailInput, guestPhoneInput, organizationInput, visitReasonInput, visitDateInput, visitTimeInput, floorSelect].forEach(input => {
                    input.addEventListener('input', () => {
                        saveCurrentGuestData();
                        updateSummary();
                        updateUI();
                    });
                });

                // Add guest button
                addGuestBtn.addEventListener('click', () => {
                    if (isCurrentGuestComplete()) {
                        guests.push({});
                        currentGuestIndex = guests.length - 1;
                        loadGuestData();
                        updateUI();
                    }
                });

                // Remove guest button
                removeGuestBtn.addEventListener('click', () => {
                    if (guests.length > 1) {
                        guests.splice(currentGuestIndex, 1);
                        currentGuestIndex = Math.max(0, currentGuestIndex - 1);
                        loadGuestData();
                        updateUI();
                    }
                });

                // Navigation buttons
                prevGuestBtn.addEventListener('click', () => {
                    if (currentGuestIndex > 0) {
                        currentGuestIndex--;
                        loadGuestData();
                        updateUI();
                    }
                });

                nextGuestBtn.addEventListener('click', () => {
                    if (currentGuestIndex < guests.length - 1) {
                        currentGuestIndex++;
                        loadGuestData();
                        updateUI();
                    }
                });

                // Download CSV template
                downloadTemplateBtn.addEventListener('click', () => {
                    const csvContent = "Guest Name,Email,Phone,Organization,Visit Reason,Visit Date,Visit Time,Floor\n" +
                                      "John Doe,john@example.com,+1234567890,ABC Corp,Business Meeting,2024-01-15,14:00,ground\n" +
                                      "Jane Smith,jane@example.com,+1987654321,XYZ Ltd,Project Review,2024-01-16,10:30,mezzanine\n" +
                                      "Chinedu Okafor,chinedu.okafor@example.com,+2348012345678,Zentech Ltd,Tech Demo,2024-01-17,11:15,1st\n" +
                                      "Amina Bello,amina.bello@example.com,+2348098765432,GreenEdge Consult,Client Onboarding,2024-01-18,09:45,2nd"

                    const blob = new Blob([csvContent], { type: 'text/csv' });
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'guest_invitation_template.csv';
                    a.click();
                    window.URL.revokeObjectURL(url);

                    showToast('Template Downloaded', 'CSV template has been downloaded to your device.');
                });

                // CSV import
                csvImportInput.addEventListener('change', (event) => {
                    const file = event.target.files[0];
                    if (!file) return;

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const text = e.target.result;
                        const lines = text.split('\n');
                        const headers = lines[0].split(',');

                        const importedGuests = [];

                        for (let i = 1; i < lines.length; i++) {
                            const values = lines[i].split(',');
                            if (values.length >= 8 && values[0].trim()) {
                                importedGuests.push({
                                    name: values[0].trim(),
                                    email: values[1].trim(),
                                    phone: values[2].trim(),
                                    organization: values[3].trim(),
                                    reason: values[4].trim(),
                                    date: values[5].trim(),
                                    time: values[6].trim(),
                                    floor: values[7].trim(),
                                });
                            }
                        }

                        if (importedGuests.length > 0) {
                            guests = importedGuests;
                            currentGuestIndex = 0;
                            loadGuestData();
                            updateUI();
                            showToast('CSV Imported Successfully', `${importedGuests.length} guest(s) imported from CSV file.`);
                        }
                    };

                    reader.readAsText(file);
                    event.target.value = '';
                });

                // Form submission
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    saveCurrentGuestData();

                    // Format data for controller
                    const formData = {
                        guests: guests.map(guest => ({
                            name: guest.name,
                            email: guest.email,
                            phone: guest.phone,
                            organization: guest.organization,
                            reason: guest.reason,
                            date: guest.date,
                            time: guest.time,
                            floor: guest.floor
                        })),
                        _token: document.querySelector('meta[name="csrf-token"]').content
                    };

                    fetch("{{ route('reception.dashboard.invite') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(formData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Invitations Sent Successfully!', `${guests.length} guest(s) will receive email invitations with unique codes.`);
                            guests = [{}];
                            currentGuestIndex = 0;
                            loadGuestData();
                            updateUI();
                        } else {
                            showToast('Error', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Error', 'An error occurred while sending invitations', 'error');
                    });
                });

                // Initialize
                updateUI();
                updateSummary();
            }
        });

        // Excel download function
        function downloadExcel(data) {
            const worksheet = XLSX.utils.json_to_sheet(data);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Visitor History');
            XLSX.writeFile(workbook, 'visitor_history.xlsx');
        }

         // PDF download function
         async function downloadPDF(data) {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            const headers = Object.keys(data[0] || {});
            let y = 10;

            doc.setFontSize(10);
            doc.text('Visitor History Report', 10, y);
            y += 10;

            data.forEach(row => {
                let line = headers.map(h => `${h}: ${row[h] ?? ''}`).join(' | ');
                doc.text(line, 10, y);
                y += 7;
                if (y > 270) {
                    doc.addPage();
                    y = 10;
                }
            });

            doc.save('visitor_history.pdf');
        }

    </script>
</body>
</html>
