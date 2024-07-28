<?php 
/** 
* Plugin Name: Fun Crypto Donation
* Plugin URI: #
* Description: This plugin is used to handle crypto Donation.
* Version: 1.0.0
* Author: Fun Crypto
* Author URI: #
* License: GPL2
*/

class DonationSetting {
	private $setting_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'setting_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'setting_page_init' ) );
	}

	public function setting_add_plugin_page() {
		add_options_page(
			'Fun Crypto Donation Setting', 
			'Fun Crypto Donation',
			'manage_options', 
			'donation-setting', 
			array( $this, 'setting_create_admin_page' ) 
		);
	}

	public function setting_create_admin_page() {
		$this->setting_options = get_option( 'setting_option' ); ?>

		<div class="wrap">
			<h2> Donation Setting</h2>
			<p></p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'setting_option_group' );
					do_settings_sections( 'donation-setting-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function setting_page_init() {
		register_setting(
			'setting_option_group', 
			'setting_option',
			array( $this, 'setting_sanitize' ) 
		);

		add_settings_section(
			'setting_setting_section', 
			'Settings',
			array( $this, 'setting_section_info' ),
			'donation-setting-admin'
		);

		add_settings_field(
			'donation_thank_you_page',
			'Donation Thank you Page',
			array( $this, 'donation_thank_you_page_callback' ),
			'donation-setting-admin',
			'setting_setting_section'
		);

		add_settings_field(
			'donation_reciver_wallet_kujira', 
			'Donation Reciver Wallet (kujira)', 
			array( $this, 'donation_reciver_wallet_kujira_callback' ), 
			'donation-setting-admin',
			'setting_setting_section'
		);

		add_settings_field(
			'donation_paypal_button_id',
			'Donation Paypal Button ID',
			array( $this, 'donation_paypal_button_id_callback' ),
			'donation-setting-admin',
			'setting_setting_section' 
		);
	}

	public function setting_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['donation_thank_you_page'] ) ) {
			$sanitary_values['donation_thank_you_page'] = sanitize_text_field( $input['donation_thank_you_page'] );
		}

		if ( isset( $input['donation_reciver_wallet_kujira'] ) ) {
			$sanitary_values['donation_reciver_wallet_kujira'] = sanitize_text_field( $input['donation_reciver_wallet_kujira'] );
		}

		if ( isset( $input['donation_paypal_button_id'] ) ) {
			$sanitary_values['donation_paypal_button_id'] = sanitize_text_field( $input['donation_paypal_button_id'] );
		}

		return $sanitary_values;
	}

	public function setting_section_info() {
		
	}

	public function donation_thank_you_page_callback() {
		printf(
			'<input class="regular-text" type="text" name="setting_option[donation_thank_you_page]" id="donation_thank_you_page" value="%s">',
			isset( $this->setting_options['donation_thank_you_page'] ) ? esc_attr( $this->setting_options['donation_thank_you_page']) : ''
		);
	}

	public function donation_reciver_wallet_kujira_callback() {
		printf(
			'<input class="regular-text" type="text" name="setting_option[donation_reciver_wallet_kujira]" id="donation_reciver_wallet_kujira" value="%s">',
			isset( $this->setting_options['donation_reciver_wallet_kujira'] ) ? esc_attr( $this->setting_options['donation_reciver_wallet_kujira']) : ''
		);
	}

	public function donation_paypal_button_id_callback() {
		printf(
			'<input class="regular-text" type="text" name="setting_option[donation_paypal_button_id]" id="donation_paypal_button_id" value="%s">',
			isset( $this->setting_options['donation_paypal_button_id'] ) ? esc_attr( $this->setting_options['donation_paypal_button_id']) : ''
		);
	}

}
if ( is_admin() )
	$setting = new DonationSetting();


    
