// User Menu - Session Storage Based (legacy client overlay). When the server
// already rendered the logged-in navbar (`data-public-auth="in"`), skip this
// entirely so we never hide wrong nodes or fight Bootstrap.
(function() {
    if (document.documentElement.getAttribute('data-public-auth') === 'in') {
        return;
    }

    // Get language code and base path from data attributes set by PHP
    const langCode = document.documentElement.getAttribute('data-lang') || 'lv';
    const basePath = document.documentElement.getAttribute('data-base') || '';

    const isLoggedIn = sessionStorage.getItem('isLoggedIn') === 'true';
    const username = sessionStorage.getItem('username') || 'User';
    
    if (isLoggedIn) {
        // Find the login/register buttons
        const loginBtn = document.querySelector('a[href*="login/login.php"]');
        const registerBtn = document.querySelector('a[href*="register/register.php"]');
        const loginLi = loginBtn?.closest('li');
        const registerLi = registerBtn?.closest('li');
        
        // Hide login/register buttons
        if (loginLi) loginLi.style.display = 'none';
        if (registerLi) registerLi.style.display = 'none';
        
        // Create user dropdown menu
        const navbarNav = document.querySelector('.navbar-nav');
        // Check if user menu already exists to prevent duplicates
        const existingUserMenu = document.querySelector('#userDropdown');
        if (navbarNav && !existingUserMenu) {
            const userMenu = document.createElement('li');
            userMenu.className = 'nav-item dropdown ms-3';
            // Check if we're on the profile page
            const isProfilePage = window.location.pathname.includes('profile.php');
            
            // Construct profile link with base path
            const profileLink = `${basePath}UserProfile/profile.php?lang=${langCode}`;

            userMenu.innerHTML = `
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle me-2"></i>
                    ${username}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    ${!isProfilePage ? `<li><a class="dropdown-item" href="${profileLink}">
                        <i class="fas fa-user me-2"></i>Profile
                    </a></li>
                    <li><hr class="dropdown-divider"></li>` : ''}
                    <li><a class="dropdown-item text-danger" href="#" onclick="sessionStorage.clear(); window.location.reload();">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a></li>
                </ul>
            `;
            
            // Insert before language switcher
            const langSwitcher = navbarNav.querySelector('.d-flex.flex-column.gap-2');
            if (langSwitcher) {
                navbarNav.insertBefore(userMenu, langSwitcher);
            }
            
            // Apply hover handlers to the newly created user dropdown
            if (window.innerWidth >= 992) {
                const userToggle = userMenu.querySelector('.dropdown-toggle');
                const userDropdownMenu = userMenu.querySelector('.dropdown-menu');
                
                if (userToggle && userDropdownMenu) {
                    // Prevent Bootstrap's click behavior
                    userToggle.addEventListener('click', function(e) {
                        if (window.innerWidth >= 992) {
                            e.preventDefault();
                            e.stopPropagation();
                        }
                    }, true);
                    
                    // Show on hover
                    userMenu.addEventListener('mouseenter', function() {
                        if (window.innerWidth >= 992) {
                            userDropdownMenu.style.display = 'block';
                            userDropdownMenu.style.opacity = '1';
                            userDropdownMenu.style.pointerEvents = 'auto';
                            userDropdownMenu.classList.add('show');
                        }
                    });
                    
                    // Keep open when hovering menu
                    userDropdownMenu.addEventListener('mouseenter', function() {
                        if (window.innerWidth >= 992) {
                            this.style.display = 'block';
                            this.style.opacity = '1';
                            this.style.pointerEvents = 'auto';
                            this.classList.add('show');
                        }
                    });
                    
                    // Hide when leaving - use setTimeout to allow mouse to reach menu
                    userMenu.addEventListener('mouseleave', function(e) {
                        if (window.innerWidth >= 992) {
                            // Use setTimeout to allow mouse to reach menu
                            setTimeout(function() {
                                // Check if mouse is actually over menu
                                const isOverMenu = userDropdownMenu.matches(':hover') || userMenu.matches(':hover');
                                if (!isOverMenu) {
                                    userDropdownMenu.style.display = 'none';
                                    userDropdownMenu.style.opacity = '0';
                                    userDropdownMenu.style.pointerEvents = 'none';
                                    userDropdownMenu.classList.remove('show');
                                }
                            }, 100);
                        }
                    });
                    
                    // Hide when leaving menu - use setTimeout to check if mouse moved to dropdown
                    userDropdownMenu.addEventListener('mouseleave', function(e) {
                        if (window.innerWidth >= 992) {
                            // Use setTimeout to check if mouse moved to dropdown
                            setTimeout(function() {
                                const isOverDropdown = userMenu.matches(':hover');
                                if (!isOverDropdown) {
                                    userDropdownMenu.style.display = 'none';
                                    userDropdownMenu.style.opacity = '0';
                                    userDropdownMenu.style.pointerEvents = 'none';
                                    userDropdownMenu.classList.remove('show');
                                }
                            }, 100);
                        }
                    });
                }
            }
        }
    }
})();

