<?php

class Debug_Bar_MailHog {
    public function __construct(){
        $this->settings = get_option( 'debug_bar_mailhog_settings' );
        if( false === $this->settings ) {
            $this->settings = array(
                'http_address' => 'http://192.168.50.4:8025/',
                'smtp_address' => 'http://192.168.50.4:1025/'
            );
            add_option( 'debug_bar_mailhog_settings', $this->settings );
        }

        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_filter( 'debug_bar_panels', array( $this, 'debug_bar_mailhog_panel' ) );
        add_action( 'phpmailer_init', array( $this, 'phpmailer_init_smtp' ) );
    }

    function phpmailer_init_smtp($phpmailer) {
        $smtp_server = $this->get_setting_value('smtp_address');
        $parts = parse_url($smtp_server);
        $phpmailer->Mailer = 'smtp';
        $phpmailer->Host = $parts['host'];
        $phpmailer->Port = $parts['port'];
    }

    function debug_bar_mailhog_panel( $panels ) {
        if ( ! class_exists('Debug_Bar_MailHog_Panel') ) {
            require_once 'class-debug-bar-mailhog-panel.php';
        }
        if ( ! class_exists('MailHog_API') ) {
            require_once 'class-mailhog-api.php';
        }

        $panels[] = new Debug_Bar_MailHog_Panel(new MailHog_API($this->get_setting_value('http_address')));
        return $panels;
    }

    private function get_setting_value($key) {
        return $this->settings[$key];
    }

    function register_settings() {
        add_settings_section(
            'general_settings_section',
            __( 'General settings', 'debug-bar-mailhog' ),
            array( $this, 'general_settings_callback' ),
            'debug_bar_mailhog_settings'
        );

        add_settings_field(
            'http_address',
            __( 'HTTP Server', 'debug-bar-mailhog' ),
            array( $this, 'input_element_callback' ),
            'debug_bar_mailhog_settings',
            'general_settings_section',
            array(
                'key' => 'http_address',
                'description' => __( 'Configure this to enable retrieval of MailHog mails.', 'debug-bar-mailhog' ),
            )
        );
        add_settings_field(
            'smtp_address',
            __( 'SMTP Server', 'debug-bar-mailhog' ),
            array( $this, 'input_element_callback' ),
            'debug_bar_mailhog_settings',
            'general_settings_section',
            array(
                'key' => 'smtp_address',
                'description' => __( 'Configure this to send mails to MailHog.', 'debug-bar-mailhog' ),
            )
        );

        register_setting( 'debug_bar_mailhog_settings', 'debug_bar_mailhog_settings' );
    }
    function input_element_callback($args) {
        $options = get_option( 'debug_bar_mailhog_settings' );
        // Render the output
        echo '<input type="text" id="',$args['key'],'" name="debug_bar_mailhog_settings[',$args['key'],']" value="' . $options[$args['key']] . '" />';

    }
    function general_settings_callback() {
        echo '<p>' . __( 'Alter MailHog settings.', 'debug-bar-mailhog' ) . '</p>';
    }

    function admin_menu() {
        add_submenu_page(
            'tools.php',
            'MailHog',
            'MailHog',
            'manage_options',
            'debug_bar_mailhog_settings',
            array(
                $this,
                'settings_page'
            )
        );
    }

    function  settings_page() {?>
        <div class="wrap">
            <h2><?php esc_html_e('MailHog settings', 'debug-bar-mailhog') ?></h2>
            <form method="POST" action="options.php">
                <?php settings_fields( 'debug_bar_mailhog_settings' );	//pass slug name of page, also referred
                //to in Settings API as option group name
                do_settings_sections( 'debug_bar_mailhog_settings' ); 	//pass slug name of page
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}