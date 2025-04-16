function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const content = document.querySelector('.with-sidebar');
    sidebar.classList.toggle('collapsed');
    content.classList.toggle('collapsed');
}