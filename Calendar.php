<?php
/** @var array $texts */
$calPopupTitle = htmlspecialchars($texts['booking_calendar_popup_title'] ?? 'Bookings for {date}', ENT_QUOTES, 'UTF-8');
$calPopupEmpty = htmlspecialchars($texts['booking_calendar_empty'] ?? 'No bookings', ENT_QUOTES, 'UTF-8');
?>
<!-- calendar container -->
<div id="calendar" class="calendar" data-popup-title="<?= $calPopupTitle; ?>" data-popup-empty="<?= $calPopupEmpty; ?>"></div>
<div id="popup"></div>

<!-- calendar logic -->
<script>
    // Pick a JS locale that matches the active site language so month / day names localize.
    (function () {
        const langCode = document.documentElement.getAttribute('data-lang') || 'lv';
        const localeMap = { lv: 'lv-LV', en: 'en-GB' };
        window.calendarLocale = localeMap[langCode] || 'en-GB';
    })();

    // Build localized short weekday labels (Sunday-first to match the existing grid layout).
    function buildWeekdayLabels(locale) {
        // 2024-01-07 was a Sunday; iterate seven days from there.
        const labels = [];
        for (let i = 0; i < 7; i++) {
            const d = new Date(Date.UTC(2024, 0, 7 + i));
            const label = d.toLocaleString(locale, { weekday: 'short', timeZone: 'UTC' });
            // Tidy up trailing punctuation that some locales return (e.g. "Sv.").
            labels.push(label.replace(/\.$/, ''));
        }
        return labels;
    }

    function escapeHtml(text) {
        const d = document.createElement('div');
        d.textContent = text == null ? '' : String(text);
        return d.innerHTML;
    }

    function showPopup(content) {
        const popup = document.getElementById('popup');

        popup.innerHTML = `
            <div style="background: #ffffff; padding: 30px; border-radius: 20px; max-width: 500px; width: 90%; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); position: relative; color: #000000;">
                <button id="closePopup" style="position: absolute; top: 15px; right: 20px; background: #e74c3c; color: white; border: none; border-radius: 50%; width: 30px; height: 30px; font-size: 18px; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>
                ${content}
            </div>
        `;

        popup.style.display = 'flex';

        document.getElementById('closePopup').addEventListener('click', function () {
            popup.style.display = 'none';
        });
    }
    

    document.addEventListener('DOMContentLoaded', function () {
        const calendar = document.getElementById('calendar');
        const locale = window.calendarLocale || 'en-GB';
        const langCode = document.documentElement.getAttribute('data-lang') || 'lv';
        const popupTitleTpl = calendar.getAttribute('data-popup-title') || 'Bookings for {date}';
        const popupEmpty = calendar.getAttribute('data-popup-empty') || 'No bookings';
        const weekdayLabels = buildWeekdayLabels(locale);

        const today = new Date();
        let year = today.getFullYear();
        let month = today.getMonth();

        let reservedDates = [];

        fetch('../Contact/getReservedDates.php')
            .then(response => response.json())
            .then(data => {
                reservedDates = data;
                renderCalendar(year, month);
        });

        function renderCalendar(y, m) {
            const firstDay = new Date(y, m, 1).getDay();
            const daysInMonth = new Date(y, m + 1, 0).getDate();

            // header with dropdowns
            let html = `
            <div class="calendar-header">
                <button class="nav-btn" onclick="changeMonth(-1)">&#10094;</button>
                <select id="month-select" class="calendar-select">
                    ${Array.from({ length: 12 }, (_, i) =>
                `<option value="${i}" ${i === m ? 'selected' : ''}>${new Date(0, i).toLocaleString(locale, { month: 'long' })}</option>`
            ).join('')}
                </select>
                <select id="year-select" class="calendar-select">
                    ${Array.from({ length: 9 }, (_, i) => {
                const yVal = today.getFullYear() - 4 + i;
                return `<option value="${yVal}" ${yVal === y ? 'selected' : ''}>${yVal}</option>`;
            }).join('')}
                </select>
                <button class="nav-btn" onclick="changeMonth(1)">&#10095;</button>
            </div>
            <div class="calendar-grid">
                ${weekdayLabels.map(label => `<div>${label}</div>`).join('')}
        `;

            for (let i = 0; i < firstDay; i++) html += '<div class="empty"></div>';

            for (let day = 1; day <= daysInMonth; day++) {
                const dateStr = `${y}-${String(m + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const isReserved = reservedDates.includes(dateStr);
                const isToday = day === today.getDate() && m === today.getMonth() && y === today.getFullYear();

                html += `<div class="calendar-day${isToday ? ' today' : ''}${isReserved ? ' reserved' : ''}" data-date="${dateStr}">${day}</div>`;
            }

            html += '</div>';
            calendar.innerHTML = html;

            document.querySelectorAll('.calendar-day').forEach(day => {
                day.addEventListener('click', function() {
                    const selectedDate = this.getAttribute('data-date');

                    fetch('../Contact/BookingInformation.php?date=' + encodeURIComponent(selectedDate) + '&lang=' + encodeURIComponent(langCode))
                        .then(response => response.json())
                        .then(data => {
                            const heading = popupTitleTpl.split('{date}').join(selectedDate);
                            let content = '<h3>' + escapeHtml(heading) + '</h3>';
                            if (data.length === 0) {
                                content += '<p>' + escapeHtml(popupEmpty) + '</p>';
                            } else {
                                data.forEach(booking => {
                                    content += '<p><strong>' + escapeHtml(booking.item_name) + '</strong><br>' +
                                        escapeHtml(booking.start_date) + ' → ' + escapeHtml(booking.end_date) + '</p><hr>';
                                });
                            }

                            showPopup(content);
                        });
                });
            });
            
        }

        // month nav buttons
        window.changeMonth = function(offset) {
            month += offset;
            if (month > 11) {
                month = 0;
                year++;
            } else if (month < 0) {
                month = 11;
                year--;
            }
            renderCalendar(year, month);
        };

        // dropdown listeners
        document.addEventListener('change', function (e) {
            if (e.target.id === 'month-select') {
                month = parseInt(e.target.value);
                renderCalendar(year, month);
            }
            if (e.target.id === 'year-select') {
                year = parseInt(e.target.value);
                renderCalendar(year, month);
            }
        });

        renderCalendar(year, month);
    });
</script>
