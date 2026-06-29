<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>Residents Record - DIGIBARANGAY</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo_zed.png') }}" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
    <style>
        .admin-dashboard {
            position: relative;
        }

        .admin-dashboard::before {
            content: '';
            position: fixed;
            left: 50%;
            top: 54%;
            width: 460px;
            height: 460px;
            transform: translate(-50%, -50%);
            background: url('{{ asset('img/Barangay Official Logo.png') }}') center/contain no-repeat;
            opacity: .07;
            filter: grayscale(100%) blur(2px);
            pointer-events: none;
            z-index: 0;
        }

        .adm-layout {
            position: relative;
            z-index: 1;
        }

        .resident-doc-filters {
            display: flex;
            flex-wrap: wrap;
            gap: .6rem;
            margin: 1rem 0 1rem;
        }

        .resident-doc-btn {
            border: 1px solid #cbd5e1;
            background: #fff;
            color: #0f172a;
            border-radius: 999px;
            padding: .55rem .9rem;
            font-size: .86rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .18s ease;
        }

        .resident-doc-btn:hover {
            border-color: #2b77d1;
            color: #2b77d1;
            background: #eef5ff;
        }

        .resident-doc-btn.active {
            background: #2b77d1;
            border-color: #2b77d1;
            color: #fff;
            box-shadow: 0 8px 18px rgba(43, 119, 209, .18);
        }

        .resident-volume {
            margin: 0 0 1rem;
            padding: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        }

        .resident-volume-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            margin-bottom: .9rem;
            flex-wrap: wrap;
        }

        .resident-volume-title {
            font-size: 1rem;
            font-weight: 800;
            color: #0f172a;
        }

        .resident-volume-subtitle {
            font-size: .84rem;
            color: #64748b;
            margin-top: .15rem;
        }

        .resident-volume-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: .75rem;
        }

        .resident-volume-card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            background: #fff;
            padding: .8rem .85rem;
            transition: all .18s ease;
        }

        .resident-volume-card:hover {
            border-color: #2b77d1;
            box-shadow: 0 10px 22px rgba(2, 6, 23, .08);
            transform: translateY(-1px);
        }

        .resident-volume-card.active {
            border-color: #2b77d1;
            background: #eef5ff;
            box-shadow: 0 10px 22px rgba(43, 119, 209, .12);
        }

        .resident-volume-card .label {
            font-size: .82rem;
            color: #475569;
            font-weight: 700;
        }

        .resident-volume-card .value {
            margin-top: .3rem;
            font-size: 1.45rem;
            font-weight: 800;
            color: #111827;
        }

        .resident-volume-bar {
            margin-top: .55rem;
            height: 8px;
            border-radius: 999px;
            background: #e5e7eb;
            overflow: hidden;
        }

        .resident-volume-bar>span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #2b77d1, #60a5fa);
        }

        .resident-report-btn {
            border: 1px solid #2b77d1;
            background: #2b77d1;
            color: #fff;
            border-radius: 12px;
            padding: .72rem 1rem;
            font-size: .9rem;
            font-weight: 800;
            cursor: pointer;
            transition: all .18s ease;
            box-shadow: 0 10px 20px rgba(43, 119, 209, .16);
            white-space: nowrap;
        }

        .resident-report-btn:hover {
            background: #1d5fae;
            border-color: #1d5fae;
            transform: translateY(-1px);
            box-shadow: 0 14px 24px rgba(43, 119, 209, .2);
        }

        .resident-report-btn:active {
            transform: translateY(0);
        }
    </style>
</head>

