# Media Focus Point

Focus point add-on for Kirby sites without [Kirby Media Hub Pro](https://github.com/logitechshiv/kirby-media-hub-pro).

> **Using Media Hub Pro 1.8 or later?** You don't need this add-on: the Media Hub has the same feature built in, in Free and Pro. If both are installed, this add-on steps aside automatically. See [Using with Media Hub Pro](#using-with-media-hub-pro).

When an editor sets a focus point on an image, every `<img>` of that image on your site keeps that spot in view. It works with any template, snippet or block. You don't have to change your markup.

```html
<!-- your template outputs -->
<img src="/media/pages/blog/a1b2c3-1712345678/team-800x.webp" class="w-full h-64 object-cover">

<!-- visitors receive -->
<img src="/media/pages/blog/a1b2c3-1712345678/team-800x.webp" class="w-full h-64 object-cover" style="object-position: 62.5% 30%">
```

## How it works

1. **Set a focus point.** In Kirby's own file view, open an image and set its focus point. With the Media Hub, click the spot in the Media Hub or the picker field instead. Either way it is stored in Kirby's native `focus` field.
2. **The add-on applies it.** After Kirby renders a page, the add-on checks every `<img>`. If the `src` (or `data-src`) points to an image with a focus point, it adds `object-position: X% Y%`. This works for the original file and for every thumb or `srcset` size.
3. **CSS does the rest.** With `object-fit: cover`, the browser crops around that point at every screen size.

The add-on doesn't change images without a focus point, so your stylesheet stays in charge of them.

## Requirements

- Kirby 5
- PHP 8.2+
- Nothing else. Focus points are set in Kirby's file view.

## Installation

### Download

Download this repository and copy it to `site/plugins/media-focus-point`.

### Git submodule

```bash
git submodule add https://github.com/logitechshiv/media-focus-point.git site/plugins/media-focus-point
```

No configuration is needed. Once installed, it runs on every page.

## Guidelines for templates

**Use `object-fit: cover` on cropped images.** A focus point only matters when the image is cropped to fit its box. Images that show in full (`object-fit: contain`, or natural size) don't change.

```html
<div class="aspect-video">
  <img src="<?= $image->thumb(['width' => 1200])->url() ?>" class="w-full h-full object-cover" alt="">
</div>
```

**Prefer width-only thumbs.** Thumbs like `thumb(['width' => 800])` and `srcset()` keep the whole image, and the browser crops around the focus point. You don't need fixed crops for every breakpoint.

**Server-side crops already use the focus point.** `$image->crop(400, 300)` and thumbs with `'crop' => true` cut around the focus point on the server, because Kirby reads the same `focus` field.

**Override one image when you need to.** If an `<img>` already has an inline `object-position`, the add-on leaves it alone:

```html
<img src="…" class="object-cover" style="object-position: center top">
```

**Use a stylesheet position as the default.** A stylesheet rule such as `.hero img { object-position: 70% 40%; }` keeps applying to images without a focus point. As soon as an editor sets one, the inline focus point wins. Don't use `!important` on these rules, or editors can't override them.

**Existing inline styles are kept.** The focus point is appended to them:

```html
<img src="…" style="opacity: .8"> → <img src="…" style="opacity: .8; object-position: 40% 25%">
```

## Options

```php
// site/config/config.php
return [
    // Turn the automatic <img> rewriting off (default: true)
    'kirbycode.media-focus-point.enabled' => false,
];
```

## PHP helpers

Use these for markup the add-on can't reach, such as a CSS `background-image` or JSON for a JavaScript slider:

```php
use Kirbycode\MediaFocusPoint\FocusPoint;

FocusPoint::position($image); // "62.5% 30%" or null
FocusPoint::style($image);    // "object-position: 62.5% 30%" or ''
```

```php
<div class="hero" style="background-image: url(<?= $image->url() ?>); background-position: <?= FocusPoint::position($image) ?? 'center' ?>">
```

The add-on accepts the same focus values Kirby does: `X% Y%`, keywords like `top left`, and the JSON format of the former Focus plugin.

## Limitations

- **CSS backgrounds.** CSS `background-image` isn't rewritten. Use `FocusPoint::position()`, shown above.
- **Content loaded with JavaScript.** Images added after the page loads (AJAX, "load more", client-side rendering) don't go through Kirby's page render, so they don't get the focus point. Include `FocusPoint::position()` in the JSON your endpoint returns.
- **`<picture>` with different files per breakpoint.** The `<img>` gets the focus point of its own `src` file, even when a `<source>` swaps in a different image.

## Using with Media Hub Pro

Media Hub Pro 1.8 and later include this feature, in Free and Pro, along with click-to-set focus points and the `$file->focusPosition()` / `$file->focusStyle()` helpers. On those sites you can remove this add-on.

If both are installed, this add-on does nothing while the Media Hub applies focus points. If you turn the Media Hub's version off (`'kirbycode.media-hub.focus-frontend' => false`), the add-on takes over again. Set `'kirbycode.media-focus-point.enabled' => false` too if you want neither.

With Media Hub Pro 1.7, this add-on is needed to apply focus points automatically.

## Performance

The add-on only looks up the images that are on the rendered page, and each URL only once. The rewrite happens before Kirby's page cache, so with caching enabled it runs once per cached page, not on every visit.

## License

Proprietary. See [LICENSE](LICENSE).

© 2026 [Shivlal Sheladiya](https://kirbycode.com)
