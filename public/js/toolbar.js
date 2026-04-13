/**
 * Royal Access — Frontend Accessibility Toolbar
 *
 * Vanilla JS, no jQuery dependency. ES5-compatible.
 * Preferences persisted via localStorage (GDPR-friendly, no cookies, no server calls).
 */
(function() {
	'use strict';

	var STORAGE_KEY = 'raccess_prefs';
	var config = window.raccessConfig || {};
	var i18n = config.i18n || {};
	var features = config.features || {};

	// Current state.
	var prefs = {};
	var panelOpen = false;

	/* ─── Preference persistence ─── */

	function loadPrefs() {
		try {
			var raw = localStorage.getItem(STORAGE_KEY);
			if (raw) {
				prefs = JSON.parse(raw);
			}
		} catch (e) {
			prefs = {};
		}
		if (typeof prefs !== 'object' || prefs === null) {
			prefs = {};
		}
		return prefs;
	}

	function savePrefs() {
		try {
			localStorage.setItem(STORAGE_KEY, JSON.stringify(prefs));
		} catch (e) {
			// localStorage full or unavailable — fail silently.
		}
	}

	/* ─── Feature application ─── */

	/**
	 * Apply or remove a CSS class feature on <body>.
	 */
	function applyFeature(feature, enabled) {
		var className = 'raccess-' + feature;
		if (enabled) {
			document.body.classList.add(className);
		} else {
			document.body.classList.remove(className);
		}
	}

	/**
	 * Apply font size level (1-5, 0 = default).
	 */
	function applyFontSize(level) {
		// Remove all existing levels.
		for (var i = 1; i <= 5; i++) {
			document.body.classList.remove('raccess-font-' + i);
		}
		if (level > 0 && level <= 5) {
			document.body.classList.add('raccess-font-' + level);
		}
	}

	/**
	 * Apply reading guide visibility.
	 */
	function applyReadingGuide(enabled) {
		var guide = document.getElementById('raccess-reading-guide');
		if (guide) {
			guide.style.display = enabled ? '' : 'none';
		}
	}

	/**
	 * Apply all saved preferences on page load.
	 */
	function applyAllPrefs() {
		loadPrefs();

		// Font size.
		if (prefs.fontSize) {
			applyFontSize(prefs.fontSize);
		}

		// Toggle features.
		var toggleFeatures = [
			'high-contrast', 'dark-mode', 'dyslexia-font', 'line-height',
			'letter-spacing', 'word-spacing', 'link-highlight', 'big-cursor',
			'stop-animations', 'hide-images', 'monochrome', 'highlight-focus'
		];

		for (var i = 0; i < toggleFeatures.length; i++) {
			var feat = toggleFeatures[i];
			if (prefs[feat]) {
				applyFeature(feat, true);
			}
		}

		// Reading guide.
		if (prefs['reading-guide']) {
			applyFeature('reading-guide', true);
			applyReadingGuide(true);
		}
	}

	/**
	 * Reset all features — remove all classes and clear prefs.
	 */
	function resetAll() {
		// Remove body classes.
		var classes = document.body.className.split(' ');
		for (var i = classes.length - 1; i >= 0; i--) {
			if (classes[i].indexOf('raccess-') === 0) {
				document.body.classList.remove(classes[i]);
			}
		}

		// Hide reading guide.
		applyReadingGuide(false);

		// Clear prefs.
		prefs = {};
		savePrefs();

		// Update toggle button states.
		updateToggleStates();
	}

	/* ─── Panel toggle ─── */

	function openPanel() {
		var panel = document.getElementById('raccess-panel');
		var toggle = document.getElementById('raccess-toggle');
		if (!panel || !toggle) return;

		panel.style.display = '';
		toggle.setAttribute('aria-expanded', 'true');
		toggle.setAttribute('aria-label', i18n.closeToolbar || 'Close accessibility toolbar');
		panelOpen = true;

		// Focus first interactive element.
		var firstBtn = panel.querySelector('button, [tabindex]');
		if (firstBtn) {
			firstBtn.focus();
		}
	}

	function closePanel() {
		var panel = document.getElementById('raccess-panel');
		var toggle = document.getElementById('raccess-toggle');
		if (!panel || !toggle) return;

		panel.style.display = 'none';
		toggle.setAttribute('aria-expanded', 'false');
		toggle.setAttribute('aria-label', i18n.openToolbar || 'Open accessibility toolbar');
		panelOpen = false;
		toggle.focus();
	}

	function togglePanel() {
		if (panelOpen) {
			closePanel();
		} else {
			openPanel();
		}
	}

	/* ─── Update UI state ─── */

	function updateToggleStates() {
		var buttons = document.querySelectorAll('.raccess-feature-toggle');
		for (var i = 0; i < buttons.length; i++) {
			var btn = buttons[i];
			var feat = btn.getAttribute('data-feature');
			if (feat) {
				// Prefs are stored under CSS class name, not feature key.
				var cssClass = featureClassMap[feat] || feat;
				var active = !!prefs[cssClass];
				btn.setAttribute('aria-pressed', active ? 'true' : 'false');
			}
		}
	}

	/* ─── Feature-to-class mapping ─── */

	var featureClassMap = {
		'contrast':       'high-contrast',
		'dark-mode':      'dark-mode',
		'dyslexia':       'dyslexia-font',
		'line-height':    'line-height',
		'letter-spacing': 'letter-spacing',
		'word-spacing':   'word-spacing',
		'links':          'link-highlight',
		'big-cursor':     'big-cursor',
		'animations':     'stop-animations',
		'images':         'hide-images',
		'monochrome':     'monochrome',
		'reading-guide':  'reading-guide',
		'focus':          'highlight-focus'
	};

	/* ─── Action handler ─── */

	function handleAction(action) {
		if (action === 'close') {
			closePanel();
			return;
		}

		if (action === 'reset') {
			resetAll();
			return;
		}

		if (action === 'font-increase') {
			var level = (prefs.fontSize || 0) + 1;
			if (level > 5) level = 5;
			prefs.fontSize = level;
			applyFontSize(level);
			savePrefs();
			return;
		}

		if (action === 'font-decrease') {
			var lvl = (prefs.fontSize || 0) - 1;
			if (lvl < 0) lvl = 0;
			prefs.fontSize = lvl;
			applyFontSize(lvl);
			savePrefs();
			return;
		}

		// Toggle features (action format: "toggle-{feature}")
		if (action.indexOf('toggle-') === 0) {
			var feature = action.substring(7); // Remove "toggle-"
			var cssClass = featureClassMap[feature];
			if (!cssClass) return;

			var isActive = !!prefs[cssClass];
			prefs[cssClass] = !isActive;

			applyFeature(cssClass, !isActive);

			// Special handling for reading guide.
			if (cssClass === 'reading-guide') {
				applyReadingGuide(!isActive);
			}

			// Mutually exclusive: high contrast + dark mode.
			if (cssClass === 'high-contrast' && !isActive) {
				prefs['dark-mode'] = false;
				applyFeature('dark-mode', false);
			} else if (cssClass === 'dark-mode' && !isActive) {
				prefs['high-contrast'] = false;
				applyFeature('high-contrast', false);
			}

			savePrefs();
			updateToggleStates();
		}
	}

	/* ─── Reading guide mouse tracking ─── */

	function initReadingGuide() {
		document.addEventListener('mousemove', function(e) {
			if (!prefs['reading-guide']) return;

			var guide = document.getElementById('raccess-reading-guide');
			if (guide) {
				guide.style.top = (e.clientY - 6) + 'px';
			}
		});
	}

	/* ─── Emergency reset ─── */

	/**
	 * Check for ?raccess-reset=1 URL parameter — emergency escape hatch.
	 * Clears all preferences immediately and removes the param from URL.
	 */
	function checkUrlReset() {
		try {
			var params = new URLSearchParams(window.location.search);
			if (params.get('raccess-reset') === '1') {
				localStorage.removeItem(STORAGE_KEY);
				prefs = {};
				// Clean URL without reload.
				params.delete('raccess-reset');
				var newUrl = window.location.pathname;
				var remaining = params.toString();
				if (remaining) {
					newUrl += '?' + remaining;
				}
				if (window.location.hash) {
					newUrl += window.location.hash;
				}
				window.history.replaceState({}, '', newUrl);
			}
		} catch (e) {
			// URLSearchParams not available (old browsers) — fail silently.
		}
	}

	/* ─── Event binding ─── */

	// Triple-Escape tracking for emergency reset.
	var escapeCount = 0;
	var escapeTimer = null;

	function bindEvents() {
		var toggle = document.getElementById('raccess-toggle');
		if (toggle) {
			toggle.addEventListener('click', function(e) {
				e.preventDefault();
				togglePanel();
			});
		}

		// Event delegation for all data-action buttons.
		var toolbar = document.getElementById('raccess-toolbar');
		if (toolbar) {
			toolbar.addEventListener('click', function(e) {
				var btn = e.target.closest('[data-action]');
				if (btn && btn !== toggle) {
					e.preventDefault();
					handleAction(btn.getAttribute('data-action'));
				}
			});
		}

		// Keyboard: Escape to close panel, triple-Escape to emergency reset.
		document.addEventListener('keydown', function(e) {
			if (e.key === 'Escape') {
				if (panelOpen) {
					closePanel();
				}

				// Triple-Escape within 1.5 seconds = emergency reset.
				escapeCount++;
				if (escapeTimer) {
					clearTimeout(escapeTimer);
				}
				if (escapeCount >= 3) {
					escapeCount = 0;
					resetAll();
				} else {
					escapeTimer = setTimeout(function() {
						escapeCount = 0;
					}, 1500);
				}
			}
		});

		// Click outside to close.
		document.addEventListener('click', function(e) {
			if (!panelOpen) return;
			var toolbarEl = document.getElementById('raccess-toolbar');
			if (toolbarEl && !toolbarEl.contains(e.target)) {
				closePanel();
			}
		});

		// Reading guide tracking.
		initReadingGuide();
	}

	/* ─── Init ─── */

	// Check for emergency reset URL param BEFORE applying preferences.
	checkUrlReset();

	function init() {
		applyAllPrefs();
		updateToggleStates();
		bindEvents();
	}

	// Apply preferences ASAP to avoid visual flash.
	// Body classes can be applied before DOMContentLoaded as long as <body> exists.
	if (document.body) {
		applyAllPrefs();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}

})();
