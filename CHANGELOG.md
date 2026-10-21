# Changelog

All notable changes to Media Focus Point are documented here.

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).
This project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [Unreleased]

---

## [1.1.0] — 2026-10-21

### Changed

- Steps aside when Media Hub Pro 1.8+ applies focus points itself, so a page is never rewritten twice. It takes over again if the Media Hub's version is turned off with `kirbycode.media-hub.focus-frontend`.
- README: positioned as the add-on for sites without the Media Hub.

---

## [1.0.0] — 2026-10-21

### Added

- Applies each image's focus point (Kirby's native `focus` field) as `object-position` to every `<img>` on the rendered page. It works for originals, thumbs, `srcset` and `data-src`, whichever template, snippet or block wrote the tag.
- An inline `object-position` on an `<img>` is never overridden; other inline styles are kept.
- Accepts every focus format Kirby does: `X% Y%`, keywords such as `top left`, and the former Focus plugin's JSON.
- PHP helpers `FocusPoint::position()` and `FocusPoint::style()` for backgrounds and JSON.
- Option `kirbycode.media-focus-point.enabled` to turn the rewriting off.
