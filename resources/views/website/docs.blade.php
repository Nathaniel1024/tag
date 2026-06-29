<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Documents - DIGIBARANGAY</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo_zed.png') }}" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
    <style>
        /* Toast styles */
        #toastContainer .toast {
            min-width: 300px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: .8rem 1rem;
            border-radius: 8px;
            box-shadow: 0 8px 30px rgba(2, 6, 23, 0.12);
            margin-bottom: .6rem;
            opacity: 0;
            transform: translateY(-8px);
            transition: all .3s ease;
            pointer-events: auto
        }

        #toastContainer .toast.success {
            background: linear-gradient(180deg, #ecfdf5, #bbf7d0);
            border-color: #86efac
        }

        #toastContainer .toast .toast-body {
            display: flex;
            align-items: center;
            gap: .6rem
        }

        #toastContainer .toast .toast-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: #10b981;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700
        }

        #toastContainer .toast .toast-text {
            font-weight: 600;
            color: #064e3b
        }

        .dashboard-top {
            background: linear-gradient(90deg, #0b66c3 0%, #0a5fb8 100%);
            color: #fff;
            padding: .9rem 0;
            box-shadow: 0 2px 8px rgba(2, 6, 23, 0.08)
        }

        .dashboard-top .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap
        }

        .dashboard-top .brand-block {
            display: flex;
            align-items: center;
            gap: 1rem;
            min-width: 0
        }

        .dashboard-top .brand-copy {
            min-width: 0
        }

        .dashboard-top .brand-copy>div:first-child {
            font-weight: 700;
            line-height: 1.2
        }

        .dashboard-top .brand-copy>div:last-child {
            font-size: .9rem;
            opacity: .9;
            line-height: 1.35
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: .6rem;
            flex-wrap: wrap;
            justify-content: flex-end
        }

        .user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            border: 1px solid rgba(255, 255, 255, 0.12)
        }

        .dashboard-top img {
            border-radius: 50%;
            background: #fff;
            padding: 4px
        }

        .logout-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: #fff;
            cursor: pointer
        }

        .logout-icon:hover {
            background: rgba(255, 255, 255, 0.18)
        }

        .logout-icon svg {
            width: 18px;
            height: 18px;
            stroke: #fff
        }

        .page-hero {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap
        }

        .page-actions {
            display: flex;
            align-items: center;
            gap: .75rem;
            flex-wrap: wrap
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1rem;
            margin: 1rem 0
        }

        .stat-box {
            flex: 1;
            background: #fff;
            border-radius: 10px;
            padding: 1.2rem;
            border: 1px solid #eef4fb;
            text-align: center;
            box-shadow: 0 4px 18px rgba(2, 6, 23, 0.04)
        }

        .stat-box div:first-child {
            font-size: 1.5rem;
            font-weight: 800;
            color: #0b66c3
        }

        .apply-btn {
            background: #ffd400;
            color: #111;
            padding: .6rem .9rem;
            border-radius: 8px;
            font-weight: 700;
            box-shadow: 0 6px 12px rgba(255, 212, 77, 0.18);
            border: 1px solid rgba(0, 0, 0, 0.06)
        }

        /* controls */
        .controls {
            display: flex;
            gap: .75rem;
            align-items: center;
            margin: 1rem 0;
            flex-wrap: wrap
        }

        .search-box {
            flex: 1;
            display: flex;
            align-items: center;
            background: #fff;
            border: 1px solid #eef2f7;
            padding: .4rem .6rem;
            border-radius: 8px
        }

        .search-box input {
            border: 0;
            outline: none;
            padding: .5rem .5rem;
            width: 100%;
            font-size: .95rem
        }

        .filter-select {
            min-width: 160px;
            padding: .5rem;
            border-radius: 8px;
            border: 1px solid #eef2f7;
            background: #fff
        }

        /* actions */
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: 1px solid #eef2f7;
            background: #fff;
            color: #374151;
            margin-left: .3rem
        }

        .action-btn:hover {
            background: #f8fafc
        }

        .actions-cell {
            display: flex;
            gap: .4rem;
            align-items: center
        }

        /* subtle card around table */
        .table-card {
            background: #fff;
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #eef4fb;
            overflow-x: auto
        }

        .requests-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            background: #fff;
            border-radius: 8px;
            overflow: hidden
        }

        .requests-table {
            min-width: 860px
        }

        .requests-table thead th {
            background: #fbfdff;
            padding: 10px 12px;
            border-bottom: 1px solid #eef4fb;
            text-align: left;
            font-weight: 700
        }

        .requests-table tbody td {
            padding: .75rem 12px;
            border-bottom: 1px solid #f4f6f9
        }

        .requests-table tbody tr:last-child td {
            border-bottom: 0
        }

        .status-badge {
            display: inline-block;
            padding: .25rem .6rem;
            border-radius: 999px;
            font-weight: 700;
            font-size: .85rem
        }

        .status-pending {
            background: #fff7ed;
            color: #92400e;
            border: 1px solid #fde3bf
        }

        .status-approved {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #bbf7d0
        }

        .status-rejected {
            background: #fff1f2;
            color: #831843;
            border: 1px solid #ffd6e0
        }

        .modal-form select {
            width: 100%;
            box-sizing: border-box;
            padding: .8rem 1rem;
            border-radius: 10px;
            border: 1px solid #e6e9ef;
            background: #f8fafc
        }

        body {
            position: relative;
        }

        body::before {
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

        body>* {
            position: relative;
            z-index: 1;
        }

        @media (max-width: 1024px) {
            .dashboard-top .container {
                align-items: flex-start
            }

            .stats-row {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }
        }

        @media (max-width: 768px) {
            .dashboard-top {
                padding: .8rem 0
            }

            .dashboard-top .container {
                flex-direction: column;
                align-items: flex-start
            }

            .dashboard-top .brand-block {
                width: 100%
            }

            .dashboard-top .brand-copy>div:last-child {
                font-size: .85rem
            }

            .user-info {
                width: 100%;
                justify-content: space-between
            }

            .page-hero {
                align-items: flex-start
            }

            .page-actions {
                width: 100%
            }

            .page-actions .apply-btn {
                width: 100%
            }

            .stats-row {
                grid-template-columns: 1fr
            }

            .search-box {
                min-width: 0;
                width: 100%
            }

            .filter-select {
                width: 100%
            }

            .controls {
                gap: .6rem
            }

            .table-card {
                padding: 8px
            }

            .requests-table {
                min-width: 760px
            }

            .modal {
                width: min(96vw, 520px)
            }

            .modal-body {
                max-height: 75vh
            }

            #viewFrame {
                height: 62vh !important
            }
        }

        @media (max-width: 480px) {
            .container {
                width: 92%
            }

            .dashboard-top .brand-block {
                gap: .75rem
            }

            .dashboard-top img {
                height: 38px !important
            }

            .user-avatar {
                width: 38px;
                height: 38px
            }

            .logout-icon {
                width: 34px;
                height: 34px
            }

            .page-hero h2 {
                font-size: 1.25rem
            }

            .stat-box {
                padding: 1rem
            }

            .stat-box div:first-child {
                font-size: 1.35rem
            }

            .table-card {
                margin-left: -2px;
                margin-right: -2px
            }

            .requests-table {
                min-width: 700px
            }

            .modal {
                padding: 1rem
            }

            .modal .modal-header {
                margin: -.75rem -1rem 0
            }

            .modal-body {
                max-height: 72vh
            }

            #viewFrame {
                height: 56vh !important
            }
        }
    </style>
</head>

