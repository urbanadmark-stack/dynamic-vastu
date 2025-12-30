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
    window.changeImage = function(element) {
        const mainImage = document.getElementById('main-image');
        if (mainImage && element) {
            // Use the element's src attribute directly (relative path)
            const newSrc = element.getAttribute('src') || element.src;
            mainImage.src = newSrc;
            
            // Update active thumbnail - directly mark the clicked element as active
            document.querySelectorAll('.gallery-thumb').forEach(thumb => {
                thumb.classList.remove('active');
            });
            element.classList.add('active');
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
    const searchTypeSelect = document.getElementById('search-type-select');
    
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
                    
                    // Fix parameter name mismatch: change property_type to project_type when searching projects
                    if (action.includes('projects.php') && searchTypeSelect) {
                        searchTypeSelect.setAttribute('name', 'project_type');
                        // Update options for project types (matching database enum values)
                        const currentValue = searchTypeSelect.value;
                        searchTypeSelect.innerHTML = `
                            <option value="">All Project Types</option>
                            <option value="residential" ${currentValue === 'residential' ? 'selected' : ''}>Residential</option>
                            <option value="commercial" ${currentValue === 'commercial' ? 'selected' : ''}>Commercial</option>
                            <option value="mixed_use" ${currentValue === 'mixed_use' ? 'selected' : ''}>Mixed Use</option>
                            <option value="plotted_development" ${currentValue === 'plotted_development' ? 'selected' : ''}>Plotted Development</option>
                        `;
                    } else if (searchTypeSelect) {
                        // Restore property_type for listings
                        searchTypeSelect.setAttribute('name', 'property_type');
                        const currentValue = searchTypeSelect.value;
                        searchTypeSelect.innerHTML = `
                            <option value="">All Residential</option>
                            <option value="house" ${currentValue === 'house' ? 'selected' : ''}>House</option>
                            <option value="apartment" ${currentValue === 'apartment' ? 'selected' : ''}>Apartment</option>
                            <option value="villa" ${currentValue === 'villa' ? 'selected' : ''}>Villa</option>
                        `;
                    }
                }
            });
        });
    }
});

