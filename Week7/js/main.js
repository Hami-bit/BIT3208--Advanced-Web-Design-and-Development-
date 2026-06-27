// Week 2 - main.js - NexaBank Basic Scripts
// Refactored for better maintainability and extended functionality.

document.addEventListener("DOMContentLoaded", function () {
    /**
     * Finds all links matching a selector and adds an 'active' class
     * if the link's href matches the current page URL.
     * @param {string} selector The CSS selector for the links.
     */
    function highlightActiveLinks(selector) {
        const links = document.querySelectorAll(selector);
        if (links.length === 0) return;

        // Use the full pathname for more reliable matching
        const currentPath = window.location.pathname;

        links.forEach(function (link) {
            const linkPath = new URL(link.href).pathname;
            // Check if the current path ends with the link's path.
            // This makes matching more robust (e.g. /NexaBank_Week7/login.php will match login.php)
            if (currentPath.endsWith(linkPath)) {
                link.classList.add("active");
            }
        });
    }

    highlightActiveLinks(".nav-links a"); // For the main top navbar
    highlightActiveLinks(".sidebar-nav li a"); // For the dashboard sidebar
});
