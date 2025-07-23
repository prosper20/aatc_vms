@extends('layouts.vmc')

@section('title', 'VMC Dashboard')

@section('body')
    <!-- Header -->
    <header class="bg-white text-gray-900 shadow-lg sticky top-0 z-50">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-3 lg:py-6 md:py-4">
                <!-- Logo/Branding Section -->
                <div class="flex items-center space-x-3 md:space-x-4">
                    <div class="flex-shrink-0">
                        <img src="{{ asset('assets/logo-green-yellow.png') }}" alt="Logo" class="h-10 w-auto md:h-12">
                    </div>
                    <div class="hidden sm:block">
                        <h1 class="text-lg md:text-2xl font-semibold leading-tight">Visitor Management Center</h1>
                        <p class="text-xs md:text-[14px] opacity-90">VMC Portal</p>
                    </div>
                </div>

                <!-- User Controls -->
                <div class="flex items-center space-x-2 md:space-x-4">
                    <div class="hidden sm:block text-right">
                        <p class="text-sm md:text-base font-medium truncate max-w-[160px] md:max-w-none">Welcome, {{$firstName}}</p>
                    </div>
                    <form method="POST" action="{{ route('reception.logout') }}">
                        @csrf
                        <a href="{{ route('reception.dashboard') }}" class="bg-[#ffcd00] hover:bg-[#e6b800] text-[#00736e] px-4 py-2 rounded-lg text-sm font-medium transition-colors ">
                            <i class="fas fa-arrow-left mr-2 hidden md:inline"></i>Back
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-[84rem] mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">
        <!-- Search and Register Section - Stacked on mobile -->
        <div class="flex flex-col sm:flex-row gap-4 mb-6 justify-between">

        </div>

        <!-- Visitor Management Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8" aria-label="Tabs">
                    <button onclick="showTab('walk-in')" class="tab-button py-4 px-6 border-b-2 font-medium text-sm transition-colors border-[#fecd01] text-[#007570]" data-tab="walk-in" data-state="active">
                        Walk-in Guest
                    </button>
                    <button onclick="showTab('staff-guest')" class="tab-button py-4 px-6 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="staff-guest">
                        Staff Guests
                    </button>
                    <button onclick="showTab('history')" class="tab-button py-4 px-6 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="history">
                        Visitor History
                    </button>
                </nav>
            </div>

            <div class="p-6">
                <!-- Walk-in Guest Tab -->
                <div id="walk-in-tab" class="tab-content">
                    <livewire:vmc.walk-in />
                </div>

                <!-- Staff Guests Tab -->
                <div id="staff-guest-tab" class="tab-content hidden">
                    <livewire:vmc.staff-guest />
                </div>

                <!-- Visitor History Tab -->
                <div id="history-tab" class="tab-content hidden">
                    <livewire:vmc.visitor-history />
                </div>
            </div>
        </div>
    </main>

    <div id="export-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle w-full sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Export Visitor Data</h3>
                    <form id="export-form" class="space-y-6">
                        @csrf

                        <!-- Quick Export Options -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quick Export</label>
                            <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                                <button type="button" onclick="setQuickExport('today')" class="quick-export-btn px-3 py-2 text-sm rounded-lg border border-gray-300 hover:bg-gray-50">
                                    Today
                                </button>
                                <button type="button" onclick="setQuickExport('week')" class="quick-export-btn px-3 py-2 text-sm rounded-lg border border-gray-300 hover:bg-gray-50">
                                    This Week
                                </button>
                                <button type="button" onclick="setQuickExport('month')" class="quick-export-btn px-3 py-2 text-sm rounded-lg border border-gray-300 hover:bg-gray-50">
                                    This Month
                                </button>
                                <button type="button" onclick="setQuickExport('all')" class="quick-export-btn px-3 py-2 text-sm rounded-lg border border-gray-300 hover:bg-gray-50">
                                    All Time
                                </button>
                            </div>
                        </div>

                        <!-- Custom Date Range -->
                        <div class="border-t border-gray-200 pt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Custom Date Range</label>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="export_start_date" class="block text-xs font-medium text-gray-500 mb-1">From Date</label>
                                    <input type="date" name="start_date" id="export_start_date"
                                        class="w-full py-2 px-3 border border-gray-300 rounded-lg bg-slate-50 focus:outline-none focus:border-emerald-300 focus:bg-white">
                                </div>
                                <div>
                                    <label for="export_end_date" class="block text-xs font-medium text-gray-500 mb-1">To Date</label>
                                    <input type="date" name="end_date" id="export_end_date"
                                        class="w-full py-2 px-3 border border-gray-300 rounded-lg bg-slate-50 focus:outline-none focus:border-emerald-300 focus:bg-white">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mt-2">
                                <div>
                                    <label for="export_start_time" class="block text-xs font-medium text-gray-500 mb-1">From Time</label>
                                    <input type="time" name="start_time" id="export_start_time"
                                        class="w-full py-2 px-3 border border-gray-300 rounded-lg bg-slate-50 focus:outline-none focus:border-emerald-300 focus:bg-white">
                                </div>
                                <div>
                                    <label for="export_end_time" class="block text-xs font-medium text-gray-500 mb-1">To Time</label>
                                    <input type="time" name="end_time" id="export_end_time"
                                        class="w-full py-2 px-3 border border-gray-300 rounded-lg bg-slate-50 focus:outline-none focus:border-emerald-300 focus:bg-white">
                                </div>
                            </div>
                        </div>

                        <!-- Export Format -->
                        <div class="border-t border-gray-200 pt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Export Format</label>
                            <div class="flex space-x-4">
                                <div class="flex items-center">
                                    <input id="export_format_csv" name="export_format" type="radio" value="csv" checked
                                        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                    <label for="export_format_csv" class="ml-2 block text-sm text-gray-700">
                                        CSV
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="export_format_excel" name="export_format" type="radio" value="excel"
                                        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                    <label for="export_format_excel" class="ml-2 block text-sm text-gray-700">
                                        Excel
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="export_format_pdf" name="export_format" type="radio" value="pdf"
                                        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                    <label for="export_format_pdf" class="ml-2 block text-sm text-gray-700">
                                        PDF
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Options -->
                        <div class="border-t border-gray-200 pt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Options</label>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input id="include_checked_in" name="include_checked_in" type="checkbox" checked
                                        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                    <label for="include_checked_in" class="ml-2 block text-sm text-gray-700">
                                        Include checked-in visitors
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="include_checked_out" name="include_checked_out" type="checkbox" checked
                                        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                    <label for="include_checked_out" class="ml-2 block text-sm text-gray-700">
                                        Include checked-out visitors
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="include_pending" name="include_pending" type="checkbox"
                                        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                    <label for="include_pending" class="ml-2 block text-sm text-gray-700">
                                        Include pending visits
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="submitExportForm()"
                            class="px-4 py-3 rounded-xl text-white text-base font-medium transition-all duration-200 bg-[#07AF8B] hover:bg-[#007570] active:translate-y-0 hover:-translate-y-px cursor-pointer w-full inline-flex justify-center sm:ml-3 sm:w-auto">
                        Export Data
                    </button>
                    <button type="button" onclick="closeExportModal()"
                            class="mt-3 w-full inline-flex justify-center px-4 py-3 rounded-xl text-base font-medium border border-gray-300 hover:bg-gray-50 transition-all duration-200 active:translate-y-0 hover:-translate-y-px cursor-pointer sm:mt-0 sm:ml-3 sm:w-auto">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>



    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-4 right-4 hidden">
        <div class="bg-green-500 text-white px-4 py-2 rounded-md shadow-lg flex items-center">
            <span id="toast-message"></span>
            <button onclick="document.getElementById('toast').classList.add('hidden')" class="ml-4">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tabs from URL hash
            const initialTab = window.location.hash ? window.location.hash.substring(1) : 'walk-in';
            showTab(initialTab);

            // Tab click handler
            document.querySelectorAll('.tab-button').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const tabId = this.getAttribute('data-tab');
                    showTab(tabId);
                });
            });

            // Only show search form when on history tab
            function toggleSearchForm(visible) {
                const form = document.getElementById('historySearchForm');
                if (form) {
                    form.style.display = visible ? 'block' : 'none';
                }
            }

            // Show tab function
            function showTab(tabId) {
                // Update URL hash
                history.pushState(null, null, '#' + tabId);

                // Update tab buttons
                document.querySelectorAll('.tab-button').forEach(btn => {
                    if (btn.getAttribute('data-tab') === tabId) {
                        btn.classList.add('border-[#fecd01]', 'text-[#007570]');
                        btn.classList.remove('border-transparent', 'text-gray-500');
                    } else {
                        btn.classList.remove('border-[#fecd01]', 'text-[#007570]');
                        btn.classList.add('border-transparent', 'text-gray-500');
                    }
                });

                // Update tab content
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });
                document.getElementById(tabId + '-tab').classList.remove('hidden');

                // Show/hide search form
                toggleSearchForm(tabId === 'history');
            }

            // Initialize search form visibility
            toggleSearchForm(initialTab === 'history');
        });

        // Simplified tab switching
        function showTab(tabId) {
            // Update URL hash
            history.pushState(null, null, '#' + tabId);

            // Update tab buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                if (btn.getAttribute('data-tab') === tabId) {
                    btn.classList.add('border-[#fecd01]', 'text-[#007570]');
                    btn.classList.remove('border-transparent', 'text-gray-500');
                } else {
                    btn.classList.remove('border-[#fecd01]', 'text-[#007570]');
                    btn.classList.add('border-transparent', 'text-gray-500');
                }
            });

            // Update tab content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            document.getElementById(tabId + '-tab').classList.remove('hidden');
        }

        // Initialize tab on load
        document.addEventListener('DOMContentLoaded', function() {
            const initialTab = window.location.hash ? window.location.hash.substring(1) : 'walk-in';
            showTab(initialTab);
        });

        // export logic
        function setQuickExport(range) {
        const today = new Date();
        const startDate = document.getElementById('export_start_date');
        const endDate = document.getElementById('export_end_date');

        // Reset times
        document.getElementById('export_start_time').value = '00:00';
        document.getElementById('export_end_time').value = '23:59';

        switch(range) {
            case 'today':
                startDate.value = formatDate(today);
                endDate.value = formatDate(today);
                break;
            case 'week':
                const firstDay = new Date(today.setDate(today.getDate() - today.getDay()));
                const lastDay = new Date(today.setDate(today.getDate() - today.getDay() + 6));
                startDate.value = formatDate(firstDay);
                endDate.value = formatDate(lastDay);
                break;
            case 'month':
                const firstDayMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                const lastDayMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                startDate.value = formatDate(firstDayMonth);
                endDate.value = formatDate(lastDayMonth);
                break;
            case 'all':
                startDate.value = '';
                endDate.value = '';
                break;
        }

        // Highlight the selected button
        document.querySelectorAll('.quick-export-btn').forEach(btn => {
            btn.classList.remove('bg-emerald-100', 'border-emerald-300');
        });
        event.target.classList.add('bg-emerald-100', 'border-emerald-300');
    }

    function formatDate(date) {
        return date.toISOString().split('T')[0];
    }

    function closeExportModal() {
        document.getElementById('export-modal').classList.add('hidden');
    }

    // Initialize with today's date by default
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        document.getElementById('export_start_date').value = formatDate(today);
        document.getElementById('export_end_date').value = formatDate(today);
        document.getElementById('export_start_time').value = '00:00';
        document.getElementById('export_end_time').value = '23:59';
    });

    // submit export form
    async function submitExportForm() {
        const form = document.getElementById('export-form');
        const formData = new FormData(form);

        const exportButton = document.querySelector('[onclick="submitExportForm()"]');
        const originalText = exportButton.textContent;
        exportButton.textContent = 'Exporting...';
        exportButton.disabled = true;

        // Validate
        const startDate = formData.get('start_date');
        const endDate = formData.get('end_date');
        if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
            alert('Start date must be before or equal to end date.');
            resetButton();
            return;
        }
        if (!formData.has('include_checked_in') && !formData.has('include_checked_out') && !formData.has('include_pending')) {
            alert('Select at least one status');
            resetButton();
            return;
        }

        // Prepare payload
        const payload = {};
        formData.forEach((value, key) => payload[key] = value);
        payload.include_checked_in = formData.has('include_checked_in') ? 1 : 0;
        payload.include_checked_out = formData.has('include_checked_out') ? 1 : 0;
        payload.include_pending = formData.has('include_pending') ? 1 : 0;

        try {
            const res = await fetch('/reception/visitor-history/export', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            // const json = await res.json();

            // if (json.success) {
            //     if (json.format === 'csv') {
            //         downloadCSV(json.data);
            //     } else if (json.format === 'excel') {
            //         downloadExcel(json.data);
            //     } else if (json.format === 'pdf') {
            //         downloadPDF(json.data);
            //     }
            //     showNotification('Export successful!', 'success');
            // } else {
            //     showNotification(json.message || 'Export failed.', 'error');
            // }
            if (payload.export_format === 'pdf') {
                const blob = await res.blob();
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', 'visitor_history.pdf');
                document.body.appendChild(link);
                link.click();
                link.remove();
                window.URL.revokeObjectURL(url);
                showNotification('PDF export successful!', 'success');
            } else {
                const json = await res.json();

                if (json.success) {
                    if (json.format === 'csv') {
                        downloadCSV(json.data);
                    } else if (json.format === 'excel') {
                        downloadExcel(json.data);
                    }
                    showNotification('Export successful!', 'success');
                } else {
                    showNotification(json.message || 'Export failed.', 'error');
                }
            }

        } catch (err) {
            handleExportError(err);
        } finally {
            resetButton();
            closeExportModal();
        }

        function resetButton() {
            exportButton.textContent = originalText;
            exportButton.disabled = false;
        }
    }


