<?php

$utility_plugins = [];
$utility_plugins = is_array( get_option( 'all_utility_plugins' ) ) ? get_option( 'all_utility_plugins' ) : [];
$plugin_author = get_option( 'dxw3_plugins_author' );

?>
    <div id="dxw3-utilities-wrapper">
    <img src="<?php echo esc_url( plugins_url( 'images/dxw3_logo_sqr_sm.png', dirname( __FILE__, 2 ) ) ); ?>">
    <h3>Activation Settings for the Plugins Author:</h3><input id="plugins_author" value = "<?php echo esc_attr( $plugin_author ); ?>"><br>
    <?php
    $slugs = [];
    foreach( $utility_plugins as $plugin_slug => $items ) {
            $slug = strtok( $plugin_slug, '/' );
            $slugs[ $slug ] = $plugin_slug;
        ?>
            <div class="onoff">
            <input type="checkbox" class="dxw3-ui-toggle" id="<?php echo esc_attr( $slug ); ?>" value="1" <?php echo ((int) get_option( $slug ) === 1) ? 'checked' : ''; ?>>
            <label for="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $items[ "Name" ] ); ?></label>
            </div>
        <?php
    }
    update_option( 'dxw3_utility_plugins', $slugs );
    ?>
    <button id="dxw3_utilities_save">Save settings</button>
    </div>
<?php
