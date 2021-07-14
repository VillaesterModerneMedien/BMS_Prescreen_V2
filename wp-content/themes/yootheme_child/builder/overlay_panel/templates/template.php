<?php

$el = $this->el('div', [

    'class' => [
        'uk-panel',
        'uk-text-{text_style}',
        'uk-text-{text_size} {@!text_style}',
        'uk-text-{text_color}',
        'uk-dropcap {@dropcap}',
        'uk-column-{column}[@{column_breakpoint}]',
        'uk-column-divider {@column} {@column_divider}',
    ],

]);

// Column
$breakpoints = ['xl', 'l', 'm', 's', ''];

if ($props['column'] && false !== $index = array_search($props['column_breakpoint'], $breakpoints)) {

    list(, $columns) = explode('-', $props['column']);

    foreach (array_splice($breakpoints, $index + 1, 4) as $breakpoint) {

        if ($columns < 2) {
            break;
        }

        $el->attr('class', ['uk-column-1-' . (--$columns) . ($breakpoint ? "@{$breakpoint}" : '')]);
    }
}



//echo $view->image([$props['image'], 'thumbnail' => ['auto', 'auto'], 'srcset' => true], ['uk-img' => true, 'alt' => $props['image']]);


require_once dirname(__FILE__) . '/../helper/Mobile_Detect.php';

$detect = new Mobile_Detect;

if ( $detect->isMobile() ) {
    $mobile = true;
}
else{
    $mobile = false;
}
if($props['ausrichten']){

}
?>
<?php if(!$mobile): ?>
<div class="expandedContainer uk-container uk-container-medium <?php if(!$props['ausrichten']): ?>uk-container-expand-right<?php else: ?>uk-container-expand-left<?php endif; ?>">
    <div class="tm-grid-expand uk-grid-margin uk-grid" uk-grid="">

        <?php if(!$props['ausrichten']): ?>
        <div class="flexContainer uk-width-1-2@m uk-first-column">
            <h4 class="uk-heading-small uk-margin-remove-vertical">
                <?= $props['subtitle']; ?>
            </h4>
            <h2 class="uk-heading-large uk-margin-remove-top uk-margin-remove-bottom">
                <?= $props['title']; ?>
            </h2>
            <div class="uk-panel uk-margin">
                <?= $props['content']; ?>
            </div>
            <?php if($props['buttonText']): ?>
            <div class="uk-margin">
                <a class="uk-button uk-button-default-dark" href="<?= $props['link']; ?>" uk-scroll="">
                    <?= $props['buttonText']; ?>
                </a>
            </div>
            <?php endif; ?>
        </div>

        <div class="uk-width-1-2@m">
            <div class="uk-flex">
                <?php if($props['image']): ?>
                <div class="imageOverlayContainer imageOverlayContainerRight" style="background: <?= $props['backgroundColor']; ?>; ">
                    <div class="overlayImage" style="background-image: url(<?= $props['image']; ?>); background-position: <?= $props['bild_verschieben']; ?>">

                    </div>
                </div>
                <?php else: ?>
                    <div class="imageOverlayContainer imageOverlayContainerRight iconOverlay" style="background: <?= $props['backgroundColor']; ?>; ">
                        <img class="iconOverlayIcon" src="<?= $props['icon']; ?>" />
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php else: ?>

            <div class=" uk-width-1-2@m uk-first-column">
                <div class="uk-flex">
                    <?php if($props['image']): ?>
                        <div class="imageOverlayContainer imageOverlayContainerLeft" style="background: <?= $props['backgroundColor']; ?>; ">
                            <div class="overlayImage" style="background-image: url(<?= $props['image']; ?>); background-position: <?= $props['bild_verschieben']; ?>">

                            </div>
                        </div>
                    <?php else: ?>
                        <div class="imageOverlayContainer imageOverlayContainerLeft iconOverlay" style="background: <?= $props['backgroundColor']; ?>; ">
                            <img class="iconOverlayIcon" src="<?= $props['icon']; ?>" />
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="flexContainer uk-width-1-2@m">
                <h4 class="uk-heading-small uk-margin-remove-vertical">
                    <?= $props['subtitle']; ?>
                </h4>
                <h2 class="uk-heading-large uk-margin-remove-top uk-margin-remove-bottom">
                    <?= $props['title']; ?>
                </h2>
                <div class="uk-panel uk-margin">
                    <?= $props['content']; ?>
                </div>

                <?php if($props['buttonText']): ?>
                    <div class="uk-margin">
                        <a class="uk-button uk-button-default-dark" href="<?= $props['link']; ?>" uk-scroll="">
                            <?= $props['buttonText']; ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>



        <?php endif; ?>
    </div>
</div>
<?php else: ?>
    <div class="expandedContainer uk-container uk-container-large <?php if(!$props['ausrichten']): ?>uk-container-expand-right<?php else: ?>uk-container-expand-left<?php endif; ?>">
        <div class="tm-grid-expand uk-grid-margin uk-grid" uk-grid="">

                <div class="uk-width-1-1@m">
                    <div class="uk-flex">
                        <div class="imageOverlayContainer imageOverlayContainerMobile <?php if(!$props['ausrichten']): ?>imageOverlayContainerRight<?php else: ?>imageOverlayContainerLeft<?php endif; ?>" style="background: <?= $props['backgroundColor']; ?>; ">
                            <div class="overlayImage" style="background-image: url(<?= $props['image']; ?>); background-position: <?= $props['bild_verschieben']; ?>">

                            </div>

                            <div class="flexContainerMobile">
                                <h4 class="uk-heading-small uk-margin-remove-vertical">
                                    <?= $props['subtitle']; ?>
                                </h4>
                                <h2 class="uk-heading-large uk-margin-remove-top uk-margin-remove-bottom uk-heading-light">
                                    <?= $props['title']; ?>
                                </h2>
                                <div class="uk-panel uk-margin uk-text-light">
                                    <?= $props['content']; ?>
                                </div>

                                <div class="uk-margin">
                                    <a class="uk-button uk-button-default-light" href="<?= $props['link']; ?>" uk-scroll="">
                                        <?= $props['buttonText']; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
<?php endif; ?>