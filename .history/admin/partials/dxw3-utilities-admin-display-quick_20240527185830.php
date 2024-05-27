<?php
/**
 * Quick settings container with toggle buttons. The container is located under the plugin name on the plugins page.
 */

$utility_plugins = [];
$utility_plugins = is_array( get_option( 'all_utility_plugins' ) ) ? get_option( 'all_utility_plugins' ) : [];
$plugin_author = get_option( 'dxw3_plugins_author' );
?>
	<tr class="quick-toggles-container toggles-hidden"><td></td>
    <td><a id="dxw3_utilities_save">Save settings</a></td><td>
    <div id="dxw3-utilities-wrapper-quick">
    <input type="hidden" id="plugins_author" value = "<?php echo esc_attr( $plugin_author ); ?>">
    <?php
    $files = []; $slugs = [];    
    $updates = get_plugin_updates();
    foreach( $updates as $plugin_slug => $update ) $files[$plugin_slug] = [ 'version' => $update->Version, 'new_version' => $update->update->new_version ];
    foreach( $utility_plugins as $plugin_slug => $items ) {
        $url = self_admin_url( 'update.php?action=upgrade-plugin&plugin=' . $plugin_slug );
        $url = wp_nonce_url( $url, 'upgrade-plugin_' . $plugin_slug );
        $url = "<a href='" . esc_url( $url ) . "'>update now</a>";
        $slug = strtok( $plugin_slug, '/' );
        $slugs[ $slug ] = $plugin_slug;
        ?>
            <div class="onoff">
            <input type="checkbox" class="dxw3-ui-toggle" id="<?php echo esc_attr( $slug ); ?>" value="1" <?php echo ((int) get_option( $slug ) === 1) ? 'checked' : ''; ?>>
            <label for="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $items[ "Name" ] ); ?></label>
            <?php if( isset( $files[ $plugin_slug ] ) ) { ?>
                <span>
                    <?php echo wp_kses( $url, array( 'a' => array( 'href' => array() ) ) ) . '&nbsp;&nbsp;' . $files[ $plugin_slug ][ 'version' ] . ' -> ' . $files[ $plugin_slug ][ 'new_version' ]; ?>
                </span>
            <?php } else { ?><span>up to date</span><?php } ?>
            </div>
        <?php
    }
    update_option( 'dxw3_utility_plugins', $slugs );
    ?>
    </div>
    </td></tr>
<?php
