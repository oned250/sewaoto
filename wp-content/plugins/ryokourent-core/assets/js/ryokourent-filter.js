/**
 * Ryokourent Catalog Filter & Interaction Script
 *
 * Lightweight vanilla JavaScript (zero jQuery dependency) for client-side
 * category filtering, empty-state toggling, and auto-selecting motor units
 * on the booking form.
 *
 * @package Ryokourent_Core
 * @since   1.0.0
 */

(function () {
  'use strict';

  function initRyokouCatalog() {
    const catalogSection = document.getElementById('katalog-motor');
    if (!catalogSection) {
      return;
    }

    const filterButtons = catalogSection.querySelectorAll('.ryokou-filter-btn');
    const motorCards = catalogSection.querySelectorAll('.ryokou-motor-card');
    const emptyState = document.getElementById('ryokou-catalog-empty');
    const resetBtn = document.getElementById('ryokou-reset-filter-btn');

    // 1. Category Tab Filtering
    filterButtons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        const targetCategory = this.getAttribute('data-filter') || 'all';

        // Update active button state
        filterButtons.forEach(function (b) {
          b.classList.remove('active');
          b.setAttribute('aria-selected', 'false');
        });
        this.classList.add('active');
        this.setAttribute('aria-selected', 'true');

        let visibleCount = 0;

        motorCards.forEach(function (card) {
          const cardCategories = (card.getAttribute('data-category') || '').trim().split(/\s+/);
          const isMatch = targetCategory === 'all' || cardCategories.indexOf(targetCategory) !== -1;

          if (isMatch) {
            card.style.display = '';
            card.style.opacity = '0';
            card.style.transform = 'translateY(8px)';
            requestAnimationFrame(function () {
              card.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
              card.style.opacity = '1';
              card.style.transform = 'translateY(0)';
            });
            visibleCount++;
          } else {
            card.style.display = 'none';
          }
        });

        // Toggle Empty State
        if (emptyState) {
          emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
        }
      });
    });

    // Reset filter button inside empty state notice
    if (resetBtn) {
      resetBtn.addEventListener('click', function () {
        const allBtn = catalogSection.querySelector('.ryokou-filter-btn[data-filter="all"]');
        if (allBtn) {
          allBtn.click();
        }
      });
    }

    // 2. "Sewa Sekarang" Motor Preselection Handler
    const selectButtons = catalogSection.querySelectorAll('.ryokou-btn-select-motor');
    selectButtons.forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        const motorId = this.getAttribute('data-motor-id');
        const motorName = this.getAttribute('data-motor-name');

        // Locate potential booking form select inputs
        const targetSelect = document.querySelector('#rented_motor_id, select[name="rented_motor_id"], #ryokourent_booking_motor');
        const bookingForm = document.querySelector('#booking-form, #ryokourent-booking-form, .ryokou-booking-section');

        if (targetSelect && bookingForm) {
          e.preventDefault();

          // Try matching option by value (ID) or text (Title)
          let matched = false;
          for (let i = 0; i < targetSelect.options.length; i++) {
            const opt = targetSelect.options[i];
            if (opt.value === motorId || (motorName && opt.text.indexOf(motorName) !== -1)) {
              targetSelect.selectedIndex = i;
              matched = true;
              break;
            }
          }

          // Trigger change event to notify pricing/duration calculation engines
          const changeEvent = new Event('change', { bubbles: true });
          targetSelect.dispatchEvent(changeEvent);

          // Smooth scroll into booking form
          bookingForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      });
    });
  }

  // Initialize once DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initRyokouCatalog);
  } else {
    initRyokouCatalog();
  }
})();
