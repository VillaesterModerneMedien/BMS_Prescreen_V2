<?php

// Resets
if ($props['panel_link']) {
    $props['title_link'] = '';
    $props['image_link'] = '';
}

$el = $this->el('div', [

    'uk-filter' => $tags ? [
        'target: .js-filter;',
        'animation: {filter_animation};',
    ] : false,

]);

// Grid
$grid = $this->el('div', [

    'class' => [
        'js-filter' => $tags,
        'uk-child-width-[1-{@!grid_default: auto}]{grid_default}',
        'uk-child-width-[1-{@!grid_small: auto}]{grid_small}@s',
        'uk-child-width-[1-{@!grid_medium: auto}]{grid_medium}@m',
        'uk-child-width-[1-{@!grid_large: auto}]{grid_large}@l',
        'uk-child-width-[1-{@!grid_xlarge: auto}]{grid_xlarge}@xl',
        'uk-flex-center {@grid_column_align}',
        'uk-flex-middle {@grid_row_align}',
        $props['grid_column_gap'] == $props['grid_row_gap'] ? 'uk-grid-{grid_column_gap}' : '[uk-grid-column-{grid_column_gap}] [uk-grid-row-{grid_row_gap}]',
        'uk-grid-divider {@grid_divider} {@!grid_column_gap:collapse} {@!grid_row_gap:collapse}',
        'uk-grid-match {@!grid_masonry}',
    ],

    'uk-grid' => $this->expr([
        'masonry: {grid_masonry};',
        'parallax: {grid_parallax};',
    ], $props) ?: true,

    'uk-lightbox' => [
        'toggle: a[data-type];' => $props['lightbox'],
    ],

]);

// Filter
$filter_grid = $this->el('div', [

    'class' => [
        'uk-child-width-expand',
        $props['filter_grid_column_gap'] == $props['filter_grid_row_gap'] ? 'uk-grid-{filter_grid_column_gap}' : '[uk-grid-column-{filter_grid_column_gap}] [uk-grid-row-{filter_grid_row_gap}]',
    ],

    'uk-grid' => true,
]);

$filter_cell = $this->el('div', [

    'class' => [
        'uk-width-{filter_grid_width}@{filter_grid_breakpoint}',
        'uk-flex-last@{filter_grid_breakpoint} {@filter_position: right}',
    ],

]);

?>

<script>
    jQuery( document ).ready(function() {
        jQuery('.companyGridLink').on('click', function(e){
            var hiddenContent = jQuery(this).find('.hiddenContent');

            console.log(content);
            e.preventDefault();
            var id              = jQuery(this).data('id');
            var headline        = jQuery(this).data('headline');
            var company         = jQuery(this).data('company');
            var logo            = jQuery(this).data('logo');
            var joblink         = jQuery(this).data('joblink');
            var webseiteLink    = jQuery(this).data('webseiteLink');
            var content         = jQuery(hiddenContent).html();

            jQuery('.cgContent').html(content);
            jQuery('.cgHeadline').html(headline);
            jQuery('.cgLogo').attr('src', logo);
            jQuery('#').attr('src', logo);
            jQuery('#jobButton').removeClass();
            jQuery('#jobButton').addClass('uk-button-default-light uk-button uk-button-company-modal ' + company);

            var modal = UIkit.modal("#companyGridModal");
            modal.show();
        })
    });
</script>


<div class="uk-margin companyGridContainer">
    <div class="uk-child-width-1-1 uk-child-width-1-2@m uk-grid-match uk-grid" uk-grid="">
        <?php
            $counter = 0;
        ?>
        <?php foreach ($children as $child) : ?>
        <?php
            $counter++;
            $modulo = $counter % 2;
        ?>
            <?php if($modulo === 1): ?>
            <div class="uk-first-column">
                <?= $builder->render($child, ['element' => $props]) ?>
            <?php else: ?>
            <div>
                <?= $builder->render($child, ['element' => $props]) ?>
            <?php endif; ?>
            </div>
        <?php endforeach ?>
    </div>
</div>

<!-- This is the modal -->
<div id="companyGridModal" uk-modal>
    <div class="uk-modal-dialog uk-modal-body companyModalDialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>

        <div uk-grid>
            <div class="uk-width-1-1@m">
                <div class="companyModalContainer">
                    <img src="" class="cgLogo" />
                    <div class="cgContents">
                        <span class="cgSubheadline">Kurzportrait</span>
                        <h2 class="cgHeadline uk-heading-large uk-margin-remove-top uk-margin-remove-bottom"></h2>
                        <div class="cgContent"></div>
                    </div>
                    <div class="cgButtonContainer">
                        <a href="#" id="companyButton" class="uk-button-default-dark uk-button uk-button-company-modal">Zur Firmenwebsite</a>
                        <a href="#" id="jobButton" class="uk-button-default-light uk-button uk-button-company-modal">Zu den Jobs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>