=== Royal Access ===
Contributors: royalpluginsteam
Tags: accessibility, wcag, ada, toolbar, a11y
Requires at least: 5.8
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.0.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Free WordPress accessibility toolbar with 14 features, WCAG code fixes, contrast checker & statement generator. Not an overlay — no account needed.

== Description ==

Royal Access is a free WordPress accessibility plugin with a frontend toolbar, automatic WCAG code fixes, a contrast checker, and an accessibility statement generator — no account required, no external dependencies, no SaaS subscription.

Built for WCAG 2.2, ADA, Section 508, and European Accessibility Act (EAA) awareness. Includes a bundled dyslexia-friendly font (OpenDyslexic) that most accessibility plugins don't offer.

**Not an overlay.** Unlike tools condemned by accessibility professionals and fined by the FTC, Royal Access does NOT reparse the DOM, does NOT interfere with screen readers, and does NOT inject AI-generated ARIA. It simply provides user controls that work WITH assistive technology.

= Frontend Accessibility Toolbar =

A small floating button that opens a panel with 14 accessibility controls:

* **Font Size** — Scale text up or down (5 levels)
* **High Contrast** — Black background with white/yellow text
* **Dark Mode** — Dark background with light text
* **Dyslexia Font** — OpenDyslexic font for easier reading
* **Line Height** — Increase line spacing for readability
* **Letter Spacing** — Increase letter spacing
* **Word Spacing** — Increase word spacing
* **Highlight Links** — Underline and outline all links
* **Big Cursor** — Larger mouse cursor
* **Stop Animations** — Disable all animations and transitions
* **Hide Images** — Dim images for distraction-free reading
* **Monochrome** — Convert page to greyscale
* **Reading Guide** — Horizontal highlight bar follows mouse
* **Highlight Focus** — Extra-visible focus indicators
* **Reset All** — Return to defaults

All user preferences are saved in the browser (localStorage) and persist across page loads — no cookies, no server calls, fully GDPR-friendly.

= Automatic Code-Level Fixes =

Applied server-side via WordPress hooks — no user action needed:

* **Skip-to-Content Link** — Keyboard-accessible link to skip navigation
* **Focus Indicators** — Consistent :focus-visible outlines on all elements
* **Viewport Zoom Fix** — Removes user-scalable=no so users can pinch-to-zoom
* **Read More Context** — Adds post title to "Read more" links for screen readers

= Admin Tools =

* **Accessibility Checklist** — 8-item checklist showing which accessibility features are active
* **Contrast Checker** — Verify your colors meet WCAG AA/AAA standards
* **Statement Generator** — Generate a starting-point accessibility statement template (WCAG 2.1, 2.2, or EAA) — must be reviewed and customized before publishing
* **Per-Feature Controls** — Enable/disable any toolbar feature individually

= Why Royal Access? =

* **100% free** — Every feature, no upsells, no locked Pro tier
* **No account required** — Install and activate, that's it
* **No external dependencies** — All assets are local, no CDN calls
* **Lightweight** — Under 60KB total frontend assets
* **Privacy-first** — No cookies, no tracking, localStorage only
* **WCAG-aware** — Built with WCAG 2.2 guidelines in mind (does not guarantee compliance)
* **EAA-aware** — Helps address some European Accessibility Act requirements
* **Not an overlay** — Works WITH screen readers, not against them
* **Keyboard navigable** — The toolbar itself is fully keyboard-accessible
* **Translation-ready** — All strings are internationalized

= Who Is This For? =

* **Small business owners** who need basic accessibility improvements without hiring a consultant
* **Freelancers and agencies** building client sites that need an accessibility toolbar out of the box
* **Non-profits and government sites** working toward Section 508 or ADA requirements
* **WooCommerce store owners** wanting to make their shop more inclusive
* **Bloggers and publishers** who want readers to adjust font size, contrast, and spacing
* **Anyone avoiding overlays** — if you've read the Overlay Fact Sheet and want a tool that works WITH assistive technology instead of against it