<body>
    <header class="dashboard-top">
        <div class="container">
            <div class="brand-block">
                <img src="{{ asset('img/logo_zed.png') }}" alt="logo" style="height:44px" />
                <div class="brand-copy">
                    <div>Resident Dashboard</div>
                    <div>Apply for barangay clearance and track your requests</div>
                </div>
            </div>
            <div class="user-info" style="cursor:pointer;" id="profileInfo">
                <div style="text-align:right">
                    <div id="dashUserName">User Name</div>
                    <div id="dashUserEmail" style="font-size:.9rem;opacity:.85"></div>
                </div>
                <div class="user-avatar" id="dashAvatar">JD</div>
                <button id="logoutBtn" class="logout-icon" type="button" aria-label="Logout" title="Logout"
                    onclick="return window.handleResidentLogout(event)">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <path d="M16 17l5-5-5-5" />
                        <path d="M21 12H9" />
                    </svg>
                </button>
            </div>
        </div>
    </header>
    <!-- Toast notifications container -->
    <div id="toastContainer" style="position:fixed;top:16px;right:16px;z-index:120;pointer-events:none"></div>

    <main class="container" style="padding:1.25rem 0">
        <div class="page-hero">
            <h2 id="welcomeResidentText">Welcome back, resident!</h2>
            <div class="page-actions">
                <button class="apply-btn">Apply for New Clearance</button>
            </div>
        </div>

        <div class="announcement-list" style="margin-top:1rem">
            <div style="background:#eef7ff;padding:1rem;border-radius:6px">How to Apply / Paano Mag-Apply: 1) Click
                "Apply for Clearance" to submit a new request. 2) Fill out the required information accurately. 3)
                Upload a valid ID if needed. 4) Track your request status.</div>
        </div>

        <div class="stats-row">
            <div class="stat-box">
                <div id="statTotal">0</div>
                <div style="color:var(--muted)">Total Requests</div>
            </div>
            <div class="stat-box">
                <div id="statPending">0</div>
                <div style="color:var(--muted)">Pending</div>
            </div>
            <div class="stat-box">
                <div id="statApproved">0</div>
                <div style="color:var(--muted)">Approved</div>
            </div>
            <div class="stat-box">
                <div id="statRejected">0</div>
                <div style="color:var(--muted)">Rejected</div>
            </div>
        </div>

        <section style="margin-top:1rem">
            <h3>My Clearance Requests</h3>
            <div class="controls">
                <div class="search-box"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                        viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg><input id="searchInput" placeholder="Search by Reference ID or Name" /></div>
                <select id="statusFilter" class="filter-select">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
                <div style="margin-left:auto"></div>
            </div>
            <div class="table-card">
                <table class="requests-table">
                    <thead>
                        <tr>
                            <th>Reference ID</th>
                            <th>Name</th>
                            <th>Date Requested</th>
                            <th>Valid Until</th>
                            <th>Status</th>
                            <th>PDF</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="requestsBody">
                        <tr>
                            <td colspan="7" class="muted">You have no requests yet.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- Apply for Clearance Modal -->
    <div id="applyModal" class="modal-overlay" hidden>
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="applyTitle">
            <button class="modal-close" id="applyModalClose" aria-label="Close">✕</button>
            <div class="modal-header">
                <h2 id="applyTitle">Apply for Barangay Clearance</h2>
            </div>
            <div class="modal-body">
                <form id="applyForm" class="modal-form">
                    <fieldset>
                        <label>Full Name *
                            <input name="fullName" type="text" required />
                        </label>
                        <label>Email Address *
                            <input name="email" type="email" placeholder="resident@example.com" required />
                        </label>
                        <label>Complete Address *
                            <input name="address" type="text" required />
                        </label>
                        <label>Age *
                            <input name="age" type="number" min="0" required />
                        </label>
                        <label>Contact Number *
                            <input name="contact" type="tel" placeholder="09XXXXXXXXX" required />
                        </label>
                        <label>Type of Certicate *
                            <select name="purpose" required>
                                <option value="" selected disabled>Select clearance type</option>
                                <option value="Barangay Clearance">Barangay Clearance</option>
                                <option value="Barangay Indigency">Barangay Indigency</option>
                                <option value="Certificate of Good Moral Character">Certificate of Good Moral Character
                                </option>
                                <option value="Certificate of Oneness">Certificate of Oneness</option>
                                <option value="Certificate for 1st-Time Job Seeker">Certificate for 1st-Time Job Seeker
                                </option>
                                <option value="Oath of Undertaking">Oath of Undertaking</option>
                            </select>
                        </label>
                        <label>Purpose *
                            <input name="purposeReason" type="text"
                                placeholder="Hal. Requirement sa trabaho o school" required />
                        </label>
                        <label>Upload Valid ID (photo of your ID) *
                            <input name="idfile" type="file" accept="image/*" />
                            <small style="display:block;font-size:.85rem;color:#475569;margin-top:.35rem">Upload a
                                clear photo of your valid ID card, passport, or driver's license.</small>
                        </label>
                        <div id="idPreview" style="display:none;margin-top:.8rem;">
                            <img id="idPreviewImage" alt="ID preview"
                                style="max-width:100%;border-radius:10px;border:1px solid #e5e7eb;" />
                        </div>
                    </fieldset>
                    <div class="form-actions">
                        <button type="button" class="btn" id="applyCancel">CANCEL</button>
                        <button type="submit" class="btn primary">SUBMIT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View ID Image Modal -->
    <div id="viewIdModal" class="modal-overlay" hidden>
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="viewIdTitle">
            <button class="modal-close" id="viewIdModalClose" aria-label="Close">✕</button>
            <div class="modal-header">
                <h2 id="viewIdTitle">Uploaded ID Image</h2>
            </div>
            <div class="modal-body" style="display:flex;flex-direction:column;gap:1rem;align-items:center;">
                <img id="viewIdImage" alt="Uploaded ID"
                    style="max-width:100%;border-radius:14px;border:1px solid #e5e7eb;object-fit:contain;" />
                <div id="viewIdCaption" style="font-size:.95rem;color:#334155;text-align:center;"></div>
            </div>
        </div>
    </div>

    <!-- View Request Modal -->
    <div id="viewModal" class="modal-overlay" hidden>
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="viewTitle">
            <button class="modal-close" id="viewModalClose" aria-label="Close">✕</button>
            <div class="modal-header">
                <h2 id="viewTitle">Certificate PDF Preview</h2>
            </div>
            <div class="modal-body" id="viewBody" style="padding:0;display:flex;flex-direction:column;gap:1rem;">
                <div style="display:flex;justify-content:flex-end;gap:.5rem;padding:1rem 1rem 0;flex-wrap:wrap;">
                    <button type="button" class="btn primary" id="viewDownloadBtn">Download PDF</button>
                </div>
                <iframe id="viewFrame" title="Certificate preview"
                    style="width:100%;height:78vh;border:0;background:#fff;border-radius:0 0 16px 16px;"></iframe>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div id="changePasswordModal" class="modal-overlay" hidden>
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="changePasswordTitle">
            <button class="modal-close" id="changePasswordModalClose" aria-label="Close">✕</button>
            <div class="modal-header">
                <h2 id="changePasswordTitle">Change Password</h2>
            </div>
            <div class="modal-body">
                <form id="changePasswordForm" class="modal-form">
                    <fieldset>
                        <label>Current Password *
                            <input id="oldPasswordInput" name="oldPassword" type="password" required />
                        </label>
                        <label>New Password *
                            <input id="newPasswordInput" name="newPassword" type="password" required />
                        </label>
                        <label>Confirm Password *
                            <input id="confirmPasswordInput" name="confirmPassword" type="password" required />
                        </label>
                    </fieldset>
                    <div class="form-actions">
                        <button type="button" class="btn" id="changePasswordCancel">CANCEL</button>
                        <button type="submit" class="btn primary">UPDATE PASSWORD</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <script>
        const CERT_AUTOFILL_KEY = 'digibarangay_cert_autofill';
        const TEMPLATE_KEY = 'digibarangay_certificate_template_v2';
        const TEMPLATE_OVERRIDE_KEY = 'digibarangay_cert_template_override_v1';
        const GLOBAL_CLEARANCE_TEMPLATE_KEY = 'digibarangay_saved_clearance_template_v1';
        const DEFAULT_TEMPLATE = {
            certificateType: 'BARANGAY CLEARANCE',
            bodyHeading: 'TO WHOM IT MAY CONCERN:',
            mainBody: 'This is to certify that (NAME), (AGE) years old, a resident of (ADDRESS), is known to be of good moral character and has no derogatory records filed in this barangay.',
            purposeStatement: 'This certification is issued upon the request of the above-named person for (PURPOSE).',
            issuedLine: 'Issued this (DATE) at BARANGAY 192.',
            signName: 'Barangay Captain Name',
            signTitle: 'Punong Barangay',
            barangayName: 'BARANGAY 192',
            barangayAddress: 'City/Municipality, Province'
        };
        let currentPreviewRequest = null;
        let currentPreviewPdfUrl = '';
        let currentPreviewPdfBlob = null;

        function readGlobalClearanceTemplate() {
            try {
                const raw = localStorage.getItem(GLOBAL_CLEARANCE_TEMPLATE_KEY);
                const parsed = raw ? JSON.parse(raw) : null;
                return parsed && typeof parsed === 'object' ? parsed : null;
            } catch {
                return null;
            }
        }

        // Layout preview: skip backend auth check and use any stored local user (if present)
        (function() {
            try {
                function clearResidentAuthState() {
                    localStorage.removeItem('authToken');
                    localStorage.removeItem('digibarangay_logged_in');
                    localStorage.removeItem('digibarangay_user');
                    localStorage.removeItem('digibarangay_registered_user');
                    sessionStorage.removeItem('digibarangay_logged_in');
                    sessionStorage.removeItem('digibarangay_user');
                }

                window.handleResidentLogout = async function(event) {
                    if (event) {
                        event.preventDefault();
                        event.stopPropagation();
                        if (typeof event.stopImmediatePropagation === 'function') {
                            event.stopImmediatePropagation();
                        }
                    }

                    const token = localStorage.getItem('authToken');
                    clearResidentAuthState();

                    try {
                        const headers = {
                            'Content-Type': 'application/json'
                        };
                        if (token) headers.Authorization = 'Bearer ' + token;
                        await fetch('/api/auth/logout', {
                            method: 'POST',
                            headers
                        });
                    } catch (err) {
                        console.error('Logout error:', err);
                    }

                    window.location.replace('/');
                    return false;
                };

                function hasResidentSession() {
                    return localStorage.getItem('digibarangay_logged_in') === '1';
                }

                if (!hasResidentSession()) {
                    window.location.replace('/');
                    return;
                }

                window.addEventListener('pageshow', function() {
                    if (!hasResidentSession()) {
                        window.location.replace('/');
                    }
                });

                function getResidentUserSnapshot() {
                    try {
                        const s = localStorage.getItem('digibarangay_user') || localStorage.getItem(
                            'digibarangay_registered_user');
                        if (!s) return null;
                        try {
                            return JSON.parse(s);
                        } catch (_e) {
                            // Backward compatibility if old code stored plain text instead of JSON
                            return {
                                fullname: String(s),
                                username: String(s),
                                email: ''
                            };
                        }
                    } catch (e) {
                        return null;
                    }
                }

                const user = getResidentUserSnapshot();

                function userIdentityKey(u) {
                    if (!u || typeof u !== 'object') return '';
                    return String(
                        u.user_key ||
                        u.id ||
                        u.email ||
                        u.username ||
                        u.fullname ||
                        u.name ||
                        ''
                    ).trim().toLowerCase();
                }

                const currentUserKey = userIdentityKey(user);

                function currentUserIdentityCandidates() {
                    return [
                        currentUserKey,
                        user?.email,
                        user?.username,
                        user?.fullname,
                        user?.name,
                    ].map((value) => String(value || '').trim().toLowerCase()).filter(Boolean);
                }

                function readAllRequests() {
                    try {
                        const raw = localStorage.getItem('digibarangay_requests');
                        const parsed = raw ? JSON.parse(raw) : [];
                        return Array.isArray(parsed) ? parsed : [];
                    } catch (_e) {
                        return [];
                    }
                }

                function requestBelongsToCurrentUser(req) {
                    if (!req) return false;
                    const userCandidates = currentUserIdentityCandidates();
                    if (!userCandidates.length) return false;

                    const requestCandidates = [
                        req.ownerKey,
                        req.owner_key,
                        req.userKey,
                        req.ownerName,
                        req.owner_name,
                        req.ownerEmail,
                        req.owner_email,
                        req.email,
                    ].map((value) => String(value || '').trim().toLowerCase()).filter(Boolean);

                    if (requestCandidates.some((candidate) => userCandidates.includes(candidate))) {
                        return true;
                    }

                    // Backward compatibility for old records without owner key.
                    const reqName = String(req.name || '').trim().toLowerCase();
                    const currentName = String(user?.fullname || user?.name || user?.username || '').trim().toLowerCase();
                    return reqName !== '' && currentName !== '' && reqName === currentName;
                }

                function readMyRequests() {
                    return readAllRequests().filter(requestBelongsToCurrentUser);
                }

                function writeAllRequests(next) {
                    try {
                        localStorage.setItem('digibarangay_requests', JSON.stringify(next));
                    } catch (err) {
                        console.error('save request', err);
                        return false;
                    }
                    return true;
                }

                function normalizeRequestOwner(req) {
                    if (!req || typeof req !== 'object') return req;
                    if (!req.ownerKey && !req.owner_key && !req.userKey && requestBelongsToCurrentUser(req)) {
                        req.ownerKey = currentUserKey;
                    }
                    return req;
                }

                function normalizeServerRequest(request) {
                    if (!request || typeof request !== 'object') return null;
                    return {
                        ref: String(request.ref || '').trim(),
                        name: String(request.name || '').trim(),
                        email: String(request.email || '').trim(),
                        ownerName: String(request.ownerName || request.owner_name || '').trim(),
                        ownerEmail: String(request.ownerEmail || request.owner_email || '').trim(),
                        address: String(request.address || '').trim(),
                        age: String(request.age ?? '').trim(),
                        dateRequested: String(request.dateRequested || '').trim(),
                        validUntil: String(request.validUntil || '').trim(),
                        status: String(request.status || 'pending').trim(),
                        purpose: String(request.purpose || '').trim(),
                        purposeReason: String(request.purposeReason || request.purpose_reason || '').trim(),
                        contact: String(request.contact || '').trim(),
                        ownerKey: String(request.ownerKey || request.owner_key || currentUserKey || '').trim(),
                        idFileName: String(request.idFileName || request.id_file_name || '').trim(),
                        idFileType: String(request.idFileType || request.id_file_mime || '').trim(),
                        idFileUrl: String(request.idFileUrl || request.id_file_url || '').trim(),
                        pdfSaved: !!request.pdfSaved,
                        savedTemplate: request.savedTemplate || null,
                    };
                }

                async function syncRequestsFromServer() {
                    try {
                        const params = new URLSearchParams();
                        if (currentUserKey) params.set('owner_key', currentUserKey);
                        if (user?.email) params.set('email', String(user.email).trim().toLowerCase());
                        if (user?.fullname) params.set('owner_name', String(user.fullname).trim().toLowerCase());
                        if (user?.username) params.set('username', String(user.username).trim().toLowerCase());

                        const response = await fetch('/clearance-requests?' + params.toString(), {
                            headers: {
                                'Accept': 'application/json'
                            },
                        });
                        if (!response.ok) return;

                        const result = await response.json().catch(() => ({}));
                        const serverRequests = Array.isArray(result.data) ?
                            result.data.map(normalizeServerRequest).filter(Boolean) :
                            [];
                        if (!serverRequests.length) return;

                        if (writeAllRequests(serverRequests)) {
                            requests = serverRequests.map(normalizeRequestOwner);
                            renderRequests(getFilteredRequests());
                        }
                    } catch (error) {
                        console.warn('Unable to sync clearance requests from server', error);
                    }
                }
                const nameEl = document.getElementById('dashUserName');
                const emailEl = document.getElementById('dashUserEmail');
                const avatar = document.getElementById('dashAvatar');
                const welcomeEl = document.getElementById('welcomeResidentText');

                if (user) {
                    const displayName = user.fullname || user.name || user.username || 'Resident';
                    nameEl.textContent = displayName;
                    emailEl.textContent = user.email || '';
                    if (welcomeEl) welcomeEl.textContent = `Welcome back, ${displayName}!`;
                    const initials = displayName.split(' ').map(s => s[0]).slice(0, 2).join('').toUpperCase();
                    avatar.textContent = initials;
                }

                let requests = readMyRequests().map(normalizeRequestOwner);
                if (requests.some(r => r.ownerKey === currentUserKey)) {
                    const allForMigration = readAllRequests().map(normalizeRequestOwner);
                    writeAllRequests(allForMigration);
                }

                const total = requests.length;
                const pending = requests.filter(r => r.status === 'pending').length;
                const approved = requests.filter(r => r.status === 'approved').length;
                const rejected = requests.filter(r => r.status === 'rejected').length;
                document.getElementById('statTotal').textContent = total;
                document.getElementById('statPending').textContent = pending;
                document.getElementById('statApproved').textContent = approved;
                document.getElementById('statRejected').textContent = rejected;

                // populate requests table if any
                const tbody = document.getElementById('requestsBody');

                function renderRequests(list) {
                    if (!list || !list.length) {
                        tbody.innerHTML = '<tr><td colspan="7" class="muted">You have no requests yet.</td></tr>';
                        return;
                    }
                    tbody.innerHTML = list.map(r => {
                        const statusLabel = r.status ? (r.status.charAt(0).toUpperCase() + r.status.slice(1)) :
                            '';
                        const statusHtml =
                        `<span class="status-badge status-${r.status}">${statusLabel}</span>`;
                        const globalTemplate = readGlobalClearanceTemplate();
                        const certTypeRaw = String((r.savedTemplate && r.savedTemplate.certificateType) || r
                                .savedCertType || (globalTemplate && globalTemplate.certificateType) || '')
                            .trim();
                        const certTypeSafe = certTypeRaw
                            .replaceAll('&', '&amp;')
                            .replaceAll('<', '&lt;')
                            .replaceAll('>', '&gt;');
                        const certTypeNote = certTypeSafe ?
                            '<div style="font-size:.72rem;color:#4b5563;margin-top:.25rem;line-height:1.2;">' +
                            certTypeSafe + '</div>' :
                            '';
                        const pdfHtml = r.pdfSaved ||
                            !!globalTemplate ?
                            ('<span class="status-badge status-approved">Saved</span>' + certTypeNote) :
                            '<span class="status-badge status-pending">Not Saved</span>';
                        const viewIdButton = (r.idFileUrl || r.idFileDataUrl) ?
                            `<button class="action-btn" data-action="view-id" data-ref="${r.ref||''}" title="View ID">🖼️</button>` :
                            '';
                        const actions =
                            `<div class="actions-cell">${viewIdButton}<button class="action-btn" data-action="view" data-ref="${r.ref||''}" title="View">👁️</button><button class="action-btn" data-action="delete" data-ref="${r.ref||''}" title="Delete">🗑️</button></div>`;
                        return `<tr data-ref="${r.ref||''}"><td>${r.ref||''}</td><td>${r.name||''}</td><td>${r.dateRequested||''}</td><td>${r.validUntil||''}</td><td>${statusHtml}</td><td>${pdfHtml}</td><td>${actions}</td></tr>`;
                    }).join('');
                }
                renderRequests(requests);
                syncRequestsFromServer();
                // search and filter controls
                const searchInput = document.getElementById('searchInput');
                const statusFilter = document.getElementById('statusFilter');

                function getFilteredRequests() {
                    const q = (searchInput && searchInput.value || '').trim().toLowerCase();
                    const status = (statusFilter && statusFilter.value) || '';
                    return requests.filter(r => {
                        if (status && r.status !== status) return false;
                        if (!q) return true;
                        return (r.ref || '').toLowerCase().includes(q) || (r.name || '').toLowerCase().includes(
                            q);
                    });
                }
                if (searchInput) searchInput.addEventListener('input', () => {
                    renderRequests(getFilteredRequests());
                });
                if (statusFilter) statusFilter.addEventListener('change', () => {
                    renderRequests(getFilteredRequests());
                });

                // Profile modal wiring
                const profileInfo = document.getElementById('profileInfo');
                const changePasswordModal = document.getElementById('changePasswordModal');
                const changePasswordModalClose = document.getElementById('changePasswordModalClose');
                const changePasswordCancel = document.getElementById('changePasswordCancel');
                const changePasswordForm = document.getElementById('changePasswordForm');

                function openChangePasswordModal() {
                    if (changePasswordModal) {
                        changePasswordModal.hidden = false;
                        changePasswordModal.classList.add('open');
                        const f = changePasswordModal.querySelector('input[name="oldPassword"]');
                        if (f) f.focus();
                    }
                }

                function closeChangePasswordModal() {
                    if (changePasswordModal) {
                        changePasswordModal.hidden = true;
                        changePasswordModal.classList.remove('open');
                    }
                }

                if (profileInfo) profileInfo.addEventListener('click', (e) => {
                    // Don't open modal if user clicked the logout button area
                    if (e.target.closest('#logoutBtn')) return;
                    e.preventDefault();
                    if (changePasswordForm) changePasswordForm.reset();
                    openChangePasswordModal();
                });
                if (changePasswordModalClose) changePasswordModalClose.addEventListener('click',
                    closeChangePasswordModal);
                if (changePasswordCancel) changePasswordCancel.addEventListener('click', closeChangePasswordModal);
                if (changePasswordModal) changePasswordModal.addEventListener('click', (e) => {
                    if (e.target === changePasswordModal) closeChangePasswordModal();
                });

                // Password change form submission
                if (changePasswordForm) {
                    changePasswordForm.addEventListener('submit', async (e) => {
                        e.preventDefault();
                        const oldPassword = document.getElementById('oldPasswordInput').value.trim();
                        const newPassword = document.getElementById('newPasswordInput').value.trim();
                        const confirmPassword = document.getElementById('confirmPasswordInput').value
                    .trim();

                        // Validation
                        if (!oldPassword || !newPassword || !confirmPassword) {
                            showToast('All fields are required', {
                                duration: 2500
                            });
                            return;
                        }

                        if (newPassword !== confirmPassword) {
                            showToast('New Password and Confirm Password do not match', {
                                duration: 2500
                            });
                            return;
                        }

                        if (newPassword.length < 6) {
                            showToast('New Password must be at least 6 characters long', {
                                duration: 2500
                            });
                            return;
                        }

                        try {
                            const token = localStorage.getItem('authToken');
                            const response = await fetch('/resident/change-password', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute('content'),
                                    'Authorization': token ? 'Bearer ' + token : ''
                                },
                                body: JSON.stringify({
                                    email: user?.email,
                                    oldPassword: oldPassword,
                                    newPassword: newPassword
                                })
                            });

                            const result = await response.json();

                            if (response.ok && result.success) {
                                showToast('Password changed successfully!', {
                                    duration: 3000
                                });
                                closeChangePasswordModal();
                            } else {
                                showToast(result.message || 'Failed to change password', {
                                    duration: 2500
                                });
                            }
                        } catch (err) {
                            console.error('Password change error:', err);
                            showToast('Error changing password. Please try again.', {
                                duration: 2500
                            });
                        }
                    });
                }

                // Apply modal wiring
                const applyBtn = document.querySelector('.apply-btn');
                const applyModal = document.getElementById('applyModal');
                const applyModalClose = document.getElementById('applyModalClose');
                const applyCancel = document.getElementById('applyCancel');
                const applyForm = document.getElementById('applyForm');
                const MAX_ID_IMAGE_UPLOAD_BYTES = 10 * 1024 * 1024;
                const MAX_ID_IMAGE_STORE_BYTES = 850 * 1024;
                const TARGET_ID_IMAGE_STORE_BYTES = 650 * 1024;

                function openApply() {
                    if (applyModal) {
                        applyModal.hidden = false;
                        applyModal.classList.add('open');
                        const profile = getResidentUserSnapshot() || {};
                        const form = applyModal.querySelector('#applyForm');
                        if (form) {
                            if (form.elements.fullName) form.elements.fullName.value = String(profile.fullname ||
                                profile.name || profile.username || '').trim();
                            if (form.elements.email) form.elements.email.value = String(profile.email || '').trim();
                            if (form.elements.address) form.elements.address.value = String(profile.address || '')
                            .trim();
                            if (form.elements.age) form.elements.age.value = String(profile.age || '').trim();
                            if (form.elements.contact) form.elements.contact.value = String(profile.contact || '')
                            .trim();
                            ['fullName', 'email', 'address', 'age', 'contact'].forEach((fieldName) => {
                                if (form.elements[fieldName]) form.elements[fieldName].disabled = true;
                            });
                        }
                        const f = applyModal.querySelector('input[name="fullName"]');
                        if (f) f.focus();
                    }
                }

                function closeApply() {
                    if (applyModal) {
                        applyModal.hidden = true;
                        applyModal.classList.remove('open');
                    }
                }

                function readFileAsDataURL(file) {
                    return new Promise((resolve, reject) => {
                        const reader = new FileReader();
                        reader.onload = () => resolve(reader.result);
                        reader.onerror = () => reject(new Error('Unable to read file'));
                        reader.readAsDataURL(file);
                    });
                }

                function blobToDataURL(blob) {
                    return new Promise((resolve, reject) => {
                        const reader = new FileReader();
                        reader.onload = () => resolve(String(reader.result || ''));
                        reader.onerror = () => reject(new Error('Unable to read image data.'));
                        reader.readAsDataURL(blob);
                    });
                }

                function blobToFile(blob, fileName, fileType) {
                    return new File([blob], fileName, {
                        type: fileType || blob.type || 'application/octet-stream'
                    });
                }

                function loadImageFromObjectUrl(objectUrl) {
                    return new Promise((resolve, reject) => {
                        const image = new Image();
                        image.onload = () => resolve(image);
                        image.onerror = () => reject(new Error('Unable to process image.'));
                        image.src = objectUrl;
                    });
                }

                async function compressUploadImageFile(file) {
                    if (!file) return null;
                    if (!file.type.startsWith('image/')) return file;

                    const objectUrl = URL.createObjectURL(file);
                    try {
                        const image = await loadImageFromObjectUrl(objectUrl);
                        const attempts = [{
                                dimension: 1600,
                                quality: 0.82
                            },
                            {
                                dimension: 1200,
                                quality: 0.72
                            },
                            {
                                dimension: 1000,
                                quality: 0.62
                            },
                            {
                                dimension: 800,
                                quality: 0.58
                            },
                        ];

                        let fallbackBlob = null;
                        for (const attempt of attempts) {
                            const scale = Math.min(1, attempt.dimension / Math.max(image.width || 1, image.height ||
                                1));
                            const canvas = document.createElement('canvas');
                            canvas.width = Math.max(1, Math.round((image.width || 1) * scale));
                            canvas.height = Math.max(1, Math.round((image.height || 1) * scale));
                            const context = canvas.getContext('2d');
                            if (!context) continue;
                            context.fillStyle = '#ffffff';
                            context.fillRect(0, 0, canvas.width, canvas.height);
                            context.drawImage(image, 0, 0, canvas.width, canvas.height);
                            const blob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/jpeg', attempt
                                .quality));
                            if (!blob) continue;
                            fallbackBlob = blob;
                            if (blob.size <= 2 * 1024 * 1024) {
                                return blobToFile(blob, file.name.replace(/\.[^.]+$/, '') + '.jpg', 'image/jpeg');
                            }
                        }

                        if (fallbackBlob) {
                            return blobToFile(fallbackBlob, file.name.replace(/\.[^.]+$/, '') + '.jpg',
                                'image/jpeg');
                        }

                        return file;
                    } finally {
                        URL.revokeObjectURL(objectUrl);
                    }
                }

                async function prepareStoredIdImageDataUrl(file) {
                    if (!file) return null;
                    if (file.size > MAX_ID_IMAGE_UPLOAD_BYTES) {
                        throw new Error('Please upload an image smaller than 10MB.');
                    }

                    const objectUrl = URL.createObjectURL(file);
                    try {
                        if (file.size <= MAX_ID_IMAGE_STORE_BYTES) {
                            return await blobToDataURL(file);
                        }

                        const image = await loadImageFromObjectUrl(objectUrl);
                        const attempts = [{
                                dimension: 1400,
                                quality: 0.82
                            },
                            {
                                dimension: 1100,
                                quality: 0.72
                            },
                            {
                                dimension: 900,
                                quality: 0.62
                            },
                            {
                                dimension: 700,
                                quality: 0.55
                            },
                        ];

                        let fallbackBlob = null;
                        for (const attempt of attempts) {
                            const scale = Math.min(1, attempt.dimension / Math.max(image.width || 1, image.height ||
                                1));
                            const canvas = document.createElement('canvas');
                            canvas.width = Math.max(1, Math.round((image.width || 1) * scale));
                            canvas.height = Math.max(1, Math.round((image.height || 1) * scale));
                            const context = canvas.getContext('2d');
                            if (!context) continue;
                            context.fillStyle = '#ffffff';
                            context.fillRect(0, 0, canvas.width, canvas.height);
                            context.drawImage(image, 0, 0, canvas.width, canvas.height);
                            const blob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/jpeg', attempt
                                .quality));
                            if (!blob) continue;
                            fallbackBlob = blob;
                            if (blob.size <= TARGET_ID_IMAGE_STORE_BYTES) {
                                return await blobToDataURL(blob);
                            }
                        }
                        if (fallbackBlob) {
                            return await blobToDataURL(fallbackBlob);
                        }
                        return await blobToDataURL(file);
                    } finally {
                        URL.revokeObjectURL(objectUrl);
                    }
                }

                if (applyBtn) applyBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    editingRef = null;
                    if (applyForm) {
                        applyForm.reset();
                        const fd = applyForm.elements;
                        const profile = getResidentUserSnapshot() || {};
                        if (fd.fullName) fd.fullName.value = String(profile.fullname || profile.name || profile
                            .username || '').trim();
                        if (fd.email) fd.email.value = String(profile.email || '').trim();
                        if (fd.address) fd.address.value = String(profile.address || '').trim();
                        if (fd.age) fd.age.value = String(profile.age || '').trim();
                        if (fd.contact) fd.contact.value = String(profile.contact || '').trim();
                        ['fullName', 'email', 'address', 'age', 'contact'].forEach((fieldName) => {
                            if (fd[fieldName]) fd[fieldName].disabled = true;
                        });
                        if (fd.idfile) fd.idfile.value = '';
                        const preview = document.getElementById('idPreview');
                        if (preview) preview.style.display = 'none';
                    }
                    openApply();
                });

                const idFileInput = document.querySelector('#applyForm input[name="idfile"]');
                const idPreview = document.getElementById('idPreview');
                const idPreviewImage = document.getElementById('idPreviewImage');

                if (idFileInput) {
                    idFileInput.addEventListener('change', async () => {
                        const file = idFileInput.files && idFileInput.files[0];
                        if (!file) {
                            if (idPreview) idPreview.style.display = 'none';
                            return;
                        }
                        if (!file.type.startsWith('image/')) {
                            alert('Please upload an image file for your valid ID.');
                            idFileInput.value = '';
                            if (idPreview) idPreview.style.display = 'none';
                            return;
                        }
                        if (file.size > MAX_ID_IMAGE_UPLOAD_BYTES) {
                            alert('Please upload an image smaller than 10MB.');
                            idFileInput.value = '';
                            if (idPreview) idPreview.style.display = 'none';
                            return;
                        }
                        const dataUrl = await prepareStoredIdImageDataUrl(file);
                        if (idPreviewImage) {
                            idPreviewImage.src = dataUrl;
                        }
                        if (idPreview) {
                            idPreview.style.display = 'block';
                        }
                    });
                }

                if (applyModalClose) applyModalClose.addEventListener('click', closeApply);
                if (applyCancel) applyCancel.addEventListener('click', closeApply);
                if (applyModal) applyModal.addEventListener('click', (e) => {
                    if (e.target === applyModal) closeApply();
                });

                // state for edit mode
                let editingRef = null;

                // helper: create toast notification
                function showToast(message, options) {
                    const container = document.getElementById('toastContainer');
                    if (!container) return;
                    const toast = document.createElement('div');
                    toast.className = 'toast success';
                    toast.style.pointerEvents = 'auto';
                    toast.innerHTML =
                        `<div class="toast-body"><div class="toast-icon">✔</div><div class="toast-text">${message}</div></div>`;
                    container.appendChild(toast);
                    // animate in
                    requestAnimationFrame(() => {
                        toast.style.transform = 'translateY(0)';
                        toast.style.opacity = '1';
                    });
                    // auto remove
                    setTimeout(() => {
                        toast.style.opacity = '0';
                        toast.style.transform = 'translateY(-10px)';
                        setTimeout(() => toast.remove(), 400);
                    }, options && options.duration || 3500);
                }

                // dynamic update of counts and table without reload
                function refreshDashboardFromRequests(allRequests) {
                    const mine = (allRequests || []).filter(requestBelongsToCurrentUser);
                    const total = mine.length;
                    const pending = mine.filter(r => r.status === 'pending').length;
                    const approved = mine.filter(r => r.status === 'approved').length;
                    const rejected = mine.filter(r => r.status === 'rejected').length;
                    document.getElementById('statTotal').textContent = total;
                    document.getElementById('statPending').textContent = pending;
                    document.getElementById('statApproved').textContent = approved;
                    document.getElementById('statRejected').textContent = rejected;
                    requests = mine;
                    // re-render using current filters
                    if (typeof getFilteredRequests === 'function') {
                        renderRequests(getFilteredRequests());
                    } else {
                        renderRequests(mine);
                    }
                }

                function buildCertificatePdfPayload(req) {
                    return {
                        ref: String(req?.ref || '').trim(),
                        name: String(req?.name || '').trim(),
                        age: req?.age ?? '',
                        address: String(req?.address || '').trim(),
                        purpose: String(req?.purposeReason || req?.purpose || '').trim(),
                        date: String(req?.dateRequested || req?.date || '').trim(),
                    };
                }

                function revokeCurrentPreviewPdfUrl() {
                    if (!currentPreviewPdfUrl) return;
                    URL.revokeObjectURL(currentPreviewPdfUrl);
                    currentPreviewPdfUrl = '';
                }

                async function requestCertificatePdfBlob(req) {
                    if (!window.html2canvas || !window.jspdf || !window.jspdf.jsPDF) {
                        throw new Error(
                            'PDF libraries are not available. Please check your internet connection and refresh the page.'
                            );
                    }

                    const payload = buildCertificatePdfPayload(req);
                    try {
                        sessionStorage.setItem(CERT_AUTOFILL_KEY, JSON.stringify(payload));
                        if (req && req.savedTemplate && typeof req.savedTemplate === 'object') {
                            sessionStorage.setItem(TEMPLATE_OVERRIDE_KEY, JSON.stringify(req.savedTemplate));
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

                    const sourceFrame = document.createElement('iframe');
                    sourceFrame.setAttribute('aria-hidden', 'true');
                    sourceFrame.style.position = 'fixed';
                    sourceFrame.style.left = '0';
                    sourceFrame.style.top = '0';
                    sourceFrame.style.opacity = '0';
                    sourceFrame.style.pointerEvents = 'none';
                    sourceFrame.style.zIndex = '-1';
                    sourceFrame.style.width = '794px';
                    sourceFrame.style.height = '1123px';
                    sourceFrame.style.border = '0';
                    sourceFrame.src = '/certificate?mode=docs&t=' + Date.now();
                    document.body.appendChild(sourceFrame);

                    try {
                        await new Promise((resolve, reject) => {
                            const failTimer = setTimeout(() => reject(new Error(
                                'Timed out loading certificate preview.')), 20000);
                            sourceFrame.onload = () => {
                                clearTimeout(failTimer);
                                resolve(null);
                            };
                            sourceFrame.onerror = () => {
                                clearTimeout(failTimer);
                                reject(new Error('Unable to load certificate preview.'));
                            };
                        });

                        const sourceDoc = sourceFrame.contentDocument;
                        const paper = sourceDoc && sourceDoc.getElementById('paper');
                        if (!sourceDoc || !paper) {
                            throw new Error('Certificate template content not found.');
                        }

                        if (sourceDoc.fonts && sourceDoc.fonts.ready) {
                            try {
                                await sourceDoc.fonts.ready;
                            } catch (_) {}
                        }

                        const imageEls = Array.from(sourceDoc.images || []);
                        await Promise.all(imageEls.map((img) => {
                            if (img.complete) return Promise.resolve();
                            return new Promise((resolve) => {
                                img.addEventListener('load', resolve, {
                                    once: true
                                });
                                img.addEventListener('error', resolve, {
                                    once: true
                                });
                            });
                        }));

                        await new Promise((resolve) => setTimeout(resolve, 220));

                        const rect = paper.getBoundingClientRect();
                        const captureWidth = Math.max(
                            1,
                            Math.ceil(rect.width),
                            Math.ceil(paper.scrollWidth || 0),
                            Math.ceil(paper.offsetWidth || 0)
                        );
                        const captureHeight = Math.max(
                            1,
                            Math.ceil(rect.height),
                            Math.ceil(paper.scrollHeight || 0),
                            Math.ceil(paper.offsetHeight || 0)
                        );

                        const canvas = await window.html2canvas(paper, {
                            scale: 2.2,
                            useCORS: true,
                            backgroundColor: '#ffffff',
                            width: captureWidth,
                            height: captureHeight,
                            windowWidth: Math.max(sourceDoc.documentElement.scrollWidth, captureWidth),
                            windowHeight: Math.max(sourceDoc.documentElement.scrollHeight, captureHeight),
                            scrollX: 0,
                            scrollY: 0,
                        });

                        const {
                            jsPDF
                        } = window.jspdf;
                        const pdf = new jsPDF({
                            orientation: 'portrait',
                            unit: 'pt',
                            format: 'a4'
                        });
                        pdf.setDisplayMode('fullpage', 'single', 'UseNone');
                        const pageWidth = pdf.internal.pageSize.getWidth();
                        const pageHeight = pdf.internal.pageSize.getHeight();
                        const imageData = canvas.toDataURL('image/png');
                        pdf.addImage(imageData, 'PNG', 0, 0, pageWidth, pageHeight, undefined, 'FAST');

                        return {
                            blob: pdf.output('blob'),
                            payload
                        };
                    } finally {
                        sourceFrame.remove();
                    }
                }

                async function openCertificateWithRequest(req) {
                    if (!req) return;
                    currentPreviewRequest = req;

                    const viewModal = document.getElementById('viewModal');
                    const viewFrame = document.getElementById('viewFrame');
                    const viewDownloadBtn = document.getElementById('viewDownloadBtn');

                    if (viewModal) {
                        viewModal.hidden = false;
                        viewModal.classList.add('open');
                    }

                    if (viewFrame) {
                        viewFrame.src = 'about:blank';
                    }

                    if (viewDownloadBtn) {
                        viewDownloadBtn.disabled = true;
                        viewDownloadBtn.onclick = function() {
                            if (currentPreviewRequest) {
                                downloadCertificatePdf(currentPreviewRequest);
                            }
                        };
                    }

                    try {
                        const {
                            blob
                        } = await requestCertificatePdfBlob(req);
                        currentPreviewPdfBlob = blob;
                        revokeCurrentPreviewPdfUrl();
                        currentPreviewPdfUrl = URL.createObjectURL(blob);
                        if (viewFrame) {
                            viewFrame.src = currentPreviewPdfUrl;
                        }
                    } catch (error) {
                        console.error('PDF preview error:', error);
                        alert('Unable to load PDF preview right now.');
                    } finally {
                        if (viewDownloadBtn) {
                            viewDownloadBtn.disabled = false;
                        }
                    }
                }

                async function downloadCertificatePdf(req) {
                    if (!req) return;

                    try {
                        const payload = buildCertificatePdfPayload(req);
                        const blob = currentPreviewPdfBlob || (await requestCertificatePdfBlob(req)).blob;
                        const url = URL.createObjectURL(blob);
                        const link = document.createElement('a');
                        link.href = url;
                        link.download = (payload.ref || 'certificate') + '.pdf';
                        document.body.appendChild(link);
                        link.click();
                        link.remove();
                        URL.revokeObjectURL(url);
                    } catch (error) {
                        console.error('PDF download error:', error);
                        alert('Unable to download PDF right now.');
                    }
                }

                if (applyForm) {
                    applyForm.addEventListener('submit', async (e) => {
                        e.preventDefault();
                        if (!applyForm.checkValidity()) {
                            applyForm.reportValidity();
                            return;
                        }
                        const fd = applyForm.elements;
                        const fullName = String(fd.fullName.value || '').trim();
                        const email = String(fd.email?.value || '').trim().toLowerCase();
                        const address = String(fd.address?.value || '').trim();
                        const age = String(fd.age?.value || '').trim();
                        const contact = String(fd.contact?.value || '').trim();
                        const purpose = String(fd.purpose.value || '').trim();
                        const purposeReason = String(fd.purposeReason?.value || '').trim();
                        const idFileInput = fd.idfile;
                        const idFile = idFileInput && idFileInput.files && idFileInput.files[0];

                        if (!fullName || !email || !address || !age || !contact || !purpose || !
                            purposeReason) {
                            alert('Please complete required fields.');
                            return;
                        }
                        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                            alert('Please enter a valid email address.');
                            return;
                        }
                        if (!idFile && !editingRef) {
                            alert('Please upload a photo of your valid ID.');
                            return;
                        }
                        if (idFile && !idFile.type.startsWith('image/')) {
                            alert('Please upload an image file for your valid ID.');
                            return;
                        }
                        if (idFile && idFile.size > MAX_ID_IMAGE_UPLOAD_BYTES) {
                            alert('Please upload an image smaller than 10MB.');
                            return;
                        }

                        const allRequests = readAllRequests();
                        const now = new Date();
                        if (editingRef) {
                            // update existing request
                            const idx = allRequests.findIndex(r => r.ref === editingRef &&
                                requestBelongsToCurrentUser(r));
                            if (idx !== -1) {
                                allRequests[idx].name = fullName;
                                allRequests[idx].email = email;
                                allRequests[idx].address = address;
                                allRequests[idx].age = age;
                                allRequests[idx].dateRequested = allRequests[idx].dateRequested || now
                                    .toISOString().split('T')[0];
                                allRequests[idx].validUntil = allRequests[idx].validUntil || new Date(now
                                        .getFullYear() + 1, now.getMonth(), now.getDate()).toISOString()
                                    .split('T')[0];
                                allRequests[idx].purpose = purpose;
                                allRequests[idx].purposeReason = purposeReason;
                                allRequests[idx].contact = contact;
                                allRequests[idx].ownerKey = currentUserKey;
                                if (idFile) {
                                    allRequests[idx].idFileName = idFile.name;
                                    allRequests[idx].idFileType = idFile.type;
                                    allRequests[idx].idFileUrl = allRequests[idx].idFileUrl || '';
                                }
                                // keep status unchanged
                            }
                            editingRef = null;
                            if (!writeAllRequests(allRequests)) {
                                alert(
                                    'Unable to save this request. The uploaded image may be too large for browser storage. Please try a smaller image.');
                                return;
                            }
                            closeApply();
                            showToast('Application updated', {
                                duration: 2500
                            });
                            refreshDashboardFromRequests(allRequests);
                            return;
                        }
                        // new request
                        const ref = 'BR' + now.getTime();
                        const dateRequested = now.toISOString().split('T')[0];
                        const validUntil = new Date(now.getFullYear() + 1, now.getMonth(), now.getDate())
                            .toISOString().split('T')[0];
                        const formData = new FormData();
                        formData.append('ref', ref);
                        formData.append('owner_key', currentUserKey);
                        formData.append('owner_name', String(user?.fullname || user?.name || user
                            ?.username || '').trim());
                        formData.append('owner_email', String(user?.email || email || '').trim());
                        formData.append('name', fullName);
                        formData.append('email', email);
                        formData.append('address', address);
                        formData.append('age', age);
                        formData.append('contact', contact);
                        formData.append('purpose', purpose);
                        formData.append('purpose_reason', purposeReason);
                        const uploadFile = await compressUploadImageFile(idFile);
                        formData.append('idfile', uploadFile, uploadFile?.name || idFile.name);

                        const submitResponse = await fetch('/clearance-requests', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    ?.getAttribute('content') || '',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: formData,
                        });

                        const submitResult = await submitResponse.json().catch(() => ({}));
                        if (!submitResponse.ok) {
                            alert(submitResult.message || 'Unable to submit this request.');
                            return;
                        }

                        const savedRequest = normalizeServerRequest(submitResult.request || {});
                        if (!savedRequest || !savedRequest.ref) {
                            alert('The request was saved, but the server response was incomplete.');
                            return;
                        }

                        const newReq = {
                            ref: savedRequest.ref,
                            name: savedRequest.name || fullName,
                            email: savedRequest.email || email,
                            address: savedRequest.address || address,
                            age: savedRequest.age || age,
                            dateRequested: savedRequest.dateRequested || dateRequested,
                            validUntil: savedRequest.validUntil || validUntil,
                            status: savedRequest.status || 'pending',
                            purpose: savedRequest.purpose || purpose,
                            purposeReason: savedRequest.purposeReason || purposeReason,
                            contact: savedRequest.contact || contact,
                            ownerKey: savedRequest.ownerKey || currentUserKey,
                            idFileName: savedRequest.idFileName || (idFile ? idFile.name : null),
                            idFileType: savedRequest.idFileType || (idFile ? idFile.type : null),
                            idFileUrl: savedRequest.idFileUrl || '',
                            pdfSaved: !!savedRequest.pdfSaved
                        };
                        allRequests.unshift(newReq);
                        if (!writeAllRequests(allRequests)) {
                            alert('Unable to save this request locally.');
                            return;
                        }
                        closeApply();
                        // show success toast with reference
                        showToast(
                            `Application Submitted successfully! <strong>${savedRequest.ref}</strong>`, {
                                duration: 4000
                            });
                        // update UI immediately
                        refreshDashboardFromRequests(allRequests);
                        syncRequestsFromServer();
                    });
                }
                window.__digibarangayApplySubmitBound = true;
                // view/delete/download handlers (delegated)
                document.getElementById('requestsBody').addEventListener('click', (e) => {
                    const btn = e.target.closest && e.target.closest('button[data-action]');
                    if (!btn) return;
                    const action = btn.getAttribute('data-action');
                    const ref = btn.getAttribute('data-ref');
                    const allRequests = readAllRequests();
                    const req = allRequests.find(r => r.ref === ref && requestBelongsToCurrentUser(r));
                    if (action === 'view-id') {
                        if (!req) return;
                        openViewIdModal(req);
                    } else if (action === 'view') {
                        if (!req) return;
                        openCertificateWithRequest(req);
                    } else if (action === 'delete') {
                        if (!req) return;
                        if (!confirm('Delete this request?')) return;
                        const newList = allRequests.filter(r => !(r.ref === ref && requestBelongsToCurrentUser(
                            r)));
                        writeAllRequests(newList);
                        showToast('Request deleted', {
                            duration: 2000
                        });
                        refreshDashboardFromRequests(newList);
                    }
                });

                const viewIdModal = document.getElementById('viewIdModal');
                const viewIdModalClose = document.getElementById('viewIdModalClose');
                const viewIdImage = document.getElementById('viewIdImage');
                const viewIdCaption = document.getElementById('viewIdCaption');

                function openViewIdModal(req) {
                    const imageSrc = req && (req.idFileUrl || req.idFileDataUrl);
                    if (!req || !imageSrc) {
                        alert('No uploaded ID image is available for this request.');
                        return;
                    }
                    if (viewIdImage) {
                        viewIdImage.src = imageSrc;
                    }
                    if (viewIdCaption) {
                        viewIdCaption.textContent = req.idFileName || 'Uploaded valid ID';
                    }
                    if (viewIdModal) {
                        viewIdModal.hidden = false;
                        viewIdModal.classList.add('open');
                    }
                }

                function closeViewIdModal() {
                    if (viewIdModal) {
                        viewIdModal.hidden = true;
                        viewIdModal.classList.remove('open');
                    }
                    if (viewIdImage) {
                        viewIdImage.src = '';
                    }
                }

                if (viewIdModalClose) viewIdModalClose.addEventListener('click', closeViewIdModal);
                if (viewIdModal) viewIdModal.addEventListener('click', (e) => {
                    if (e.target === viewIdModal) closeViewIdModal();
                });

                // view modal close
                const viewModal = document.getElementById('viewModal');
                const viewModalClose = document.getElementById('viewModalClose');
                const viewFrame = document.getElementById('viewFrame');

                function closeViewModal() {
                    if (viewModal) {
                        viewModal.hidden = true;
                        viewModal.classList.remove('open');
                    }
                    if (viewFrame) {
                        viewFrame.src = 'about:blank';
                    }
                    currentPreviewPdfBlob = null;
                    revokeCurrentPreviewPdfUrl();
                }

                if (viewModalClose) viewModalClose.addEventListener('click', closeViewModal);
                if (viewModal) viewModal.addEventListener('click', (e) => {
                    if (e.target === viewModal) {
                        closeViewModal();
                    }
                });
            } catch (e) {
                console.error(e);
            }
        })();
    </script>
    <script>
        (function() {
            function getStoredUser() {
                const raw = localStorage.getItem('digibarangay_user') || localStorage.getItem(
                    'digibarangay_registered_user');
                if (!raw) return null;
                try {
                    return JSON.parse(raw);
                } catch (_err) {
                    return {
                        fullname: String(raw),
                        name: String(raw),
                        username: String(raw),
                        email: ''
                    };
                }
            }

            let residentProfile = null;
            let residentProfilePromise = null;

            function getUserDisplayName(user) {
                return (user && (user.fullname || user.name || user.username || user.email)) || 'User Name';
            }

            function getUserKey(user) {
                return String(user && (user.user_key || user.id || user.email || user.username || user.fullname || user
                    .name) || '').trim().toLowerCase();
            }

            function readRequests() {
                try {
                    const parsed = JSON.parse(localStorage.getItem('digibarangay_requests') || '[]');
                    return Array.isArray(parsed) ? parsed : [];
                } catch (_err) {
                    return [];
                }
            }

            function writeRequests(nextRequests) {
                localStorage.setItem('digibarangay_requests', JSON.stringify(nextRequests));
            }

            function escapeHtml(value) {
                return String(value ?? '')
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            }

            function normalizeResidentProfile(profile) {
                if (!profile || typeof profile !== 'object') return null;
                return {
                    id: profile.id ?? null,
                    fullname: String(profile.fullname || profile.name || '').trim(),
                    name: String(profile.name || profile.fullname || '').trim(),
                    first_name: String(profile.first_name || '').trim(),
                    middle_name: String(profile.middle_name || '').trim(),
                    last_name: String(profile.last_name || '').trim(),
                    email: String(profile.email || '').trim().toLowerCase(),
                    contact: String(profile.contact || '').trim(),
                    age: String(profile.age ?? '').trim(),
                    address: String(profile.address || '').trim(),
                };
            }

            function cacheResidentProfile(profile) {
                const normalized = normalizeResidentProfile(profile);
                if (!normalized) return null;

                residentProfile = normalized;

                try {
                    const stored = getStoredUser() || {};
                    const merged = {
                        ...stored,
                        ...normalized,
                        user_key: stored.user_key || String(stored.id || normalized.id || normalized.email ||
                                normalized.username || normalized.fullname || normalized.name || '').trim()
                            .toLowerCase(),
                    };
                    localStorage.setItem('digibarangay_user', JSON.stringify(merged));
                    localStorage.setItem('digibarangay_registered_user', JSON.stringify(merged));
                } catch (_err) {
                    // Ignore storage failures and keep the in-memory profile.
                }

                return residentProfile;
            }

            function buildResidentProfileFallback() {
                return normalizeResidentProfile(getStoredUser() || {});
            }

            async function loadResidentProfile() {
                if (residentProfile) return residentProfile;
                if (residentProfilePromise) return residentProfilePromise;

                residentProfilePromise = (async () => {
                    const user = getStoredUser();
                    const email = String(user?.email || '').trim().toLowerCase();
                    if (!email) {
                        return cacheResidentProfile(buildResidentProfileFallback());
                    }

                    try {
                        const response = await fetch('/resident/profile?email=' + encodeURIComponent(
                            email), {
                            headers: {
                                'Accept': 'application/json'
                            },
                            credentials: 'same-origin',
                        });

                        if (!response.ok) {
                            return cacheResidentProfile(buildResidentProfileFallback());
                        }

                        const body = await response.json().catch(() => ({}));
                        return cacheResidentProfile(body.data || buildResidentProfileFallback());
                    } catch (error) {
                        console.warn('Unable to load resident profile', error);
                        return cacheResidentProfile(buildResidentProfileFallback());
                    }
                })();

                return residentProfilePromise;
            }

            function readFileAsDataURL(file) {
                return new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.onload = () => resolve(reader.result);
                    reader.onerror = () => reject(new Error('Unable to read file.'));
                    reader.readAsDataURL(file);
                });
            }

            function normalizeServerRequest(request) {
                if (!request || typeof request !== 'object') return null;
                return {
                    ref: String(request.ref || '').trim(),
                    name: String(request.name || '').trim(),
                    email: String(request.email || '').trim(),
                    ownerName: String(request.ownerName || request.owner_name || '').trim(),
                    ownerEmail: String(request.ownerEmail || request.owner_email || '').trim(),
                    address: String(request.address || '').trim(),
                    age: String(request.age ?? '').trim(),
                    dateRequested: String(request.dateRequested || '').trim(),
                    validUntil: String(request.validUntil || '').trim(),
                    status: String(request.status || 'pending').trim(),
                    purpose: String(request.purpose || '').trim(),
                    purposeReason: String(request.purposeReason || request.purpose_reason || '').trim(),
                    contact: String(request.contact || '').trim(),
                    ownerKey: String(request.ownerKey || request.owner_key || '').trim(),
                    idFileName: String(request.idFileName || request.id_file_name || '').trim(),
                    idFileType: String(request.idFileType || request.id_file_mime || '').trim(),
                    idFileUrl: String(request.idFileUrl || request.id_file_url || '').trim(),
                    pdfSaved: !!request.pdfSaved,
                    savedTemplate: request.savedTemplate || null,
                };
            }

            async function syncRequestsFromServer() {
                try {
                    const user = getStoredUser();
                    const params = new URLSearchParams();
                    const userKey = getUserKey(user);
                    if (userKey) params.set('owner_key', userKey);
                    if (user?.email) params.set('email', String(user.email).trim().toLowerCase());
                    if (user?.fullname) params.set('owner_name', String(user.fullname).trim().toLowerCase());
                    if (user?.username) params.set('username', String(user.username).trim().toLowerCase());

                    const response = await fetch('/clearance-requests?' + params.toString(), {
                        headers: {
                            'Accept': 'application/json'
                        },
                    });
                    if (!response.ok) return;

                    const result = await response.json().catch(() => ({}));
                    const serverRequests = Array.isArray(result.data) ?
                        result.data.map(normalizeServerRequest).filter(Boolean) :
                        [];
                    if (!serverRequests.length) return;

                    writeRequests(serverRequests);
                    renderRequests();
                } catch (error) {
                    console.warn('Unable to sync resident requests from server', error);
                }
            }

            function clearResidentAuthState() {
                localStorage.removeItem('authToken');
                localStorage.removeItem('digibarangay_logged_in');
                localStorage.removeItem('digibarangay_user');
                localStorage.removeItem('digibarangay_registered_user');
                sessionStorage.removeItem('digibarangay_logged_in');
                sessionStorage.removeItem('digibarangay_user');
            }

            function setHeaderUser(user) {
                const nameEl = document.getElementById('dashUserName');
                const emailEl = document.getElementById('dashUserEmail');
                const avatarEl = document.getElementById('dashAvatar');
                const welcomeEl = document.getElementById('welcomeResidentText');
                const displayName = getUserDisplayName(user);
                if (nameEl) nameEl.textContent = displayName;
                if (emailEl) emailEl.textContent = user?.email || '';
                if (avatarEl) {
                    const initials = displayName
                        .split(' ')
                        .filter(Boolean)
                        .map((part) => part[0])
                        .slice(0, 2)
                        .join('')
                        .toUpperCase();
                    avatarEl.textContent = initials || 'U';
                }
                if (welcomeEl) welcomeEl.textContent = `Welcome back, ${displayName}!`;
            }

            function currentRequestsForUser(user) {
                const userKey = getUserKey(user);
                const userName = getUserDisplayName(user).trim().toLowerCase();
                const userEmail = String(user?.email || '').trim().toLowerCase();
                const userFullname = String(user?.fullname || '').trim().toLowerCase();
                const userUsername = String(user?.username || '').trim().toLowerCase();
                return readRequests().filter((request) => {
                    const candidates = [
                        request.ownerKey,
                        request.owner_key,
                        request.userKey,
                        request.ownerName,
                        request.owner_name,
                        request.ownerEmail,
                        request.owner_email,
                        request.email,
                    ].map((value) => String(value || '').trim().toLowerCase()).filter(Boolean);

                    if (candidates.some((candidate) => candidate === userKey || candidate === userEmail || candidate ===
                            userFullname || candidate === userUsername)) {
                        return true;
                    }

                    return String(request.name || '').trim().toLowerCase() === userName;
                });
            }

            function renderRequests() {
                const user = getStoredUser();
                const tbody = document.getElementById('requestsBody');
                if (!tbody) return;

                const requests = currentRequestsForUser(user);
                if (!requests.length) {
                    tbody.innerHTML = '<tr><td colspan="7" class="muted">You have no requests yet.</td></tr>';
                    return;
                }

                tbody.innerHTML = requests.map((request) => {
                    const statusLabel = request.status ? request.status.charAt(0).toUpperCase() + request.status
                        .slice(1) : '';
                    const statusHtml =
                        `<span class="status-badge status-${escapeHtml(request.status || 'pending')}">${escapeHtml(statusLabel)}</span>`;
                    const pdfHtml = request.pdfSaved ?
                        '<span class="status-badge status-approved">Saved</span>' :
                        '<span class="status-badge status-pending">Not Saved</span>';
                    const viewIdButton = (request.idFileUrl || request.idFileDataUrl) ?
                        `<button class="action-btn" data-action="view-id" data-ref="${escapeHtml(request.ref || '')}" title="View ID">🖼️</button>` :
                        '';
                    const actions = `
            <div class="actions-cell">
              ${viewIdButton}
              <button class="action-btn" data-action="view" data-ref="${escapeHtml(request.ref || '')}" title="View">👁️</button>
              <button class="action-btn" data-action="delete" data-ref="${escapeHtml(request.ref || '')}" title="Delete">🗑️</button>
            </div>
          `;
                    return `<tr data-ref="${escapeHtml(request.ref || '')}"><td>${escapeHtml(request.ref || '')}</td><td>${escapeHtml(request.name || '')}</td><td>${escapeHtml(request.dateRequested || '')}</td><td>${escapeHtml(request.validUntil || '')}</td><td>${statusHtml}</td><td>${pdfHtml}</td><td>${actions}</td></tr>`;
                }).join('');
            }

            syncRequestsFromServer();

            function createCertificatePdfBlob(request) {
                const jsPDF = window.jspdf && window.jspdf.jsPDF;
                if (!jsPDF) {
                    throw new Error('PDF library unavailable.');
                }

                const pdf = new jsPDF({
                    orientation: 'portrait',
                    unit: 'pt',
                    format: 'a4'
                });
                const left = 48;
                let cursorY = 54;
                const lineHeight = 20;
                const contentWidth = pdf.internal.pageSize.getWidth() - left * 2;

                pdf.setFont('helvetica', 'bold');
                pdf.setFontSize(18);
                pdf.text('Barangay Clearance Request', left, cursorY);
                cursorY += 18;

                pdf.setFont('helvetica', 'normal');
                pdf.setFontSize(11);
                const lines = [
                    `Reference ID: ${request.ref || '-'}`,
                    `Name: ${request.name || '-'}`,
                    `Email: ${request.email || '-'}`,
                    `Address: ${request.address || '-'}`,
                    `Age: ${request.age || '-'}`,
                    `Contact: ${request.contact || '-'}`,
                    `Purpose: ${request.purpose || request.purposeReason || '-'}`,
                    `Date Requested: ${request.dateRequested || '-'}`,
                    `Valid Until: ${request.validUntil || '-'}`,
                    `Status: ${request.status || '-'}`,
                ];

                lines.forEach((line) => {
                    const wrapped = pdf.splitTextToSize(line, contentWidth);
                    wrapped.forEach((part) => {
                        if (cursorY > 760) {
                            pdf.addPage();
                            cursorY = 54;
                        }
                        pdf.text(part, left, cursorY);
                        cursorY += lineHeight;
                    });
                });

                return pdf.output('blob');
            }

            function openViewIdModal(request) {
                const modal = document.getElementById('viewIdModal');
                const image = document.getElementById('viewIdImage');
                const caption = document.getElementById('viewIdCaption');
                const imageSrc = request && (request.idFileUrl || request.idFileDataUrl);
                if (!modal || !request || !imageSrc) return;
                if (image) image.src = imageSrc;
                if (caption) caption.textContent = request.idFileName || 'Uploaded valid ID';
                modal.hidden = false;
                modal.classList.add('open');
            }

            function closeViewIdModal() {
                const modal = document.getElementById('viewIdModal');
                const image = document.getElementById('viewIdImage');
                if (modal) {
                    modal.hidden = true;
                    modal.classList.remove('open');
                }
                if (image) image.src = '';
            }

            function openViewModal(request) {
                const modal = document.getElementById('viewModal');
                const frame = document.getElementById('viewFrame');
                const downloadBtn = document.getElementById('viewDownloadBtn');
                if (!modal || !frame || !request) return;

                const blob = createCertificatePdfBlob(request);
                const url = URL.createObjectURL(blob);
                frame.src = url;
                modal.hidden = false;
                modal.classList.add('open');

                const closeCurrentUrl = () => URL.revokeObjectURL(url);
                if (downloadBtn) {
                    downloadBtn.onclick = () => downloadCertificatePdf(request);
                }
                modal.addEventListener('click', function onModalClick(event) {
                    if (event.target === modal) {
                        closeCurrentUrl();
                        modal.removeEventListener('click', onModalClick);
                        modal.hidden = true;
                        modal.classList.remove('open');
                        frame.src = 'about:blank';
                    }
                });
            }

            async function downloadCertificatePdf(request) {
                try {
                    const blob = createCertificatePdfBlob(request);
                    const link = document.createElement('a');
                    const url = URL.createObjectURL(blob);
                    link.href = url;
                    link.download = `${request.ref || 'certificate'}.pdf`;
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                    URL.revokeObjectURL(url);
                } catch (error) {
                    alert(error.message || 'Unable to download PDF right now.');
                }
            }

            function fillApplyModalFromResident(profile) {
                const form = document.getElementById('applyForm');
                if (!form) return;

                const resident = profile || getStoredUser() || {};
                const lockedFields = ['fullName', 'email', 'address', 'age', 'contact'];

                lockedFields.forEach((fieldName) => {
                    const field = form.elements[fieldName];
                    if (!field) return;

                    if (fieldName === 'fullName') field.value = String(resident.fullname || resident.name ||
                        resident.username || '').trim();
                    if (fieldName === 'email') field.value = String(resident.email || '').trim();
                    if (fieldName === 'address') field.value = String(resident.address || '').trim();
                    if (fieldName === 'age') field.value = String(resident.age || '').trim();
                    if (fieldName === 'contact') field.value = String(resident.contact || '').trim();

                    field.disabled = true;
                });
            }

            async function openApplyModal() {
                const modal = document.getElementById('applyModal');
                const form = document.getElementById('applyForm');
                if (!modal || !form) return;
                form.reset();
                await loadResidentProfile();
                fillApplyModalFromResident(residentProfile);
                const preview = document.getElementById('idPreview');
                if (preview) preview.style.display = 'none';
                modal.hidden = false;
                modal.classList.add('open');
                const firstInput = form.querySelector('input[name="purpose"]');
                if (firstInput) firstInput.focus();
            }

            function closeApplyModal() {
                const modal = document.getElementById('applyModal');
                if (modal) {
                    modal.hidden = true;
                    modal.classList.remove('open');
                }
            }

            function openChangePasswordModal() {
                const modal = document.getElementById('changePasswordModal');
                const form = document.getElementById('changePasswordForm');
                if (!modal || !form) return;
                form.reset();
                modal.hidden = false;
                modal.classList.add('open');
            }

            function closeChangePasswordModal() {
                const modal = document.getElementById('changePasswordModal');
                if (modal) {
                    modal.hidden = true;
                    modal.classList.remove('open');
                }
            }

            async function handleLogout(event) {
                if (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    if (typeof event.stopImmediatePropagation === 'function') {
                        event.stopImmediatePropagation();
                    }
                }

                const token = localStorage.getItem('authToken');
                clearResidentAuthState();

                try {
                    const headers = {
                        'Content-Type': 'application/json'
                    };
                    if (token) headers.Authorization = 'Bearer ' + token;
                    await fetch('/api/auth/logout', {
                        method: 'POST',
                        headers
                    });
                } catch (err) {
                    console.error('Logout error:', err);
                }

                window.location.replace('/');
                return false;
            }

            window.handleResidentLogout = handleLogout;

            const user = getStoredUser();
            if (user) setHeaderUser(user);
            renderRequests();

            const logoutBtn = document.getElementById('logoutBtn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', handleLogout);
            }

            const profileInfo = document.getElementById('profileInfo');
            if (profileInfo) {
                profileInfo.addEventListener('click', (event) => {
                    if (event.target.closest('#logoutBtn')) return;
                    event.preventDefault();
                    openChangePasswordModal();
                });
            }

            const applyBtn = document.querySelector('.apply-btn');
            if (applyBtn) {
                applyBtn.addEventListener('click', async (event) => {
                    event.preventDefault();
                    await openApplyModal();
                });
            }

            loadResidentProfile().catch((error) => {
                console.warn('Resident profile preload failed', error);
            });

            const applyModalClose = document.getElementById('applyModalClose');
            const applyCancel = document.getElementById('applyCancel');
            if (applyModalClose) applyModalClose.addEventListener('click', closeApplyModal);
            if (applyCancel) applyCancel.addEventListener('click', closeApplyModal);
            const applyModal = document.getElementById('applyModal');
            if (applyModal) {
                applyModal.addEventListener('click', (event) => {
                    if (event.target === applyModal) closeApplyModal();
                });
            }

            const viewIdModalClose = document.getElementById('viewIdModalClose');
            const viewIdModal = document.getElementById('viewIdModal');
            if (viewIdModalClose) viewIdModalClose.addEventListener('click', closeViewIdModal);
            if (viewIdModal) {
                viewIdModal.addEventListener('click', (event) => {
                    if (event.target === viewIdModal) closeViewIdModal();
                });
            }

            const viewModalClose = document.getElementById('viewModalClose');
            const viewModal = document.getElementById('viewModal');
            const viewFrame = document.getElementById('viewFrame');
            if (viewModalClose && viewModal && viewFrame) {
                viewModalClose.addEventListener('click', () => {
                    viewModal.hidden = true;
                    viewModal.classList.remove('open');
                    viewFrame.src = 'about:blank';
                });
                viewModal.addEventListener('click', (event) => {
                    if (event.target === viewModal) {
                        viewModal.hidden = true;
                        viewModal.classList.remove('open');
                        viewFrame.src = 'about:blank';
                    }
                });
            }

            const changePasswordModalClose = document.getElementById('changePasswordModalClose');
            const changePasswordCancel = document.getElementById('changePasswordCancel');
            const changePasswordModal = document.getElementById('changePasswordModal');
            const changePasswordForm = document.getElementById('changePasswordForm');
            if (changePasswordModalClose) changePasswordModalClose.addEventListener('click', closeChangePasswordModal);
            if (changePasswordCancel) changePasswordCancel.addEventListener('click', closeChangePasswordModal);
            if (changePasswordModal) {
                changePasswordModal.addEventListener('click', (event) => {
                    if (event.target === changePasswordModal) closeChangePasswordModal();
                });
            }
            if (changePasswordForm) {
                changePasswordForm.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    const oldPassword = document.getElementById('oldPasswordInput')?.value.trim() || '';
                    const newPassword = document.getElementById('newPasswordInput')?.value.trim() || '';
                    const confirmPassword = document.getElementById('confirmPasswordInput')?.value.trim() ||
                        '';

                    if (!oldPassword || !newPassword || !confirmPassword) {
                        alert('All fields are required.');
                        return;
                    }
                    if (newPassword !== confirmPassword) {
                        alert('New Password and Confirm Password do not match.');
                        return;
                    }
                    if (newPassword.length < 6) {
                        alert('New Password must be at least 6 characters long.');
                        return;
                    }

                    try {
                        const token = localStorage.getItem('authToken');
                        const response = await fetch('/resident/change-password', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Authorization': token ? 'Bearer ' + token : ''
                            },
                            body: JSON.stringify({
                                email: user?.email,
                                oldPassword,
                                newPassword
                            })
                        });

                        const result = await response.json().catch(() => ({}));
                        if (response.ok && result.success) {
                            alert('Password changed successfully!');
                            closeChangePasswordModal();
                        } else {
                            alert(result.message || 'Failed to change password.');
                        }
                    } catch (error) {
                        console.error('Password change error:', error);
                        alert('Error changing password. Please try again.');
                    }
                });
            }

            const requestsBody = document.getElementById('requestsBody');
            if (requestsBody) {
                requestsBody.addEventListener('click', async (event) => {
                    const button = event.target.closest('button[data-action]');
                    if (!button) return;

                    const action = button.getAttribute('data-action');
                    const ref = button.getAttribute('data-ref');
                    const requests = readRequests();
                    const user = getStoredUser();
                    const request = requests.find((entry) => {
                        const matchesRef = String(entry.ref || '') === String(ref || '');
                        const ownerKey = String(entry.ownerKey || entry.owner_key || entry
                            .userKey || '').trim().toLowerCase();
                        const userKey = getUserKey(user);
                        const userName = getUserDisplayName(user).trim().toLowerCase();
                        const matchesOwner = ownerKey ? ownerKey === userKey : String(entry.name ||
                            '').trim().toLowerCase() === userName;
                        return matchesRef && matchesOwner;
                    });

                    if (!request) return;

                    if (action === 'view-id') {
                        openViewIdModal(request);
                        return;
                    }

                    if (action === 'view') {
                        openViewModal(request);
                        return;
                    }

                    if (action === 'delete') {
                        if (!confirm('Delete this request?')) return;
                        const nextRequests = requests.filter((entry) => String(entry.ref || '') !== String(
                            ref || ''));
                        writeRequests(nextRequests);
                        renderRequests();
                    }
                });
            }

            const applyForm = document.getElementById('applyForm');
            if (applyForm && !window.__digibarangayApplySubmitBound) {
                const idFileInput = applyForm.querySelector('input[name="idfile"]');
                const idPreview = document.getElementById('idPreview');
                const idPreviewImage = document.getElementById('idPreviewImage');
                const MAX_ID_IMAGE_UPLOAD_BYTES = 10 * 1024 * 1024;
                const MAX_ID_IMAGE_STORE_BYTES = 850 * 1024;
                const TARGET_ID_IMAGE_STORE_BYTES = 650 * 1024;

                if (idFileInput) {
                    idFileInput.addEventListener('change', async () => {
                        const file = idFileInput.files && idFileInput.files[0];
                        if (!file) {
                            if (idPreview) idPreview.style.display = 'none';
                            return;
                        }
                        if (!file.type.startsWith('image/')) {
                            alert('Please upload an image file for your valid ID.');
                            idFileInput.value = '';
                            if (idPreview) idPreview.style.display = 'none';
                            return;
                        }
                        if (file.size > MAX_ID_IMAGE_UPLOAD_BYTES) {
                            alert('Please upload an image smaller than 10MB.');
                            idFileInput.value = '';
                            if (idPreview) idPreview.style.display = 'none';
                            return;
                        }
                        const dataUrl = await prepareStoredIdImageDataUrl(file);
                        if (idPreviewImage) idPreviewImage.src = dataUrl;
                        if (idPreview) idPreview.style.display = 'block';
                    });
                }

                function blobToDataURL(blob) {
                    return new Promise((resolve, reject) => {
                        const reader = new FileReader();
                        reader.onload = () => resolve(String(reader.result || ''));
                        reader.onerror = () => reject(new Error('Unable to read image data.'));
                        reader.readAsDataURL(blob);
                    });
                }

                function loadImageFromObjectUrl(objectUrl) {
                    return new Promise((resolve, reject) => {
                        const image = new Image();
                        image.onload = () => resolve(image);
                        image.onerror = () => reject(new Error('Unable to process image.'));
                        image.src = objectUrl;
                    });
                }

                async function prepareStoredIdImageDataUrl(file) {
                    if (!file) return null;
                    const objectUrl = URL.createObjectURL(file);
                    try {
                        if (file.size <= MAX_ID_IMAGE_STORE_BYTES) {
                            return await blobToDataURL(file);
                        }

                        const image = await loadImageFromObjectUrl(objectUrl);
                        const attempts = [{
                                dimension: 1400,
                                quality: 0.82
                            },
                            {
                                dimension: 1100,
                                quality: 0.72
                            },
                            {
                                dimension: 900,
                                quality: 0.62
                            },
                            {
                                dimension: 700,
                                quality: 0.55
                            },
                        ];

                        let fallbackBlob = null;
                        for (const attempt of attempts) {
                            const scale = Math.min(1, attempt.dimension / Math.max(image.width || 1, image.height ||
                                1));
                            const canvas = document.createElement('canvas');
                            canvas.width = Math.max(1, Math.round((image.width || 1) * scale));
                            canvas.height = Math.max(1, Math.round((image.height || 1) * scale));
                            const context = canvas.getContext('2d');
                            if (!context) continue;
                            context.fillStyle = '#ffffff';
                            context.fillRect(0, 0, canvas.width, canvas.height);
                            context.drawImage(image, 0, 0, canvas.width, canvas.height);
                            const blob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/jpeg', attempt
                                .quality));
                            if (!blob) continue;
                            fallbackBlob = blob;
                            if (blob.size <= TARGET_ID_IMAGE_STORE_BYTES) {
                                return await blobToDataURL(blob);
                            }
                        }
                        if (fallbackBlob) {
                            return await blobToDataURL(fallbackBlob);
                        }
                        return await blobToDataURL(file);
                    } finally {
                        URL.revokeObjectURL(objectUrl);
                    }
                }

                applyForm.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    const formData = new FormData(applyForm);
                    const resident = residentProfile || getStoredUser() || {};
                    const fullName = String(formData.get('fullName') || resident.fullname || resident
                        .name || resident.username || '').trim();
                    const email = String(formData.get('email') || resident.email || '').trim()
                .toLowerCase();
                    const address = String(formData.get('address') || resident.address || '').trim();
                    const age = String(formData.get('age') || resident.age || '').trim();
                    const contact = String(formData.get('contact') || resident.contact || '').trim();
                    const purpose = String(formData.get('purpose') || '').trim();
                    const purposeReason = String(formData.get('purposeReason') || '').trim();
                    const idFile = idFileInput && idFileInput.files && idFileInput.files[0];

                    if (!fullName || !email || !address || !age || !contact || !purpose || !purposeReason) {
                        alert('Please complete required fields.');
                        return;
                    }
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                        alert('Please enter a valid email address.');
                        return;
                    }
                    if (!idFile || !idFile.type.startsWith('image/')) {
                        alert('Please upload a photo of your valid ID.');
                        return;
                    }
                    if (idFile.size > MAX_ID_IMAGE_UPLOAD_BYTES) {
                        alert('Please upload an image smaller than 10MB.');
                        return;
                    }

                    const requests = readRequests();
                    const now = new Date();
                    const user = getStoredUser();
                    const ref = 'BR' + now.getTime();
                    const idDataUrl = await prepareStoredIdImageDataUrl(idFile);

                    requests.unshift({
                        ref,
                        name: fullName,
                        email,
                        address,
                        age,
                        dateRequested: now.toISOString().split('T')[0],
                        validUntil: new Date(now.getFullYear() + 1, now.getMonth(), now.getDate())
                            .toISOString().split('T')[0],
                        status: 'pending',
                        purpose,
                        purposeReason,
                        contact,
                        ownerKey: getUserKey(user),
                        idFileName: idFile.name,
                        idFileType: idFile.type,
                        idFileUrl: '',
                        pdfSaved: false
                    });

                    writeRequests(requests);
                    closeApplyModal();
                    renderRequests();
                    alert(`Application submitted successfully! ${ref}`);
                });
            }

            window.addEventListener('storage', (event) => {
                if (event.key === 'digibarangay_requests' || event.key === 'digibarangay_user' || event.key ===
                    'digibarangay_registered_user') {
                    setHeaderUser(getStoredUser());
                    renderRequests();
                }
            });
        })();
    </script>
</body>

</html>
