<?php

use Kirby\Cms\App;
use Kirbycode\MediaFocusPoint\FocusPoint;

/**
 * Media Focus Point — add-on for Kirby Media Hub Pro.
 *
 * Applies each image's focus point to every <img> on the rendered page,
 * whichever template, snippet or block wrote it. See README.md.
 */

if (!class_exists(FocusPoint::class)) {
	require_once __DIR__ . '/src/FocusPoint.php';
}

App::plugin('kirbycode/media-focus-point', [
	'options' => [
		// false turns the automatic <img> rewriting off
		'enabled' => true,
	],

	'hooks' => [
		'page.render:after' => function (string $contentType, string $html) {
			if ($contentType !== 'html' || App::instance()->option('kirbycode.media-focus-point.enabled', true) === false) {
				return $html;
			}

			return FocusPoint::apply($html);
		},
	],
]);
