<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ __('Dashboard - AFREXIMBANK') }}</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <style>
    :root {
      --primary: #07AF8B;
      --accent: #FFCA00;
      --deep: #007570;
      --bg: #f4f6f8;
      --text-dark: #1f2d3d;
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: var(--bg);
      color: var(--text-dark);
    }

    a {
      text-decoration: none;
      color: var(--primary);
    }

    .container {
      padding: 1rem;
      max-width: 1200px;
      margin: auto;
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 2rem;
      background: white;
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
      flex-wrap: wrap;
      gap: 1rem;
    }

    .topbar img {
      height: 45px;
    }

    .user-section {
      display: flex;
      align-items: center;
      gap: 1rem;
      flex-wrap: wrap;
    }

    .user-icon {
      font-size: 30px;
      color: var(--deep);
    }

    .new-request-btn {
      background: var(--accent);
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 8px;
      font-weight: bold;
      color: #;
      cursor: pointer;
      transition: background 0.3s;
    }

    .new-request-btn:hover {
      background: #f0b800;
    }

    .main {
      display: flex;
      flex-wrap: wrap;
      gap: 2rem;
      margin-top: 2rem;
    }

    .left-panel,
    .right-panel {
      flex: 1 1 350px;
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }

    .new-request-box {
      background: var(--primary);
      height: 180px;
      border-radius: 12px;
      display: flex;
      justify-content: center;
      align-items: flex-end;
      padding: 1rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .new-request-btn a {
  color: black;
  text-decoration: none;
}

    .notifications,
    .my-requests {
      background: white;
      border-radius: 12px;
      padding: 1rem 1.5rem;
      box-shadow: 0 1px 5px rgba(0,0,0,0.05);
    }

    .notifications ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .notifications li {
      display: flex;
      justify-content: space-between;
      margin-bottom: 1rem;
      font-size: 14px;
    }

    .summary-header {
      font-size: 22px;
      font-weight: bold;
      margin-bottom: 0.5rem;
    }

    .tabs {
      display: flex;
      gap: 0.5rem;
    }

    .tabs button {
      border: none;
      background: #eee;
      padding: 0.4rem 1rem;
      border-radius: 6px;
      cursor: pointer;
      transition: all 0.3s;
    }

    .tabs .active {
      background: var(--deep);
      color: white;
    }

    .stats {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .stat-card {
      flex: 1 1 100px;
      padding: 1rem;
      border-radius: 12px;
      color: white;
      text-align: center;
      font-weight: bold;
    }

    .gray { background: #6c757d; }
    .green { background: var(--primary); }
    .red { background: #b00020; }

    .my-requests table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
    }

    th {
      background: var(--primary);
      color: white;
      text-align: left;
      padding: 0.6rem;
    }

    td {
      padding: 0.6rem;
      border-bottom: 1px solid #eee;
    }

    .badge {
      background: var(--primary);
      color: white;
      border-radius: 6px;
      padding: 0.2rem 0.6rem;
      font-size: 12px;
    }

    .search-box {
      padding: 0.4rem;
      border: 0px solid #ccc;
      border-radius: 6px;
      width: 100%;
      margin-top: 1rem;
    }

    .search-box i { position: absolute; left: 15px; top: 10px; color: #6c757d; }


    @media (max-width: 768px) {
      .topbar,
      .user-section {
        flex-direction: column;
        align-items: flex-start;
      }
      .search-box {
        width: 100%;
      }
    }

    .request-content {
  text-align: left;
  color: white;
  max-width: 600px;
}

.request-content h2 {
  font-size: 20px;
  margin-bottom: 0.5rem;
}

.request-content p {
  font-size: 14px;
  margin-bottom: 1rem;
  line-height: 1.5;
}

.new-request-action {
  display: inline-block;
  background: var(--accent);
  color: #000;
  padding: 0.5rem 1.2rem;
  border-radius: 8px;
  font-weight: bold;
  text-decoration: none;
  transition: background 0.3s;
}

.new-request-action:hover {
  background: #f0b800;
}

.scroll-box {
  max-height: 170px; /* Adjust depending on your row height */
  overflow-y: auto;
}

.accent { background: var(--accent); color: #000; }


/* Add this to your existing CSS */
.request-content {
        text-align: left;
        color: white;
        max-width: 100%; /* Changed from 600px to 100% */
        padding: 0 1rem; /* Add padding to prevent text from touching edges */
        box-sizing: border-box; /* Include padding in width calculation */
    }

    .request-content h2 {
        font-size: clamp(16px, 4vw, 20px); /* Responsive font size */
        margin-bottom: 0.5rem;
        word-wrap: break-word; /* Ensure long words break */
        overflow-wrap: break-word; /* Alternative for better browser support */
    }

    .request-content p {
        font-size: clamp(12px, 3vw, 14px); /* Responsive font size */
        margin-bottom: 1rem;
        line-height: 1.5;
        word-wrap: break-word;
    }

    .new-request-box {
        background: var(--primary);
        min-height: 180px; /* Changed from height to min-height */
        border-radius: 12px;
        display: flex;
        justify-content: center;
        align-items: flex-end;
        padding: 1rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    @media (max-width: 480px) {
        .request-content {
            padding: 0 0.5rem; /* Smaller padding on very small screens */
        }

        .request-content h2 {
            line-height: 1.3; /* Tighter line height on small screens */
        }
    }

/* Modal styles */
.modal {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,0.5);
}

.modal-content {
  background-color: white;
  margin: 10% auto;
  padding: 20px;
  border-radius: 8px;
  width: 80%;
  max-width: 600px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
}

.close:hover {
  color: black;
}

.modal-body {
  margin-top: 20px;
}

.modal-row {
  display: flex;
  margin-bottom: 10px;
}

.modal-label {
  font-weight: bold;
  width: 150px;
  color: var(--deep);
}

.modal-value {
  flex: 1;
}

.clickable-row {
  cursor: pointer;
}

.clickable-row:hover {
  background-color: #f5f5f5;
}

.status-pending {
  color: #FFA500;
}
.status-approved {
  color: #07AF8B;
}
.status-rejected {
  color: #b00020;
}
.status-checked_in {
  color: #07AF8B;
}
.status-checked_out {
  color: #6c757d;
}
  </style>
</head>
<body>

<div class="topbar">
  <img src="{{ asset('assets/logo-green-yellow.png') }}" alt="{{ __('Logo') }}" />
  <div class="user-section">
    <a href="{{ route('register_visitor') }}">{{ __('New request') }}</a>
    <a href="{{ route('profile.update') }}">
      <span class="material-icons user-icon" aria-label="{{ __('User profile') }}">account_circle</span>
    </a>
    <div class="user-info">
      <strong>{{ $employee->name }}</strong><br>
      {{ __('Location') }}: <strong>{{ __('Abuja') }}</strong>
    </div>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button class="new-request-btn" type="submit">{{ __('Logout') }}</button>
    </form>
  </div>
</div>

<div class="container">

  <div class="notifications">
    <h5>{{ __('Notifications') }}</h5>
    <ul>
      @foreach ($notifications as $note)
        <li>
          <span>
            <span class="material-icons" style="color: var(--primary);">
              {{ $note->status === 'approved' ? 'check_circle' : 'cancel' }}
            </span>
            {{ $note->name }} {{ __($note->status) }}.
          </span>
          <span class="timestamp">{{ $note->updated_at->format('g:iA\<\b\\r\>d/m/Y') }}</span>
        </li>
      @endforeach
    </ul>
  </div>

  <div class="stats">
    <div class="stat-card accent"><h1>{{ $stats['total_requests'] ?? 0 }}</h1><p>{{ __('Requests') }}</p></div>
    <div class="stat-card green"><h1>{{ $stats['approved'] ?? 0 }}</h1><p>{{ __('Approved') }}</p></div>
    <div class="stat-card gray"><h1>{{ $stats['declined'] ?? 0 }}</h1><p>{{ __('Declined') }}</p></div>
  </div>

  <div class="my-requests">
    <h5>{{ __('My Requests') }}</h5>
    <form method="GET" action="{{ route('home') }}" style="display:flex; align-items:center; gap:0.5rem; margin-bottom:1rem;">
        <input type="text" name="search" class="search-box" placeholder="{{ __('Search visitors...') }}" value="{{ $search }}">
        <button type="submit" class="new-request-btn" style="padding:0.5rem 1rem;">{{ __('Search') }}</button>
        @if($search)
            <a href="{{ route('home') }}" style="color: var(--primary);">{{ __('Clear') }}</a>
        @endif
    </form>

    <table>
        <thead>
            <tr>
                <th>{{ __('Visitor Name') }}</th>
                <th>{{ __('Visitor ID') }}</th>
                <th>{{ __('Request Date') }}</th>
                <th>{{ __('Status') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($requests as $request)
                <tr class="clickable-row" onclick="openModal({{ $request->id }})" title="{{ __('Click to view details') }}">
                    <td>{{ $request->visitor->name ?? 'N/A' }}</td>
                    <td>{{ $request->unique_code }}</td>
                    <td>{{ \Carbon\Carbon::parse($request->visit_date)->format('d/m/Y g:i A') }}</td>
                    <td>
                        @php $status = strtolower($request->status); @endphp
                        @if($status === 'approved')
                            <span class="status-approved">{{ __('Approved') }}</span>
                        @elseif($status === 'pending')
                            <span class="status-pending">{{ __('Pending') }}</span>
                        @elseif(in_array($status, ['declined', 'rejected']))
                            <span class="status-declined">{{ __('Declined') }}</span>
                        @else
                            <span>{{ __($request->status) }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">{{ __('No requests found.') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>


</div>

<div id="requestModal" class="modal" aria-hidden="true" role="dialog" aria-labelledby="modalTitle" aria-describedby="modalDesc">
  <div class="modal-content" role="document">
    <button class="close" aria-label="{{ __('Close modal') }}" onclick="closeModal()">&times;</button>
    <h3 id="modalTitle">{{ __('Request Details') }}</h3>
    <div id="modalBody" aria-live="polite" aria-atomic="true">
      <p>{{ __('Loading details...') }}</p>
    </div>
  </div>
</div>

<script>
  const requestsData = @json($requests->keyBy('id'));

  function openModal(requestId) {
    const modal = document.getElementById('requestModal');
    const modalBody = document.getElementById('modalBody');
    const request = requestsData[requestId];

    if (!request) {
      modalBody.innerHTML = '<p>{{ __("Request data not found.") }}</p>';
      modal.style.display = 'block';
      modal.setAttribute('aria-hidden', 'false');
      return;
    }

    let content = `
      <div class="modal-row"><span class="modal-label">{{ __('Visitor Name') }}:</span><span class="modal-value">${request.name}</span></div>
      <div class="modal-row"><span class="modal-label">{{ __('Visitor ID') }}:</span><span class="modal-value">${request.unique_code}</span></div>
      <div class="modal-row"><span class="modal-label">{{ __('Request Date') }}:</span><span class="modal-value">${new Date(request.visit_date).toLocaleString()}</span></div>
      <div class="modal-row"><span class="modal-label">{{ __('Status') }}:</span><span class="modal-value">${request.status.charAt(0).toUpperCase() + request.status.slice(1)}</span></div>
      <div class="modal-row"><span class="modal-label">{{ __('Purpose') }}:</span><span class="modal-value">${request.reason || '{{ __("N/A") }}'}</span></div>
      <div class="modal-row"><span class="modal-label">{{ __('Created At') }}:</span><span class="modal-value">${new Date(request.created_at).toLocaleString()}</span></div>
      <div class="modal-row"><span class="modal-label">{{ __('Updated At') }}:</span><span class="modal-value">${new Date(request.updated_at).toLocaleString()}</span></div>
    `;

    modalBody.innerHTML = content;
    modal.style.display = 'block';
    modal.setAttribute('aria-hidden', 'false');
  }

  function closeModal() {
    const modal = document.getElementById('requestModal');
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden', 'true');
  }

  window.onclick = function(event) {
    const modal = document.getElementById('requestModal');
    if (event.target === modal) {
      closeModal();
    }
  };

  window.addEventListener('keydown', function(e) {
    if (e.key === "Escape") {
      closeModal();
    }
  });
</script>

</body>
</html>
