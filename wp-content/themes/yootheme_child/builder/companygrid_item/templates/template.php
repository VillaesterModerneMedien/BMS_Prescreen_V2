<?php

// Image
//$props['image'] = $this->render("{$__dir}/template-image", compact('props'));

// Image
$image = $this->el('image', [

    'class' => [
        'el-image',
        'personImageRound'
    ],

    'src' => $props['image'],
    'alt' => $props['image_alt'],
    'width' => $element['image_width'],
    'height' => $element['image_height'],
    'uk-img' => 'target: !.uk-slideshow-items',
    'uk-cover' => false,
    'thumbnail' => true,
]);

// Panel/Card
$el = $this->el($props['link'] && $element['panel_link'] ? 'a' : 'div', [

    'class' => [
        'el-item',
        'uk-margin-auto uk-width-{item_maxwidth}',
        'uk-panel {@!panel_style}',
        'uk-card uk-{panel_style} [uk-card-{panel_size}]',
        'uk-card-hover {@!panel_style: |card-hover} {@panel_link}' => $props['link'],
        'uk-card-body {@panel_style} {@!has_panel_card_image}',
        'uk-margin-remove-first-child' => (!$element['panel_style'] && !$element['has_content_padding']) || ($element['panel_style'] && !$element['has_panel_card_image']),
        'uk-flex {@panel_style} {@has_panel_card_image} {@image_align: left|right}', // Let images cover the card height if the cards have different heights
        'uk-transition-toggle {@image_transition} {@panel_link}' => $props['image'],
    ],

]);

// Image align
$grid = $this->el('div', [

    'class' => [
        'uk-child-width-expand',
        $element['panel_style'] && $element['has_panel_card_image']
            ? 'uk-grid-collapse uk-grid-match'
            : ($element['image_grid_column_gap'] == $element['image_grid_row_gap']
                ? 'uk-grid-{image_grid_column_gap}'
                : '[uk-grid-column-{image_grid_column_gap}] [uk-grid-row-{image_grid_row_gap}]'),
        'uk-flex-middle {@image_vertical_align}',
    ],

    'uk-grid' => true,
]);
$company = $props['title'];
$company = preg_replace("/[^A-Za-z0-9 ]/", '_', $company);
$company = str_replace(' ', '_', $company);
$company = strtolower($company);
?>

<div class="companyGridSingleContainer">
    <a class="companyGridLink" data-id="<?= $props['id'];?>"  data-company="<?= $company;?>" data-headline="<?= $props['headline'];?>" data-logo="<?= $props['image'];?>" data-joblink="<?= $props['jobLink'];?>" data-webseiteLink="<?= $props['webseiteLink'];?>" data-headline="<?= $props['headline'];?>" data-logo="<?= $props['image'];?>" >
        <div class="hiddenContent"><?= $props['content'];?></div>
        <img src="<?= $props['image'];?>" />
    </a>
</div>




