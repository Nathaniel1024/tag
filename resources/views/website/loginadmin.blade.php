<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - DIGIBARANGAY</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo_zed.png') }}" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">
        
        <div class="bg-linear-to-b from-[#4facfe] to-[#72c667] p-10 text-center text-white">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('img/logo_zed.png') }}" alt="DIGIBARANGAY logo" class="w-20 h-20 object-contain" />
            </div>
            <h1 class="text-4xl font-bold tracking-tight">Admin</h1>
            <p class="text-sm opacity-90 mt-2">Login to access the admin dashboard</p>
        </div>

        <div class="p-8">
            <form id="adminLoginForm" action="{{ route('loginadmin.submit') }}" method="POST" class="space-y-6">
              @csrf
                <div>
                    <label for="login" class="block text-gray-800 font-bold mb-2">Email or Username</label>
                    <input 
                        type="text" 
                        id="login" 
                        name="login" 
                        placeholder="Enter your Email or Username" 
                        class="w-full px-4 py-3 bg-[#f1f4f9] border-none rounded-xl focus:ring-2 focus:ring-blue-400 outline-none transition-all placeholder:text-gray-400 text-gray-700"
                        required
                    >
                </div>

                <div>
                    <label for="password" class="block text-gray-800 font-bold mb-2">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Enter your Password" 
                        class="w-full px-4 py-3 bg-[#f1f4f9] border-none rounded-xl focus:ring-2 focus:ring-blue-400 outline-none transition-all placeholder:text-gray-400 text-gray-700"
                        required
                    >
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-[#4facfe] hover:bg-blue-600 text-white font-bold py-3 rounded-xl transition-colors duration-300 shadow-md"
                >
                    Login
                </button>
            </form>

        </div>
    </div>

<div id="errorModal" class="fixed inset-0 bg-black/50 items-center justify-center hidden z-50">
  <div class="bg-white w-full max-w-sm rounded-xl shadow-xl p-6 text-center">
    <h2 class="text-lg font-bold mb-2">Login Error</h2>
    <p id="errorMessage" class="text-gray-600 mb-5">
      Access denied. Admin accounts only.
    </p>
    <button id="errorOkBtn" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-full font-semibold">
      OK
    </button>
  </div>
</div>

<!-- Account not found modal -->
<div id="notFoundModal" class="fixed inset-0 bg-black/50 items-center justify-center hidden z-50">
  <div class="bg-white w-full max-w-sm rounded-xl shadow-xl p-6 text-center">
    <h2 class="text-lg font-bold mb-2">Account Not Found</h2>
    <p id="notFoundMessage" class="text-gray-600 mb-5">Invalid credentials or account not found in the database.</p>
    <button id="notFoundOkBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-full font-semibold">OK</button>
  </div>
</div>

<!-- Unauthorized role modal -->
<div id="unauthModal" class="fixed inset-0 bg-black/50 items-center justify-center hidden z-50">
  <div class="bg-white w-full max-w-sm rounded-xl shadow-xl p-6 text-center">
    <h2 class="text-lg font-bold mb-2">Access Denied</h2>
    <p id="unauthMessage" class="text-gray-600 mb-5">Access denied. Admin or Official accounts only.</p>
    <button id="unauthOkBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-full font-semibold">OK</button>
  </div>
</div>

<script>
  const errorModal = document.getElementById('errorModal');
  const errorMessage = document.getElementById('errorMessage');
  const errorOkBtn = document.getElementById('errorOkBtn');

  function showError(message) {
    errorMessage.textContent = message;
    errorModal.classList.add('flex');
    errorModal.classList.remove('hidden');
  }

  function closeError() {
    errorModal.classList.remove('flex');
    errorModal.classList.add('hidden');
  }

  errorOkBtn.addEventListener('click', closeError);

  // click outside = close
  errorModal.addEventListener('click', (e) => {
    if (e.target === errorModal) closeError();
  });

  const adminLoginForm = document.getElementById('adminLoginForm');
  adminLoginForm.addEventListener('submit', async function (e) {
    e.preventDefault();
    const login = String(adminLoginForm.login.value || '').trim();
    const password = String(adminLoginForm.password.value || '').trim();
    const csrf = adminLoginForm.querySelector('input[name="_token"]')?.value || '';

    if (!login || !password) {
      showError('Please enter username/email and password.');
      return;
    }

    try {
      const res = await fetch(adminLoginForm.action, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ login, email: login, password })
      });

      const body = await res.json().catch(() => ({}));
      if (!res.ok) {
        // Show specific modal based on response status
        if (res.status === 401) {
          const msg = body.message || 'Invalid credentials or account not found in the database.';
          document.getElementById('notFoundMessage').textContent = msg;
          notFoundModal.classList.add('flex');
          notFoundModal.classList.remove('hidden');
          return;
        }
        if (res.status === 403) {
          const msg = body.message || 'Access denied. Admin or Official accounts only.';
          document.getElementById('unauthMessage').textContent = msg;
          unauthModal.classList.add('flex');
          unauthModal.classList.remove('hidden');
          return;
        }

        showError(body.message || 'Login failed.');
        return;
      }

      window.location.replace(body.redirect || '/dashs');
    } catch (err) {
      showError('Network error. Please try again.');
    }
  });

  // Modal elements for account-not-found and unauthorized role
  const notFoundModal = document.getElementById('notFoundModal');
  const notFoundOkBtn = document.getElementById('notFoundOkBtn');
  const unauthModal = document.getElementById('unauthModal');
  const unauthOkBtn = document.getElementById('unauthOkBtn');

  function closeNotFound() {
    notFoundModal.classList.remove('flex');
    notFoundModal.classList.add('hidden');
  }

  function closeUnauth() {
    unauthModal.classList.remove('flex');
    unauthModal.classList.add('hidden');
  }

  if (notFoundOkBtn) notFoundOkBtn.addEventListener('click', closeNotFound);
  if (unauthOkBtn) unauthOkBtn.addEventListener('click', closeUnauth);

  if (notFoundModal) notFoundModal.addEventListener('click', (e) => { if (e.target === notFoundModal) closeNotFound(); });
  if (unauthModal) unauthModal.addEventListener('click', (e) => { if (e.target === unauthModal) closeUnauth(); });

  // If redirected with ?unauth=1, show an unauthorized modal
  (function showUnauthorizedIfNeeded() {
    try {
      const params = new URLSearchParams(window.location.search);
      if (params.get('unauth')) {
        document.getElementById('unauthMessage').textContent = 'Access denied. Admin or Official accounts only.';
        unauthModal.classList.add('flex');
        unauthModal.classList.remove('hidden');
      }
    } catch (e) {
      // ignore
    }
  })();
</script>

</body>
</html>