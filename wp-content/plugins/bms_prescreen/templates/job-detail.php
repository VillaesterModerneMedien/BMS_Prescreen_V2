<?php

use BMSPrescreen\Helpers\JoblistHelper;
use Joomla\CMS\Language\Text;

if (!defined('ABSPATH')) exit;
/*
Template Name: job-detail
 */
$jobdetails = $args['response']->data[0];
//use BMSPrescreen\Helpers\JobdetailsHelper;
require_once(BMSPRE_PLUGIN_DIR . '/helpers/jobdetailsHelper.php');
require_once(BMSPRE_PLUGIN_DIR . '/helpers/joblistHelper.php');
$jobdetailsHelper = new JobdetailsHelper();
$joblistHelper = new JoblistHelper();

$customFields = $jobdetailsHelper->getCustomFields($jobdetails->custom_fields);
$toggleCount = $customFields['ToggleSwitches'];
//var_dump($jobdetails->description);
//var_dump($jobdetails->job_contents[2]->content);die();
$switches = $jobdetailsHelper->getRecruitainmentLi($jobdetails->job_contents[5]->content, $toggleCount, 'switch');
$skills = $jobdetailsHelper->getRecruitainmentLi($jobdetails->job_contents[5]->content, $toggleCount, 'skill');
//var_dump($toggleCount);
//var_dump($jobdetails->job_contents[5]->content);
$companyInformationsJoblist = $joblistHelper->setUnternehmen($customFields['00_wer_schreibt_aus']);
$companyInfosYootheme = $args['company'][$companyInformationsJoblist['name']];

$companyName = $companyInfosYootheme['company'];
$companyClass = $companyName;
$companyClass = preg_replace("/[^A-Za-z0-9 ]/", '_', $companyClass);
$companyClass = str_replace(' ', '_', $companyClass);
$companyClass = strtolower($companyClass);
$html = $jobdetails->job_contents[9]->content;
//var_dump($jobdetails);
?>
<?php get_header(); ?>

<div class="preloader">
    <div class="spinner-border" role="status">
        <span class="sr-only"><img src="/wp-content/plugins/bms_prescreen/assets/images/preloader.gif"></span>
    </div>
    <p>Ihre Bewerbung wird übermittelt...</p>
