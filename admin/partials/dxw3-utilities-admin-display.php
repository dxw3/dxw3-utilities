<?php

$utility_plugins = [];
$utility_plugins = is_array( get_option( 'all_utility_plugins' ) ) ? get_option( 'all_utility_plugins' ) : [];

?>
    <div id="dxw3-utilities-wrapper">
    <img src="<?php echo plugins_url( 'dxw3-utilities' ); ?>/images/dxw3_logo_sqr_sm.png">
    <h3>Activation Settings for the Grouped Plugins</h3>
    <?php
    $slugs = [];
    foreach( $utility_plugins as $plugin_slug => $items ) {
            $slug = strtok( $plugin_slug, '/' );
            $slugs[ $slug ] = $plugin_slug;
        ?>
            <div class="onoff">
            <input type="checkbox" class="dxw3-ui-toggle" id="<?php echo $slug; ?>" value="1" <?php echo ((int) get_option( $slug ) === 1) ? 'checked' : ''; ?>>
            <label for="<?php echo $slug; ?>"><?php echo $items[ "Name" ]; ?></label>
            </div>
        <?php
    }
    update_option( 'dxw3_utility_plugins', $slugs );
    ?>
    <button id="dxw3_utilities_save">Save settings</button>
    </div>
<?php
