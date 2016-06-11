<?php
# Database Configuration
define( 'DB_NAME', 'snapshot_womenintechs' );
define( 'DB_USER', 'womenintechs' );
define( 'DB_PASSWORD', 'cvJ3zNAkvo4wwBk8' );
define( 'DB_HOST', '127.0.0.1' );
define( 'DB_HOST_SLAVE', '127.0.0.1' );
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_unicode_ci');
$table_prefix = 'wp_';

# Security Salts, Keys, Etc
define('AUTH_KEY',         '_w<@oyD>D>}55C)|NPlU9$svE+Sy;Tg8=0OG$#3}DUZf35ukysc(uGQty%Qj3Mm$');
define('SECURE_AUTH_KEY',  '3pU06&n=-k1Ah3Gn|ab-l-*l^o*+Wfth5W~v,j05Iw}~?B,]Z[8}{b:l$o1,.;r?');
define('LOGGED_IN_KEY',    '#:.diM,h--JNcm3Tfmz^NH-@6@J2B=F(g@DDtON_rzmu&xHtMj$b[U#s-Q!7~ofs');
define('NONCE_KEY',        '[#w>@|(I:^+ V?}[K<P*i>$<5uf{;1BP FY6C*7d^a(*:v=|hcK[n#<BG4{Ix4:r');
define('AUTH_SALT',        'fg*cK0+I_~-UN)gLTrnLOm4{uyWSZ+JxmM~4>r%frcn8Y; u*^prv%]=>^74gGI,');
define('SECURE_AUTH_SALT', 'r@n,jTueU5C 5P9)R$pcvLyu1;/`z{=ZvNRnn"XbYk!kaeBo)`S%yhR9VfCfbSQG');
define('LOGGED_IN_SALT',   'w_oPEAXo02=cQ<}~qL c5P}"nvfp/.*.RH0Hef^$h(+[7J~GzKSI zWhGh1a_X3o');
define('NONCE_SALT',       'y>)xX@gUn;iNhD+~$@^>9hI=cXDP7xj$^s~jrvRW7rjLL9cE(]G+^vThamu6Z|NZ');


# Localized Language Stuff

define( 'WP_CACHE', TRUE );

define( 'WP_AUTO_UPDATE_CORE', false );

define( 'PWP_NAME', 'womenintechs' );

define( 'FS_METHOD', 'direct' );

define( 'FS_CHMOD_DIR', 0775 );

define( 'FS_CHMOD_FILE', 0664 );

define( 'PWP_ROOT_DIR', '/nas/wp' );

define( 'WPE_APIKEY', '5221d6732e756b62cdb5a3a323c317579bbcec8a' );

define( 'WPE_FOOTER_HTML', "" );

define( 'WPE_CLUSTER_ID', '40609' );

define( 'WPE_CLUSTER_TYPE', 'pod' );

define( 'WPE_ISP', true );

define( 'WPE_BPOD', false );

define( 'WPE_RO_FILESYSTEM', false );

define( 'WPE_LARGEFS_BUCKET', 'largefs.wpengine' );

define( 'WPE_SFTP_PORT', 2222 );

define( 'WPE_LBMASTER_IP', '45.56.75.97' );

define( 'WPE_CDN_DISABLE_ALLOWED', false );

define( 'DISALLOW_FILE_EDIT', FALSE );

define( 'DISALLOW_FILE_MODS', FALSE );

define( 'DISABLE_WP_CRON', false );

define( 'WPE_FORCE_SSL_LOGIN', false );

define( 'FORCE_SSL_LOGIN', false );

/*SSLSTART*/ if ( isset($_SERVER['HTTP_X_WPE_SSL']) && $_SERVER['HTTP_X_WPE_SSL'] ) $_SERVER['HTTPS'] = 'on'; /*SSLEND*/

define( 'WPE_EXTERNAL_URL', false );

define( 'WP_POST_REVISIONS', FALSE );

define( 'WPE_WHITELABEL', 'wpengine' );

define( 'WP_TURN_OFF_ADMIN_BAR', false );

define( 'WPE_BETA_TESTER', false );

umask(0002);

$wpe_cdn_uris=array ( );

$wpe_no_cdn_uris=array ( );

$wpe_content_regexs=array ( );

$wpe_all_domains=array ( 0 => 'womenintechsummit.net', 1 => 'www.womenintechsummit.net', 2 => 'womenintechs.wpengine.com', );

$wpe_varnish_servers=array ( 0 => 'pod-40609', );

$wpe_special_ips=array ( 0 => '45.56.75.97', );

$wpe_ec_servers=array ( );

$wpe_largefs=array ( );

$wpe_netdna_domains=array ( );

$wpe_netdna_domains_secure=array ( );

$wpe_netdna_push_domains=array ( );

$wpe_domain_mappings=array ( );

$memcached_servers=array ( );

define( 'WP_SITEURL', 'http://womenintechs.staging.wpengine.com' );

define( 'WP_HOME', 'http://womenintechs.staging.wpengine.com' );
define('WPLANG','');

# WP Engine ID


# WP Engine Settings






# That's It. Pencils down
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');

$_wpe_preamble_path = null; if(false){}