<body class="admin-dashboard">
    <div class="adm-layout">
        <button class="adm-sidebar-overlay" id="admSidebarOverlay" type="button" aria-label="Close menu"></button>
        <aside class="adm-sidebar">
            <div class="adm-brand">
                <img src="{{ asset('img/logo_zed.png') }}" alt="DIGIBARANGAY logo" />
                <div>
                    <strong>DIGIBARANGAY</strong>
                    <small>Smart Clearance System</small>
                </div>
            </div>

            @php($isAdmin = session('admin_role') === 'admin')
            <nav class="adm-nav" id="admSidebarNav" aria-label="Admin navigation">
                <a href="/dashs"><span class="ico">🏠</span><span>Dashboard</span></a>
                <a class="active" href="/resident"><span class="ico">👥</span><span>Resident
                        Records</span></a>
                <a href="/rest-acc"><span class="ico">🔐</span><span>Resident Accounts</span></a>
                @if ($isAdmin)
                    <a href="/dashboard"><span class="ico">🧭</span><span>Admin Dashboard</span></a>
                @endif
            </nav>

            <div class="adm-sidebar-footer">
                <button class="adm-logout" type="button" id="adminLogout">
                    <span class="ico">âŽ‹</span><span>Logout</span>
                </button>
            </div>
        </aside>

        <main class="adm-main">
            <header class="adm-topbar">
                <button class="adm-menu-toggle" id="admMenuToggle" type="button" aria-label="Toggle menu"
                    aria-expanded="false" aria-controls="admSidebarNav">
                    <span class="bars" aria-hidden="true"><span></span><span></span><span></span></span>
                </button>
                <div class="role">
                    <strong id="topbarUserName">{{ session('admin_name', 'CHAIRMAN') }}</strong>
                    <span>{{ $isAdmin ? 'Barangay Admin' : 'Barangay Official' }}</span>
                </div>
                <div class="top-icons">
                    <div style="position:relative;display:inline-block;">
                        <button id="notifBellBtn" class="bubble" type="button" title="Notifications"
                            aria-label="Notifications" aria-expanded="false" style="cursor:pointer;position:relative;">
                            🔔
                            <span id="notifBadge" hidden
                                style="position:absolute;top:-3px;right:-3px;min-width:18px;height:18px;border-radius:999px;background:#ef4444;color:#fff;font-size:.68rem;font-weight:700;line-height:18px;text-align:center;padding:0 4px;">0</span>
                        </button>
                        <div id="notifPanel" hidden
                            style="position:absolute;right:0;top:120%;width:320px;max-height:360px;overflow:auto;background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 14px 34px rgba(2,6,23,.16);z-index:30;">
                            <div
                                style="padding:.75rem .9rem;border-bottom:1px solid #eef2f7;font-weight:700;color:#111827;">
                                New Requests</div>
                            <div id="notifList" style="padding:.4rem;"></div>
                        </div>
                    </div>
                </div>
            </header>

            <section class="adm-content">
                <div class="adm-title">Resident Records</div>
                <div class="adm-subtitle">Browse resident request records by document type and processing volume</div>

                <div class="resident-doc-filters" id="residentDocFilters" aria-label="Document filters"></div>

                <div class="resident-volume">
                    <div class="resident-volume-head">
                        <div>
                            <div class="resident-volume-title">Processing Volume</div>
                            <div class="resident-volume-subtitle">Current request volume by document type</div>
                        </div>
                        <div class="muted" id="residentVolumeSummary" style="font-size:.86rem;">0 total requests</div>
                    </div>
                    <div class="resident-volume-grid" id="residentVolumeGrid"></div>
                </div>

                <div class="adm-stats">
                    <div class="stat total">
                        <div class="meta">
                            <div class="label">Total Residents</div>
                            <div class="value" id="statResidents">0</div>
                        </div>
                        <div class="dot">#</div>
                    </div>
                    <div class="stat pending">
                        <div class="meta">
                            <div class="label">Residents w/ Pending</div>
                            <div class="value" id="statResidentsPending">0</div>
                        </div>
                        <div class="dot">!</div>
                    </div>
                    <div class="stat approved">
                        <div class="meta">
                            <div class="label">Total Approved Requests</div>
                            <div class="value" id="statApproved">0</div>
                        </div>
                        <div class="dot">👍🏻</div>
                    </div>
                    <div class="stat rejected">
                        <div class="meta">
                            <div class="label">Total Rejected Requests</div>
                            <div class="value" id="statRejected">0</div>
                        </div>
                        <div class="dot">X</div>
                    </div>
                </div>

                <div class="adm-card" style="padding:1rem">
                    <div class="adm-toolbar">
                        <div class="search" aria-label="Search">
                            <span style="opacity:.7">🔎</span>
                            <input id="q" type="text" placeholder="Search resident name" />
                        </div>
                        <select id="hasPending" aria-label="Filter">
                            <option value="">All</option>
                            <option value="pending">With Pending</option>
                            <option value="nopending">No Pending</option>
                        </select>
                        <button id="exportResidentReport" class="resident-report-btn" type="button">Export
                            Report</button>
                    </div>

                    <div class="adm-table-wrap">
                        <table class="adm-table" aria-label="Residents table">
                            <thead>
                                <tr>
                                    <th>Resident</th>
                                    <th>Total Requests</th>
                                    <th>Pending</th>
                                    <th>Last Request</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="rows"></tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <div id="notifToastHost"
        style="position:fixed;top:16px;right:16px;z-index:120;display:flex;flex-direction:column;gap:8px;pointer-events:none;">
    </div>

    <!-- Resident details modal -->
    <div id="residentModal" class="modal-overlay" hidden>
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="residentTitle">
            <button class="modal-close" id="residentClose" aria-label="Close">X</button>
            <div class="modal-header">
                <h2 id="residentTitle">Resident</h2>
            </div>
            <div class="modal-body">
                <div id="residentMeta" class="muted" style="margin-bottom:.75rem"></div>
                <div class="adm-table-wrap">
                    <table class="adm-table" aria-label="Resident requests">
                        <thead>
                            <tr>
                                <th>Ref</th>
                                <th>Purpose</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>PDF</th>
                            </tr>
                        </thead>
                        <tbody id="residentReqRows"></tbody>
                    </table>
                </div>
                <div class="form-actions" style="margin-top:1rem;justify-content:flex-end">
                    <button class="btn" type="button" id="goDashboard">Open Dashboard</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const REQUESTS_KEY = 'digibarangay_requests';
        const CERT_AUTOFILL_KEY = 'digibarangay_cert_autofill';
        const TEMPLATE_OVERRIDE_KEY = 'digibarangay_cert_template_override_v1';
        const GLOBAL_CLEARANCE_TEMPLATE_KEY = 'digibarangay_saved_clearance_template_v1';
        const NOTIF_SEEN_KEY = 'digibarangay_seen_request_refs_v1';
        const DOCUMENT_FILTERS = [{
                key: 'all',
                label: 'All Documents'
            },
            {
                key: 'clearance',
                label: 'Barangay Clearance'
            },
            {
                key: 'indigency',
                label: 'Certificate of Indigency'
            },
            {
                key: 'good_moral',
                label: 'Good Moral'
            },
            {
                key: 'oneness',
                label: 'Certificate of Oneness'
            },
            {
                key: 'job_seeker',
                label: 'First-Time Job Seeker'
            },
            {
                key: 'oath',
                label: 'Oath of Undertaking'
            },
            {
                key: 'other',
                label: 'Other Certificates'
            },
        ];
        // Page init / refresh
        let residents = [];
        let activeDocFilter = 'all';
        let knownRefSet = new Set(loadRequests().map((r) => String(r?.ref || '').trim()).filter(Boolean));
        let residentSyncPromise = null;

        if (!localStorage.getItem(NOTIF_SEEN_KEY)) {
            writeSeenRefs(knownRefSet);
        }

        const notifBellBtn = document.getElementById('notifBellBtn');
        const notifPanel = document.getElementById('notifPanel');

        function markAllCurrentRequestsAsSeen() {
            const requests = loadRequests();
            const seenSet = readSeenRefs();
            requests.forEach((r) => {
                const ref = String(r?.ref || '').trim();
                if (ref) seenSet.add(ref);
            });
            writeSeenRefs(seenSet);
            updateNotifications(requests);
        }

        function detectNewRequestsAndNotify() {
            const requests = loadRequests();
            const nowSet = new Set(requests.map((r) => String(r?.ref || '').trim()).filter(Boolean));
            const newItems = requests.filter((r) => {
                const ref = String(r?.ref || '').trim();
                return ref && !knownRefSet.has(ref);
            });

            if (newItems.length) {
                updateNotifications(requests, {
                    toastNew: true,
                    newItems
                });
            } else {
                updateNotifications(requests);
            }

            knownRefSet = nowSet;
        }

        if (notifBellBtn && notifPanel) {
            notifBellBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const willOpen = notifPanel.hidden;
                notifPanel.hidden = !willOpen;
                notifBellBtn.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
                if (willOpen) {
                    markAllCurrentRequestsAsSeen();
                }
            });

            document.addEventListener('click', (e) => {
                if (notifPanel.hidden) return;
                if (notifPanel.contains(e.target) || notifBellBtn.contains(e.target)) return;
                notifPanel.hidden = true;
                notifBellBtn.setAttribute('aria-expanded', 'false');
            });
        }

        function refreshResidentsTable() {
            const allRequests = loadRequests();
            const filteredRequests = filterRequestsByDocument(allRequests, activeDocFilter);
            residents = groupResidents(filteredRequests);
            renderDocumentFilters(allRequests);
            renderProcessingVolume(allRequests);
            setStats(filteredRequests, residents);
            renderResidents(residents);
            updateNotifications(allRequests);
        }

        refreshResidentsTable();

        document.getElementById('q').addEventListener('input', () => renderResidents(residents));
        document.getElementById('hasPending').addEventListener('change', () => renderResidents(residents));
        document.getElementById('exportResidentReport').addEventListener('click', () => {
            window.location.href = '/resident/report?t=' + Date.now();
        });

        // If requests are submitted from docs in another tab, refresh automatically.
        window.addEventListener('storage', (e) => {
            if (e.key === REQUESTS_KEY) {
                detectNewRequestsAndNotify();
                refreshResidentsTable();
            }
        });

        async function syncRequestsFromServer() {
            try {
                const response = await fetch('/clearance-requests', {
                    headers: {
                        'Accept': 'application/json'
                    },
                });

                if (!response.ok) return;

                const result = await response.json().catch(() => ({}));
                const serverRequests = Array.isArray(result.data) ? result.data : [];
                localStorage.setItem(REQUESTS_KEY, JSON.stringify(serverRequests));
                return serverRequests;
            } catch (error) {
                console.warn('Unable to sync resident requests from server', error);
                return null;
            }
        }

        async function refreshResidentsFromServer() {
            const serverRequests = await syncRequestsFromServer();
            if (Array.isArray(serverRequests)) {
                detectNewRequestsAndNotify();
                refreshResidentsTable();
            }
        }

        // Refresh when tab gains focus and keep the list synced with the server.
        window.addEventListener('pageshow', () => {
            refreshResidentsTable();
            if (!residentSyncPromise) {
                residentSyncPromise = refreshResidentsFromServer().finally(() => {
                    residentSyncPromise = null;
                });
            }
        });
        setInterval(() => {
            if (residentSyncPromise) return;
            residentSyncPromise = refreshResidentsFromServer().finally(() => {
                residentSyncPromise = null;
            });
        }, 5000);
        setInterval(detectNewRequestsAndNotify, 5000);

        function safeJsonParse(raw, fallback) {
            try {
                const v = raw ? JSON.parse(raw) : null;
                return v ?? fallback;
            } catch {
                return fallback;
            }
        }

        function escapeHtml(text) {
            return String(text ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function formatReportDate(raw) {
            const s = String(raw || '').trim();
            if (!s) return '-';
            const d = new Date(s);
            if (Number.isNaN(d.getTime())) return s;
            return d.toLocaleString();
        }

        function getRequestSortValue(req) {
            const raw = String(req?.dateRequested || req?.date || '').trim();
            const time = new Date(raw).getTime();
            return Number.isNaN(time) ? 0 : time;
        }

        function buildResidentReportHtml(requests) {
            const allRequests = Array.isArray(requests) ? requests.slice() : [];
            const sections = DOCUMENT_FILTERS.filter((item) => item.key !== 'all').map((item) => {
                const matched = allRequests
                    .filter((req) => getRequestDocumentKey(req) === item.key)
                    .sort((a, b) => getRequestSortValue(b) - getRequestSortValue(a));

                return {
                    key: item.key,
                    label: item.label,
                    count: matched.length,
                    pending: matched.filter((req) => normalize(req.status) === 'pending').length,
                    approved: matched.filter((req) => normalize(req.status) === 'approved').length,
                    rejected: matched.filter((req) => normalize(req.status) === 'rejected').length,
                    requests: matched,
                };
            });

            const totalPending = allRequests.filter((req) => normalize(req.status) === 'pending').length;
            const totalApproved = allRequests.filter((req) => normalize(req.status) === 'approved').length;
            const totalRejected = allRequests.filter((req) => normalize(req.status) === 'rejected').length;
            const generatedAt = new Date().toLocaleString();

            const summaryRows = sections.map((section) => (
                '<tr>' +
                    '<td>' + escapeHtml(section.label) + '</td>' +
                    '<td>' + section.count + '</td>' +
                    '<td>' + section.pending + '</td>' +
                    '<td>' + section.approved + '</td>' +
                    '<td>' + section.rejected + '</td>' +
                '</tr>'
            )).join('');

            const sectionBlocks = sections.map((section) => {
                const rows = section.requests.length ? section.requests.map((req, index) => {
                    const status = normalize(req.status) || 'pending';
                    const statusLabel = status === 'approved' ? 'Approved' : (status === 'rejected' ? 'Rejected' : 'Pending');
                    return '<tr>' +
                        '<td>' + (index + 1) + '</td>' +
                        '<td>' + escapeHtml(req.name || '') + '</td>' +
                        '<td>' + escapeHtml(req.ref || '') + '</td>' +
                        '<td>' + escapeHtml(req.purpose || req.purposeReason || '') + '</td>' +
                        '<td>' + escapeHtml(statusLabel) + '</td>' +
                        '<td>' + escapeHtml(formatReportDate(req.dateRequested || req.date || '')) + '</td>' +
                        '</tr>';
                }).join('') : (
                    '<tr><td colspan="6">No requests in this section.</td></tr>'
                );

                return (
                    '<div class="section-title">' + escapeHtml(section.label) + ' (' + section.count + ')</div>' +
                    '<table>' +
                        '<tr>' +
                            '<th>#</th>' +
                            '<th>Resident</th>' +
                            '<th>Reference</th>' +
                            '<th>Purpose</th>' +
                            '<th>Status</th>' +
                            '<th>Date</th>' +
                        '</tr>' +
                        rows +
                    '</table>'
                );
            }).join('');

            return (
                '<html xmlns:o="urn:schemas-microsoft-com:office:office" ' +
                'xmlns:x="urn:schemas-microsoft-com:office:excel" ' +
                'xmlns="http://www.w3.org/TR/REC-html40">' +
                '<head>' +
                    '<meta charset="UTF-8">' +
                    '<meta name="viewport" content="width=device-width, initial-scale=1.0">' +
                    '<!--[if gte mso 9]><xml>' +
                        '<' + 'x:ExcelWorkbook>' +
                            '<' + 'x:ExcelWorksheets>' +
                                '<' + 'x:ExcelWorksheet>' +
                                    '<' + 'x:Name>Report</' + 'x:Name>' +
                                    '<' + 'x:WorksheetOptions><' + 'x:DisplayGridlines/></' + 'x:WorksheetOptions>' +
                                '</' + 'x:ExcelWorksheet>' +
                            '</' + 'x:ExcelWorksheets>' +
                        '</' + 'x:ExcelWorkbook>' +
                    '</xml><![endif]-->' +
                    '<style>' +
                        'body{font-family:Arial,sans-serif;color:#111827;}' +
                        'h1,h2,h3,p{margin:0 0 12px;}' +
                        '.meta{margin-bottom:18px;}' +
                        'table{border-collapse:collapse;width:100%;margin:0 0 24px;}' +
                        'th,td{border:1px solid #cbd5e1;padding:8px 10px;font-size:12px;vertical-align:top;}' +
                        'th{background:#eaf2ff;font-weight:700;}' +
                        '.summary td,.summary th{text-align:left;}' +
                        '.section-title{margin:18px 0 8px;font-size:16px;font-weight:700;}' +
                    '</style>' +
                '</head>' +
                '<body>' +
                    '<h1>Resident Certificate Requests Report</h1>' +
                    '<div class="meta">' +
                        '<p><strong>Generated:</strong> ' + escapeHtml(generatedAt) + '</p>' +
                        '<p><strong>Total requests:</strong> ' + allRequests.length + '</p>' +
                        '<p><strong>Pending:</strong> ' + totalPending + ' | <strong>Approved:</strong> ' + totalApproved + ' | <strong>Rejected:</strong> ' + totalRejected + '</p>' +
                    '</div>' +
                    '<table class="summary">' +
                        '<tr><th>Section</th><th>Total</th><th>Pending</th><th>Approved</th><th>Rejected</th></tr>' +
                        summaryRows +
                    '</table>' +
                    sectionBlocks +
                '</body>' +
                '</html>'
            );
        }

        function downloadResidentReport(requests) {
            const html = buildResidentReportHtml(requests);
            const blob = new Blob(['\ufeff', html], {
                type: 'application/vnd.ms-excel;charset=utf-8'
            });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            const stamp = new Date().toISOString().slice(0, 10);
            link.href = url;
            link.download = 'resident-certificate-report-' + stamp + '.xls';
            document.body.appendChild(link);
            link.click();
            link.remove();
            setTimeout(() => URL.revokeObjectURL(url), 1000);
        }

        function loadRequests() {
            const arr = safeJsonParse(localStorage.getItem(REQUESTS_KEY), []);
            return Array.isArray(arr) ? arr : [];
        }

        function readGlobalClearanceTemplate() {
            return safeJsonParse(localStorage.getItem(GLOBAL_CLEARANCE_TEMPLATE_KEY), null);
        }

        function readSeenRefs() {
            const data = safeJsonParse(localStorage.getItem(NOTIF_SEEN_KEY), []);
            if (!Array.isArray(data)) return new Set();
            return new Set(data.map((v) => String(v || '').trim()).filter(Boolean));
        }

        function writeSeenRefs(setValue) {
            localStorage.setItem(NOTIF_SEEN_KEY, JSON.stringify(Array.from(setValue)));
        }

        function formatNotifDate(raw) {
            const s = String(raw || '').trim();
            if (!s) return 'Unknown date';
            const d = new Date(s);
            if (Number.isNaN(d.getTime())) return s;
            return d.toLocaleString();
        }

        function renderNotifBadge(count) {
            const badge = document.getElementById('notifBadge');
            if (!badge) return;
            if (!count) {
                badge.hidden = true;
                badge.textContent = '0';
                return;
            }
            badge.hidden = false;
            badge.textContent = String(Math.min(count, 99));
        }

        function renderNotifPanel(items) {
            const list = document.getElementById('notifList');
            if (!list) return;

            if (!items.length) {
                list.innerHTML = '<div style="padding:.85rem;color:#6b7280;font-size:.92rem;">No new requests.</div>';
                return;
            }

            list.innerHTML = items
                .slice()
                .sort((a, b) => String(b.ref || '').localeCompare(String(a.ref || '')))
                .slice(0, 10)
                .map((r) => {
                    const name = String(r.name || 'Resident').trim() || 'Resident';
                    const ref = String(r.ref || '').trim();
                    const date = formatNotifDate(r.dateRequested || r.date);
                    return '<div style="padding:.65rem .7rem;border-radius:10px;border:1px solid #eef2f7;background:#f8fafc;margin:.35rem;">' +
                        '<div style="font-size:.86rem;font-weight:700;color:#111827;">New request from ' + name +
                        '</div>' +
                        '<div style="font-size:.8rem;color:#4b5563;margin-top:.2rem;">Ref: ' + ref + '</div>' +
                        '<div style="font-size:.78rem;color:#6b7280;">' + date + '</div>' +
                        '</div>';
                })
                .join('');
        }

        function showNotifToast(message) {
            const host = document.getElementById('notifToastHost');
            if (!host) return;
            const item = document.createElement('div');
            item.style.pointerEvents = 'none';
            item.style.background = '#111827';
            item.style.color = '#fff';
            item.style.padding = '.7rem .85rem';
            item.style.borderRadius = '10px';
            item.style.boxShadow = '0 10px 28px rgba(2,6,23,.25)';
            item.style.fontSize = '.9rem';
            item.style.opacity = '0';
            item.style.transform = 'translateY(-8px)';
            item.style.transition = 'all .2s ease';
            item.textContent = message;
            host.appendChild(item);
            requestAnimationFrame(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            });
            setTimeout(() => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(-8px)';
                setTimeout(() => item.remove(), 220);
            }, 2800);
        }

        function updateNotifications(requests, options = {}) {
            const seenSet = readSeenRefs();
            const unseen = (requests || []).filter((r) => {
                const ref = String(r?.ref || '').trim();
                return ref && !seenSet.has(ref);
            });
            renderNotifBadge(unseen.length);
            renderNotifPanel(unseen);

            if (options.toastNew && options.newItems && options.newItems.length) {
                const first = options.newItems[0];
                const firstName = String(first?.name || 'resident').trim() || 'resident';
                const count = options.newItems.length;
                showNotifToast(count === 1 ?
                    ('New request received from ' + firstName) :
                    ('You have ' + count + ' new requests.'));
            }
        }

        function normalize(s) {
            return String(s || '').trim().toLowerCase();
        }

        function getRequestDocumentText(req) {
            const globalTemplate = readGlobalClearanceTemplate();
            const certType = String((req?.savedTemplate && req.savedTemplate.certificateType) || req?.savedCertType || (
                globalTemplate && globalTemplate.certificateType) || '').trim();
            const purpose = String(req?.purpose || '').trim();
            const reason = String(req?.purposeReason || '').trim();
            return normalize([certType, purpose, reason].filter(Boolean).join(' '));
        }

        function getRequestDocumentKey(req) {
            const text = getRequestDocumentText(req);
            if (!text) return 'other';
            if (text.includes('clearance')) return 'clearance';
            if (text.includes('indigency')) return 'indigency';
            if (text.includes('good moral')) return 'good_moral';
            if (text.includes('oneness')) return 'oneness';
            if (text.includes('first-time job seeker') || text.includes('first time job seeker') || text.includes(
                    'job seeker')) return 'job_seeker';
            if (text.includes('oath of undertaking')) return 'oath';
            if (text.includes('certificate') || text.includes('certification')) return 'other';
            return 'other';
        }

        function getDocumentFilterLabel(key) {
            const found = DOCUMENT_FILTERS.find((item) => item.key === key);
            return found ? found.label : 'Other Certificates';
        }

        function filterRequestsByDocument(requests, filterKey) {
            const key = String(filterKey || 'all');
            if (key === 'all') return Array.isArray(requests) ? requests.slice() : [];
            return (Array.isArray(requests) ? requests : []).filter((req) => getRequestDocumentKey(req) === key);
        }

        function getResidentDocumentRequests(resident, filterKey) {
            const reqs = Array.isArray(resident?.requests) ? resident.requests : [];
            const key = String(filterKey || 'all');
            if (key === 'all') return reqs.slice();
            return reqs.filter((req) => getRequestDocumentKey(req) === key);
        }

        function groupResidents(requests) {
            const map = new Map();
            for (const r of requests) {
                const name = String(r.name || '').trim();
                if (!name) continue;
                const key = normalize(name);
                const entry = map.get(key) || {
                    name,
                    requests: []
                };
                entry.requests.push(r);
                map.set(key, entry);
            }
            // compute stats per resident
            const residents = Array.from(map.values()).map(x => {
                const reqs = x.requests.slice().sort((a, b) => String(b.dateRequested || '').localeCompare(String(a
                    .dateRequested || '')));
                const pending = reqs.filter(r => normalize(r.status) === 'pending').length;
                const last = reqs[0] ? (reqs[0].dateRequested || reqs[0].date || '') : '';
                return {
                    name: x.name,
                    key: normalize(x.name),
                    total: reqs.length,
                    pending,
                    last,
                    requests: reqs
                };
            });
            // sort by last date desc then name
            residents.sort((a, b) => {
                const d = String(b.last || '').localeCompare(String(a.last || ''));
                if (d !== 0) return d;
                return a.name.localeCompare(b.name);
            });
            return residents;
        }

        function renderDocumentFilters(requests) {
            const host = document.getElementById('residentDocFilters');
            if (!host) return;

            host.innerHTML = DOCUMENT_FILTERS.map((item) => {
                const count = item.key === 'all' ?
                    requests.length :
                    requests.filter((req) => getRequestDocumentKey(req) === item.key).length;
                const active = activeDocFilter === item.key ? ' active' : '';
                return '<button type="button" class="resident-doc-btn' + active + '" data-doc-filter="' + item.key +
                    '" aria-pressed="' + (active ? 'true' : 'false') + '">' +
                    item.label + ' <span style="font-weight:800;opacity:.78">(' + count + ')</span>' +
                    '</button>';
            }).join('');
        }

        function renderProcessingVolume(requests) {
            const grid = document.getElementById('residentVolumeGrid');
            const summary = document.getElementById('residentVolumeSummary');
            if (!grid || !summary) return;

            const total = requests.length;
            summary.textContent = total + ' total request' + (total === 1 ? '' : 's');

            const volumeFilters = DOCUMENT_FILTERS.filter((item) => item.key !== 'all');
            const maxCount = Math.max(1, ...volumeFilters.map((item) => requests.filter((req) => getRequestDocumentKey(
                req) === item.key).length));

            grid.innerHTML = volumeFilters.map((item) => {
                const matched = requests.filter((req) => getRequestDocumentKey(req) === item.key);
                const pending = matched.filter((req) => normalize(req.status) === 'pending').length;
                const width = matched.length ? Math.max(Math.round((pending / maxCount) * 100), 6) : 0;
                const active = activeDocFilter === item.key ? ' active' : '';
                return '<button type="button" class="resident-volume-card' + active + '" data-doc-filter="' + item
                    .key + '" style="text-align:left;cursor:pointer;">' +
                    '<div class="label">' + item.label + '</div>' +
                    '<div class="value">' + pending + '</div>' +
                    '<div style="font-size:.78rem;color:#64748b;margin-top:.15rem;">Pending of ' + matched.length +
                    ' request' + (matched.length === 1 ? '' : 's') + '</div>' +
                    '<div class="resident-volume-bar" aria-hidden="true"><span style="width:' + width +
                    '%"></span></div>' +
                    '</button>';
            }).join('');
        }

        function setStats(requests, residents) {
            const approved = requests.filter(r => normalize(r.status) === 'approved').length;
            const rejected = requests.filter(r => normalize(r.status) === 'rejected').length;
            const residentsPending = residents.filter(r => r.pending > 0).length;

            document.getElementById('statResidents').textContent = String(residents.length);
            document.getElementById('statResidentsPending').textContent = String(residentsPending);
            document.getElementById('statApproved').textContent = String(approved);
            document.getElementById('statRejected').textContent = String(rejected);
        }

        function renderResidents(residents) {
            const q = normalize(document.getElementById('q').value);
            const filter = normalize(document.getElementById('hasPending').value);

            const filtered = residents.filter(r => {
                const matchQ = !q || normalize(r.name).includes(q);
                const matchPending = !filter || (filter === 'pending' ? r.pending > 0 : r.pending === 0);
                return matchQ && matchPending;
            });

            const rows = document.getElementById('rows');
            if (!filtered.length) {
                rows.innerHTML = '<tr><td colspan="5" style="padding:1rem;color:#6b7280">No residents found.</td></tr>';
                return;
            }

            rows.innerHTML = filtered.map(r => {
                const pendingBadge = r.pending > 0 ? '<span class="badge pending">' + r.pending +
                    ' Pending</span>' : '<span class="badge approved">0 Pending</span>';
                return '<tr>' +
                    '<td>' + r.name + '</td>' +
                    '<td>' + r.total + '</td>' +
                    '<td>' + pendingBadge + '</td>' +
                    '<td>' + (r.last || 'â€”') + '</td>' +
                    '<td><div class="actions">' +
                    '<button class="btn-mini view" data-action="view" data-key="' + r.key +
                    '">View Requests</button>' +
                    '</div></td>' +
                    '</tr>';
            }).join('');
        }

        // Modal handling
        const residentModal = document.getElementById('residentModal');
        const residentClose = document.getElementById('residentClose');
        const residentTitle = document.getElementById('residentTitle');
        const residentMeta = document.getElementById('residentMeta');
        const residentReqRows = document.getElementById('residentReqRows');
        let currentKey = '';
        let currentResident = null;
        let currentResidentRequests = [];

        function openResidentModal(resident) {
            currentKey = resident.key;
            currentResident = resident;
            currentResidentRequests = getResidentDocumentRequests(resident, activeDocFilter);
            residentTitle.textContent = resident.name;
            const filterLabel = activeDocFilter === 'all' ? 'all documents' : getDocumentFilterLabel(activeDocFilter)
                .toLowerCase();
            residentMeta.textContent = 'Showing ' + currentResidentRequests.length + ' request' + (currentResidentRequests
                .length === 1 ? '' : 's') + ' for ' + filterLabel + ' out of ' + resident.total + ' total request' + (
                resident.total === 1 ? '' : 's') + '.';
            if (!currentResidentRequests.length) {
                residentReqRows.innerHTML = '<tr><td colspan="5" style="padding:1rem;color:#6b7280">No requests.</td></tr>';
            } else {
                residentReqRows.innerHTML = currentResidentRequests.map(req => {
                    const st = normalize(req.status) || 'pending';
                    const badge = st === 'approved' ?
                        '<span class="badge approved">Approved</span>' :
                        (st === 'rejected' ? '<span class="badge rejected">Rejected</span>' :
                            '<span class="badge pending">Pending</span>');
                    const globalTemplate = readGlobalClearanceTemplate();
                    const certTypeRaw = String((req.savedTemplate && req.savedTemplate.certificateType) || req
                        .savedCertType || (globalTemplate && globalTemplate.certificateType) || '').trim();
                    const certTypeSafe = certTypeRaw
                        .replaceAll('&', '&amp;')
                        .replaceAll('<', '&lt;')
                        .replaceAll('>', '&gt;');
                    const certTypeNote = certTypeSafe ?
                        '<div style="font-size:.72rem;color:#4b5563;margin-top:.25rem;line-height:1.2;">' +
                        certTypeSafe + '</div>' :
                        '';
                    const pdfBadge = (req.pdfSaved || !!globalTemplate) ?
                        ('<span class="badge approved">Saved</span>' + certTypeNote) :
                        '<span class="badge pending">Not Saved</span>';
                    return '<tr>' +
                        '<td>' + (req.ref || '') + '</td>' +
                        '<td>' + (req.purpose || 'â€”') + '</td>' +
                        '<td>' + (req.dateRequested || req.date || 'â€”') + '</td>' +
                        '<td>' + badge + '</td>' +
                        '<td>' + pdfBadge + '</td>' +
                        '</tr>';
                }).join('');
            }
            residentModal.hidden = false;
            residentModal.classList.add('open');
        }

        function closeResidentModal() {
            residentModal.hidden = true;
            residentModal.classList.remove('open');
            currentKey = '';
            currentResident = null;
            currentResidentRequests = [];
        }

        function applyDocumentFilter(nextKey) {
            activeDocFilter = String(nextKey || 'all');
            refreshResidentsTable();
            if (currentResident) {
                const matchedResident = residents.find((r) => r.key === currentResident.key);
                if (matchedResident) {
                    openResidentModal(matchedResident);
                } else {
                    closeResidentModal();
                }
            }
        }

        residentClose.addEventListener('click', closeResidentModal);
        residentModal.addEventListener('click', (e) => {
            if (e.target === residentModal) closeResidentModal();
        });

        document.getElementById('goDashboard').addEventListener('click', () => {
            const latestReq = currentResidentRequests && currentResidentRequests.length ?
                currentResidentRequests[0] :
                null;

            if (latestReq) {
                const payload = {
                    name: String((currentResident && currentResident.name) || latestReq.name || '').trim(),
                    age: latestReq.age ?? '',
                    address: String(latestReq.address || '').trim(),
                    purpose: String(latestReq.purposeReason || latestReq.purpose || '').trim(),
                    date: String(latestReq.dateRequested || latestReq.date || '').trim(),
                    ref: String(latestReq.ref || '').trim(),
                };
                try {
                    sessionStorage.setItem(CERT_AUTOFILL_KEY, JSON.stringify(payload));
                    if (latestReq.savedTemplate && typeof latestReq.savedTemplate === 'object') {
                        sessionStorage.setItem(TEMPLATE_OVERRIDE_KEY, JSON.stringify(latestReq.savedTemplate));
                    } else {
                        const globalTemplate = readGlobalClearanceTemplate();
                        if (globalTemplate && typeof globalTemplate === 'object') {
                            sessionStorage.setItem(TEMPLATE_OVERRIDE_KEY, JSON.stringify(globalTemplate));
                        } else {
                            sessionStorage.removeItem(TEMPLATE_OVERRIDE_KEY);
                        }
                    }
                } catch (err) {
                    console.error('Unable to save certificate autofill payload', err);
                }
            }

            window.location.href = '/certificate?t=' + Date.now();
        });

        document.getElementById('rows').addEventListener('click', (e) => {
            const btn = e.target.closest && e.target.closest('button[data-action]');
            if (!btn) return;
            const action = btn.getAttribute('data-action');
            if (action !== 'view') return;
            const key = btn.getAttribute('data-key');
            const res = residents.find(r => r.key === key);
            if (res) openResidentModal(res);
        });

        document.getElementById('residentDocFilters').addEventListener('click', (e) => {
            const btn = e.target.closest && e.target.closest('button[data-doc-filter]');
            if (!btn) return;
            applyDocumentFilter(btn.getAttribute('data-doc-filter'));
        });

        document.getElementById('residentVolumeGrid').addEventListener('click', (e) => {
            const card = e.target.closest && e.target.closest('[data-doc-filter]');
            if (!card) return;
            applyDocumentFilter(card.getAttribute('data-doc-filter'));
        });

        const admMenuToggle = document.getElementById('admMenuToggle');
        const admSidebarOverlay = document.getElementById('admSidebarOverlay');
        const admNav = document.querySelector('.adm-nav');

        function setMobileMenu(open) {
            document.body.classList.toggle('adm-menu-open', !!open);
            if (admMenuToggle) admMenuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        }
        if (admMenuToggle) {
            admMenuToggle.addEventListener('click', () => {
                const willOpen = !document.body.classList.contains('adm-menu-open');
                setMobileMenu(willOpen);
            });
        }
        if (admSidebarOverlay) {
            admSidebarOverlay.addEventListener('click', () => setMobileMenu(false));
        }
        if (admNav) {
            admNav.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 720) setMobileMenu(false);
                });
            });
        }
        window.addEventListener('resize', () => {
            if (window.innerWidth > 720) setMobileMenu(false);
        });

        document.getElementById('adminLogout').addEventListener('click', async () => {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            try {
                await fetch('/loginadmin/logout', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
            } catch (err) {
                console.error('Logout error', err);
            }

            window.location.replace('/');
        });

        window.addEventListener('pageshow', (event) => {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
</body>

</html>
