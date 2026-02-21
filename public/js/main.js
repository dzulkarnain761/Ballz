document.addEventListener('DOMContentLoaded', () => {
    // Mobile Menu Toggle
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const navList = document.getElementById('navList');
    if (mobileMenuToggle && navList) {
        mobileMenuToggle.addEventListener('click', () => {
            navList.classList.toggle('open');
            const icon = mobileMenuToggle.querySelector('iconify-icon');
            if (navList.classList.contains('open')) {
                icon.setAttribute('icon', 'material-symbols:close-rounded');
            } else {
                icon.setAttribute('icon', 'material-symbols:menu-rounded');
            }
        });

        // Close mobile menu when a link is clicked
        navList.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navList.classList.remove('open');
                const icon = mobileMenuToggle.querySelector('iconify-icon');
                icon.setAttribute('icon', 'material-symbols:menu-rounded');
            });
        });
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            const target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

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
    if (themeToggle) {
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
    }

    // Simple scroll reveal
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });

    // Observe elements for scroll reveal
    document.querySelectorAll('.feature-card, .menu-item-card, .app-feature, .about-stat, .hero-stat').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(el);
    });

    // Add visible styles
    const style = document.createElement('style');
    style.textContent = '.visible { opacity: 1 !important; transform: translateY(0) !important; }';
    document.head.appendChild(style);

    // Stagger animation for grid items
    const staggerObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const children = entry.target.querySelectorAll('.feature-card, .menu-item-card, .about-stat');
                children.forEach((child, i) => {
                    setTimeout(() => child.classList.add('visible'), i * 100);
                });
                staggerObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.features-grid, .menu-grid, .about-stats').forEach(grid => {
        staggerObserver.observe(grid);
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
        if (e.key === 'Escape') {
            closeSidebar();
            // Also close any open modals
            closeAllModals();
        }
    });

    // Close sidebar when a nav link is clicked (mobile)
    document.querySelectorAll('.sidebar-nav a').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 1024) closeSidebar();
        });
    });

    // Close modal when clicking overlay
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });

    // Auto-dismiss alerts after 5 seconds
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 400);
        }, 5000);
    });
});

/* =============================================
   MODAL SYSTEM (Global Functions)
   ============================================= */

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        // Focus first input in modal
        setTimeout(() => {
            const firstInput = modal.querySelector('input:not([type="checkbox"]):not([type="hidden"]), textarea, select');
            if (firstInput) firstInput.focus();
        }, 300);
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

function closeAllModals() {
    document.querySelectorAll('.modal-overlay.active').forEach(modal => {
        modal.classList.remove('active');
    });
    document.body.style.overflow = '';
}

function openDeleteModal(deleteUrl, itemType) {
    const modal = document.getElementById('deleteModal');
    const message = document.getElementById('deleteModalMessage');
    const btn = document.getElementById('confirmDeleteBtn');
    
    if (message) {
        message.textContent = 'Are you sure you want to delete this ' + (itemType || 'item') + '? This action cannot be undone.';
    }
    if (btn) {
        btn.href = deleteUrl;
    }
    openModal('deleteModal');
}
