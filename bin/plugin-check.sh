#!/usr/bin/env bash
# Builds the release copy (working tree minus .distignore), runs wordpress.org's Plugin Check
# on it, and, only if it reports "No errors found", zips it as build/dark-admin-mode.zip with a dark-admin-mode/
# top folder, ready to install or attach to a GitHub Release. Run inside ddev.
# Skips plugin_updater: that check enforces wordpress.org's no-self-updates rule, and this
# plugin deliberately updates from GitHub Releases (see includes/class-updater.php).
# Skips lib/: bundled third-party code (Plugin Update Checker), vetted when updated, not edited.
# `wp plugin check` exits 0 even with findings, so the zip is gated on its summary line;
# passing a non-table --format skips the zip.
# Extra arguments pass through to `wp plugin check`, e.g. --format=csv.
set -euo pipefail
cd "$(dirname "$0")/.."
repo=$(pwd)

slug=dark-admin-mode
build_root=/tmp/plugin-check-build
build=$build_root/$slug
rm -rf "$build"
mkdir -p "$build"
rsync -a --exclude-from=.distignore ./ "$build/"

echo "Release copy: $build"
find "$build" -type f -not -path "$build/lib/*" | sed "s|^$build/|  |" | sort
echo "  lib/: $(find "$build/lib" -type f | wc -l) bundled files"
report=$(wp plugin check "$build" --slug="$slug" --exclude-checks=plugin_updater --exclude-directories=lib "$@" 2>&1) || true
echo "$report"
if ! grep -q 'No errors found' <<<"$report"; then
	echo "Plugin Check reported findings (or used a non-table format): no zip built." >&2
	exit 1
fi

mkdir -p "$repo/build"
rm -f "$repo/build/$slug.zip"
( cd "$build_root" && zip -qrX "$repo/build/$slug.zip" "$slug" )
echo "Release zip: build/$slug.zip"
