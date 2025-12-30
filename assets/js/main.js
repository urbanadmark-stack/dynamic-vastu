// Main JavaScript file

document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mainNav = document.querySelector('#mainNav');
    
    if (mobileMenuToggle && mainNav) {
        mobileMenuToggle.addEventListener('click', function() {
            mobileMenuToggle.classList.toggle('active');
            mainNav.classList.toggle('active');
        });
        
        // Close menu when clicking on a link
        const navLinks = mainNav.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenuToggle.classList.remove('active');
                mainNav.classList.remove('active');
            });
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!mainNav.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
                mobileMenuToggle.classList.remove('active');
                mainNav.classList.remove('active');
            }
        });
    }
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Image gallery functionality
    window.changeImage = function(src) {
        const mainImage = document.getElementById('main-image');
        if (mainImage) {
            mainImage.src = src;
            // Update active thumbnail - find by matching src
            document.querySelectorAll('.gallery-thumb').forEach(thumb => {
                thumb.classList.remove('active');
                const thumbSrc = thumb.getAttribute('src');
                // Match by relative path (handle both full URL and relative path)
                if (thumbSrc === src || thumb.src === src || thumbSrc.includes(src.split('/').pop())) {
                    thumb.classList.add('active');
                }
            });
        }
    };
    
    // Form validation enhancement
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                } else {
                    field.classList.remove('error');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields');
            }
        });
    });
    
    // Search Tab Functionality
    const searchTabs = document.querySelectorAll('.search-tab');
    const searchForm = document.querySelector('.hero-search-container .search-form');
    
    if (searchTabs.length > 0 && searchForm) {
        searchTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                searchTabs.forEach(t => t.classList.remove('active'));
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Update form action if data-action is present
                const action = this.getAttribute('data-action');
                if (action) {
                    searchForm.setAttribute('action', action);
                }
            });
        });
    }
});

