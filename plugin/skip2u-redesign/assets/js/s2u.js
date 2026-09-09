/**
 * Skip 2 U Tas redesign: front-end behaviour.
 *
 * Vanilla on purpose, so it does not depend on the load order of the
 * theme's jQuery bundles.
 */
(function () {
  'use strict';

  /* Mobile menu ---------------------------------------------------------- */
  var toggle = document.getElementById('navToggle');
  var close  = document.getElementById('navClose');
  var menu   = document.getElementById('mobileMenu');

  function setMenu(open) {
    if (!menu || !toggle) return;
    menu.classList.toggle('is-open', open);
    toggle.classList.toggle('is-active', open);
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    document.body.classList.toggle('s2u-nav-open', open);
  }

  if (toggle && menu) {
    toggle.addEventListener('click', function () {
      setMenu(!menu.classList.contains('is-open'));
    });
    menu.addEventListener('click', function (e) {
      if (e.target === menu) setMenu(false);
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') setMenu(false);
    });
  }
  if (close) close.addEventListener('click', function () { setMenu(false); });

  /* Mobile submenus ------------------------------------------------------ */
  var mobileNav = menu ? menu.querySelector('nav') : null;
  if (mobileNav) {
    mobileNav.querySelectorAll('.has-sub > a').forEach(function (link) {
      var sub = link.parentNode.querySelector('.sub');
      if (!sub) return;
      var btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 's2u-sub-toggle';
      btn.setAttribute('aria-label', 'Toggle submenu');
      btn.addEventListener('click', function () {
        var open = sub.hasAttribute('hidden');
        if (open) { sub.removeAttribute('hidden'); } else { sub.setAttribute('hidden', ''); }
        btn.classList.toggle('is-open', open);
      });
      sub.setAttribute('hidden', '');
      link.parentNode.insertBefore(btn, sub);
    });
  }

  /* Booking form quantity stepper ---------------------------------------- */
  /* The child theme binds its own .qty-btn handler that also recalculates
     the total. This only runs when that handler is absent, so the control
     still works if the form is rendered outside the booking flow. */
  if (!window.jQuery || !document.getElementById('skip_booking_form')) {
    document.querySelectorAll('.qty').forEach(function (box) {
      var input = box.querySelector('.qty-input');
      if (!input) return;
      box.querySelectorAll('.qty-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
          var v = parseInt(input.value, 10) || 1;
          input.value = btn.classList.contains('minus') ? Math.max(1, v - 1) : v + 1;
        });
      });
    });
  }
})();
