document.addEventListener('DOMContentLoaded', () => {
    // Menu Tab Switching
    const toggleBtns = document.querySelectorAll('.toggle-btn');
    const menuGrids = document.querySelectorAll('.menu-grid');

    toggleBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active class from all buttons and grids
            toggleBtns.forEach(b => b.classList.remove('active'));
            menuGrids.forEach(g => g.classList.remove('active'));

            // Add active class to clicked button
            btn.classList.add('active');

            // Show corresponding grid
            const target = btn.getAttribute('data-target');
            document.getElementById(target).classList.add('active');
        });
    });

    // Theme Toggle
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = themeToggle.querySelector('iconify-icon');
    const body = document.body;
    
    // Check local storage
    const currentTheme = localStorage.getItem('theme');
    if (currentTheme) {
        body.setAttribute('data-theme', currentTheme);
        themeIcon.setAttribute('icon', currentTheme === 'dark' ? 'material-symbols:light-mode-outline' : 'material-symbols:dark-mode-outline');
    }

    themeToggle.addEventListener('click', () => {
        if (body.getAttribute('data-theme') === 'dark') {
            body.setAttribute('data-theme', 'light');
            localStorage.setItem('theme', 'light');
            themeIcon.setAttribute('icon', 'material-symbols:dark-mode-outline');
        } else {
            body.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
            themeIcon.setAttribute('icon', 'material-symbols:light-mode-outline');
        }
    });

    // Simple scroll reveal
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    });

    // Sidebar Dropdown Toggle
    document.querySelectorAll('.sidebar-nav .dropdown .menu-header').forEach(toggle => {
        toggle.addEventListener('click', () => {
            const dropdown = toggle.parentElement;
            const isActive = dropdown.classList.contains('active');
            
            if (isActive) {
                dropdown.classList.remove('active');
            } else {
                // Close all others
                document.querySelectorAll('.sidebar-nav .dropdown').forEach(d => {
                    d.classList.remove('active');
                });
                dropdown.classList.add('active');
            }
        });
    });

    // Dashboard Sidebar Toggle (Mobile)
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarClose = document.getElementById('sidebarClose');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    function openSidebar() {
        if (sidebar) {
            sidebar.classList.add('open');
            if (sidebarOverlay) sidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeSidebar() {
        if (sidebar) {
            sidebar.classList.remove('open');
            if (sidebarOverlay) sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    if (sidebarToggle) sidebarToggle.addEventListener('click', openSidebar);
    if (sidebarClose) sidebarClose.addEventListener('click', closeSidebar);
    if (sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebar);

    // Close sidebar on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeSidebar();
    });

    // Close sidebar when a nav link is clicked (mobile)
    document.querySelectorAll('.sidebar-nav a').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 1024) closeSidebar();
        });
    });
});