</div>
<div class="jobdetailsMainContainer">
    <div class="uk-section-default uk-section" id="jobdetailsTop">
        <div class="uk-container uk-container-small">
            <div class="tm-grid-expand uk-child-width-1-1 uk-grid-margin uk-grid uk-grid-stack" uk-grid="">
                <div class="uk-first-column">
                    <div class="uk-panel uk-margin group_<?= $companyClass; ?>" id="jobdetailsTopHeadlineContainer">
                        <span class="companyTopHeadline"><?= $companyInfosYootheme['company']; ?></span>
                        <h1 class="uk-heading-large uk-heading-light jobdetailsHeadline">
                            <?= $jobdetails->title; ?>
                        </h1>
                        <a href="#" id="bewerben" class="uk-button-default-white">Bewirb dich jetzt</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="uk-section-default uk-section" id="jobdetailsCompanySection">
        <div class="uk-container uk-container-small">
            <div class="tm-grid-expand uk-child-width-1-1 uk-grid-margin uk-grid uk-grid-stack" uk-grid="">
                <div class="uk-first-column">
                    <div class="uk-panel uk-margin" id="jobdetailsCompanyContainer">
                        <img class="jobdetailsLogo" src="/<?= $companyInfosYootheme['logo']; ?>" />
                        <div class="companyData">
                            <span class="companySubtitle group_<?= $companyClass; ?>">Kurzportrait</span>
                            <h3 class="uk-heading-large companyHeadline"><?= $companyInfosYootheme['headline']; ?></h3>
                            <div>
                                <?= $companyInfosYootheme['content']; ?>
                            </div>
                        </div>
                        <a href="<?= $companyInfosYootheme['webseiteLink']; ?>" class="uk-button-default-dark">Zur Firmenwebsite</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="uk-section-default uk-section" id="wirbietendirSection">
        <div class="uk-container uk-container-small">
            <div class="tm-grid-expand uk-child-width-1-2@m uk-grid-margin uk-grid uk-grid-stack" id="wirbietendirGrid" uk-grid="">
                <?php
                    $aufgaben = [
                        $jobdetails->job_contents[11]->content,
                        $jobdetails->job_contents[12]->content,
                        $jobdetails->job_contents[13]->content,
                        $jobdetails->job_contents[14]->content,
                        //$jobdetails->job_contents[19]->content,
                        $jobdetails->job_contents[17]->content,
                        $jobdetails->job_contents[18]->content,
                        //$jobdetails->job_contents[22]->content,
                    ];
                ?>
                <?php foreach($aufgaben as $listenelement): ?>
                    <div class="wirbietendirElements">
                        <img src="<?= '/wp-content/plugins/bms_prescreen/assets/images/recruitainment/wirbieten_icons/' . $jobdetailsHelper->imageRenaming($listenelement) . '.svg'; ?>" />
                        <h4 class="wirbietendirHeadline"><?= $listenelement; ?></h4>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="uk-section-default uk-section" id="aufgabenSection">
        <div class="uk-container uk-container-small">
            <?php
                $html = $jobdetails->job_contents[3]->content;
            ?>
            <div class="tm-grid-expand uk-grid-margin uk-grid-nopadding" uk-grid="">
                <div class="uk-width-1-1@m">
                    <span class="subtitle">Stellenprofil</span>
                    <h3 class="uk-heading-large">
                        <?= $jobdetails->job_contents[2]->content; ?>
                    </h3>
                </div>
                <div class="uk-width-1-1@m">
                    <ul class="aufgabenList">
                        <?php foreach($jobdetailsHelper->getListElements($html) as $listenelement): ?>
                            <li>
                                <?= $listenelement; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <form enctype="multipart/form-data" action="<?=admin_url( 'admin-post.php' ) ?>" method="post" name="bewerbungForm" id="bewerbung">
        <div class="uk-section-default uk-section greyBoxSection" id="unswichtigSection">
            <div class="uk-container uk-container-small">
                <div class="tm-grid-expand uk-grid-margin uk-grid" uk-grid="">
                    <div class="uk-grid-item-match uk-width-1-1@m">
                        <div class="uk-tile-muted uk-tile tileMobileRight">
                            <span class="subtitle">Selbsteinschätzung</span>
                            <h3 class="uk-heading-large">
                                <?= $customFields['03_unswichtig_ueberschrift']; ?>
                            </h3>
                            <p class="jobdetailsText"><?= $customFields['04_unswichtig_hilfetext']; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="uk-container uk-container-small">

                <div class="tm-grid-expand uk-grid-margin uk-grid checkboxRow" uk-grid="">
                    <?php $counter = 0; ?>
                    <?php foreach($switches as $switch): ?>
                        <?php $counter++; ?>
                        <div class="uk-width-1-3@s">
                            <label class="customCheckboxLabel" for="<?= $jobdetailsHelper->imageRenaming($switch); ?>">
                                <span class="switchLabel"><?= $switch; ?></span>
                                <input data-company="group_<?= $companyClass; ?>" id="<?= $jobdetailsHelper->imageRenaming($switch); ?>" name="skillSwitch<?= $counter; ?>" aria-describedby="<?= $jobdetailsHelper->imageRenaming($switch); ?>" class="recruit-toggleswitch-checkbox skillCheckbox" type="checkbox" value="<?= $jobdetailsHelper->imageRenaming($switch); ?>">
                                <span class="customCheckbox"></span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="tm-grid-expand uk-grid-margin uk-grid" uk-grid="">
                    <?php $counter = 0; ?>
                    <?php foreach($skills as $skill): ?>
                        <?php $counter++; ?>
                        <div class="uk-width-1-2@s">
                            <div class="bmsSkillField">
                                <div class="starRatingContainer">
                                    <div class="bms-field-element">
                                        <span class="starrating">
                                            <div class="stars group_<?= $companyClass; ?>" data-id="<?= $jobdetailsHelper->imageRenaming($skill); ?>">
                                                <a class="star" title="1"></a>
                                                <a class="star" title="2"></a>
                                                <a class="star" title="3"></a>
                                                <a class="star" title="4"></a>
                                                <a class="star" title="5"></a>
                                                <input type="text" class="starHidden" name="skillStar<?= $counter; ?>" data-id="<?= $skill;?>" id="<?= $jobdetailsHelper->imageRenaming($skill); ?>">
                                            </div>
                                        </span>
                                    </div>
                                    <div class="bms-field-label">
                                        <label for="<?= $jobdetailsHelper->imageRenaming($skill); ?>"  class=""><?= $skill; ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="uk-section-default uk-section greyBoxSection" id="dirwichtigSection">
            <div class="uk-container uk-container-small">

                <div class="tm-grid-expand uk-grid-margin uk-grid" uk-grid="">
                    <div class="uk-width-1-1@m uk-first-column">
                        <div class="uk-tile-muted uk-tile tileMobileLeft">
                            <div class="dirwichtigContent">
                                <span class="subtitle">Selbsteinschätzung</span>
                                <h3 class="uk-heading-large">
                                    <?= $customFields['05_dirwichtig_ueberschrift']; ?>
                                </h3>
                                <p class="jobdetailsText"><?= $customFields['06_dirwichtig_hilfetext']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="slidersSection">
                <div class="uk-container uk-container-small">
                    <div class="tm-grid-expand uk-grid-margin uk-grid sliders" uk-grid="">
                        <?php
                            $field = $customFields['07_slider_1'];
                            $values = explode('<->', $field);
                            if($field !== '' && $field !== '(slider nicht vorhanden)'):
                        ?>
                        <div class="uk-width-1-2@m uk-first-column">
                            <div class="field">
                                <div class="sliderLeft">
                                    <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/slider_icons/<?= $jobdetailsHelper->imageRenaming(strtolower($values[0])); ?>.svg">
                                    <label><?= $values[0]; ?></label>
                                </div>
                                <div class="sliderRight">
                                    <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/slider_icons/<?= $jobdetailsHelper->imageRenaming(strtolower($values[1])); ?>.svg">
                                    <label><?= $values[1]; ?></label>

                                </div>
                                <div class="sliderContainer">
                                    <input type="range" min="-100" max="100" value="0" class="slider group_<?= $companyClass; ?>" id="slider1">
                                    <input type="hidden" name="slider1" id="slider1hidden" $value="<?= $field; ?>" data-value="<?= $field; ?>">
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php
                            $field = $customFields['07_slider_2'];
                            $values = explode('<->', $field);
                            if($field !== '' && $field !== '(slider nicht vorhanden)'):
                        ?>
                        <div class="uk-width-1-2@m">
                            <div class="field">
                                <div class="sliderLeft">
                                    <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/slider_icons/<?= $jobdetailsHelper->imageRenaming(strtolower($values[0])); ?>.svg">
                                    <label><?= $values[0]; ?></label>
                                </div>
                                <div class="sliderRight">
                                    <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/slider_icons/<?= $jobdetailsHelper->imageRenaming(strtolower($values[1])); ?>.svg">
                                    <label><?= $values[1]; ?></label>

                                </div>
                                <div class="sliderContainer">
                                    <input type="range" min="-100" max="100" value="0" class="slider group_<?= $companyClass; ?>" id="slider2">
                                    <input type="hidden" name="slider2" id="slider2hidden" $value="<?= $field; ?>" data-value="<?= $field; ?>">
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php
                            $field = $customFields['07_slider_3'];
                            $values = explode('<->', $field);
                            if($field !== '' && $field !== '(slider nicht vorhanden)'):
                        ?>
                        <div class="uk-width-1-2@m">
                            <div class="field">
                                <div class="sliderLeft">
                                    <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/slider_icons/<?= $jobdetailsHelper->imageRenaming(strtolower($values[0])); ?>.svg">
                                    <label><?= $values[0]; ?></label>
                                </div>
                                <div class="sliderRight">
                                    <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/slider_icons/<?= $jobdetailsHelper->imageRenaming(strtolower($values[1])); ?>.svg">
                                    <label><?= $values[1]; ?></label>

                                </div>
                                <div class="sliderContainer">
                                    <input type="range" min="-100" max="100" value="0" class="slider group_<?= $companyClass; ?>" id="slider3">
                                    <input type="hidden" name="slider3" id="slider3hidden" $value="<?= $field; ?>" data-value="<?= $field; ?>">
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php
                            $field = $customFields['07_slider_4'];
                            $values = explode('<->', $field);
                            if($field !== '' && $field !== '(slider nicht vorhanden)'):
                        ?>
                        <div class="uk-width-1-2@m">
                            <div class="field">
                                <div class="sliderLeft">
                                    <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/slider_icons/<?= $jobdetailsHelper->imageRenaming(strtolower($values[0])); ?>.svg">
                                    <label><?= $values[0]; ?></label>
                                </div>
                                <div class="sliderRight">
                                    <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/slider_icons/<?= $jobdetailsHelper->imageRenaming(strtolower($values[1])); ?>.svg">
                                    <label><?= $values[1]; ?></label>

                                </div>
                                <div class="sliderContainer">
                                    <input type="range" min="-100" max="100" value="0" class="slider group_<?= $companyClass; ?>" id="slider4">
                                    <input type="hidden" name="slider4" id="slider4hidden" $value="<?= $field; ?>" data-value="<?= $field; ?>">
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php
                            $field = $customFields['07_slider_5'];
                            $values = explode('<->', $field);
                            if($field !== '' && $field !== '(slider nicht vorhanden)'):
                        ?>
                        <div class="uk-width-1-2@m">
                            <div class="field">
                                <div class="sliderLeft">
                                    <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/slider_icons/<?= $jobdetailsHelper->imageRenaming(strtolower($values[0])); ?>.svg">
                                    <label><?= $values[0]; ?></label>
                                </div>
                                <div class="sliderRight">
                                    <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/slider_icons/<?= $jobdetailsHelper->imageRenaming(strtolower($values[1])); ?>.svg">
                                    <label><?= $values[1]; ?></label>

                                </div>
                                <div class="sliderContainer">
                                    <input type="range" min="-100" max="100" value="0" class="slider group_<?= $companyClass; ?>" id="slider4">
                                    <input type="hidden" name="slider4" id="slider4hidden" $value="<?= $field; ?>" data-value="<?= $field; ?>">
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php
                            $field = $customFields['07_slider_6'];
                            $values = explode('<->', $field);
                            if($field !== '' && $field !== '(slider nicht vorhanden)'):
                        ?>
                        <div class="uk-width-1-2@m">
                            <div class="field">
                                <div class="sliderLeft">
                                    <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/slider_icons/<?= $jobdetailsHelper->imageRenaming(strtolower($values[0])); ?>.svg">
                                    <label><?= $values[0]; ?></label>
                                </div>
                                <div class="sliderRight">
                                    <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/slider_icons/<?= $jobdetailsHelper->imageRenaming(strtolower($values[1])); ?>.svg">
                                    <label><?= $values[1]; ?></label>

                                </div>
                                <div class="sliderContainer">
                                    <input type="range" min="-100" max="100" value="0" class="slider group_<?= $companyClass; ?>" id="slider4">
                                    <input type="hidden" name="slider4" id="slider4hidden" $value="<?= $field; ?>" data-value="<?= $field; ?>">
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="uk-section-default uk-section greyBoxSection" id="typSection">
            <div class="uk-container uk-container-small">
                <div class="tm-grid-expand uk-grid-margin uk-grid" uk-grid="">
                    <div class="uk-grid-item-match uk-width-1-1@m">
                        <div class="uk-tile-muted uk-tile tileMobileRight">
                            <span class="subtitle">Selbsteinschätzung</span>
                            <h3 class="uk-heading-large">
                                <?= $customFields['08_werbistdu_ueberschrift']; ?>
                            </h3>
                            <p class="jobdetailsText"><?= $customFields['09_werbistdu_hilfetext']; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="uk-container uk-container-small">
                <div class="tm-grid-expand uk-child-width-1-1 uk-grid-margin uk-grid uk-grid-stack uk-text-center" uk-grid="">
                    <div class="uk-first-column">
                        <div class="typeCheckboxes">
                            <label class="typeCheckboxLabel" for="typeCheckbox-1">
                                <div class="circle">
                                    <div class="innerCircle group_<?= $companyClass; ?>"></div>
                                    <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/werbistdu_icons/<?= $jobdetailsHelper->imageRenaming($customFields['10_personas_1']); ?>.svg">
                                </div>
                                <input class="typeCheckbox" id="typeCheckbox-1" type="checkbox" name="personas1" value="<?= $customFields['10_personas_1']; ?>">
                                <span><?= $customFields['10_personas_1']; ?></span>
                            </label>

                            <label class="typeCheckboxLabel" for="typeCheckbox-2">
                                <div class="circle">
                                    <div class="innerCircle group_<?= $companyClass; ?>"></div>
                                    <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/werbistdu_icons/<?= $jobdetailsHelper->imageRenaming($customFields['10_personas_2']); ?>.svg">
                                </div>
                                <input class="typeCheckbox" id="typeCheckbox-2" type="checkbox" name="personas2" value="<?= $customFields['10_personas_2']; ?>">
                                <span><?= $customFields['10_personas_2']; ?></span>
                            </label>

                            <label class="typeCheckboxLabel" for="typeCheckbox-3">
                                <div class="circle">
                                    <div class="innerCircle group_<?= $companyClass; ?>"></div>
                                    <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/werbistdu_icons/<?= $jobdetailsHelper->imageRenaming($customFields['10_personas_3']); ?>.svg">
                                </div>
                                <input class="typeCheckbox" id="typeCheckbox-3" type="checkbox" name="personas3" value="<?= $customFields['10_personas_3']; ?>">
                                <span><?= $customFields['10_personas_3']; ?></span>
                            </label>

                            <label class="typeCheckboxLabel" for="typeCheckbox-4">
                                <div class="circle">
                                    <div class="innerCircle group_<?= $companyClass; ?>"></div>
                                    <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/werbistdu_icons/<?= $jobdetailsHelper->imageRenaming($customFields['10_personas_4']); ?>.svg">
                                </div>
                                <input class="typeCheckbox" id="typeCheckbox-4" type="checkbox" name="personas4" value="<?= $customFields['10_personas_4']; ?>">
                                <span><?= $customFields['10_personas_4']; ?></span>
                            </label>

                            <label class="typeCheckboxLabel" for="typeCheckbox-5">
                                <div class="circle">
                                    <div class="innerCircle group_<?= $companyClass; ?>"></div>
                                    <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/werbistdu_icons/<?= $jobdetailsHelper->imageRenaming($customFields['10_personas_5']); ?>.svg">
                                </div>
                                <input class="typeCheckbox" id="typeCheckbox-5" type="checkbox" name="personas5" value="<?= $customFields['10_personas_5']; ?>">
                                <span><?= $customFields['10_personas_5']; ?></span>
                            </label>

                            <label class="typeCheckboxLabel" for="typeCheckbox-6">
                                <div class="circle">
                                    <div class="innerCircle group_<?= $companyClass; ?>"></div>
                                    <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/werbistdu_icons/<?= $jobdetailsHelper->imageRenaming($customFields['10_personas_6']); ?>.svg">
                                </div>
                                <input class="typeCheckbox" id="typeCheckbox-6" type="checkbox" name="personas6" value="<?= $customFields['10_personas_6']; ?>">
                                <span><?= $customFields['10_personas_6']; ?></span>
                            </label>

                            <label class="typeCheckboxLabel" for="typeCheckbox-7">
                                <div class="circle">
                                    <div class="innerCircle group_<?= $companyClass; ?>"></div>
                                    <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/werbistdu_icons/<?= $jobdetailsHelper->imageRenaming($customFields['10_personas_7']); ?>.svg">
                                </div>
                                <input class="typeCheckbox" id="typeCheckbox-7" type="checkbox" name="personas7" value="<?= $customFields['10_personas_7']; ?>">
                                <span><?= $customFields['10_personas_7']; ?></span>
                            </label>

                            <label class="typeCheckboxLabel" for="typeCheckbox-8">
                                <div class="circle">
                                    <div class="innerCircle group_<?= $companyClass; ?>"></div>
                                    <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/werbistdu_icons/<?= $jobdetailsHelper->imageRenaming($customFields['10_personas_8']); ?>.svg">
                                </div>
                                <input class="typeCheckbox" id="typeCheckbox-8" type="checkbox" name="personas8" value="<?= $customFields['10_personas_8']; ?>">
                                <span><?= $customFields['10_personas_8']; ?></span>
                            </label>

                            <label class="typeCheckboxLabel" for="typeCheckbox-9">
                                <div class="circle">
                                    <div class="innerCircle group_<?= $companyClass; ?>"></div>
                                    <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/werbistdu_icons/<?= $jobdetailsHelper->imageRenaming($customFields['10_personas_9']); ?>.svg">
                                </div>
                                <input class="typeCheckbox" id="typeCheckbox-9" type="checkbox" name="personas9" value="<?= $customFields['10_personas_9']; ?>">
                                <span><?= $customFields['10_personas_9']; ?></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <section id="sectionKontakt" class="uk-section-default sectionKontakt uk-section">
            <div class="uk-container uk-container-small">
                <div class="tm-grid-expand uk-grid-margin">
                    <span class="subtitle">Los geht es!</span>
                    <h2 class="uk-heading-large">
                        <?= $customFields['11_ansprechpartner_ueberschrift']; ?>
                    </h2>
                    <h3 class="uk-heading-medium">
                        Dein/e Ansprechpartner:in
                    </h3>
                    <ul class="contactData">
                        <?php
                        $contactData = $jobdetails->main_contact;
                        ?>
                        <li class="contactImage"><img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/ansprechpartner/recruitainment_<?= strtolower($contactData->firstname . '_' . $contactData->lastname); ?>.png"></li>
                        <li class="contactDetails">
                            <span class="contactName"><strong><?= $contactData->firstname . ' ' . $contactData->lastname; ?></strong></span>
                            <span class="contactPosition"><?= $contactData->job_title; ?></span>
                            <span class="contactPhone"><?= $contactData->phone; ?></span>
                        </li>
                    </ul>
                </div>

                <div class="tm-grid-expand uk-child-width-1-1 uk-grid-margin uk-grid uk-grid-stack sectionMeta uk-text-center" uk-grid="">
                    <div class="formRow">
                        <label class="uk-form-label" for="firstname">Vorname*</label>
                        <div class="uk-form-controls">
                            <div class="uk-inline uk-display-block">
                                <input class="uk-input form-control inputText" id="firstname" name="firstname" type="text" placeholder="Vorname">
                            </div>
                            <div class="warning" class="form-text">Dies ist ein Pflichtfeld.</div>
                        </div>
                    </div>

                    <div class="formRow">
                        <label class="uk-form-label" for="lastname">Nachname*</label>
                        <div class="uk-form-controls">
                            <div class="uk-inline uk-display-block">
                                <input class="uk-input form-control inputText" id="lastname" name="lastname" type="text" placeholder="Nachname">
                            </div>
                            <div class="warning" class="form-text">Dies ist ein Pflichtfeld.</div>
                        </div>
                    </div>

                    <div class="formRow">
                        <label class="uk-form-label" for="email">E-Mail Adresse*</label>
                        <div class="uk-form-controls">
                            <div class="uk-inline uk-display-block">
                                <input class="uk-input form-control inputText" id="email" name="email" type="text" placeholder="E-Mail Adresse">
                            </div>
                            <div class="warning" class="form-text">Dies ist ein Pflichtfeld.</div>
                        </div>
                    </div>

                    <div class="formRow">
                        <label class="uk-form-label" for="file">Lebenslauf</label>
                        <div class="uk-form-controls">
                            <div class="uk-inline uk-display-block">
                                <input type="file" name="file" id="fileCV">
                                <div class="upload-areaCV upload-area"  id="uploadCV">
                                    <span class="uploadMessageCV">Lebenslauf mit der Mouse hereinziehen oder auf das Feld klicken zum Hochladen<br/><strong>Achtung: Nur doc, docx, pdf und rtf erlaubt.</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="formRow">
                        <label class="uk-form-label" for="file">Sonstige Dateien</label>
                        <div class="uk-form-controls">
                            <div class="uk-inline uk-display-block">
                                <input type="file" name="filesAdditional[]" id="filesAdditional" multiple>
                                <div class="upload-areaAdd upload-area"  id="uploadAdditional">
                                    <span class="uploadMessageAdd">Sonstige Dateien mit der Mouse hereinziehen oder auf das Feld klicken zum Hochladen<br/><strong>Achtung: Nur doc, docx, pdf und rtf erlaubt.</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="formRow">
                        <div class="uk-form-controls">
                            <div class="uk-inline uk-display-block">
                                <div id="recaptcha" class="g-recaptcha" data-callback="imNotARobot" data-sitekey="<?= get_option( 'googleApiKey' ); ?>"></div>
                            </div>
                        </div>
                    </div>

                    <div class="formRow">
                        <div class="uk-form-controls">
                            <div class="uk-inline uk-display-block">
                                <button type="submit" id="sendCandidate" class="uk-button-default-company group_<?= $companyClass; ?>" disabled>Bewerbung abschicken</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <input id="jobID" name="job_id" type="hidden" value="<?= $jobdetails->id; ?>">
    </form>
