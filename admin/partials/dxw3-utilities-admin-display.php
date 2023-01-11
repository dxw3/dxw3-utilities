<?php

$utility_plugins = [];
$utility_plugins = is_array( get_option( 'all_utility_plugins' ) ) ? get_option( 'all_utility_plugins' ) : [];
$plugin_author = get_option( 'dxw3_plugins_author' );

?>
    <div id="dxw3-utilities-wrapper">
    <img src="<?php echo plugins_url( 'dxw3-utilities' ); ?>/images/dxw3_logo_sqr_sm.png">
    <h3>Activation Settings for the Plugins Author:</h3><input id="plugins_author" value = "<?php echo $plugin_author; ?>"><br>
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