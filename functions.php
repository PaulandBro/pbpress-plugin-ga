<?php

if(!defined('PB_DOCUMENT_PATH')){
	die( '-1' );
}

define('PB_GOOGLE_ANS_PATH', dirname(__FILE__)."/");
define('PB_GOOGLE_ANS_URL', PB_PLUGINS_URL . str_replace(PB_PLUGINS_PATH, "", PB_GOOGLE_ANS_PATH));

function _pb_ajax_google_analysis_register_manage_site_menu_list($results_){
	$results_['manage-google-analysis'] = array(
		'name' => '구글분석도구',
		'renderer' => '_pb_ajax_google_analysis_hook_render_manage_site',
	);
	return $results_;
}
pb_hook_add_filter('pb-admin-manage-site-menu-list', "_pb_ajax_google_analysis_register_manage_site_menu_list");

function _pb_ajax_google_analysis_hook_render_manage_site($menu_data_){
	?>

	<div class="manage-site-form-panel panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">구글분석도구</h3>
		</div>
		<div class="panel-body">
			<div class="form-group">
				<label>사이트키</label>
				<input type="text" name="pb_google_analysis_site_key" value="<?=pb_option_value("pb_google_analysis_site_key")?>" placeholder="UA-XXXXXXXXX-X" class="form-control" >
			</div>	

			<div class="form-group">
				<div class="checkbox">
					<label><input type="checkbox" name="pb_google_analysis_tracking_admin_yn" value="Y" <?=pb_checked(pb_option_value("pb_google_analysis_tracking_admin_yn", "N"), "Y")?>> 관리자페이지도 추적</label>
				</div>
			</div>	
		</div>
	</div>
	<?php
}

function _pb_ajax_google_analysis_hook_update_site_settings($settings_data_){
	pb_option_update('pb_google_analysis_site_key', $settings_data_['pb_google_analysis_site_key']);
	pb_option_update('pb_google_analysis_tracking_admin_yn', isset($settings_data_['pb_google_analysis_tracking_admin_yn']) ? $settings_data_['pb_google_analysis_tracking_admin_yn'] : "N");
}
pb_hook_add_action('pb-admin-update-site-settings', "_pb_ajax_google_analysis_hook_update_site_settings");

function _pb_head_hook_for_google_analysis(){
	$site_key_ = pb_option_value('pb_google_analysis_site_key');
	if(!strlen($site_key_)) return;

	?>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=<?=$site_key_?>"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
	gtag('config', '<?=$site_key_?>');
	</script>
	<?php
}

pb_hook_add_action('pb_head', '_pb_head_hook_for_google_analysis');
if(pb_option_value('pb_google_analysis_tracking_admin_yn', "N") === "Y"){
	pb_hook_add_action('pb_admin_head', '_pb_head_hook_for_google_analysis');
}

?>