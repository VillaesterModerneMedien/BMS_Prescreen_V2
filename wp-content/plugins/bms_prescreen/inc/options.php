<?php
/**
 * Plugin Options page
 *
 * @package    BMS Prescreen Rest API
 * @author     Kiki Schuelling <cs@villaester.de>
 * @copyright  Copyright (c) 2021 BMS Consulting
 * @link       http://digitalliberation.org/plugins/bmsPrescreen

 */?>
<div class="wrap">
  <h2><?php esc_html_e( 'BMS Prescreen REST API - Options', 'bmsPrescreen'); ?></h2>

  <hr />
  <div id="poststuff">
  <div id="post-body" class="metabox-holder columns-2">
    <div id="post-body-content">
      <div class="postbox">
        <div class="inside">
          <form name="dofollow" action="options.php" method="post">

                <?php settings_fields( 'bms-prescreen-api' ); ?>

                <h3 class="bmsLabels" for="bmsLabels"><?php esc_html_e( 'BMS Prescreen REST API Einstellungen:', 'bms-prescreen-api'); ?></h3>
                <p><?php esc_html_e( 'API Key', 'bms-prescreen-api'); ?></p>
                <input style="width:98%;font-family:monospace;" id="apiKey" name="apiKey" value="<?php echo esc_html( get_option( 'apiKey' ) ); ?>">

              <p><?php esc_html_e( 'Rewrite Slug (Job Details)', 'bms-prescreen-api'); ?></p>
                <input style="width:98%;font-family:monospace;" id="BMSRewriteSlugJobdetail" name="BMSRewriteSlugJobdetail" value="<?php echo esc_html( get_option( 'BMSRewriteSlugJobdetail' ) ); ?>">

                <p class="submit">
                    <input class="button button-primary" type="submit" name="Submit" value="<?php esc_html_e( 'Einstellungen speichern', 'bms-prescreen-api'); ?>" />
                </p>

          </form>
        </div>
    </div>
    </div>

    <?php require_once(BMSPRE_PLUGIN_DIR . '/inc/sidebar.php'); ?>
    </div>
  </div>
</div>
