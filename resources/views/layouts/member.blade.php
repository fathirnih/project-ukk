<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan Digital - Anggota')</title>
    <script>
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            document.documentElement.classList.add('member-sidebar-collapsed');
        }
    </script>
    @vite(['resources/css/site-theme.css', 'resources/css/member-theme.css', 'resources/js/member.js'])
</head>
<body class="member-layout">
    @include('partials.navbar', [
        'navbarClass' => 'fixed-top member-navbar',
        'collapseId' => 'memberNavbarContent',
        'showMemberToggle' => true,
    ])
    @include('partials.flash-message')

    <!-- Main Layout -->
    <div class="d-flex member-shell">
        @if(session('anggota_id'))
        @include('partials.member-sidebar')
        @endif

        <!-- Main Content -->
        <main class="member-content" id="mainContent">
            <div class="content-panel">
                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>
