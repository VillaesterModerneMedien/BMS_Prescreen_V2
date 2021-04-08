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
<div class="filterContainer">
    <div class="filterColumn">
        <label>Position</label>
        <dt>
            <input type="text" id="position" name="position" placeholder="Position">
        </dt>
    </div>

    <div class="filterColumn">
        <label>Bereich</label>
        <dl class="dropdown" id="bereich">
            <dt>
                <a data-id="bereich">
                    <span class="initialOption">Bereich auswählen</span>
                    <p class="multiSel"></p>
                    <input type="hidden" class="hiddenSelect">
                </a>
            </dt>

            <dd>
                <div class="multiSelect">
                    <ul>
                        <li>
                            <input type="checkbox" data-boxtype="Bereich" data-id="bereich" id="NPO" value="Non Profit Organisationen" />
                            <label for="NPO">Non Profit Organisationen</label>

                        </li>
                        <li>
                            <input type="checkbox" data-boxtype="Bereich" data-id="bereich" id="finanz" value="Finanzdienstleister" />
                            <label for="finanz">Finanzdienstleister</label>
                        </li>
                    </ul>
                </div>
            </dd>
        </dl>
    </div>

    <div class="filterColumn">
        <label>Unternehmen</label>
        <dl class="dropdown" id="unternehmen">
            <dt>
                <a data-id="unternehmen">
                    <span class="initialOption">Unternehmen auswählen</span>
                    <p class="multiSel"></p>
                    <input type="hidden" class="hiddenSelect">
                </a>
            </dt>

            <dd>
                <div class="multiSelect">
                    <ul>
                        <?php foreach ($companies as $company): ?>
                            <li>
                                <input type="checkbox" data-boxtype="Unternehmen" data-id="unternehmen" id="<?= $company; ?>" value="<?= $company; ?>" />
                                <label for="<?= $company; ?>"><?= $company; ?></label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </dd>
        </dl>
    </div>

    <div class="filterColumn">
        <label>Standort</label>
        <dl class="dropdown" id="standort">
            <dt>
                <a data-id="standort">
                    <span class="initialOption">Standort auswählen</span>
                    <p class="multiSel"></p>
                    <input type="hidden" class="hiddenSelect">
                </a>
            </dt>

            <dd>
                <div class="multiSelect">
                    <ul>
                        <?php foreach ($cities as $city): ?>
                            <li>
                                <input type="checkbox" data-boxtype="Standort" data-id="standort" value="<?= $city; ?>" /> <?= $city; ?>
                                <label for="<?= $city; ?>"><?= $city; ?></label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </dd>
        </dl>
    </div>

</div>


<table id="joblistTable" class="joblistTable">
    <thead>
    <tr>
        <th>Position</th>
        <th>Bereich</th>
        <th>Unternehmen</th>
        <th>Standort</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $stellenanzeige): ?>

        <?php
            $counter++;
            if($counter % 2){
                $rowClass = 'odd';
            }
            else{
                $rowClass = 'even';
            }
            $customFields = $joblistHelper->customFields($stellenanzeige->custom_fields);
            $link = '/' . $rewriteSlug . '/' .  $joblistHelper->stringURLSafe($stellenanzeige->title) . '-' . $stellenanzeige->id;

        ?>

        <tr class="<?= $rowClass; ?>"  data-link="<?= $link; ?>">
            <td class="joblist-column-1">
                <?= substr($stellenanzeige->title, 0, 55 ) . '...'; ?>
            </td>
            <td class="joblist-column-2">
                <?= $joblistHelper->setUnternehmen($customFields['00_wer_schreibt_aus'])['type']; ?>
            </td>
            <td class="joblist-column-3">
                <?= $joblistHelper->setUnternehmen($customFields['00_wer_schreibt_aus'])['name']; ?>
            </td>
            <td class="joblist-column-4">
                <?= $customFields['01a_standort_der_stelle']; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

