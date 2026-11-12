=== Dark Admin Mode ===
Contributors: dmchale
Tags: dark mode, admin, color scheme, dashboard, accessibility
Requires at least: 7.0
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.1.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A dark Administration Color Scheme for wp-admin. Each user opts in from their profile; nothing is forced on anyone.

== Description ==

Dark Admin Mode adds a dark choice to WordPress's built-in Administration Color Scheme picker. It always renders dark, whatever the operating system or browser preference.

It styles core admin surfaces: dashboard, list tables, forms, settings, Site Health, the plugin and theme browsers, the theme/plugin file editors, Fonts and Connectors, and the block editor's controls (not the content canvas).

* Activating the plugin selects the scheme for you, the activating user. Nobody else is changed.
* Other users can choose it under Users → Profile → Administration Color Scheme.
* It uses WordPress's own per-user preference: no settings page, cookies or local storage.
* Updates come from the plugin's GitHub Releases, shown in Dashboard → Updates like any other plugin. That check is the only external request it makes.
* Deactivating gives everyone still using it their previous scheme back (on multisite, when network-deactivated).

Not styled: the block editor's content canvas, the public site, and third-party plugin screens that bring their own styling.

== Installation ==

1. Download `dark-admin-mode.zip` from the latest release at https://github.com/dmchale/dark-admin-mode/releases and upload it under Plugins → Add New → Upload Plugin. (Use that file, not GitHub's "Source code" zips: those install under the wrong folder name.)
2. Activate. The scheme is selected for your account.
3. Other users can select it under Users → Profile.

Activating from WP-CLI without `--user` changes no one's preference.

== Frequently Asked Questions ==

= How do I turn it off for just me? =

Pick another scheme under Users → Profile → Administration Color Scheme.

= What happens when I deactivate the plugin? =

Every user still on Dark Admin Mode goes back to the scheme they used before (or WordPress's default, if that scheme no longer exists). Users who had already switched to another scheme are left alone.

= Where do updates come from? =

GitHub Releases, not WordPress.org. The plugin checks https://github.com/dmchale/dark-admin-mode about twice a day and WordPress offers new versions the usual way. Its `Update URI` header keeps WordPress from ever replacing it with an unrelated WordPress.org plugin of the same name.

= Does it follow my system's dark/light setting? =

No. Once selected it is always dark.

== Changelog ==

= 1.1.2 =
* Admin notices keep their colored edges again (success, warning, error, info).
* Color scheme picker: the third swatch is visible when selected, and palettes have an outline so dark swatches stand out.

= 1.1.1 =
* Block inserter: dark search strip and Media/Patterns panel.
* Block inserter: visible outlines around media previews, so dark images stand out on the dark panel.

= 1.1.0 =
* Block editor: dark sidebars, inserter, menus, modals and welcome guides, including the WordPress 7 design tokens the editor sets inline.
* Block editor: the content area (canvas, block toolbar, insertion line, editor notices) is left exactly as core and your theme draw it.
* Readable link-style values in the editor sidebar (status, date, slug, author and so on).
* Revisions: dark Compare Revisions screen and editor revisions panel, with readable diff colors.

= 1.0.0 =
* Initial release.
