<div align="center">

# Royal Access

**Free WordPress accessibility toolbar with 14 features, WCAG code fixes, contrast checker & statement generator.**

[![WordPress](https://img.shields.io/badge/WordPress-5.8+-21759B?style=flat-square&logo=wordpress)](https://wordpress.org/plugins/royal-access/)
[![PHP](https://img.shields.io/badge/PHP-7.4+-777BB4?style=flat-square&logo=php)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-GPLv2-blue?style=flat-square)](https://www.gnu.org/licenses/gpl-2.0.html)
[![Version](https://img.shields.io/badge/Version-1.0.3-C9A227?style=flat-square)](https://royalplugins.com/royal-access/)
[![WP.org](https://img.shields.io/badge/Download-WordPress.org-21759B?style=flat-square&logo=wordpress)](https://wordpress.org/plugins/royal-access/)

[Download from WordPress.org](https://wordpress.org/plugins/royal-access/) · [Documentation](https://royalplugins.com/royal-access/) · [Royal Plugins](https://royalplugins.com)

</div>

---

Royal Access is a free WordPress accessibility plugin that adds a floating toolbar to your site with 14 user-facing features, 4 automatic code-level fixes, a contrast checker, and an accessibility statement generator. No account required, no external dependencies, no SaaS subscription.

**Not an overlay.** Unlike tools condemned by accessibility professionals and fined by the FTC, Royal Access does NOT reparse the DOM, does NOT interfere with screen readers, and does NOT inject AI-generated ARIA. It simply provides user controls that work WITH assistive technology.

## Features

### Frontend Accessibility Toolbar

A floating button that opens a panel with 14 accessibility controls:

- **Font Size** — Scale text up or down (5 levels)
- **High Contrast** — Black background with white/yellow text
- **Dark Mode** — Dark background with light text
- **Dyslexia Font** — OpenDyslexic font for easier reading
- **Line Height** — Increase line spacing for readability
- **Letter Spacing** — Increase letter spacing
- **Word Spacing** — Increase word spacing
- **Highlight Links** — Underline and outline all links
- **Big Cursor** — Larger mouse cursor
- **Stop Animations** — Disable all animations and transitions
- **Hide Images** — Dim images for distraction-free reading
- **Monochrome** — Convert page to greyscale
- **Reading Guide** — Horizontal highlight bar follows mouse
- **Highlight Focus** — Extra-visible focus indicators
- **Reset All** — Return to defaults

All preferences are saved in localStorage — no cookies, no server calls, fully GDPR-friendly.

### Automatic Code-Level Fixes

Applied server-side via WordPress hooks — no user action needed:

- **Skip-to-Content Link** — Keyboard-accessible link to skip navigation
- **Focus Indicators** — Consistent `:focus-visible` outlines on all elements
- **Viewport Zoom Fix** — Removes `user-scalable=no` so users can pinch-to-zoom
- **Read More Context** — Adds post title to "Read more" links for screen readers

### Admin Tools

- **Accessibility Checklist** — 8-item checklist showing which features are active
- **Contrast Checker** — Verify your colors meet WCAG AA/AAA standards
- **Statement Generator** — Generate an accessibility statement template (WCAG 2.1, 2.2, or EAA)
- **Per-Feature Controls** — Enable/disable any toolbar feature individually

## Installation

### From WordPress.org (Recommended)
1. Go to **Plugins > Add New** in your WordPress admin
2. Search for "Royal Access"
3. Click **Install Now** and then **Activate**

### Manual Installation
1. Download from [WordPress.org](https://wordpress.org/plugins/royal-access/)
2. Upload the `royal-access` folder to `/wp-content/plugins/`
3. Activate through the **Plugins** menu

## Requirements

- WordPress 5.8+
- PHP 7.4+

## Why Royal Access?

- **100% free** — Every feature, no upsells, no locked Pro tier
- **No account required** — Install and activate, that's it
- **No external dependencies** — All assets are local, no CDN calls
- **Lightweight** — Under 60KB total frontend assets
- **Privacy-first** — No cookies, no tracking, localStorage only
- **Not an overlay** — Works WITH screen readers, not against them
- **Keyboard navigable** — The toolbar itself is fully keyboard-accessible
- **Translation-ready** — All strings are internationalized

## Free WordPress Tools

- [WordPress Security Scanner](https://royalplugins.com/tools/wordpress-security-scanner/) — Scan your WordPress site for vulnerabilities
- [Schema Validator](https://royalplugins.com/tools/schema-validator/) — Validate your structured data markup
- [SERP Preview](https://royalplugins.com/tools/serp-preview/) — Preview how your pages look in Google search results
- [Meta Tag Checker](https://royalplugins.com/tools/meta-tag-checker/) — Analyze your page meta tags

## About Royal Plugins

Royal Access is built by [Royal Plugins](https://royalplugins.com) — lightweight, security-first WordPress plugins built with clean code.

Check out our other free plugins:
- [GuardPress](https://github.com/royalplugins/guardpress) — WordPress security hardening
- [SiteVault Lite](https://wordpress.org/plugins/sitevault-backup-restore-migration/) — Free WordPress backups with one-click restore
- [RoyalComply](https://github.com/royalplugins/royal-comply) — Cookie compliance with real script blocking
- [Royal Ledger](https://github.com/royalplugins/royal-ledger) — Track site costs and store license keys
- [Royal Links](https://wordpress.org/plugins/royal-links/) — Link management and tracking
- [Royal SMTP](https://github.com/royalplugins/royal-smtp) — Simple, reliable SMTP email
- [Royal MCP](https://wordpress.org/plugins/royal-mcp/) — AI platform integration for WordPress

## Disclaimer

Royal Access is provided as-is without warranty of any kind. While it helps improve your site's accessibility, it does not guarantee full WCAG, ADA, or EAA compliance. A comprehensive accessibility audit by a qualified professional is recommended.

## License

Royal Access is licensed under the [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html).