</div>

<!-- This is the modal -->
<div id="validationModal" uk-modal>
    <div class="uk-modal-dialog uk-modal-body uk-container uk-container-small ">
        <div class="companyModalDialog">
            <button class="uk-modal-close-default" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" width="29.25" height="29.25" viewBox="0 0 29.25 29.25"><path d="M23.295,21.705,19.589,18l3.705-3.705a1.124,1.124,0,0,0-1.589-1.589L18,16.411l-3.705-3.705a1.124,1.124,0,0,0-1.589,1.589L16.411,18l-3.705,3.705a1.086,1.086,0,0,0,0,1.589,1.116,1.116,0,0,0,1.589,0L18,19.589l3.705,3.705a1.129,1.129,0,0,0,1.589,0A1.116,1.116,0,0,0,23.295,21.705Z" transform="translate(-3.375 -3.375)"/><path d="M18,5.344A12.651,12.651,0,1,1,9.049,9.049,12.573,12.573,0,0,1,18,5.344m0-1.969A14.625,14.625,0,1,0,32.625,18,14.623,14.623,0,0,0,18,3.375Z" transform="translate(-3.375 -3.375)"/></svg>
            </button>
            <h2 class="uk-heading-large">Achtung</h2>
            <p class="warningText">
            </p>
        </div>
    </div>
</div>
<?php do_action('qodef_page_after_container') ?>
<?php get_footer(); ?>