== Installation ==

1. Upload the `royal-access` folder to `/wp-content/plugins/`
2. Activate through the 'Plugins' menu in WordPress
3. Visit Royal Access > Settings to configure features
4. The accessibility toolbar will appear on your frontend automatically

== Frequently Asked Questions ==

= Is this an accessibility overlay? =

No. Royal Access is NOT an overlay. Overlays reparse the DOM, interfere with screen readers, and inject automated ARIA — practices condemned by over 1,000 accessibility professionals. Royal Access simply adds CSS classes to the page based on user preferences, working WITH existing assistive technology.

= Does this make my site WCAG compliant? =

No. Royal Access helps users customize their browsing experience and addresses some common automated accessibility issues (skip links, focus indicators, zoom restrictions). However, full WCAG compliance requires manual auditing of your content, semantic HTML structure, alternative text for media, proper form labels, and more. No plugin can make a site fully compliant on its own — that requires human review and ongoing effort. Royal Access is a helpful tool, not a compliance solution.

= Does this make my site ADA compliant? =

No. ADA compliance for websites involves meeting WCAG standards, which requires comprehensive manual auditing beyond what any plugin can provide. Royal Access helps improve the user experience for visitors with accessibility needs, but it is not a substitute for a professional accessibility audit. This plugin does not provide legal advice.

= Does it affect page load speed? =

Minimal impact. The toolbar CSS and JS together are under 15KB (gzipped). The OpenDyslexic font (~100KB) is only loaded when a user activates the dyslexia font feature.

= Does it use cookies? =

No. User preferences are stored in localStorage, which stays in the browser and is never sent to any server. This makes Royal Access GDPR-friendly by default.

= Does it work with caching plugins? =

Yes. Since the toolbar HTML is the same for all visitors and preferences are handled client-side via localStorage, it works perfectly with all caching plugins.

= Can I customize the toolbar colors? =

Yes. Go to Royal Access > Settings and use the color pickers to set background, text, and accent colors for the toolbar.

== Screenshots ==

1. Frontend accessibility toolbar (closed)
2. Frontend accessibility toolbar (open)
3. Admin dashboard with accessibility checklist
4. Settings page with feature toggles
5. Contrast checker tool
6. Accessibility statement generator

== Changelog ==

= 1.0.3 =
* Security: Added emergency reset via URL parameter (?raccess-reset=1) and triple-Escape shortcut
* Fix: Big Cursor now preserves pointer/text/resize cursors on interactive elements with CSS fallback
* Fix: Viewport zoom fix now targets only viewport meta tags instead of entire head output
* Fix: Hide Images excludes navigation and UI icons to prevent layout breakage
* Fix: Stop Animations excludes the accessibility toolbar itself
* Fix: Softened legal language — plugin does not claim WCAG/ADA/EAA compliance
* Fix: Added ADA compliance FAQ clarifying plugin limitations

= 1.0.2 =
* Fix: WordPress Plugin Check (PCP) compliance — all errors resolved
* Fix: Prefixed all global variables in view files (WordPress naming convention)
* Fix: Added intval() escaping on integer output in dashboard
* Fix: Added version parameter to inline style registrations
* Fix: Updated "Tested up to" to WordPress 6.9

= 1.0.1 =
* Fix: Removed fabricated organizational claims from accessibility statement generator (legal risk)
* Fix: Statement now includes disclaimer that template must be reviewed before publishing
* Fix: Changed "complete accessibility solution" to "accessibility toolkit" to avoid overpromising
* Fix: Renamed "Compliance Dashboard" to "Accessibility Checklist" to avoid implying legal compliance

= 1.0.0 =
* Initial release
* Frontend accessibility toolbar with 14 features
* Automatic code-level fixes (skip link, focus CSS, viewport, read more)
* Admin dashboard with accessibility checklist
* Contrast checker tool
* Accessibility statement generator
* OpenDyslexic font bundled (SIL Open Font License)
