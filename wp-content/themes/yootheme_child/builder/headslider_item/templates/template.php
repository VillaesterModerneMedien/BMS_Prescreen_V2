<?php

// Display
foreach (['title', 'meta', 'content', 'link'] as $key) {
    if (!$element["show_{$key}"]) { $props[$key] = ''; }
}

$el = $this->el($props['link'] && $element['overlay_link'] ? 'a' : 'div', [

    'class' => [
        'uk-cover-container',
        'uk-transition-toggle' => $isTransition = $element['overlay_hover'] || $element['image_transition'],
    ],

    'style' => [
        'background-color: {media_background};',
    ],

    'tabindex' => $isTransition ? 0 : null,
]);

$overlay = $this->el('div', [

    'class' => [
        'uk-{overlay_style}',
        'uk-transition-{overlay_transition} {@overlay_hover} {@overlay_cover}',

        'uk-position-cover {@overlay_cover}',
        'uk-position-{overlay_margin} {@overlay_cover}',
    ],

]);

$content = $this->el('div', [

    'class' => [
        $element['overlay_style'] ? 'uk-overlay' : 'uk-panel',
        'uk-padding {@!overlay_padding} {@!overlay_style}',
        'uk-padding-{!overlay_padding: |none}',
        'uk-padding-remove {@overlay_padding: none} {@overlay_style}',
        'uk-width-{overlay_maxwidth} {@!overlay_position: top|bottom}',
        'uk-position-{!overlay_position: .*center.*} [uk-position-{overlay_margin} {@overlay_style}]',

        'uk-transition-{overlay_transition} {@overlay_hover}' => !$element['overlay_transition_background'] || !$element['overlay_cover'],
        'uk-margin-remove-first-child',
    ],

]);

if (!$element['overlay_cover']) {
    $content->attr($overlay->attrs);
}

// Position
$center = $this->el('div', [

    'class' => [
        'uk-position-{overlay_position: .*center.*} [uk-position-{overlay_margin} {@overlay_style}]',
    ],

]);



?>

<?= $el($element, $attrs) ?>

        <div class="bmsTopSlider uk-container" style="background-color:<?= $props['backgroundColor'] ?>">
            <div class="sliderContent">
                <span class="sliderTopHeadline"><?= $props['topHeadline']; ?></span>
                <img class="sliderTopLogo" src="<?= $props['logo']; ?>" />
                <h3 class="sliderTopTitle"><?= $props['title']; ?></h3>
                <a class="uk-button uk-button-default sliderButton" href="<?= $props['link']; ?>" uk-scroll><?= $props['buttonText']; ?></a>
            </div>
        </div>

<?= $el->end() ?>

