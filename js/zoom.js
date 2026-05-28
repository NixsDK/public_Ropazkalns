/**
 * Rental gallery lightbox: zoom + prev/next without closing.
 * Images must use .zoomable-image inside a .gallery-row (same row = one carousel).
 */

(function () {
    var lightboxState = {
        images: [],
        index: 0
    };

    var lightboxEl;
    var lightboxImg;
    var btnPrev;
    var btnNext;
    var btnClose;

    function closeLightbox() {
        if (!lightboxEl || !lightboxImg) return;
        lightboxEl.style.display = 'none';
        lightboxImg.removeAttribute('src');
        lightboxImg.alt = '';
        document.body.style.overflow = '';
        lightboxState.images = [];
        lightboxState.index = 0;
    }

    function updateLightbox() {
        var images = lightboxState.images;
        var index = lightboxState.index;
        if (!images.length || !lightboxImg) return;
        var item = images[index];
        lightboxImg.src = item.src;
        lightboxImg.alt = item.alt || '';

        var multi = images.length > 1;
        if (btnPrev && btnNext) {
            btnPrev.hidden = !multi;
            btnNext.hidden = !multi;
            if (multi) {
                btnPrev.disabled = false;
                btnNext.disabled = false;
            }
        }
    }

    function navigate(delta) {
        var images = lightboxState.images;
        if (images.length <= 1) return;
        var n = images.length;
        lightboxState.index = (lightboxState.index + delta + n) % n;
        updateLightbox();
    }

    function openLightboxFromRow(row, clickedImg) {
        var imgs = row ? row.querySelectorAll('.zoomable-image') : [];
        if (!imgs.length && clickedImg) {
            lightboxState.images = [{ src: clickedImg.src, alt: clickedImg.alt || '' }];
        } else {
            lightboxState.images = Array.prototype.map.call(imgs, function (img) {
                return { src: img.src, alt: img.alt || '' };
            });
        }
        var start = 0;
        if (clickedImg) {
            for (var i = 0; i < lightboxState.images.length; i++) {
                if (lightboxState.images[i].src === clickedImg.src) {
                    start = i;
                    break;
                }
            }
        }
        lightboxState.index = start;
        updateLightbox();
        if (lightboxEl) {
            lightboxEl.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    function injectLightbox() {
        if (document.getElementById('imageLightbox')) return;
        var html =
            '<div id="imageLightbox" class="lightbox-overlay" role="dialog" aria-modal="true" aria-label="Image viewer">' +
            '<button type="button" class="lightbox-close" aria-label="Close">&times;</button>' +
            '<button type="button" class="lightbox-nav lightbox-prev" aria-label="Previous image" hidden>&#8249;</button>' +
            '<button type="button" class="lightbox-nav lightbox-next" aria-label="Next image" hidden>&#8250;</button>' +
            '<img class="lightbox-image" id="lightboxImage" src="" alt="">' +
            '</div>';
        document.body.insertAdjacentHTML('beforeend', html);

        lightboxEl = document.getElementById('imageLightbox');
        lightboxImg = document.getElementById('lightboxImage');
        btnClose = lightboxEl.querySelector('.lightbox-close');
        btnPrev = lightboxEl.querySelector('.lightbox-prev');
        btnNext = lightboxEl.querySelector('.lightbox-next');

        lightboxEl.addEventListener('click', function (e) {
            if (e.target === lightboxEl) {
                closeLightbox();
            }
        });

        if (btnClose) {
            btnClose.addEventListener('click', function (e) {
                e.stopPropagation();
                closeLightbox();
            });
        }
        if (lightboxImg) {
            lightboxImg.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        }
        if (btnPrev) {
            btnPrev.addEventListener('click', function (e) {
                e.stopPropagation();
                navigate(-1);
            });
        }
        if (btnNext) {
            btnNext.addEventListener('click', function (e) {
                e.stopPropagation();
                navigate(1);
            });
        }

        document.addEventListener('keydown', function (e) {
            if (!lightboxEl || lightboxEl.style.display !== 'flex') return;
            if (e.key === 'Escape') {
                closeLightbox();
            } else if (e.key === 'ArrowLeft') {
                navigate(-1);
            } else if (e.key === 'ArrowRight') {
                navigate(1);
            }
        });
    }

    function initZoomableGallery() {
        var zoomableImages = document.querySelectorAll('.zoomable-image');
        if (zoomableImages.length === 0) return;

        injectLightbox();

        zoomableImages.forEach(function (img) {
            img.style.cursor = 'zoom-in';
            img.addEventListener('click', function (e) {
                e.stopPropagation();
                var row = img.closest('.gallery-row');
                openLightboxFromRow(row, img);
            });
        });
    }

    /* Legacy global names if anything still calls them */
    window.openLightbox = function (src) {
        injectLightbox();
        lightboxState.images = [{ src: src, alt: '' }];
        lightboxState.index = 0;
        updateLightbox();
        if (lightboxEl) {
            lightboxEl.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    };
    window.closeLightbox = closeLightbox;

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initZoomableGallery);
    } else {
        initZoomableGallery();
    }
})();

document.addEventListener('DOMContentLoaded', function () {
    var images = document.querySelectorAll('img');
    images.forEach(function (img) {
        img.addEventListener('load', function () {
            this.style.opacity = '1';
        });
        if (!img.complete) {
            img.style.opacity = '1';
        } else {
            img.style.opacity = '1';
        }
    });

    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            var target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    var observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.info-box, .rental-card, .kempings-box').forEach(function (el) {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });

    document.querySelectorAll('.rental-card').forEach(function (card) {
        card.addEventListener('mouseenter', function () {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        card.addEventListener('mouseleave', function () {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    document.querySelectorAll('.toggle-info').forEach(function (card) {
        card.addEventListener('click', function () {
            var details = this.nextElementSibling;
            if (details && details.classList.contains('card-details')) {
                if (details.style.display === 'block') {
                    details.style.display = 'none';
                    details.classList.remove('show');
                } else {
                    details.style.display = 'block';
                    details.classList.add('show');
                }
            }
        });
    });

    document.querySelectorAll('input, textarea, select').forEach(function (input) {
        input.addEventListener('focus', function () {
            if (this.parentElement) this.parentElement.style.transform = 'translateY(-2px)';
        });
        input.addEventListener('blur', function () {
            if (this.parentElement) this.parentElement.style.transform = 'translateY(0)';
        });
    });

    document.querySelectorAll('.btn-rent, button[type="submit"]').forEach(function (button) {
        button.addEventListener('click', function () {
            if (!this.classList.contains('loading')) {
                this.classList.add('loading');
                this.style.pointerEvents = 'none';
                var self = this;
                setTimeout(function () {
                    self.classList.remove('loading');
                    self.style.pointerEvents = 'auto';
                }, 2000);
            }
        });
    });

    document.querySelectorAll('.dropdown-menu').forEach(function (dropdown) {
        dropdown.addEventListener('show.bs.dropdown', function () {
            this.style.opacity = '0';
            this.style.transform = 'translateY(-10px)';
            var self = this;
            setTimeout(function () {
                self.style.opacity = '1';
                self.style.transform = 'translateY(0)';
            }, 10);
        });
    });

    document.querySelectorAll('.hero-overlay h2, .btn-rent').forEach(function (el) {
        el.addEventListener('mouseenter', function () {
            this.style.animation = 'pulse 0.6s ease-in-out';
        });
        el.addEventListener('animationend', function () {
            this.style.animation = '';
        });
    });

    var scrollIndicator = document.createElement('div');
    scrollIndicator.style.cssText =
        'position:fixed;top:0;left:0;width:0%;height:3px;background:linear-gradient(90deg,#508c39,#3b6e2d);z-index:10000;transition:width 0.1s ease;';
    document.body.appendChild(scrollIndicator);
    window.addEventListener('scroll', function () {
        var scrolled =
            (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100;
        scrollIndicator.style.width = scrolled + '%';
    });

    document.querySelectorAll('[data-tooltip]').forEach(function (element) {
        element.addEventListener('mouseenter', function () {
            var tooltip = document.createElement('div');
            tooltip.textContent = this.getAttribute('data-tooltip');
            tooltip.style.cssText =
                'position:absolute;background:rgba(0,0,0,0.8);color:white;padding:8px 12px;border-radius:6px;font-size:12px;z-index:1000;pointer-events:none;white-space:nowrap;';
            document.body.appendChild(tooltip);
            var rect = this.getBoundingClientRect();
            tooltip.style.left = rect.left + rect.width / 2 - tooltip.offsetWidth / 2 + 'px';
            tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + 'px';
            this.tooltip = tooltip;
        });
        element.addEventListener('mouseleave', function () {
            if (this.tooltip) {
                this.tooltip.remove();
                this.tooltip = null;
            }
        });
    });
});
