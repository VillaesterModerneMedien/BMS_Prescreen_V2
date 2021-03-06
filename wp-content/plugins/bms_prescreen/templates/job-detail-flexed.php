<?php

if (!defined('ABSPATH')) exit;
/*
Template Name: job-detail
 */
$jobdetails = $args['response']->data[0];
//use BMSPrescreen\Helpers\JobdetailsHelper;
require_once(BMSPRE_PLUGIN_DIR . '/helpers/jobdetailsHelper.php');
$jobdetailsHelper = new JobdetailsHelper();

$customFields = $jobdetailsHelper->getCustomFields($jobdetails->custom_fields);
//var_dump($customFields);

$switches = $jobdetailsHelper->getRecruitainmentLi($jobdetails->description, 'recruitainment-toggleswitches');
$skills = $jobdetailsHelper->getRecruitainmentLi($jobdetails->description, 'recruitainment-skills');
?>
<?php get_header(); ?>

<section class="jobdetailsTopImage">
    <img itemprop="image" src="http://bms.wamrhein.de/wp-content/uploads/2018/10/bms_standardbanner.jpg" alt="stellenausschreibung_template">
</section>
<div class="preloader">
    <div class="spinner-border" role="status">
        <span class="sr-only">Loading...</span>
    </div>
    <p>Ihre Bewerbung wird ├╝bermittelt...</p>
</div>

<section class="container jobdetailsTitle">
    <h1><?= $jobdetails->title; ?></h1>
</section>


<section class="container-fluid  sectionAbout">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php
                    $html = $jobdetails->job_contents[0]->content;
                ?>
                <h2><?= $jobdetailsHelper->getByID($html, 'recruitainment-ueberuns-titel'); ?></h2>
                <h4 class="subtitles"><?= $jobdetailsHelper->getByID($html, 'recruitainment-ueberuns-subtitel'); ?></h4>
                <p><?= $jobdetailsHelper->getByID($html, 'recruitainment-ueberuns-text'); ?></p>
                <h4 class="subtitles"><?= $jobdetailsHelper->getByID($html, 'recruitainment-bietendir-ueberschrift'); ?></h4>
                <ul class="wirBietenList">
                    <?php foreach($jobdetailsHelper->getListElements($html) as $listenelement): ?>
                        <li>
                            <img src="<?= '/wp-content/plugins/bms_prescreen//assets/images/recruitainment/wirbieten_icons/' . $jobdetailsHelper->imageRenaming($listenelement) . '.png'; ?>" />
                            <h4><?= $listenelement; ?></h4>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="container-fluid hellgrau sectionAufgaben">
    <div class="container">
        <div class="row">
            <div class="col-12 sectionMeta">
                <?php
                $html = $jobdetails->job_contents[1]->content;
                ?>
                <h2><?= $jobdetailsHelper->getByID($html, 'recruitainment-aufgaben-ueberschrift'); ?></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <ul>
                    <?php foreach($jobdetailsHelper->getListElements($html) as $listenelement): ?>
                        <li>
                            <h4><?= $listenelement; ?></h4>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</section>

