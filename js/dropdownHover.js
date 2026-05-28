// Dropdown Hover Enhancement - Desktop Only
(function() {
    function initDropdownHover() {
        if (window.innerWidth >= 992) {
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle[data-bs-toggle="dropdown"]');

            dropdownToggles.forEach(function(toggle) {
                if (toggle.id === 'userDropdown') {
                    return;
                }

                const dropdown = toggle.closest('.dropdown');
                const dropdownMenu = dropdown && dropdown.querySelector('.dropdown-menu');

                if (!dropdown || !dropdownMenu) {
                    return;
                }

                // Prevent Bootstrap's click behavior on desktop
                toggle.addEventListener('click', function(e) {
                    if (window.innerWidth >= 992) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                }, true);

                dropdown.addEventListener('mouseenter', function() {
                    if (window.innerWidth >= 992) {
                        dropdownMenu.style.display = 'block';
                        dropdownMenu.style.opacity = '1';
                        dropdownMenu.style.pointerEvents = 'auto';
                        dropdownMenu.classList.add('show');
                    }
                });

                dropdownMenu.addEventListener('mouseenter', function() {
                    if (window.innerWidth >= 992) {
                        this.style.display = 'block';
                        this.style.opacity = '1';
                        this.style.pointerEvents = 'auto';
                        this.classList.add('show');
                    }
                });

                dropdown.addEventListener('mouseleave', function() {
                    if (window.innerWidth >= 992) {
                        setTimeout(function() {
                            const isOverMenu = dropdownMenu.matches(':hover') || dropdown.matches(':hover');
                            if (!isOverMenu) {
                                dropdownMenu.style.display = 'none';
                                dropdownMenu.style.opacity = '0';
                                dropdownMenu.style.pointerEvents = 'none';
                                dropdownMenu.classList.remove('show');
                            }
                        }, 100);
                    }
                });

                dropdownMenu.addEventListener('mouseleave', function() {
                    if (window.innerWidth >= 992) {
                        setTimeout(function() {
                            const isOverDropdown = dropdown.matches(':hover');
                            if (!isOverDropdown) {
                                dropdownMenu.style.display = 'none';
                                dropdownMenu.style.opacity = '0';
                                dropdownMenu.style.pointerEvents = 'none';
                                dropdownMenu.classList.remove('show');
                            }
                        }, 100);
                    }
                });
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDropdownHover);
    } else {
        initDropdownHover();
    }
})();
