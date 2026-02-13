import './bootstrap';
import 'bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const adminSidebar = document.getElementById('adminSidebarContainer');
    if (!adminSidebar) return;

    const collapsed = localStorage.getItem('adminSidebarCollapsed') === 'true';
    adminSidebar.classList.toggle('collapsed', collapsed);
    document.documentElement.classList.toggle('admin-sidebar-collapsed', collapsed);

    window.requestAnimationFrame(() => {
        adminSidebar.classList.add('admin-sidebar-anim');
    });
});

window.toggleAdminSidebar = function toggleAdminSidebar() {
    const adminSidebar = document.getElementById('adminSidebarContainer');
    if (!adminSidebar) return;
    if (adminSidebar.classList.contains('is-toggling')) return;

    adminSidebar.classList.add('is-toggling');
    adminSidebar.classList.toggle('collapsed');
    const collapsed = adminSidebar.classList.contains('collapsed');
    document.documentElement.classList.toggle('admin-sidebar-collapsed', collapsed);
    localStorage.setItem('adminSidebarCollapsed', String(collapsed));

    window.setTimeout(() => {
        adminSidebar.classList.remove('is-toggling');
    }, 320);
};