//     function submitExportForm() {
//     const form = document.getElementById('export-form');
//     const formData = new FormData(form);

//     // Show loading state
//     const exportButton = document.querySelector('[onclick="submitExportForm()"]');
//     const originalText = exportButton.textContent;
//     exportButton.textContent = 'Exporting...';
//     exportButton.disabled = true;

//     // Validate date range
//     const startDate = formData.get('start_date');
//     const endDate = formData.get('end_date');

//     if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
//         alert('Start date must be before or equal to end date.');
//         exportButton.textContent = originalText;
//         exportButton.disabled = false;
//         return;
//     }

//     // Check if at least one status filter is selected
//     const includeCheckedIn = formData.get('include_checked_in');
//     const includeCheckedOut = formData.get('include_checked_out');
//     const includePending = formData.get('include_pending');

//     if (!includeCheckedIn && !includeCheckedOut && !includePending) {
//         alert('Please select at least one visitor status to include in the export.');
//         exportButton.textContent = originalText;
//         exportButton.disabled = false;
//         return;
//     }

//     // Create URL with parameters
//     const params = new URLSearchParams();

//     // Add all form fields to URL parameters
//     for (let [key, value] of formData.entries()) {
//         if (value) {
//             params.append(key, value);
//         }
//     }

//     // Handle checkboxes that aren't checked (they won't be in FormData)
//     if (!formData.has('include_checked_in')) {
//         params.append('include_checked_in', '0');
//     }
//     if (!formData.has('include_checked_out')) {
//         params.append('include_checked_out', '0');
//     }
//     if (!formData.has('include_pending')) {
//         params.append('include_pending', '0');
//     }