<form enctype="multipart/form-data" action="<?=admin_url( 'admin-post.php' ) ?>" method="post" name="formTest" id="formTest">

    <section class="container-fluid sectionUnsWichtig">
        <div class="container">
            <div class="row">
                <div class="col-12 sectionMeta">
                    <h2><?= $customFields['03_unswichtig_ueberschrift']; ?></h2>
                    <p><?= $customFields['04_unswichtig_hilfetext']; ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-12 switchGrid">
                    <?php $counter = 0; ?>
                    <?php foreach($switches as $switch): ?>
                        <?php $counter++; ?>
                        <label class="recruit-toggleswitch" for="<?= $jobdetailsHelper->imageRenaming($switch); ?>">
                            <span class="switchLabel"><?= $switch; ?></span>
                            <input id="<?= $jobdetailsHelper->imageRenaming($switch); ?>" name="skillSwitch<?= $counter; ?>" aria-describedby="<?= $jobdetailsHelper->imageRenaming($switch); ?>" class="recruit-toggleswitch-checkbox" type="checkbox" value="<?= $jobdetailsHelper->imageRenaming($switch); ?>">
                            <span class="recruit-toggleswitch-slider"></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-12 skillGrid">
                    <?php $counter = 0; ?>
                    <?php foreach($skills as $skill): ?>
                        <?php $counter++; ?>
                        <div class="bmsSkillField">
                            <div class="starRatingContainer">
                                <div class="bms-field-label">
                                    <label for="<?= $jobdetailsHelper->imageRenaming($skill); ?>"  class=""><?= $skill; ?></label>
                                </div>
                                <div class="bms-field-element">
                                    <span class="starrating">
                                        <div class="stars" data-id="<?= $jobdetailsHelper->imageRenaming($skill); ?>">
                                            <a class="star" title="1"></a>
                                            <a class="star" title="2"></a>
                                            <a class="star" title="3"></a>
                                            <a class="star" title="4"></a>
                                            <a class="star" title="5"></a>
                                            <input type="text" class="starHidden" name="skillStar<?= $counter; ?>" data-id="<?= $skill;?>" id="<?= $jobdetailsHelper->imageRenaming($skill); ?>">
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="container-fluid hellgrau sectionDirWichtig">
        <div class="container">
            <div class="row">
                <div class="col-12 sectionMeta">
                    <h2><?= $customFields['05_dirwichtig_ueberschrift']; ?></h2>
                    <p><?= $customFields['06_dirwichtig_hilfetext']; ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <fieldset class="sliders">
                        <div class="field">
                            <?php
                                $field = $customFields['07_slider_1'];
                                $values = explode('<->', $field);
                            ?>
                            <div class="sliderLeft">
                                <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/slider_icons/<?= $jobdetailsHelper->imageRenaming(strtolower($values[0])); ?>.png">
                                <label><?= $values[0]; ?></label>
                            </div>
                            <div class="sliderRight">
                                <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/slider_icons/<?= $jobdetailsHelper->imageRenaming(strtolower($values[1])); ?>.png">
                                <label><?= $values[1]; ?></label>

                            </div>
                            <div class="sliderContainer">
                                <input type="range" min="-100" max="100" value="0" class="slider" id="slider1">
                                <input type="hidden" name="slider1" id="slider1hidden" $value="<?= $field; ?>" data-value="<?= $field; ?>">
                            </div>
                        </div>

                        <div class="field">
                            <?php
                                $field = $customFields['07_slider_2'];
                                $values = explode('<->', $field);
                            ?>
                            <div class="sliderLeft">
                                <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/slider_icons/<?= $jobdetailsHelper->imageRenaming(strtolower($values[0])); ?>.png">
                                <label><?= $values[0]; ?></label>
                            </div>
                            <div class="sliderRight">
                                <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/slider_icons/<?= $jobdetailsHelper->imageRenaming(strtolower($values[1])); ?>.png">
                                <label><?= $values[1]; ?></label>

                            </div>
                            <div class="sliderContainer">
                                <input type="range" min="-100" max="100" value="0" class="slider" id="slider2">
                                <input type="hidden" name="slider2" id="slider2hidden" $value="<?= $field; ?>" data-value="<?= $field; ?>">
                            </div>
                        </div>

                        <div class="field">
                            <?php
                                $field = $customFields['07_slider_3'];
                                $values = explode('<->', $field);
                            ?>
                            <div class="sliderLeft">
                                <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/slider_icons/<?= $jobdetailsHelper->imageRenaming(strtolower($values[0])); ?>.png">
                                <label><?= $values[0]; ?></label>
                            </div>
                            <div class="sliderRight">
                                <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/slider_icons/<?= $jobdetailsHelper->imageRenaming(strtolower($values[1])); ?>.png">
                                <label><?= $values[1]; ?></label>

                            </div>
                            <div class="sliderContainer">
                                <input type="range" min="-100" max="100" value="0" class="slider" id="slider3">
                                <input type="hidden" name="slider3" id="slider3hidden" $value="<?= $field; ?>" data-value="<?= $field; ?>">
                            </div>
                        </div>

                        <div class="field">
                            <?php
                                $field = $customFields['07_slider_4'];
                                $values = explode('<->', $field);
                            ?>
                            <div class="sliderLeft">
                                <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/slider_icons/<?= $jobdetailsHelper->imageRenaming(strtolower($values[0])); ?>.png">
                                <label><?= $values[0]; ?></label>
                            </div>
                            <div class="sliderRight">
                                <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/slider_icons/<?= $jobdetailsHelper->imageRenaming(strtolower($values[1])); ?>.png">
                                <label><?= $values[1]; ?></label>

                            </div>
                            <div class="sliderContainer">
                                <input type="range" min="-100" max="100" value="0" class="slider" id="slider4">
                                <input type="hidden" name="slider4" id="slider4hidden" $value="<?= $field; ?>" data-value="<?= $field; ?>">
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </section>

    <section class="container-fluid sectionTyp">
        <div class="container">
            <div class="row">
                <div class="col-12 sectionMeta">
                    <h2><?= $customFields['08_werbistdu_ueberschrift']; ?></h2>
                    <p><?= $customFields['09_werbistdu_hilfetext']; ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 offset-md-2 col-12">
                    <div class="typeCheckboxes">
                        <label class="typeCheckboxLabel" for="typeCheckbox-1">
                            <div class="circle">
                                <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/werbistdu_icons/<?= $jobdetailsHelper->imageRenaming($customFields['10_personas_1']); ?>.png">
                            </div>
                            <input class="typeCheckbox" id="typeCheckbox-1" type="checkbox" name="personas1" value="<?= $customFields['10_personas_1']; ?>">
                            <span><?= $customFields['10_personas_1']; ?></span>
                        </label>

                        <label class="typeCheckboxLabel" for="typeCheckbox-2">
                            <div class="circle">
                                <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/werbistdu_icons/<?= $jobdetailsHelper->imageRenaming($customFields['10_personas_2']); ?>.png">
                            </div>
                            <input class="typeCheckbox" id="typeCheckbox-2" type="checkbox" name="personas2" value="<?= $customFields['10_personas_2']; ?>">
                            <span><?= $customFields['10_personas_2']; ?></span>
                        </label>

                        <label class="typeCheckboxLabel" for="typeCheckbox-3">
                            <div class="circle">
                                <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/werbistdu_icons/<?= $jobdetailsHelper->imageRenaming($customFields['10_personas_3']); ?>.png">
                            </div>
                            <input class="typeCheckbox" id="typeCheckbox-3" type="checkbox" name="personas3" value="<?= $customFields['10_personas_3']; ?>">
                            <span><?= $customFields['10_personas_3']; ?></span>
                        </label>

                        <label class="typeCheckboxLabel" for="typeCheckbox-4">
                            <div class="circle">
                                <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/werbistdu_icons/<?= $jobdetailsHelper->imageRenaming($customFields['10_personas_4']); ?>.png">
                            </div>
                            <input class="typeCheckbox" id="typeCheckbox-4" type="checkbox" name="personas4" value="<?= $customFields['10_personas_4']; ?>">
                            <span><?= $customFields['10_personas_4']; ?></span>
                        </label>

                        <label class="typeCheckboxLabel" for="typeCheckbox-5">
                            <div class="circle">
                                <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/werbistdu_icons/<?= $jobdetailsHelper->imageRenaming($customFields['10_personas_5']); ?>.png">
                            </div>
                            <input class="typeCheckbox" id="typeCheckbox-5" type="checkbox" name="personas5" value="<?= $customFields['10_personas_5']; ?>">
                            <span><?= $customFields['10_personas_5']; ?></span>
                        </label>

                        <label class="typeCheckboxLabel" for="typeCheckbox-6">
                            <div class="circle">
                                <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/werbistdu_icons/<?= $jobdetailsHelper->imageRenaming($customFields['10_personas_6']); ?>.png">
                            </div>
                            <input class="typeCheckbox" id="typeCheckbox-6" type="checkbox" name="personas6" value="<?= $customFields['10_personas_6']; ?>">
                            <span><?= $customFields['10_personas_6']; ?></span>
                        </label>

                        <label class="typeCheckboxLabel" for="typeCheckbox-7">
                            <div class="circle">
                                <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/werbistdu_icons/<?= $jobdetailsHelper->imageRenaming($customFields['10_personas_7']); ?>.png">
                            </div>
                            <input class="typeCheckbox" id="typeCheckbox-7" type="checkbox" name="personas7" value="<?= $customFields['10_personas_7']; ?>">
                            <span><?= $customFields['10_personas_7']; ?></span>
                        </label>

                        <label class="typeCheckboxLabel" for="typeCheckbox-8">
                            <div class="circle">
                                <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/werbistdu_icons/<?= $jobdetailsHelper->imageRenaming($customFields['10_personas_8']); ?>.png">
                            </div>
                            <input class="typeCheckbox" id="typeCheckbox-8" type="checkbox" name="personas8" value="<?= $customFields['10_personas_8']; ?>">
                            <span><?= $customFields['10_personas_8']; ?></span>
                        </label>

                        <label class="typeCheckboxLabel" for="typeCheckbox-9">
                            <div class="circle">
                                <img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/werbistdu_icons/<?= $jobdetailsHelper->imageRenaming($customFields['10_personas_9']); ?>.png">
                            </div>
                            <input class="typeCheckbox" id="typeCheckbox-9" type="checkbox" name="personas9" value="<?= $customFields['10_personas_9']; ?>">
                            <span><?= $customFields['10_personas_9']; ?></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container-fluid hellgrau sectionForm">
        <div class="container">
            <div class="row">
                <div class="col-12 sectionMeta">
                    <h2><?= $customFields['11_ansprechpartner_ueberschrift']; ?></h2>
                    <span class="subheading">Deine Ansprechpartner</span>
                    <ul class="contactData">
                        <?php
                            $contactData = $jobdetails->main_contact;
                        ?>
                        <li><img src="/wp-content/plugins/bms_prescreen/assets/images/recruitainment/ansprechpartner/recruitainment_<?= strtolower($contactData->firstname . '_' . $contactData->lastname); ?>.png"></li>
                        <li class="contactName"><strong><?= $contactData->firstname . ' ' . $contactData->lastname; ?></strong></li>
                        <li class="contactPosition"><?= $contactData->job_title; ?></li>
                        <li class="contactPhone"><?= $contactData->phone; ?></li>
                    </ul>
                    <span class="subheading">Deine Daten</span>

                </div>
            </div>
            <div class="row">
                <div class="col-md-8 offset-md-2 col-12">

                        <div class="mb-3">
                            <label for="firstname" class="form-label">Vorname*</label>
                            <input name="firstname" type="firstname" class="form-control inputText" id="firstname" class="required">
                            <div class="warning" class="form-text">Dies ist ein Pflichtfeld.</div>
                        </div>
                        <div class="mb-3">
                            <label for="lastname" class="form-label">Nachname*</label>
                            <input name="lastname" type="lastname" class="form-control inputText" id="lastname" class="required">
                            <div class="warning" class="form-text">Dies ist ein Pflichtfeld.</div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-Mail Adresse*</label>
                            <input name="email" type="email" class="form-control inputText" id="email" class="required">
                            <div class="warning" class="form-text">Dies ist ein Pflichtfeld.</div>
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label">Lebenslauf</label>

                            <input type="file" name="file" id="file">
                            <div class="upload-area"  id="uploadfile">
                                <span class="uploadMessage">Lebenslauf mit der Mouse hereinziehen oder auf das Feld klicken zum Hochladen<br/><strong>Achtung: Nur doc, docx, pdf und rtf erlaubt.</strong></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div id="recaptcha" class="g-recaptcha" data-sitekey="6Lc3mSYTAAAAAHh2h9ERE0-nqu5Gi_1RehRxSkLy"></div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" id="sendCandidate">Bewerbung abschicken</button>
                        </div>
                </div>
            </div>
        </div>
    </section>
</form>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>





<?php do_action('qodef_page_after_container') ?>
<?php get_footer(); ?>


