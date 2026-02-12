import './bootstrap';
import 'bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const adminSidebar = document.getElementById('adminSidebarContainer');
    if (!adminSidebar) return;

    const collapsed = localStorage.getItem('adminSidebarCollapsed') === 'true';
    adminSidebar.classList.toggle('collapsed', collapsed);
    document.documentElement.classList.toggle('admin-sidebar-collapsed', collapsed);
});

window.toggleAdminSidebar = function toggleAdminSidebar() {
    const adminSidebar = document.getElementById('adminSidebarContainer');
    if (!adminSidebar) return;

    adminSidebar.classList.toggle('collapsed');
    const collapsed = adminSidebar.classList.contains('collapsed');
    document.documentElement.classList.toggle('admin-sidebar-collapsed', collapsed);
    localStorage.setItem('adminSidebarCollapsed', String(collapsed));
};