//     // Create download link
//     const exportUrl = '/reception/visitor-history/export?' + params.toString();

//     // Create a temporary link element and trigger download
//     const link = document.createElement('a');
//     link.href = exportUrl;
//     link.style.display = 'none';
//     document.body.appendChild(link);
//     link.click();
//     document.body.removeChild(link);

//     // Reset button state
//     setTimeout(() => {
//         exportButton.textContent = originalText;
//         exportButton.disabled = false;
//         closeExportModal();

//         // Show success message
//         showNotification('Export started! Your download will begin shortly.', 'success');
//     }, 1000);
// }

// Add notification function
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transition-all duration-300 transform translate-x-full`;

    // Set styles based on type
    const styles = {
        success: 'bg-green-500 text-white',
        error: 'bg-red-500 text-white',
        warning: 'bg-yellow-500 text-black',
        info: 'bg-blue-500 text-white'
    };

    notification.className += ` ${styles[type] || styles.info}`;
    notification.textContent = message;

    // Add to page
    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
        notification.classList.add('translate-x-0');
    }, 100);

    // Remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 5000);
}

// Enhanced error handling for the export process
function handleExportError(error) {
    console.error('Export error:', error);
    showNotification('Export failed. Please try again or contact support.', 'error');

    // Reset button state
    const exportButton = document.querySelector('[onclick="submitExportForm()"]');
    exportButton.textContent = 'Export Data';
    exportButton.disabled = false;
}

    // file download helper functions
    function downloadCSV(data) {
        const csv = convertToCSV(data);
        const blob = new Blob([csv], { type: 'text/csv' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'visitor_history.csv';
        link.click();
    }

    function convertToCSV(rows) {
        if (!rows.length) return '';
        const headers = Object.keys(rows[0]);
        const csv = [
            headers.join(','),
            ...rows.map(row => headers.map(h => `"${row[h] ?? ''}"`).join(','))
        ];
        return csv.join('\n');
    }

    </script>
@endsection
