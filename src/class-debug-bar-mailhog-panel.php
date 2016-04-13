<?php

/**
 * This class extends the functionality provided by the parent plugin "Debug Bar" by adding a
 * panel showing information from MailHog.
 */
class Debug_Bar_MailHog_Panel extends Debug_Bar_Panel {
    const DBMH_STYLES_VERSION = '0.1';
    const DBMH_NAME = 'debug-bar-mailhog';
    /**
     * @var MailHog_API
     */
    private $api;

    /**
     * Debug_Bar_MailHog_Panel constructor.
     * @param MailHog_API $api
     */
    public function __construct(MailHog_API $api){
        $this->api = $api;
        parent::__construct();
    }
    /**
     * Constructor.
     */
    public function init() {
        $this->load_textdomain( self::DBMH_NAME );
        $this->title( __( 'MailHog', 'debug-bar-mailhog' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }


    /**
     * Load the plugin text strings.
     *
     * Compatible with use of the plugin in the must-use plugins directory.
     *
     * @param string $domain Text domain to load.
     */
    protected function load_textdomain( $domain ) {
        if ( is_textdomain_loaded( $domain ) ) {
            return;
        }
        $lang_path = dirname( plugin_basename( __FILE__ ) ) . '/languages';
        if ( false === strpos( __FILE__, basename( WPMU_PLUGIN_DIR ) ) ) {
            load_plugin_textdomain( $domain, false, $lang_path );
        } else {
            load_muplugin_textdomain( $domain, $lang_path );
        }
    }
    /**
     * Enqueue css file.
     */
    public function enqueue_scripts() {
        $suffix = ( ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min' );
        wp_enqueue_style( self::DBMH_NAME, plugins_url( 'assets/css/mailhog' . $suffix . '.css', 'debug-bar-mailhog/plugin.php'), array(), self::DBMH_STYLES_VERSION );
    }

    public function prerender() {
        $this->set_visible( true );
    }
    /**
     * Render the tab content.
     */
    public function render() {
        $collection = $this->api->get_messages(); ?>
        <div>
            <h2><?php esc_html_e( 'MailHog Mails', 'debug-bar-mailhog' ) ?></h2>
            <table>
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Subject', 'debug-bar-mail') ?></th>
                        <th><?php esc_html_e( 'From', 'debug-bar-mail') ?></th>
                        <th><?php esc_html_e( 'To', 'debug-bar-mail') ?></th>
                        <th><?php esc_html_e( 'Date', 'debug-bar-mail') ?></th>
                    </tr>
                </thead>
                <tbody>
            <?php foreach($collection as $item): ?>
                <tr>
                    <td>
                        <?php echo esc_html($item->subject) ?>
                    </td>
                    <td>
                        <?php echo esc_html($item->from) ?>
                    </td>
                    <td>
                        <div class="debug-bar-mailhog-to">
                            <ul>
                            <?php foreach ($item->to as $to): ?>
                                <li><?php echo esc_html($to) ?></li>
                            <?php endforeach; ?>
                            </ul>
                        </div>
                    </td>
                    <td>
                        <?php echo esc_html($item->date) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th><?php esc_html_e( 'Subject', 'debug-bar-mail') ?></th>
                        <th><?php esc_html_e( 'From', 'debug-bar-mail') ?></th>
                        <th><?php esc_html_e( 'To', 'debug-bar-mail') ?></th>
                        <th><?php esc_html_e( 'Date', 'debug-bar-mail') ?></th>
                    </tr>
                </tfoot>
            </table>

        </div>
<?php

    }
}