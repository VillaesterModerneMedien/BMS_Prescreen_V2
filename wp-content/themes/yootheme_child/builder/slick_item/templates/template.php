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

?>

<div class="bmsTopSlider" style="background-color:<?= $props['backgroundColor'] ?>">
    <div class="sliderContent">
        <span class="sliderTopHeadline"><?= $props['topHeadline']; ?></span>
        <img class="sliderTopLogo" src="<?= $props['logo']; ?>" />
        <h3 class="sliderTopTitle"><?= $props['title']; ?></h3>
        <a class="uk-button uk-button-default sliderButton" href="<?= $props['link']; ?>"><?= $props['buttonText']; ?></a>
    </div>
</div>