function box_render( $atts) {
	

	  $plugin_url = plugin_dir_url( __FILE__ );
      wp_enqueue_style( 'donation-box-style',  $plugin_url . "/donation-box-style.css",array(),"1.58");
      wp_enqueue_script( 'donation-kujira-script', "https://asset.dloyal.com/kujira.js",array(),"1.54");
      wp_enqueue_script( 'donation-box-script',  $plugin_url . "/donation-box.js",array(),"1.58");

      $advertiser_options = get_option( 'setting_option' ); 
      $kujira_wallet = $advertiser_options['donation_reciver_wallet_kujira']; 
      

$box = '<div class="make-donation-wrapper" id="donate11">
            <div class="heading__1">Make a Donation</div>
            <div class="donation-amount single_amount_wrapper">
              <div class="single_amount single-10" onclick="putValue(10.00)">10</div>
              <div class="single_amount single-20" onclick="putValue(20.00)">20</div>
              <div class="single_amount single-50" onclick="putValue(50.00)">50</div>
              <div class="single_amount single-100" onclick="putValue(100.00)">100</div>
            </div>
            <div class="inputbox amount_wrapper">
              <input type="number" step="1" min="1.00" placeholder="Custom USK Amount" id="Custominputfild">
              <span>USK</span>
            </div>
            <div class="select-donation">Donation Amount : <span id="donation_amt_lbl">0</span> USK</div>
            <a href="#" rel="nofollow noopener" class="donate-btn" id="btn-donate">Donate WITH USK
              <img decoding="async" src="'.plugin_dir_url( __FILE__ ).'Vector-1.svg"></a>
          </div>
          
          <div style="position: fixed;top: 0px;right: 0px;width: 100%;height: 100%;background: #222;z-index: 99999;display: none;" id="loading-screen"><div style="height: 100%;  display: flex;">
    
          <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto;background: none;display: block;shape-rendering: auto;width: 200px !important;height: 200px !important;position: absolute;top: calc(50% - 200px);right: calc(50% - 100px);" width="500px" height="500px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
      <path d="M10 50A40 40 0 0 0 90 50A40 42 0 0 1 10 50" fill="#e53935" stroke="none">
        <animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 51;360 50 51"></animateTransform>
      </path> </svg>
      
      <img src="'.plugin_dir_url( __FILE__ ).'KKLOGO-1.svg" alt="logo" class="donation-box-kklogo" >
	  <h1 class="donation-box-please-wait-txt" >Signing Please Wait...</h1>
      </div></div>
      <input type="hidden" id="kujira-wallat" value="'.$kujira_wallet.'">
      ';

return $box;

}



add_shortcode( 'Donation-Box', 'box_render' );

function paypal_button_render( $atts) {


    $advertiser_options = get_option( 'setting_option' ); 
    $paypal_button_id = $advertiser_options['donation_paypal_button_id']; 

$paypal_btn = 
'<div id="donate-button-container">
<div id="donate-button" style="text-align: -webkit-center;"></div>
<script src="https://www.paypalobjects.com/donate/sdk/donate-sdk.js" charset="UTF-8"></script>
<script>
var thankyou_page_url = "";
PayPal.Donation.Button({
env:"production",
hosted_button_id:"'.$paypal_button_id.'",
image: {
src:"'.plugin_dir_url( __FILE__ ).'paypal-btn.png",
alt:"Donate with PayPal button",
title:"PayPal - The safer, easier way to pay online!",
}, onComplete: function (parms) {
           console.log(parms);
           window.paypal_results = parms;
           
           if(parms["st"]=="Completed"){
           
            jQuery.ajax({
                            type: "post",
                            url: "/wp-admin/admin-ajax.php", 
                            data: {
                                    action:"dl_don_cvrn",
                                    amount: parms["amt"],
                                    Tr_Hash: parms["tx"],
                                    Is_recurring:parms["cm"],
                                    payment_method: "paypal"
        
                                },
                            success: function(redirect_url){
                                if(redirect_url){ window.location.replace(redirect_url); }
                            }
                    });
                    
           }
               
       }
}).render("#donate-button");
</script>
</div>';

return $paypal_btn;

}
add_shortcode( 'Paypal-Button', 'paypal_button_render' );


function Thank_you( $atts) {
    
    $donation_amount = "";
    $donation_tx_hash = "";
    $donation_method = "";
    $payment = "USK";

    if(isset($_GET["don-amount"]) && isset($_GET["don-tx-hash"]) && isset($_GET["don-method"]))
    {

        $donation_amount = $_GET["don-amount"];
        $donation_tx_hash = $_GET["don-tx-hash"];
        $donation_method = $_GET["don-method"];

        if($donation_method=="USK")
        {
            $payment = "USK";
        }
        else
        {
            $payment = "USD";
        }

    }
    else
    {
        header("Location: ".get_home_url());
    }
   
    
   $tx_details = ' <h3 class="thank__amount">Amount : '.$donation_amount.'</h3>
   <p>Transation Hash</p>
   <p><small>'.$donation_tx_hash.'</small></p>';
   return $tx_details;
    

}
add_shortcode( 'thank-you', 'Thank_you' );


if($_SERVER['REQUEST_URI'] == '/donation-cancel')
{
    echo "<script>window.close();</script>";
}
else if($_SERVER['REQUEST_URI'] == '/donation-completed'){
    echo "<script>window.close();</script>";
}









  