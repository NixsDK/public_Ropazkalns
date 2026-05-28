/**
 * Gallery: one grid card per category; modal carousel shows all photos in that category (JSON on button).
 */
(function () {
    function getActiveFilterKey() {
        var chip = document.querySelector('.gallery-filter.is-active');
        return chip ? chip.getAttribute('data-filter') || 'all' : 'all';
    }

    function getCategoryFromTrigger(btn) {
        return btn && btn.getAttribute ? btn.getAttribute('data-category') || '' : '';
    }

    function parseSlidesFromButton(btn) {
        var raw = btn && btn.getAttribute('data-gallery-slides');
        if (!raw) {
            return [];
        }
        try {
            var slides = JSON.parse(raw);
            return Array.isArray(slides) ? slides : [];
        } catch (e) {
            return [];
        }
    }

    function getSlidesForCategory(categoryKey) {
        if (!categoryKey) {
            return [];
        }
        var item = document.querySelector('.gallery-item[data-category="' + categoryKey + '"]');
        var btn = item ? item.querySelector('.gallery-item__trigger') : null;
        return btn ? parseSlidesFromButton(btn) : [];
    }

    function getActiveSlideIndex(carouselEl) {
        var items = carouselEl.querySelectorAll('.carousel-item');
        var active = carouselEl.querySelector('.carousel-item.active');
        var idx = Array.prototype.indexOf.call(items, active);
        return idx >= 0 ? idx : 0;
    }

    function init() {
        var modalEl = document.getElementById('galleryModal');
        var carouselEl = document.getElementById('galleryCarousel');
        var innerEl = document.getElementById('galleryCarouselInner');
        var thumbsEl = document.getElementById('galleryCarouselThumbs');
        if (!modalEl || !carouselEl || !innerEl || !thumbsEl || typeof bootstrap === 'undefined') {
            return;
        }

        var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        var capEl = document.getElementById('galleryModalCaption');
        var catEl = document.getElementById('galleryModalTitle');

        function disposeGalleryCarousel() {
            var c = bootstrap.Carousel.getInstance(carouselEl);
            if (c) {
                c.dispose();
            }
        }

        function syncModalMeta() {
            var active = carouselEl.querySelector('.carousel-item.active');
            if (!active || !catEl) {
                return;
            }
            var img = active.querySelector('img');
            var label = active.getAttribute('data-category-label') || '';
            catEl.textContent = label;
            if (capEl && img) {
                capEl.textContent = img.getAttribute('alt') || '';
            }
        }

        function markThumbSelected(idx) {
            var thumbs = thumbsEl.querySelectorAll('.gallery-thumb');
            thumbs.forEach(function (t, i) {
                t.classList.toggle('is-selected', i === idx);
                t.setAttribute('aria-selected', i === idx ? 'true' : 'false');
            });
        }

        /**
         * @param {Array<{src:string, alt:string, categoryLabel:string, category:string}>} slides
         */
        function populateCarouselFromSlides(slides, startIndex) {
            disposeGalleryCarousel();
            innerEl.innerHTML = '';
            thumbsEl.innerHTML = '';

            if (!slides.length) {
                return;
            }

            var scopeCategory = slides[0].category || '';

            slides.forEach(function (s, i) {
                var src = s.src || '';
                var alt = s.alt || '';
                var label = s.categoryLabel || '';
                var cat = s.category || scopeCategory;

                var item = document.createElement('div');
                item.className = 'carousel-item' + (i === startIndex ? ' active' : '');
                item.setAttribute('data-category-label', label);
                if (cat) {
                    item.setAttribute('data-category', cat);
                }

                var slideImg = document.createElement('img');
                slideImg.className = 'd-block w-100 gallery-carousel-slide-img';
                slideImg.src = src;
                slideImg.alt = alt;
                slideImg.width = 1200;
                slideImg.height = 800;
                item.appendChild(slideImg);
                innerEl.appendChild(item);

                var thumbBtn = document.createElement('button');
                thumbBtn.type = 'button';
                thumbBtn.className = 'gallery-thumb' + (i === startIndex ? ' is-selected' : '');
                thumbBtn.setAttribute('data-bs-target', '#galleryCarousel');
                thumbBtn.setAttribute('data-bs-slide-to', String(i));
                thumbBtn.setAttribute('role', 'tab');
                thumbBtn.setAttribute('aria-selected', i === startIndex ? 'true' : 'false');
                thumbBtn.setAttribute('aria-label', alt || 'Slide ' + (i + 1));

                var thumbImg = document.createElement('img');
                thumbImg.src = src;
                thumbImg.alt = '';
                thumbImg.width = 120;
                thumbImg.height = 80;
                thumbImg.loading = 'lazy';
                thumbBtn.appendChild(thumbImg);
                thumbsEl.appendChild(thumbBtn);
            });

            carouselEl.classList.toggle('gallery-carousel--single', slides.length < 2);

            var car = new bootstrap.Carousel(carouselEl, {
                interval: false,
                wrap: true,
                ride: false
            });

            if (startIndex > 0) {
                car.to(startIndex);
            }

            syncModalMeta();
            markThumbSelected(getActiveSlideIndex(carouselEl));
        }

        function getCurrentSlideSrc() {
            var active = carouselEl.querySelector('.carousel-item.active img');
            return active ? active.getAttribute('src') : '';
        }

        function reconcileModalToFilter() {
            if (!modalEl.classList.contains('show')) {
                return;
            }
            var filter = getActiveFilterKey();
            var activeSlide = carouselEl.querySelector('.carousel-item.active');
            var scopeCat;
            if (filter !== 'all') {
                scopeCat = filter;
            } else {
                scopeCat = activeSlide ? activeSlide.getAttribute('data-category') || '' : '';
            }
            if (!scopeCat) {
                modal.hide();
                return;
            }
            var slides = getSlidesForCategory(scopeCat);
            if (!slides.length) {
                modal.hide();
                return;
            }
            var key = getCurrentSlideSrc();
            var newIdx = 0;
            if (key) {
                for (var j = 0; j < slides.length; j++) {
                    if (slides[j].src === key) {
                        newIdx = j;
                        break;
                    }
                }
            }
            populateCarouselFromSlides(slides, newIdx);
        }

        function openFromTrigger(btn) {
            var slides = parseSlidesFromButton(btn);
            if (!slides.length) {
                return;
            }
            populateCarouselFromSlides(slides, 0);
            modal.show();
        }

        document.querySelectorAll('.gallery-item__trigger').forEach(function (btn) {
            btn.addEventListener('click', function () {
                openFromTrigger(btn);
            });
        });

        carouselEl.addEventListener('slid.bs.carousel', function () {
            syncModalMeta();
            markThumbSelected(getActiveSlideIndex(carouselEl));
        });

        modalEl.addEventListener('hidden.bs.modal', function () {
            disposeGalleryCarousel();
            innerEl.innerHTML = '';
            thumbsEl.innerHTML = '';
            if (catEl) {
                catEl.textContent = '';
            }
            if (capEl) {
                capEl.textContent = '';
            }
            carouselEl.classList.remove('gallery-carousel--single');
        });

        document.querySelectorAll('.gallery-filter').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var filter = btn.getAttribute('data-filter');
                document.querySelectorAll('.gallery-filter').forEach(function (b) {
                    b.classList.toggle('is-active', b === btn);
                });
                document.querySelectorAll('.gallery-item').forEach(function (item) {
                    var cat = item.getAttribute('data-category');
                    item.hidden = filter !== 'all' && cat !== filter;
                });
                reconcileModalToFilter();
            });
        });

        document.addEventListener('keydown', function (e) {
            if (!modalEl.classList.contains('show')) {
                return;
            }
            var c = bootstrap.Carousel.getInstance(carouselEl);
            if (!c) {
                return;
            }
            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                c.prev();
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                c.next();
            }
        });

        var touchStartX = 0;
        carouselEl.addEventListener(
            'touchstart',
            function (e) {
                touchStartX = e.changedTouches[0].screenX;
            },
            { passive: true }
        );
        carouselEl.addEventListener(
            'touchend',
            function (e) {
                var c = bootstrap.Carousel.getInstance(carouselEl);
                if (!c || !modalEl.classList.contains('show')) {
                    return;
                }
                var dx = e.changedTouches[0].screenX - touchStartX;
                if (dx > 60) {
                    c.prev();
                } else if (dx < -60) {
                    c.next();
                }
            },
            { passive: true }
        );
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