// Profile Page Functionality
(function() {
    const AVATAR_STORAGE_KEY = 'userAvatar';
    const FULLNAME_STORAGE_KEY = 'fullName';
    const MAX_AVATAR_BYTES = 2 * 1024 * 1024; // 2 MB

    function getDefaultAvatarSrc(profilePage) {
        const sidebarAvatar = profilePage.querySelector('#profileAvatar');
        return sidebarAvatar ? sidebarAvatar.getAttribute('src') : '';
    }

    function applyAvatar(profilePage, src) {
        const sidebarAvatar = profilePage.querySelector('#profileAvatar');
        const previewAvatar = profilePage.querySelector('#avatarPreview');
        if (sidebarAvatar && src) sidebarAvatar.setAttribute('src', src);
        if (previewAvatar && src) previewAvatar.setAttribute('src', src);
    }

    function showMessage(el, text, type) {
        if (!el) return;
        el.textContent = text;
        el.className = 'profile-edit-message profile-edit-message--' + type;
        el.hidden = false;
        clearTimeout(el._hideTimer);
        el._hideTimer = setTimeout(() => { el.hidden = true; }, 4000);
    }

    function initEditForm(profilePage, defaults) {
        const form = profilePage.querySelector('#profileEditForm');
        if (!form) return;

        const fullNameInput = form.querySelector('#editFullName');
        const usernameInput = form.querySelector('#editUsername');
        const emailInput = form.querySelector('#editEmail');
        const passwordInput = form.querySelector('#editPassword');
        const passwordConfirmInput = form.querySelector('#editPasswordConfirm');
        const messageEl = form.querySelector('#profileEditMessage');
        const avatarInput = form.querySelector('#avatarInput');
        const removeBtn = form.querySelector('#avatarRemoveBtn');

        const storedFullName = sessionStorage.getItem(FULLNAME_STORAGE_KEY) || '';
        const storedAvatar = localStorage.getItem(AVATAR_STORAGE_KEY) || '';
        const defaultAvatar = getDefaultAvatarSrc(profilePage);

        if (fullNameInput) fullNameInput.value = storedFullName;
        if (usernameInput) usernameInput.value = defaults.username;
        if (emailInput) emailInput.value = defaults.email;

        if (storedAvatar) {
            applyAvatar(profilePage, storedAvatar);
            if (removeBtn) removeBtn.hidden = false;
        }

        if (avatarInput) {
            avatarInput.addEventListener('change', function() {
                const file = this.files && this.files[0];
                if (!file) return;

                if (!file.type.startsWith('image/')) {
                    showMessage(messageEl, profilePage.dataset.msgAvatarInvalid || 'Please choose a valid image file.', 'error');
                    this.value = '';
                    return;
                }
                if (file.size > MAX_AVATAR_BYTES) {
                    showMessage(messageEl, profilePage.dataset.msgAvatarTooLarge || 'Image is too large (max 2 MB).', 'error');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const dataUrl = e.target.result;
                    try {
                        localStorage.setItem(AVATAR_STORAGE_KEY, dataUrl);
                        applyAvatar(profilePage, dataUrl);
                        if (removeBtn) removeBtn.hidden = false;
                    } catch (err) {
                        showMessage(messageEl, profilePage.dataset.msgAvatarTooLarge || 'Image is too large to save.', 'error');
                    }
                };
                reader.readAsDataURL(file);
            });
        }

        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                localStorage.removeItem(AVATAR_STORAGE_KEY);
                applyAvatar(profilePage, defaultAvatar);
                if (avatarInput) avatarInput.value = '';
                this.hidden = true;
            });
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const newUsername = (usernameInput?.value || '').trim();
            const newEmail = (emailInput?.value || '').trim();
            const newFullName = (fullNameInput?.value || '').trim();
            const newPassword = passwordInput?.value || '';
            const confirmPassword = passwordConfirmInput?.value || '';

            if (!newUsername) {
                showMessage(messageEl, profilePage.dataset.msgUsernameRequired || 'Username is required.', 'error');
                usernameInput?.focus();
                return;
            }
            if (!newEmail || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(newEmail)) {
                showMessage(messageEl, profilePage.dataset.msgEmailInvalid || 'Please enter a valid email address.', 'error');
                emailInput?.focus();
                return;
            }
            if (newPassword || confirmPassword) {
                if (newPassword.length < 6) {
                    showMessage(messageEl, profilePage.dataset.msgPasswordShort || 'Password must be at least 6 characters.', 'error');
                    passwordInput?.focus();
                    return;
                }
                if (newPassword !== confirmPassword) {
                    showMessage(messageEl, profilePage.dataset.msgPasswordMismatch || 'Passwords do not match.', 'error');
                    passwordConfirmInput?.focus();
                    return;
                }
            }

            sessionStorage.setItem('username', newUsername);
            sessionStorage.setItem('email', newEmail);
            sessionStorage.setItem(FULLNAME_STORAGE_KEY, newFullName);
            if (newPassword) {
                sessionStorage.setItem('password', newPassword);
            }

            const profileNameEl = profilePage.querySelector('#profileName');
            const profileEmailEl = profilePage.querySelector('#profileEmail');
            if (profileNameEl) profileNameEl.textContent = newFullName || newUsername;
            if (profileEmailEl) profileEmailEl.textContent = newEmail;

            const userDropdown = document.querySelector('#userDropdown');
            if (userDropdown) {
                const icon = userDropdown.querySelector('i');
                userDropdown.textContent = ' ' + newUsername;
                if (icon) userDropdown.prepend(icon);
            }

            if (passwordInput) passwordInput.value = '';
            if (passwordConfirmInput) passwordConfirmInput.value = '';

            showMessage(messageEl, profilePage.dataset.msgSaveSuccess || 'Profile updated successfully.', 'success');
        });
    }

    function attachProfilePanels(profilePage) {
        const listItems = profilePage.querySelectorAll('.list-group-item[data-panel]');
        if (listItems.length === 0) {
            return;
        }

        listItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                profilePage.querySelectorAll('.list-group-item').forEach(i => {
                    i.classList.remove('active');
                });
                this.classList.add('active');

                profilePage.querySelectorAll('.content-panel').forEach(panel => {
                    panel.classList.remove('active');
                });

                const panelId = this.getAttribute('data-panel') + '-panel';
                const panel = document.getElementById(panelId);
                if (panel) {
                    panel.classList.add('active');
                }
            });
        });
    }

    function initProfilePage() {
        const profilePage = document.querySelector('main.profile-page');
        if (!profilePage) {
            return;
        }

        // Server-rendered account page: keep PHP output; only wire tab panels.
        if (profilePage.dataset.serverUser === '1') {
            attachProfilePanels(profilePage);
            return;
        }

        const username = sessionStorage.getItem('username') || 'Guest';
        const userEmail = sessionStorage.getItem('email') || 'user@example.com';
        const fullName = sessionStorage.getItem(FULLNAME_STORAGE_KEY) || '';

        const profileNameEl = document.getElementById('profileName');
        const profileEmailEl = document.getElementById('profileEmail');
        const memberSinceEl = document.getElementById('memberSince');

        if (profileNameEl) profileNameEl.textContent = fullName || username;
        if (profileEmailEl) profileEmailEl.textContent = userEmail;

        const memberSince = new Date().toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        if (memberSinceEl) memberSinceEl.textContent = memberSince;

        const savedAvatar = localStorage.getItem(AVATAR_STORAGE_KEY);
        if (savedAvatar) {
            applyAvatar(profilePage, savedAvatar);
        }

        initEditForm(profilePage, { username, email: userEmail, fullName });

        attachProfilePanels(profilePage);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initProfilePage);
    } else {
        setTimeout(initProfilePage, 0);
    }
})();

