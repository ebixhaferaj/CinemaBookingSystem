// sidebar-toggle.js
document.addEventListener('DOMContentLoaded', function() {
    const button = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    
    button.addEventListener('click', function() {
        sidebar.classList.toggle('show');
        if (sidebar.classList.contains('show')) {
            button.style.display = 'none';
        } else {
            button.style.display = 'block';
        }
    });
});