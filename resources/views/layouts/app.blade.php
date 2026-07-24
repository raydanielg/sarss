<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'SARS'))</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,500,600,700,800,900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="referrer" content="strict-origin-when-cross-origin">

    <style>
        @keyframes simpleFadeIn { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
        .ajax-loader { position:fixed; top:0; left:0; right:0; height:3px; background: linear-gradient(90deg, #024938, #f9ac00, #024938); background-size: 200% 100%; animation: ajaxProgress 1s linear infinite; z-index:9999; display:none; }
        @keyframes ajaxProgress { 0% { background-position: 100% 0; } 100% { background-position: -100% 0; } }
        .page-transition { animation: simpleFadeIn 0.35s ease-out both; }
        .swal2-popup.swal2-toast { padding: 8px 14px !important; min-width: 240px !important; max-width: 320px !important; font-size: 13px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.12) !important; }
        .swal2-popup.swal2-toast .swal2-title { font-size: 14px !important; font-weight: 700 !important; padding: 0 !important; margin: 0 0 2px !important; }
        .swal2-popup.swal2-toast .swal2-html-container { font-size: 12px !important; color: #6b7280 !important; margin: 0 !important; }
        .swal2-popup.swal2-toast .swal2-icon { width: 28px !important; height: 28px !important; min-width: 28px !important; }
        .swal2-popup.swal2-toast .swal2-icon .swal2-icon-content { font-size: 14px !important; }
        .swal2-popup.swal2-toast .swal2-timer-progress-bar { height: 2px !important; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        emerald: { 50:'#e6f5f1',100:'#b3e0d4',200:'#80cbc0',300:'#4db5a8',400:'#1a9f8e',500:'#024938',600:'#023d30',700:'#013028',800:'#01241f',900:'#001816' },
                        gold: { 50:'#fff5e0',100:'#ffe6b3',200:'#ffd680',300:'#ffc64d',400:'#ffb71a',500:'#f9ac00',600:'#d49700',700:'#b07c00',800:'#8c6100',900:'#684600' }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-['Nunito',sans-serif] antialiased text-slate-800 min-h-screen">

    {{-- Auth Background --}}
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('bg.jpg') }}" alt="" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-900/80 via-emerald-800/70 to-emerald-700/60"></div>
    </div>

    {{-- AJAX Progress Bar --}}
    <div id="ajaxLoader" class="ajax-loader"></div>

    <main id="authMain" class="relative z-10 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    {{-- SweetAlert2 Notifications --}}
    <script>
    const swalTheme = {
        customClass: {
            popup: 'rounded-xl',
            confirmButton: 'bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-5 py-2 rounded-lg text-sm',
            cancelButton: 'bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-5 py-2 rounded-lg text-sm',
        },
        buttonsStyling: false,
    };

    function showToast(type, title, message) {
        const icons = { success: 'success', error: 'error', warning: 'warning', info: 'info' };
        Swal.fire({
            ...swalTheme,
            icon: icons[type] || 'info',
            title: title,
            text: message || '',
            timer: 3500,
            timerProgressBar: true,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            width: '300px',
        });
    }

    function showAlert(type, title, message) {
        const types = {
            success: 'success',
            error: 'error',
            warning: 'warning',
            info: 'info',
        };
        Swal.fire({
            ...swalTheme,
            icon: types[type] || 'info',
            title: title,
            text: message || '',
            confirmButtonText: 'OK',
        });
    }

    window.showToast = showToast;
    window.showAlert = showAlert;

    @if(session('status'))
        showToast('success', 'Success', '{{ session('status') }}');
    @endif
    @if(session('error'))
        showToast('error', 'Error', '{{ session('error') }}');
    @endif
    @if(session('warning'))
        showToast('warning', 'Warning', '{{ session('warning') }}');
    @endif
    @if(session('info'))
        showToast('info', 'Info', '{{ session('info') }}');
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            showToast('error', 'Validation Error', '{{ $error }}');
        @endforeach
    @endif

    // AJAX Navigation System
    (function() {
        const authMain = document.getElementById('authMain');
        const ajaxLoader = document.getElementById('ajaxLoader');

        function showLoader() { if(ajaxLoader) ajaxLoader.style.display = 'block'; }
        function hideLoader() { if(ajaxLoader) ajaxLoader.style.display = 'none'; }

        function loadPage(url, pushState = true) {
            showLoader();
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            })
            .then(r => {
                if (!r.ok) throw new Error('Network error');
                return r.text();
            })
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.querySelector('main');
                if (newContent && authMain) {
                    authMain.innerHTML = newContent.innerHTML;
                    authMain.classList.remove('page-transition');
                    void authMain.offsetWidth;
                    authMain.classList.add('page-transition');
                    document.title = doc.title;
                    // Update CSRF token
                    if (doc.querySelector('meta[name="csrf-token"]')) {
                        const token = doc.querySelector('meta[name="csrf-token"]').content;
                        const localToken = document.querySelector('meta[name="csrf-token"]');
                        if (localToken) localToken.content = token;
                    }
                    // Re-run scripts in the new content
                    newContent.querySelectorAll('script').forEach(oldScript => {
                        const newScript = document.createElement('script');
                        if (oldScript.src) newScript.src = oldScript.src;
                        else newScript.textContent = oldScript.textContent;
                        oldScript.parentNode.replaceChild(newScript, oldScript);
                    });
                    rebindAjaxLinks();
                    rebindForms();
                    // Re-trigger session toasts from new page
                    const newScripts = doc.querySelectorAll('script');
                    newScripts.forEach(s => {
                        if (s.textContent.includes('showToast')) {
                            try { eval(s.textContent); } catch(e) {}
                        }
                    });
                }
                if (pushState) history.pushState({ url: url }, '', url);
                hideLoader();
            })
            .catch(err => {
                hideLoader();
                window.showToast && window.showToast('error', 'Connection Error', 'Please check your internet connection.');
            });
        }

        function rebindAjaxLinks() {
            document.querySelectorAll('a[href]').forEach(function(link) {
                const href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('javascript') || href.startsWith('mailto') || href.startsWith('tel') || link.target === '_blank') return;
                if (link.dataset.ajaxBound) return;
                const url = new URL(href, location.href);
                if (url.host !== location.host) return;
                link.dataset.ajaxBound = '1';
                link.addEventListener('click', handleAjaxClick);
            });
        }

        function handleAjaxClick(e) {
            e.preventDefault();
            loadPage(this.getAttribute('href'));
        }

        function rebindForms() {
            document.querySelectorAll('form[method="POST"]').forEach(function(form) {
                if (form.dataset.ajaxBound) return;
                form.dataset.ajaxBound = '1';
                form.addEventListener('submit', handleAjaxSubmit);
            });
        }

        function handleAjaxSubmit(e) {
            e.preventDefault();
            const form = e.target;
            const btn = form.querySelector('button[type="submit"]');
            const originalHTML = btn ? btn.innerHTML : '';

            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Processing...';
            }
            showLoader();

            const formData = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' },
                credentials: 'same-origin',
                redirect: 'follow'
            })
            .then(r => {
                if (r.redirected) {
                    window.location.href = r.url;
                    return null;
                }
                return r.text();
            })
            .then(html => {
                if (html === null) return;
                hideLoader();
                if (btn) { btn.disabled = false; btn.innerHTML = originalHTML; }

                if (html.trim().startsWith('<!DOCTYPE') || html.trim().startsWith('<!doctype')) {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContent = doc.querySelector('main');
                    if (newContent && authMain) {
                        authMain.innerHTML = newContent.innerHTML;
                        authMain.classList.remove('page-transition');
                        void authMain.offsetWidth;
                        authMain.classList.add('page-transition');
                        document.title = doc.title;
                        if (doc.querySelector('meta[name="csrf-token"]')) {
                            const token = doc.querySelector('meta[name="csrf-token"]').content;
                            const localToken = document.querySelector('meta[name="csrf-token"]');
                            if (localToken) localToken.content = token;
                        }
                        newContent.querySelectorAll('script').forEach(oldScript => {
                            const newScript = document.createElement('script');
                            if (oldScript.src) newScript.src = oldScript.src;
                            else newScript.textContent = oldScript.textContent;
                            oldScript.parentNode.replaceChild(newScript, oldScript);
                        });
                        rebindAjaxLinks();
                        rebindForms();
                        const newScripts = doc.querySelectorAll('script');
                        newScripts.forEach(s => {
                            if (s.textContent.includes('showToast')) {
                                try { eval(s.textContent); } catch(e) {}
                            }
                        });
                    } else {
                        window.location.reload();
                    }
                } else {
                    window.location.reload();
                }
            })
            .catch(() => {
                hideLoader();
                if (btn) { btn.disabled = false; btn.innerHTML = originalHTML; }
                window.showToast && window.showToast('error', 'Network Error', 'Please try again.');
            });
        }

        window.addEventListener('popstate', function(e) {
            if (e.state && e.state.url) loadPage(e.state.url, false);
        });

        rebindAjaxLinks();
        rebindForms();
    })();
    </script>

</body>
</html>
