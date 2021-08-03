<?php
//if (!defined('ABSPATH')) exit;
/*
Template Name: job-list
 */

use BMSPrescreen\Helpers\JoblistHelper;
require_once(BMSPRE_PLUGIN_DIR . '/helpers/joblistHelper.php');
//var_dump($data);
$counter = 0;
$joblistHelper = new JoblistHelper();
$rewriteSlug = esc_html( get_option( 'BMSRewriteSlugJobdetail' ) );

$cities = $joblistHelper->getCities();
$companies = $joblistHelper->getCompaniesSelect($data);
?>
<input type="text" id="searchPhrase">
    <div class="uk-filterContainer">
        <div class="tm-grid-expand uk-child-width-1-1 uk-grid-margin uk-grid uk-grid-stack" uk-grid="">
            <div class="uk-first-column">
                <div class="uk-margin">
                    <div>
                        <label class="uk-form-label" for="Suche">Suche</label>
                        <div class="uk-form-controls">
                            <div class="uk-inline uk-display-block">
                                <span class="uk-form-icon uk-form-icon-flip" uk-icon="icon: search"></span>
                                <input class="uk-input" id="Suche" type="text" name="Suche" placeholder="Suche">

                            </div>
                        </div>
                        <div class="uk-text-danger uk-text-small""></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tm-grid-expand uk-grid-margin uk-grid uk-grid-stack" uk-grid="">
            <div class="uk-width-1-2@m uk-first-column">

                <div class="uk-margin">
                    <div>
                        <label class="uk-form-label" for="Bereich">Bereich</label>
                        <div class="uk-form-controls">
                            <select class="uk-select filterSelect" id="Bereich" name="Bereich">
                                <option value="">Bereich</option>
                                <option value="Finanzdienstleister">Finanzdienstleister</option>
                                <option value="Non Profit Organisationen">Non Profit Organisationen</option>
                                <option value="Verwaltung">Verwaltung</option>
                                <option value="Consulting">Consulting</option>
                                <option value="Design">Design</option>
                                <option value="Training">Training</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="uk-width-1-2@m uk-grid-margin uk-first-column">
                <div class="uk-margin">
                    <div>
                        <label class="uk-form-label" for="Unternehmen">Unternehmen</label>
                        <div class="uk-form-controls">
                            <select class="uk-select filterSelect" id="Unternehmen" name="Unternehmen">
                                <option value="">Unternehmen</option>
                                <?php foreach ($companies as $company): ?>
                                    <option value="<?= $company; ?>"><?= $company; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tm-grid-expand uk-child-width-1-1 uk-grid-margin uk-grid uk-grid-stack" uk-grid="">
            <div class="uk-first-column">
                <div class="uk-margin">
                    <div>
                        <label class="uk-form-label" for="Standort">Standort</label>
                        <div class="uk-form-controls">
                            <select class="uk-select filterSelect" id="Standort" name="Standort">
                                <option value="">Standort</option>
                                <?php foreach ($cities as $city): ?>
                                    <option value="<?= $city; ?>"><?= $city; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tm-grid-expand uk-child-width-1-1 uk-grid-margin uk-grid uk-grid-stack" uk-grid="">
        <div class="uk-first-column">
            <div class="joblistResultsContainer">
                <span class="joblistResultsHeadline">Jobliste</span>
                <span class="joblistResults"></span>
            </div>
            <table id="joblistTable" class="joblistTable">
                <thead>
                <tr>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                    <?php foreach ($data as $stellenanzeige): ?>

                    <?php
                        $counter++;
                        $customFields = $joblistHelper->customFields($stellenanzeige->custom_fields);
                        $link = '/' . $rewriteSlug . '/' .  $joblistHelper->stringURLSafe($stellenanzeige->title) . '-' . $stellenanzeige->id;
                        //var_dump($customFields);

                        $company = $joblistHelper->setUnternehmen($customFields['00_wer_schreibt_aus'])['name'];
                        $company = preg_replace("/[^A-Za-z0-9 ]/", '_', $company);
                        $company = str_replace(' ', '_', $company);
                        $company = strtolower($company);
                        //var_dump($company);
                    ?>

                    <tr class="joblistResultRow" data-link="<?= $link; ?>">
                        <td class="joblistResultLeft">
                            <span class="joblistResultType <?= $company; ?>">
                                <?= $joblistHelper->setUnternehmen($customFields['00_wer_schreibt_aus'])['type']; ?>
                            </span>
                            <span class="joblistResultCompany">
                                Firma: <?= $joblistHelper->setUnternehmen($customFields['00_wer_schreibt_aus'])['name']; ?>
                            </span>
                            <h3 class="joblistResultHeadline">
                                <?= substr($stellenanzeige->title, 0, 55 ) . '...'; ?>
                            </h3>
                        </td>
                        <td class="joblistResultRight">
                            <?= $customFields['01a_standort_der_stelle']; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

