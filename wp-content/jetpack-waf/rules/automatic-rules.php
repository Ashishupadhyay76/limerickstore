<?php
$rule = (object) array( 'id' => 901140, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'tx' => 
  array (
    'only' => 
    array (
      0 => 'critical_anomaly_score',
    ),
    'count' => true,
  ),
),'eq','0',false,false)) {
$waf->set_var('tx.critical_anomaly_score','5');
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 901141, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'tx' => 
  array (
    'only' => 
    array (
      0 => 'error_anomaly_score',
    ),
    'count' => true,
  ),
),'eq','0',false,false)) {
$waf->set_var('tx.error_anomaly_score','4');
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 901142, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'tx' => 
  array (
    'only' => 
    array (
      0 => 'warning_anomaly_score',
    ),
    'count' => true,
  ),
),'eq','0',false,false)) {
$waf->set_var('tx.warning_anomaly_score','3');
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 901143, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'tx' => 
  array (
    'only' => 
    array (
      0 => 'notice_anomaly_score',
    ),
    'count' => true,
  ),
),'eq','0',false,false)) {
$waf->set_var('tx.notice_anomaly_score','2');
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 901160, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'tx' => 
  array (
    'only' => 
    array (
      0 => 'allowed_methods',
    ),
    'count' => true,
  ),
),'eq','0',false,false)) {
$waf->set_var('tx.allowed_methods','GET HEAD POST OPTIONS');
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$waf->set_var('tx.anomaly_score','0');
$waf->set_var('tx.anomaly_score_pl1','0');
$waf->set_var('tx.anomaly_score_pl2','0');
$waf->set_var('tx.anomaly_score_pl3','0');
$waf->set_var('tx.anomaly_score_pl4','0');
$waf->set_var('tx.sql_injection_score','0');
$waf->set_var('tx.xss_score','0');
$waf->set_var('tx.rfi_score','0');
$waf->set_var('tx.lfi_score','0');
$waf->set_var('tx.rce_score','0');
$waf->set_var('tx.php_injection_score','0');
$waf->set_var('tx.http_violation_score','0');
$waf->set_var('tx.session_fixation_score','0');
$waf->set_var('tx.inbound_anomaly_score','0');
$waf->set_var('tx.outbound_anomaly_score','0');
$waf->set_var('tx.outbound_anomaly_score_pl1','0');
$waf->set_var('tx.outbound_anomaly_score_pl2','0');
$waf->set_var('tx.outbound_anomaly_score_pl3','0');
$waf->set_var('tx.outbound_anomaly_score_pl4','0');
$waf->set_var('tx.sql_error_match','0');
$waf->set_var('tx.tx_source_jetpack','0');
$waf->set_var('tx.allowed_methods','GET HEAD POST OPTIONS PUT DELETE PATCH PURGE');
$rule = (object) array( 'id' => 4280017, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'begins_with','/is-admin/api/',false,false)) {
$waf->flag_rule_for_removal('id','932100');
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99100021, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin.php#Ds',false,false)) {
if($waf->match_targets(array (
),array (
  'args_get' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*page$/',
    ),
  ),
),'rx','#^CiviCRM$#Ds',false,false)) {
$waf->flag_target_for_removal('id','921130','args','report_header');
$waf->flag_target_for_removal('id','921130','args','report_footer');
$waf->flag_target_for_removal('id','941170','args','report_header');
$waf->flag_target_for_removal('id','941170','args','report_footer');
$waf->flag_target_for_removal('id','941160','args','report_header');
$waf->flag_target_for_removal('id','941160','args','report_footer');
$waf->flag_target_for_removal('id','941190','args','report_header');
$waf->flag_target_for_removal('id','941190','args','report_footer');
$waf->flag_target_for_removal('id','941250','args','report_header');
$waf->flag_target_for_removal('id','941250','args','report_footer');
$waf->flag_target_for_removal('id','941260','args','report_header');
$waf->flag_target_for_removal('id','941260','args','report_footer');
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99100023, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#(?i)\\.(?:jpe?g|png|gif|ico|css|js|woff2?|ttf|eot|otf|svg|webp)$#Ds',false,false)) {
$waf->flag_target_for_removal('id','99110071','request_uri',NULL);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99100025, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'ends_with','/wp-admin/admin.php',false,false)) {
if($waf->match_targets(array (
),array (
  'args_get' => 
  array (
    'only' => 
    array (
      0 => 'page',
    ),
  ),
),'streq','wpcode-snippet-manager',false,false)) {
$waf->flag_target_for_removal('id','99110061','args','wpcode_snippet_code');
$waf->flag_target_for_removal('id','99110071','args','wpcode_snippet_code');
$waf->flag_target_for_removal('id','99110067','args','wpcode_snippet_code');
$waf->flag_target_for_removal('id','942160','args','wpcode_snippet_code');
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99100032, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-content/glive/[^/]+\\.html$#Ds',false,false)) {
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => 'f',
    ),
  ),
),'rx','#\\.clax$#Ds',false,false)) {
$waf->flag_target_for_removal('id','99110071','args','f');
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110001, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'uppercase',
),array (
  'request_method' => 
  array (
  ),
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => '/^(?i)X-HTTP-METHOD-OVERRIDE$/',
    ),
  ),
  'args_get' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*.method$/',
    ),
  ),
),'rx','#^(PUT|PATCH|DELETE)$#Ds',false,false)) {
if($waf->match_targets(array (
),array (
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => '/^(?i)Content-Type$/',
    ),
    'count' => true,
  ),
),'eq','0',false,false)) {
$waf->set_body_processor('URLENCODED');
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110002, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'uppercase',
),array (
  'request_method' => 
  array (
  ),
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => '/^(?i)X-HTTP-METHOD-OVERRIDE$/',
    ),
  ),
  'args_get' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*.method$/',
    ),
  ),
),'rx','#^(PUT|PATCH|DELETE)$#Ds',false,false)) {
if($waf->match_targets(array (
  0 => 'lowercase',
),array (
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => '/^(?i)Content-Type$/',
    ),
  ),
),'contains','/',true,false)) {
$waf->set_body_processor('URLENCODED');
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 210005, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => 'content-type',
    ),
  ),
),'rx','#(?i)(?:^|[\\s,])application/(?:[\\w!\\#\\$&-\\^\\.\\+]+\\+)?json(?:\\+oembed)?(?:$|[\\s;,])#Ds',false,false)) {
$waf->set_body_processor('JSON');
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99101301, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_filename' => 
  array (
  ),
),'rx','#3dprint.*tinyfilemanager\\.php$#Ds',false,false)) {
$rule->reason = '3DPrint-FileManager json payload blocked';
if($waf->match_targets(array (
),array (
  'request_method' => 
  array (
  ),
),'rx','#POST#Ds',false,false)) {
$rule->reason = '3DPrint-FileManager json payload blocked';
if($waf->match_targets(array (
),array (
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => 'content-type',
    ),
  ),
),'rx','#application/json#Ds',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = '3DPrint-FileManager json payload blocked';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110012, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_filename' => 
  array (
  ),
),'rx','#\\/wp-content\\/plugins\\/core-stab\\/index.*\\.php$#Ds',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'core-stab fake plugin direct access blocked';
return $waf->block('block',$rule->id,$rule->reason,403);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110018, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-content/plugins/media-library-assistant/includes/mla-stream-image.php#Ds',false,false)) {
$rule->reason = 'Local File Inclusion or Remote Code Execution attempt against Media Library Assistant '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*mla.stream.file$/',
    ),
  ),
),'rx','#(^(https?|s?ftp|php|zlib|data|glob|phar|ssh2?|rar|ogg|expect)://)#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Local File Inclusion or Remote Code Execution attempt against Media Library Assistant '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110020, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*rest.route$/',
    ),
  ),
  'request_filename' => 
  array (
  ),
),'rx','#(?i)(/wp-json/)?tdw/save_css#Ds',false,false)) {
$rule->reason = 'tagDiv Composer json paylod blocked '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => 'content-type',
    ),
  ),
),'rx','#application/json#Ds',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'tagDiv Composer json paylod blocked '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110025, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*rest.route$/',
    ),
  ),
  'request_filename' => 
  array (
  ),
),'rx','#(?i)(/wp-json/)?wml/v1/wml_logs/?$#Ds',false,false)) {
$rule->reason = 'SQL injection in wp-mail-log (non-JSON request)';
if($waf->match_targets(array (
),array (
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => 'content-type',
    ),
  ),
),'rx','#(?i)application/json#Ds',true,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'SQL injection in wp-mail-log (non-JSON request)';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110027, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*rest.route$/',
    ),
  ),
  'request_filename' => 
  array (
  ),
),'rx','#(?i)(/wp-json/)?wml/v1/wml_logs/send_mail/?$#Ds',false,false)) {
$rule->reason = 'SQL injection in wp-mail-log send_mail (JSON request)';
if($waf->match_targets(array (
),array (
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => 'content-type',
    ),
  ),
),'rx','#(?i)application/json#Ds',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'SQL injection in wp-mail-log send_mail (JSON request)';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110028, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_cookies' => 
  array (
    'only' => 
    array (
      0 => '/wordpress.logged.in/',
    ),
  ),
),'rx','#^[^|]*[\'\\"]#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'SQL Injection in wordpress_logged_in cookie '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110029, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-content/backups-dup-.*/tmp/?$#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Attempt to list Duplicator backups directory '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110035, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'url_decode',
  1 => 'lowercase',
  2 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*rest.route$/',
    ),
  ),
),'rx','#(?i)(/wp-json/)?post-smtp/v1/connect-app#Ds',false,false)) {
if($waf->match_targets(array (
  0 => 'lowercase',
),array (
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => '/^(?i)auth.key$/',
    ),
  ),
),'rx','#(?i)^0?$#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110036, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'url_decode',
  1 => 'lowercase',
  2 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*rest.route$/',
    ),
  ),
),'rx','#(?i)(/wp-json/)?post-smtp/v1/connect-app#Ds',false,false)) {
if($waf->match_targets(array (
  0 => 'lowercase',
),array (
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => 'auth-key',
    ),
    'count' => true,
  ),
),'eq','0',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110037, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'url_decode',
  1 => 'lowercase',
  2 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#(?i)/backup-heart\\.php$#Ds',false,false)) {
if($waf->match_targets(array (
),array (
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => 'content-dir',
    ),
  ),
),'rx','#(?i)^\\w+://#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110051, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => 'user-agent',
    ),
  ),
),'rx','#^wp_is_mobile|Moblie Safari|SM-G892A Bulid|Mozlila|Team Anon Force|8mqULwuL-67#Ds',false,false)) {
$rule->reason = 'Blocked request - contains malware crawler user-agent';
return $waf->block('block',$rule->id,$rule->reason,403);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110052, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => 'referer',
    ),
  ),
),'rx','#^(www.)?google\\.com$|^(www.)?bing\\.com$|^(www.)?binance\\.com$|^(www.)?google\\.comwww\\.duckduckgo\\.com$|^(www.)?yahoo\\.com$#Ds',false,false)) {
$rule->reason = 'Blocked request - contains malware crawler referer URL';
return $waf->block('deny',$rule->id,$rule->reason,403);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110068, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'args_get' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*page$/',
    ),
  ),
),'streq','postman_email_log',false,false)) {
$rule->reason = 'Post SMTP < 3.6.1 - Unauthenticated Email Log Leak';
if($waf->match_targets(array (
),array (
  'args_get' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*view$/',
    ),
  ),
),'streq','log',false,false)) {
$rule->reason = 'Post SMTP < 3.6.1 - Unauthenticated Email Log Leak';
if($waf->match_targets(array (
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin.php#Ds',true,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Post SMTP < 3.6.1 - Unauthenticated Email Log Leak';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 920100, 'reason' => '', 'tags' => array (
  0 => 'application-multi',
  1 => 'language-multi',
  2 => 'platform-multi',
  3 => 'attack-protocol',
  4 => 'paranoia-level/1',
  5 => 'owasp_crs',
  6 => 'capec/1000/210/272',
) );
try {
if($waf->match_targets(array (
),array (
  'request_line' => 
  array (
  ),
),'rx','#(?i)^(?:(?:[a-z]{3,10}\\s+(?:\\w{3,7}?://[\\w\\-\\./]*(?::\\d+)?)?\\/[^?\\#]*(?:\\?[^\\#\\s]*)?(?:\\#[\\S]*)?|connect (?:(?:\\d{1,3}\\.){3}\\d{1,3}\\.?(?::\\d+)?|[\\w\\-\\./]+:\\d+)|options \\*)\\s+[\\w\\./]+|get \\/[^?\\#]*(?:\\?[^\\#\\s]*)?(?:\\#[\\S]*)?)$#Ds',true,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.warning_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Invalid HTTP Request Line '.$waf->meta('request_line');
return $waf->block('block',$rule->id,$rule->reason,403);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 920330, 'reason' => '', 'tags' => array (
  0 => 'application-multi',
  1 => 'language-multi',
  2 => 'platform-multi',
  3 => 'attack-protocol',
  4 => 'paranoia-level/1',
  5 => 'owasp_crs',
  6 => 'capec/1000/210/272',
) );
try {
if($waf->match_targets(array (
),array (
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => 'user-agent',
    ),
  ),
),'rx','#^$#Ds',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.notice_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Empty User Agent Header';
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$waf->set_var('tx.anomaly_score','0');
$waf->set_var('tx.anomaly_score_pl1','0');
$waf->set_var('tx.anomaly_score_pl2','0');
$waf->set_var('tx.anomaly_score_pl3','0');
$waf->set_var('tx.anomaly_score_pl4','0');
$waf->set_var('tx.sql_injection_score','0');
$waf->set_var('tx.xss_score','0');
$waf->set_var('tx.rfi_score','0');
$waf->set_var('tx.lfi_score','0');
$waf->set_var('tx.rce_score','0');
$waf->set_var('tx.php_injection_score','0');
$waf->set_var('tx.http_violation_score','0');
$waf->set_var('tx.session_fixation_score','0');
$waf->set_var('tx.inbound_anomaly_score','0');
$waf->set_var('tx.outbound_anomaly_score','0');
$waf->set_var('tx.outbound_anomaly_score_pl1','0');
$waf->set_var('tx.outbound_anomaly_score_pl2','0');
$waf->set_var('tx.outbound_anomaly_score_pl3','0');
$waf->set_var('tx.outbound_anomaly_score_pl4','0');
$waf->set_var('tx.sql_error_match','0');
$waf->set_var('tx.tx_source_jetpack','0');
$waf->set_var('tx.allowed_methods','GET HEAD POST OPTIONS PUT DELETE PATCH PURGE');
$rule = (object) array( 'id' => 99100020, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-ajax.php#Ds',false,false)) {
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*action$/',
    ),
  ),
),'rx','#elementor_ajax#Ds',false,false)) {
$waf->flag_target_for_removal('id','941110','args','actions');
$waf->flag_target_for_removal('id','941120','args','actions');
$waf->flag_target_for_removal('id','941140','args','actions');
$waf->flag_target_for_removal('id','941160','args','actions');
$waf->flag_target_for_removal('id','941170','args','actions');
$waf->flag_target_for_removal('id','941210','args','actions');
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99100022, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*rest.route$/',
    ),
  ),
  'request_uri' => 
  array (
  ),
),'rx','#(?i)(/wp-json/)?wp/v2/(posts|pages)#Ds',false,false)) {
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*json.content$/',
    ),
  ),
),'rx','#<!-- wp:html -->(.*?)<!-- /wp:html -->#Ds',false,true)) {
if($waf->match_targets(array (
),array (
  'tx' => 
  array (
    'only' => 
    array (
      0 => '1',
    ),
  ),
),'contains','<script',false,false)) {
$waf->flag_target_for_removal('id','941110','args','json.content');
$waf->flag_target_for_removal('id','941120','args','json.content');
$waf->flag_target_for_removal('id','941140','args','json.content');
$waf->flag_target_for_removal('id','941160','args','json.content');
$waf->flag_target_for_removal('id','941170','args','json.content');
$waf->flag_target_for_removal('id','941210','args','json.content');
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99100024, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-ajax.php#Ds',false,false)) {
if($waf->match_targets(array (
),array (
  'args' => 
  array (
  ),
),'rx','#(?is)(?:@import|\\burl\\s*\\()#Ds',false,false)) {
$waf->flag_target_for_removal('id','99110071','args',NULL);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99100026, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'utf8_to_unicode',
  1 => 'remove_nulls',
  2 => 'cmd_line',
),array (
  'args' => 
  array (
  ),
),'rx','#^[\\s\\S]*(?:(?:^|[\\\\\\\\/])\\.\\.[\\\\\\\\/]|[\\\\\\\\/]\\.\\.(?:[\\\\\\\\/]|$))[\\s\\S]*$#Ds',false,true)) {
if($waf->match_targets(array (
  0 => 'utf8_to_unicode',
  1 => 'remove_nulls',
  2 => 'cmd_line',
),array (
  'tx' => 
  array (
    'only' => 
    array (
      0 => '0',
    ),
  ),
),'rx','#https?://#Ds',false,false)) {
if($waf->match_targets(array (
  0 => 'utf8_to_unicode',
  1 => 'remove_nulls',
  2 => 'cmd_line',
),array (
  'tx' => 
  array (
    'only' => 
    array (
      0 => '0',
    ),
  ),
),'rx','#https?://[^?&,\\"]{1,250}[\\\\\\\\/]\\.\\.[\\\\\\\\/][^?&,\\"]{1,250}.(?:jpe?g|png|gif|ico|css|js|woff2?|ttf|svg|webp)#Ds',false,false)) {
$waf->flag_target_for_removal('id','99110071','args',NULL);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99100027, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'args' => 
  array (
  ),
),'rx','#(?:href|src|action|data|target)=[^>]*?\\.\\.[\\\\\\\\/]#Ds',false,false)) {
$waf->flag_target_for_removal('id','99110071','args',NULL);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99100037, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => 'referer',
    ),
  ),
),'rx','#/wp-admin/post(?:-new)?\\.php#Ds',false,false)) {
$waf->flag_target_for_removal('id','99110071','args',NULL);
$waf->flag_target_for_removal('id','99110009','args',NULL);
$waf->flag_target_for_removal('id','99110061','args',NULL);
$waf->flag_target_for_removal('id','99110062','args',NULL);
$waf->flag_target_for_removal('id','99110066','args',NULL);
$waf->flag_target_for_removal('id','99110067','args',NULL);
$waf->flag_target_for_removal('id','942160','args',NULL);
$waf->flag_target_for_removal('id','941240','args',NULL);
$waf->flag_target_for_removal('id','933110','args',NULL);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99100030, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => 'user-agent',
    ),
  ),
),'contains','WPUmbrella+https://wp-umbrella.com/communication-with-our-service',false,false)) {
$waf->flag_target_for_removal('id','99110071','args',NULL);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99100031, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => 'referer',
    ),
  ),
),'rx','#/wp-admin/customize\\.php#Ds',false,false)) {
$waf->flag_target_for_removal('id','99110071','args',NULL);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99100033, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/options.php#Ds',false,false)) {
$waf->flag_target_for_removal('id','99110066','args',NULL);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99100034, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_uri' => 
  array (
  ),
),'rx','#[^?\\#]*/wp-json/autonami/v1/wc-add-to-cart$#Ds',false,false)) {
$waf->flag_target_for_removal('id','99110066','args',NULL);
$waf->flag_target_for_removal('id','99110067','args',NULL);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99100035, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-ajax.php#Ds',false,false)) {
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => 'action',
    ),
  ),
),'streq','wpide_request',false,false)) {
$waf->flag_target_for_removal('id','933110','files',NULL);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99100036, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => 'referer',
    ),
  ),
),'contains','/wp-admin/admin.php?page=import-code-snippets',false,false)) {
$waf->flag_target_for_removal('id','933110','files',NULL);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99100038, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/.*password$/',
    ),
  ),
),'rx','#(?:(?:^|[\\\\\\\\/])\\.\\.[\\\\\\\\/]|[\\\\\\\\/]\\.\\.(?:[\\\\\\\\/]|$))#Ds',false,false)) {
$waf->flag_target_for_removal('id','99110071','args',NULL);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99100039, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_filename' => 
  array (
  ),
  'args_get' => 
  array (
    'only' => 
    array (
      0 => 'rest_route',
    ),
  ),
),'rx','#(?:^|/)wp-json/jetpack-boost-ds/critical-css-state/action/set-provider-css/?$|^/?jetpack-boost-ds/critical-css-state/action/set-provider-css/?$#Ds',false,false)) {
$waf->flag_target_for_removal('id','99110071','args','json.JSON.css');
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110008, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-post.php#Ds',false,false)) {
$rule->reason = 'Poll, Survey, Form & Quiz Maker XSS Attempt Detected '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args_get' => 
  array (
    'only' => 
    array (
      0 => 'page',
    ),
  ),
),'rx','#opinionstage-content-login-callback-page#Ds',false,false)) {
$rule->reason = 'Poll, Survey, Form & Quiz Maker XSS Attempt Detected '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args_get' => 
  array (
  ),
),'rx','#(var\\s*u\\s*=|\\"?>.+)#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Poll, Survey, Form & Quiz Maker XSS Attempt Detected '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110010, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-ajax.php#Ds',false,false)) {
$rule->reason = 'WooCommerce Currency Switcher LFI Attempt Detected '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => 'action',
    ),
  ),
),'streq','parse-media-shortcode',false,false)) {
$rule->reason = 'WooCommerce Currency Switcher LFI Attempt Detected '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args_post' => 
  array (
    'only' => 
    array (
      0 => 'shortcode',
    ),
  ),
),'rx','#(\\[woocs\\s.*(?<=\\s)pagepath[^\\]]+\\])#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'WooCommerce Currency Switcher LFI Attempt Detected '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110011, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-ajax.php#Ds',false,false)) {
$rule->reason = '3DPrint Lite Arbitrary File Upload detected '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*action$/',
    ),
  ),
),'streq','p3dlite_handle_upload',false,false)) {
$rule->reason = '3DPrint Lite Arbitrary File Upload detected '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'files' => 
  array (
  ),
),'rx','#\\.php#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = '3DPrint Lite Arbitrary File Upload detected '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110009, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'url_decode',
),$waf->update_targets(array (
  'request_cookies' => 
  array (
    'except' => 
    array (
      0 => '/__utm/',
    ),
  ),
  'request_cookies_names' => 
  array (
  ),
  'args_names' => 
  array (
  ),
  'args' => 
  array (
  ),
), $rule->id, $rule->tags),'rx','#(?i:(union (?:all )?select (?:(?:1,)+concat\\(0x|null|unhex|0x6c6f67696e70776e7a,|char\\(45,120,49,45,81,45\\))))#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Block MSSQL code execution and information gathering attempts '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99101000, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-ajax.php#Ds',false,false)) {
$rule->reason = 'Potential Ninja Forms RCE';
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*action$/',
    ),
  ),
),'rx','#nf_ajax_submit#Ds',false,false)) {
$rule->reason = 'Potential Ninja Forms RCE';
if($waf->match_targets(array (
  0 => 'url_decode',
),array (
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => 'referer',
    ),
  ),
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*.wp.http.referer$/',
    ),
  ),
),'rx','#\\?.*::#Ds',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Potential Ninja Forms RCE';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99101002, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-ajax.php#Ds',false,false)) {
$rule->reason = 'Authentication bypass exploit attempt';
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*action$/',
    ),
  ),
),'rx','#^ajax_save_options$#Ds',false,false)) {
$rule->reason = 'Authentication bypass exploit attempt';
if($waf->match_targets(array (
),array (
  'args_post' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*iubenda.section.name$/',
    ),
  ),
),'rx','#^(?!iubenda_)#Ds',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Authentication bypass exploit attempt';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99101003, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-ajax.php#Ds',false,false)) {
$rule->reason = 'Backdoor Upload Exploit Attempt';
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*action$/',
    ),
  ),
),'rx','#^user_registration_profile_pic_upload$#Ds',false,false)) {
$rule->reason = 'Backdoor Upload Exploit Attempt';
if($waf->match_targets(array (
),array (
  'files' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*file$/',
    ),
  ),
),'rx','#\\.(ph(p|tml)[3-8]?|htaccess)$#Ds',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Backdoor Upload Exploit Attempt';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99101004, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-ajax.php#Ds',false,false)) {
$rule->reason = 'Backdoor Upload Exploit Attempt';
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => 'action',
    ),
  ),
),'rx','#jb-upload-company-logo#Ds',false,false)) {
$rule->reason = 'Backdoor Upload Exploit Attempt';
if($waf->match_targets(array (
),array (
  'request_cookies' => 
  array (
  ),
),'rx','#\\.(ph(p|tml)[3-8]?|htaccess)$#Ds',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Backdoor Upload Exploit Attempt';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99101005, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-ajax.php#Ds',false,false)) {
$rule->reason = 'Arbitrary Plugin Upload Attack Detected';
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*action$/',
    ),
  ),
),'rx','#^(stopbadbots|wpmemory|wptools|antihacker|cardealer)_install_plugin$#Ds',false,false)) {
$rule->reason = 'Arbitrary Plugin Upload Attack Detected';
if($waf->match_targets(array (
),array (
  'args_post' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*slug$/',
    ),
  ),
),'rx','#^(antihacker|stopbadbots|recaptcha-for-all|wp-memory|toolstruthsocial)$#Ds',true,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Arbitrary Plugin Upload Attack Detected';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99101300, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_filename' => 
  array (
  ),
),'rx','#3dprint.*tinyfilemanager\\.php$#Ds',false,false)) {
$rule->reason = '3DPrint-FileManager directory traversal attack';
if($waf->match_targets(array (
),array (
  'args_post_names' => 
  array (
  ),
),'rx','#(?i)group#Ds',false,false)) {
$rule->reason = '3DPrint-FileManager directory traversal attack';
if($waf->match_targets(array (
),array (
  'args_post_names' => 
  array (
  ),
),'rx','#(?i)(delete|zip|tar)#Ds',false,false)) {
$rule->reason = '3DPrint-FileManager directory traversal attack';
if($waf->match_targets(array (
),array (
  'args_post' => 
  array (
    'only' => 
    array (
      0 => 'file',
    ),
  ),
),'rx','#\\.\\.(\\\\|/)#Ds',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = '3DPrint-FileManager directory traversal attack';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110014, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
  1 => 'lowercase',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-json/wp/v2/users#Ds',false,false)) {
$rule->reason = 'Block WooCommerce-Payments Priv. Escalation';
if($waf->match_targets(array (
),array (
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => '/^(?i)X-WCPAY-PLATFORM-CHECKOUT-USER$/',
    ),
  ),
),'rx','#^\\d+$#Ds',false,false)) {
$rule->reason = 'Block WooCommerce-Payments Priv. Escalation';
if($waf->match_targets(array (
  0 => 'lowercase',
),array (
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => '/^(?i)Content-Type$/',
    ),
  ),
),'rx','#application/json#Ds',false,false)) {
$rule->reason = 'Block WooCommerce-Payments Priv. Escalation';
if($waf->match_targets(array (
  0 => 'lowercase',
),array (
  'request_body' => 
  array (
  ),
),'contains','administrator',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Block WooCommerce-Payments Priv. Escalation';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110015, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*rest.route$/',
    ),
  ),
  'request_filename' => 
  array (
  ),
),'rx','#(?i)(/wp-json/)?frm-admin/v1/install-addon#Ds',false,false)) {
$rule->reason = 'Block Formidable-Forms Arbitrary Plugin Install. Matched Data: '. htmlentities($waf->matched_var, ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*file.url$/',
    ),
  ),
  'request_cookies' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*file.url$/',
    ),
  ),
),'rx','#(?i)wordpress\\.org#Ds',false,false)) {
$rule->reason = 'Block Formidable-Forms Arbitrary Plugin Install. Matched Data: '. htmlentities($waf->matched_var, ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*file.url$/',
    ),
  ),
  'request_cookies' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*file.url$/',
    ),
  ),
),'rx','#^(?i)https://downloads\\.wordpress\\.org/plugin/((formidable-(gravity-forms-importer|import-pirate-forms))|wp-mail-smtp)\\.zip$#Ds',true,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Block Formidable-Forms Arbitrary Plugin Install. Matched Data: '. htmlentities($waf->matched_var, ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110016, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-ajax.php#Ds',false,false)) {
$rule->reason = 'Remote Code Execution attempt against WP User Post Gallary detected '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*action$/',
    ),
  ),
),'streq','upg_datatable',false,false)) {
$rule->reason = 'Remote Code Execution attempt against WP User Post Gallary detected '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args' => 
  array (
  ),
),'rx','#field#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Remote Code Execution attempt against WP User Post Gallary detected '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110017, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'args_post_names' => 
  array (
  ),
),'rx','#^\\s*um.request$#Ds',false,false)) {
$rule->reason = 'Privilege Escalation attack against Ultimate Member detected '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args_post_names' => 
  array (
  ),
),'rx','#^\\s*.wpnonce$#Ds',false,false)) {
$rule->reason = 'Privilege Escalation attack against Ultimate Member detected '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args_post_names' => 
  array (
  ),
),'rx','#^\\s*form.id$#Ds',false,false)) {
$rule->reason = 'Privilege Escalation attack against Ultimate Member detected '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args_post_names' => 
  array (
  ),
),'rx','#\\[(?:administrator|editor|author|contributor)\\]$#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Privilege Escalation attack against Ultimate Member detected '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110019, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*rest.route$/',
    ),
  ),
  'request_filename' => 
  array (
  ),
),'rx','#(?i)(/wp-json/)?tdw/save_css#Ds',false,false)) {
$rule->reason = 'XSS attempt against tagDiv Composer '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*compiled.css$/',
    ),
  ),
),'rx','#(<[^>]+>)#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'XSS attempt against tagDiv Composer '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110021, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-ajax.php#Ds',false,false)) {
$rule->reason = 'Malicious attempt to delete options '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*action$/',
    ),
  ),
),'rx','#^(two_init_flow_score|two_activate_score_check)$#Ds',false,false)) {
$rule->reason = 'Malicious attempt to delete options '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*nonce$/',
    ),
  ),
),'rx','#^(?!two_).*$#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Malicious attempt to delete options '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110022, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-ajax.php#Ds',false,false)) {
$rule->reason = 'Attempted RCE through file upload '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*action$/',
    ),
  ),
),'streq','rtmedia_api',false,false)) {
$rule->reason = 'Attempted RCE through file upload '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*image.type$/',
    ),
  ),
),'rx','#.*(?:php\\d*|phtml)\\.*$#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Attempted RCE through file upload '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110023, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*rest.route$/',
    ),
  ),
  'request_filename' => 
  array (
  ),
),'rx','#(?i)(/wp-json/)?wml/v1/wml_logs/send_mail/?$#Ds',false,false)) {
$rule->reason = 'Backdoor Upload Exploit Attempt';
if($waf->match_targets(array (
),array (
  'files' => 
  array (
  ),
),'rx','#(?i)\\.(ph(p|tml)\\d*|htaccess)$#Ds',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Backdoor Upload Exploit Attempt';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110024, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*rest.route$/',
    ),
  ),
  'request_filename' => 
  array (
  ),
),'rx','#(?i)(/wp-json/)?wml/v1/wml_logs/?$#Ds',false,false)) {
$rule->reason = 'SQL injection in wp-mail-log';
if($waf->match_targets(array (
),array (
  'request_body' => 
  array (
  ),
),'rx','#(?i)(\\"key\\"\\s*:\\s*\\"\\w*[^\\w\\"]|\\"operator\\"\\s*:\\s*(?!\\"\\s*(=|!=|LIKE|NOT\\s+LIKE)\\s*\\")|\\"value\\"\\s*:\\s*\\"(?:[^\'\\"]|\\\\\\")*\'|\\"filterRelation\\"\\s*:\\s*(?!\\"\\s*(and|or)\\s*\\")|\\"page(Size|Index)\\"\\s*:\\s*[^\\d])#Ds',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'SQL injection in wp-mail-log';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110026, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*rest.route$/',
    ),
  ),
  'request_filename' => 
  array (
  ),
),'rx','#(?i)(/wp-json/)?wml/v1/wml_logs/send_mail/?$#Ds',false,false)) {
$rule->reason = 'SQL injection in wp-mail-log send_mail';
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*id$/',
    ),
  ),
),'rx','#[^\\d]#Ds',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'SQL injection in wp-mail-log send_mail';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110030, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'url_decode',
  1 => 'lowercase',
  2 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*rest.route$/',
    ),
  ),
),'rx','#/wpgmza/v1/(markers|features|polygons|polylines|circles|rectangles|pointlabels)#Ds',false,false)) {
if($waf->match_targets(array (
  0 => 'lowercase',
),array (
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => 'x-http-method-override',
    ),
  ),
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*.method$/',
    ),
  ),
),'streq','get',false,false)) {
if($waf->match_targets(array (
  0 => 'lowercase',
),array (
  'request_method' => 
  array (
  ),
),'streq','get',true,false)) {
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110031, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'lowercase',
),array (
  'request_filename' => 
  array (
  ),
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*rest.route$/',
    ),
  ),
),'rx','#/essential-blocks/v1/queries#Ds',false,false)) {
$rule->reason = 'LFI in Essential Blocks';
if($waf->match_targets(array (
),array (
  'request_body' => 
  array (
  ),
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*attributes$/',
    ),
  ),
),'rx','#\\\\?\\x22__file\\\\?\\x22#Ds',false,false)) {
$rule->reason = 'LFI in Essential Blocks';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110032, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'args_post' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*sgpb-is-preview$/',
    ),
  ),
),'streq','1',false,false)) {
$rule->reason = 'Popup Builder Stored XSS exploit attempt blocked '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'streq','/wp-admin/post.php',true,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Popup Builder Stored XSS exploit attempt blocked '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110033, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'url_decode',
  1 => 'lowercase',
  2 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'contains','burst-statistics-endpoint.php',false,false)) {
if($waf->match_targets(array (
),array (
  'request_body' => 
  array (
  ),
),'rx','#(?i)(\\\\\\"url\\\\\\"\\s*:\\s*\\\\\\"(([^\'\\"]|\\\\\\\\\\")*\')([^\\"]|\\\\\\\\\\")*[^\\\\\\\\]\\\\\\")#Ds',false,true)) {
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110034, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'url_decode',
  1 => 'lowercase',
  2 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-ajax.php#Ds',false,false)) {
if($waf->match_targets(array (
  0 => 'url_decode',
  1 => 'lowercase',
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*action$/',
    ),
  ),
),'rx','#elementor_ajax#Ds',false,false)) {
if($waf->match_targets(array (
  0 => 'url_decode',
  1 => 'lowercase',
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*actions$/',
    ),
  ),
),'rx','#(\\"import_template\\"\\s*:\\s*\\{)#Ds',false,false)) {
if($waf->match_targets(array (
  0 => 'url_decode',
  1 => 'lowercase',
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*actions$/',
    ),
  ),
),'rx','#(\\"filename\\"\\s*:\\s*\\"((?!\\\\\\\\)[^\\"]|\\\\\\")*(\\.ph(p\\d*|tml)[ .]*\\"|\\.\\.\\/))#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110038, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'url_decode',
  1 => 'lowercase',
  2 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-ajax.php#Ds',false,false)) {
$rule->reason = 'Cookie Information Arbitrary Option Update Exploit '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*action$/',
    ),
  ),
),'streq','wpgdprc_update_integration',false,false)) {
$rule->reason = 'Cookie Information Arbitrary Option Update Exploit '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args_post' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*data$/',
    ),
  ),
),'rx','#(\\"name\\"\\s*:\\s*\\"(?!wpgdprc_[^\\"]+|integrations\\[[^\\"]+)[^\\"]+\\"|\\"[^\\"]*\\\\[^\\"]*\\":)#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Cookie Information Arbitrary Option Update Exploit '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99101303, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-ajax.php#Ds',false,false)) {
$rule->reason = 'Malicious file upload blocked '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*action$/',
    ),
  ),
),'streq','wpr_addons_upload_file',false,false)) {
$rule->reason = 'Malicious file upload blocked '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'files' => 
  array (
  ),
),'rx','#\\..*([^a-zA-Z0-9_.]+|\\.$)#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Malicious file upload blocked '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110039, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'url_decode',
  1 => 'lowercase',
  2 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-ajax.php#Ds',false,false)) {
$rule->reason = 'Attempted XSS in events calendar plugin  '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*action$/',
    ),
  ),
),'streq','tribe_events_views_v2_fallback',false,false)) {
$rule->reason = 'Attempted XSS in events calendar plugin  '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
  0 => 'url_decode',
  1 => 'lowercase',
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*view$/',
    ),
  ),
),'rx','#reflector#Ds',false,false)) {
$rule->reason = 'Attempted XSS in events calendar plugin  '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args' => 
  array (
  ),
),'rx','#<#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Attempted XSS in events calendar plugin  '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110040, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-content/plugins/wp-automatic/inc/csv.*\\.php$#Ds',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Automatic < 3.92.1 - Unauthenticated SQL Injection';
return $waf->block('block',$rule->id,$rule->reason,403);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110041, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'url_decode',
  1 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/#Ds',true,false)) {
$rule->reason = 'Yoast SEO < 22.6 - Reflected Cross Site Scripting';
if($waf->match_targets(array (
),array (
  'args_get' => 
  array (
    'only' => 
    array (
      0 => 'page',
    ),
  ),
),'rx','#[\\"]#Ds',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Yoast SEO < 22.6 - Reflected Cross Site Scripting';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110042, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'url_decode',
  1 => 'lowercase',
  2 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*rest.route$/',
    ),
  ),
),'rx','#(?i)(/wp-json/)?seopress/v1/posts/#Ds',false,false)) {
$rule->reason = 'SEOPress Auth. Bypass';
if($waf->match_targets(array (
),array (
  'request_headers_names' => 
  array (
  ),
),'rx','#(?i)Authorization#Ds',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'SEOPress Auth. Bypass';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110043, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'url_decode',
  1 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/#Ds',true,false)) {
$rule->reason = 'XML Sitemap & Google News <= 5.4.8 - Local File Inclusion';
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => 'feed',
    ),
  ),
),'rx','#sitemap[^/]*/#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'XML Sitemap & Google News <= 5.4.8 - Local File Inclusion';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110044, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'url_decode',
  1 => 'lowercase',
  2 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*rest.route$/',
    ),
  ),
),'rx','#(?i)(/wp-json/)?lp/v1/courses/archive-course#Ds',false,false)) {
$rule->reason = 'LearnPress <= 4.2.6.5 - Unauthenticated SQLi';
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => 'term_id',
    ),
  ),
),'rx','#\\)#Ds',false,false)) {
$rule->reason = 'LearnPress <= 4.2.6.5 - Unauthenticated SQLi';
if($waf->match_targets(array (
),array (
  'request_headers_names' => 
  array (
  ),
),'rx','#(?i)X-WP-Nonce#Ds',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'LearnPress <= 4.2.6.5 - Unauthenticated SQLi';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110045, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'url_decode',
  1 => 'lowercase',
  2 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-ajax.php#Ds',false,false)) {
$rule->reason = 'Email Subscribers by Icegram < 5.7.21 - Unauthenticated SQLi';
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => 'action',
    ),
  ),
),'streq','es_add_subscriber',false,false)) {
$rule->reason = 'Email Subscribers by Icegram < 5.7.21 - Unauthenticated SQLi';
if($waf->match_targets(array (
  0 => 'base64_decode_ext',
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*hash$/',
    ),
  ),
),'rx','#(?i)(\\"list_ids\\"\\s*:\\s*(\\"[^\\"]*(=|-|SELECT|FROM|\\\\u[0-9a-fA-F]{4})[^\\"]*\\"))#Ds',false,false)) {
$rule->reason = 'Email Subscribers by Icegram < 5.7.21 - Unauthenticated SQLi';
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*es$/',
    ),
  ),
),'streq','optin',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Email Subscribers by Icegram < 5.7.21 - Unauthenticated SQLi';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110046, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-ajax.php#Ds',false,false)) {
$rule->reason = 'Malicious object injection blocked '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*action$/',
    ),
  ),
),'streq','give_donation_import',false,false)) {
$rule->reason = 'Malicious object injection blocked '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args_post' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*mapto$/',
    ),
  ),
),'rx','#[oOcC]:?\\+?\\d+:\\".+?[\\w\\W]{2}\\+?\\d+..*}#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Malicious object injection blocked '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110047, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'url_decode',
  1 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/#Ds',true,false)) {
$rule->reason = 'Profile Builder <= 3.11.8 - Privilege Escalation';
if($waf->match_targets(array (
),array (
  'args_post' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*action$/',
    ),
  ),
),'streq','register',false,false)) {
$rule->reason = 'Profile Builder <= 3.11.8 - Privilege Escalation';
if($waf->match_targets(array (
),array (
  'args_post' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*email$/',
    ),
  ),
),'rx','#[\\s\\n\\r\\t\\v\\x00]#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Profile Builder <= 3.11.8 - Privilege Escalation';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110048, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'url_decode',
  1 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-ajax.php#Ds',false,false)) {
$rule->reason = 'GiveWP <= 3.14.1 - Object Injection';
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*action$/',
    ),
  ),
),'streq','give_process_donation',false,false)) {
$rule->reason = 'GiveWP <= 3.14.1 - Object Injection';
if($waf->match_targets(array (
),array (
  'args_post' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*give.title$/',
      1 => '/^\\s*give-form-title$/',
    ),
  ),
),'rx','#[oOC]\\\\*:\\\\*\\+?\\\\*\\d+\\\\*:\\\\*\\"#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'GiveWP <= 3.14.1 - Object Injection';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110049, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_cookies_names' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*litespeed.role$/',
    ),
    'count' => true,
  ),
),'gt','0',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'LiteSpeed Cache Privilege Escalation';
return $waf->block('block',$rule->id,$rule->reason,403);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110050, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'args_get' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*wc-ajax$/',
    ),
  ),
),'rx','#(tinvwl)#Ds',false,false)) {
$rule->reason = 'TI WooCommerce Wishlist <= 2.8.2 - Unauthenticated SQLi';
if($waf->match_targets(array (
),array (
  'args_post' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*lang$/',
    ),
  ),
),'rx','#(\\\\)#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'TI WooCommerce Wishlist <= 2.8.2 - Unauthenticated SQLi';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110053, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'url_decode',
  1 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-ajax.php#Ds',false,false)) {
$rule->reason = 'HUSKY < 1.3.6.6  - LFI';
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*action$/',
    ),
  ),
),'streq','woof_text_search',false,false)) {
$rule->reason = 'HUSKY < 1.3.6.6  - LFI';
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*template$/',
    ),
  ),
),'rx','#[^a-z0-9_\\-]#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'HUSKY < 1.3.6.6  - LFI';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110054, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'args_post' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*everest.forms.\\d+.[a-zA-Z0-9]{10}-\\d+$/',
    ),
  ),
),'rx','#(?i)(\\.(ph(p\\d?|html|ar)|htaccess)\\")#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Everest Forms < 3.0.9.5  - Arbitrary File Upload';
return $waf->block('block',$rule->id,$rule->reason,403);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110055, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'url_decode',
  1 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-ajax.php#Ds',false,false)) {
$rule->reason = 'Ultimate Member < 2.10.2  - SQL Injection';
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*action$/',
    ),
  ),
),'streq','um_get_members',false,false)) {
$rule->reason = 'Ultimate Member < 2.10.2  - SQL Injection';
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*search$/',
    ),
  ),
),'rx','#(?i)([$\\|]|user_pass|meta_value)#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Ultimate Member < 2.10.2  - SQL Injection';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110056, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*spbc.remote.call.token$/',
    ),
  ),
),'rx','#^(d41d8cd98f00b204e9800998ecf8427e|e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855)$#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Cleantalk Spam Protection < 6.45  - Unauthenticated Arbitrary Plugin Install';
return $waf->block('block',$rule->id,$rule->reason,403);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110057, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'url_decode',
  1 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-ajax.php#Ds',false,false)) {
$rule->reason = 'Depicter < 3.6.2  - SQL Injection';
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*action$/',
    ),
  ),
),'rx','#depicter-lead-(list|index)#Ds',false,false)) {
$rule->reason = 'Depicter < 3.6.2  - SQL Injection';
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*s$/',
    ),
  ),
),'rx','#(?i)SELECT.*FROM#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Depicter < 3.6.2  - SQL Injection';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110058, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'args_post' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*rating_filter$/',
    ),
  ),
),'rx','#(?i)^\\s*0*\\.?[1-5].*(user|select)#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Tutor LMS < 2.7.7  - Unauthenticated SQL Injection';
return $waf->block('block',$rule->id,$rule->reason,403);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110059, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'args_names' => 
  array (
  ),
),'rx','#^\\s*s$#Ds',false,false)) {
$rule->reason = 'Relevanssi < 4.24.5 - Unauthenticated SQL Injection';
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*cats$/',
      1 => '/^\\s*tags$/',
    ),
  ),
),'rx','#(?i)select.*from#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Relevanssi < 4.24.5 - Unauthenticated SQL Injection';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110060, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'url_decode',
  1 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-ajax.php#Ds',false,false)) {
$rule->reason = 'User-Registration < 4.1.2  - Privilege Escalation';
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*action$/',
    ),
  ),
),'rx','#user_registration_membership_register_member#Ds',false,false)) {
$rule->reason = 'User-Registration < 4.1.2  - Privilege Escalation';
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*members_data$/',
    ),
  ),
),'rx','#(\\"role\\"|\\\\u00[4567])#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'User-Registration < 4.1.2  - Privilege Escalation';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110061, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),$waf->update_targets(array (
  'request_cookies' => 
  array (
    'except' => 
    array (
      0 => '/__utm/',
    ),
  ),
  'request_cookies_names' => 
  array (
  ),
  'args_names' => 
  array (
  ),
  'args' => 
  array (
  ),
), $rule->id, $rule->tags),'rx','#(?i)php://filter(?:/|$)#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'PHP Injection Attack: php://filter stream found Matched Data: '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') .' found within '. htmlentities($waf->matched_var_name, ENT_QUOTES, 'UTF-8') .': '. htmlentities($waf->matched_var, ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110062, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'utf8_to_unicode',
  1 => 'remove_nulls',
  2 => 'cmd_line',
),$waf->update_targets(array (
  'request_cookies' => 
  array (
    'except' => 
    array (
      0 => '/__utm/',
    ),
  ),
  'request_cookies_names' => 
  array (
  ),
  'args_names' => 
  array (
  ),
  'args' => 
  array (
  ),
), $rule->id, $rule->tags),'rx','#(?i:phar)://#Ds',false,false)) {
$rule->reason = 'PHP Injection Attack: Wrapper scheme detected Matched Data: '. htmlentities($waf->matched_var, ENT_QUOTES, 'UTF-8') .' found within '. htmlentities($waf->matched_var_name, ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110063, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_filename' => 
  array (
  ),
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*rest.route$/',
    ),
  ),
),'rx','#(?i)(/wp-json/)?litespeed/v1/cdn_status#Ds',false,false)) {
$rule->reason = 'LiteSpeed Cache <= 5.7 - Unauthenticated Stored XSS';
if($waf->match_targets(array (
),array (
  'args_post' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*result\\[.msg\\]$/',
      1 => '/^\\s*result\\[nameservers\\]$/',
    ),
  ),
),'contains','<',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'LiteSpeed Cache <= 5.7 - Unauthenticated Stored XSS';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110064, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'url_decode',
  1 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-ajax.php#Ds',false,false)) {
$rule->reason = 'DB Backup <= 6.0 - Missing Authorization';
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*action$/',
    ),
  ),
),'streq','myAjax',false,false)) {
$rule->reason = 'DB Backup <= 6.0 - Missing Authorization';
if($waf->match_targets(array (
  0 => 'url_decode',
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*data$/',
    ),
  ),
),'contains','dbbkp_option',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'DB Backup <= 6.0 - Missing Authorization';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110065, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'args_get' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*wpfi-ajax$/',
    ),
  ),
),'streq','wp_freeio_ajax_register',false,false)) {
$rule->reason = 'WP FreeIO < 1.2.22 - Privilege Escalation';
if($waf->match_targets(array (
),array (
  'args_post' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*role$/',
    ),
  ),
),'rx','#^(?:wp_freeio_employer|wp_freeio_freelancer)$#Ds',true,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'WP FreeIO < 1.2.22 - Privilege Escalation';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110066, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),$waf->update_targets(array (
  'request_cookies' => 
  array (
    'except' => 
    array (
      0 => '/__utm/',
    ),
  ),
  'request_cookies_names' => 
  array (
  ),
  'request_headers' => 
  array (
  ),
  'args_names' => 
  array (
  ),
  'args' => 
  array (
  ),
), $rule->id, $rule->tags),'rx','#(?i)[OC]:\\d+:\\"(?!StdClass).+?\\":\\+?\\d+:{.*}#Ds',false,true)) {
$rule->reason = 'PHP Injection Attack: Serialized Object Injection Matched Data: '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') .' found within '. htmlentities($waf->matched_var_name, ENT_QUOTES, 'UTF-8') .': '. htmlentities($waf->matched_var, ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110067, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),$waf->update_targets(array (
  'request_uri' => 
  array (
  ),
  'request_headers' => 
  array (
  ),
  'args' => 
  array (
  ),
), $rule->id, $rule->tags),'validate_byte_range',array (
  'min' => 1,
  'max' => 255,
  'range' => 
  array (
    0 => 
    array (
      0 => 1,
      1 => 255,
    ),
  ),
),false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Invalid character in request (null character) '. htmlentities($waf->matched_var_name, ENT_QUOTES, 'UTF-8') .'='. htmlentities($waf->matched_var, ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110069, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'lowercase',
),array (
  'request_filename' => 
  array (
  ),
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*rest.route$/',
    ),
  ),
),'contains','/pbb/v1/popup/logs',false,false)) {
$rule->reason = 'Popup builder < 2.1.4 - Unauthenticated SQL Injection';
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*id$/',
    ),
  ),
),'rx','#[^0-9]#Ds',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Popup builder < 2.1.4 - Unauthenticated SQL Injection';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110070, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/admin-ajax.php#Ds',false,false)) {
$rule->reason = 'ACF Extended < 0.9.2 - RCE';
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*action$/',
    ),
  ),
),'streq','acfe/form/render_form_ajax',false,false)) {
$rule->reason = 'ACF Extended < 0.9.2 - RCE';
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^\\s*form\\[render\\]$/',
    ),
  ),
),'rx','#.#Ds',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'ACF Extended < 0.9.2 - RCE';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110071, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'remove_nulls',
),$waf->update_targets(array (
  'request_uri' => 
  array (
  ),
  'args' => 
  array (
  ),
  'request_headers' => 
  array (
    'except' => 
    array (
      0 => 'referer',
    ),
  ),
), $rule->id, $rule->tags),'rx','#(?:(?:^|[\\\\\\\\/])\\.\\.[\\\\\\\\/]|[\\\\\\\\/]\\.\\.(?:[\\\\\\\\/]|$))#Ds',false,true)) {
$rule->reason = 'Path Traversal Attack (/../) Matched Data: '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') .' found within '. htmlentities($waf->matched_var_name, ENT_QUOTES, 'UTF-8') .': '. htmlentities($waf->matched_var, ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110072, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'ends_with','/wp-content/plugins/wp2epub/myphp/epub.php',false,false)) {
$rule->reason = 'wp2epub - Blocking access to vulnerable epub.php file';
return $waf->block('block',$rule->id,$rule->reason,403);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110073, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/(?i)billing/',
      1 => '/(?i)shipping/',
    ),
  ),
),'rx','#(?:[OC]:\\d+:\\"[^\\"]{1,255}\\":\\+?\\d+[\\s\\S]{2}|E:\\d+:\\"[^\\"]{1,255}:[^\\"]{1,255}\\";)#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'WooCommerce Subscriptions - serialized PHP object in an address field Matched Data: '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') .' found within '. htmlentities($waf->matched_var_name, ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110074, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => 'user-agent',
      1 => 'x-forwarded-for',
      2 => 'x-real-ip',
      3 => 'client-ip',
      4 => 'true-client-ip',
      5 => 'cf-connecting-ip',
    ),
  ),
),'rx','#(?:[OC]:\\d+:\\"[^\\"]{1,255}\\":\\+?\\d+[\\s\\S]{2}|E:\\d+:\\"[^\\"]{1,255}:[^\\"]{1,255}\\";)#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'WooCommerce Subscriptions - serialized PHP object in a client-identity header Matched Data: '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') .' found within '. htmlentities($waf->matched_var_name, ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 210006, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_filename' => 
  array (
  ),
),'rx','#(?i)/.*batch/v1[/\\x5c]*$#Ds',false,false)) {
$rule->reason = 'WordPress REST API batch - invalid sub-request path '. htmlentities($waf->matched_var_name, ENT_QUOTES, 'UTF-8') .': '. htmlentities($waf->matched_var, ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^json\\.requests\\.[^.]+\\.path$/',
      1 => '/^requests\\[[^\\]]*\\]\\[path\\]$/',
    ),
  ),
),'rx','#^/(?![/\\x5c])#Ds',true,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'WordPress REST API batch - invalid sub-request path '. htmlentities($waf->matched_var_name, ENT_QUOTES, 'UTF-8') .': '. htmlentities($waf->matched_var, ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 210007, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^[\\x20]*rest[_\\x2e\\x20\\x5b]route$/',
    ),
  ),
),'rx','#(?i)^/batch/v1[/\\x5c]*$#Ds',false,false)) {
$rule->reason = 'WordPress REST API batch (rest_route) - invalid sub-request path '. htmlentities($waf->matched_var_name, ENT_QUOTES, 'UTF-8') .': '. htmlentities($waf->matched_var, ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^json\\.requests\\.[^.]+\\.path$/',
      1 => '/^requests\\[[^\\]]*\\]\\[path\\]$/',
    ),
  ),
),'rx','#^/(?![/\\x5c])#Ds',true,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'WordPress REST API batch (rest_route) - invalid sub-request path '. htmlentities($waf->matched_var_name, ENT_QUOTES, 'UTF-8') .': '. htmlentities($waf->matched_var, ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 210008, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_filename' => 
  array (
  ),
),'rx','#(?i)/.*batch/v1[/\\x5c]*$#Ds',false,false)) {
$rule->reason = 'WordPress REST API nested batch - invalid sub-request path '. htmlentities($waf->matched_var_name, ENT_QUOTES, 'UTF-8') .': '. htmlentities($waf->matched_var, ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^json\\.requests\\.[^.]+\\.path$/',
      1 => '/^requests\\[[^\\]]*\\]\\[path\\]$/',
    ),
  ),
),'rx','#(?i)^/batch/v1[/\\x5c]*$#Ds',false,false)) {
$rule->reason = 'WordPress REST API nested batch - invalid sub-request path '. htmlentities($waf->matched_var_name, ENT_QUOTES, 'UTF-8') .': '. htmlentities($waf->matched_var, ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^json\\.requests\\.[^.]+(?:\\.body\\.requests\\.[^.]+)+\\.path$/',
      1 => '/^requests\\[[^\\]]*\\](?:\\[body\\]\\[requests\\]\\[[^\\]]*\\])+\\[path\\]$/',
    ),
  ),
),'rx','#^/(?![/\\x5c])#Ds',true,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'WordPress REST API nested batch - invalid sub-request path '. htmlentities($waf->matched_var_name, ENT_QUOTES, 'UTF-8') .': '. htmlentities($waf->matched_var, ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 210009, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^[\\x20]*rest[_\\x2e\\x20\\x5b]route$/',
    ),
  ),
),'rx','#(?i)^/batch/v1[/\\x5c]*$#Ds',false,false)) {
$rule->reason = 'WordPress REST API nested batch (rest_route) - invalid sub-request path '. htmlentities($waf->matched_var_name, ENT_QUOTES, 'UTF-8') .': '. htmlentities($waf->matched_var, ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^json\\.requests\\.[^.]+\\.path$/',
      1 => '/^requests\\[[^\\]]*\\]\\[path\\]$/',
    ),
  ),
),'rx','#(?i)^/batch/v1[/\\x5c]*$#Ds',false,false)) {
$rule->reason = 'WordPress REST API nested batch (rest_route) - invalid sub-request path '. htmlentities($waf->matched_var_name, ENT_QUOTES, 'UTF-8') .': '. htmlentities($waf->matched_var, ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^json\\.requests\\.[^.]+(?:\\.body\\.requests\\.[^.]+)+\\.path$/',
      1 => '/^requests\\[[^\\]]*\\](?:\\[body\\]\\[requests\\]\\[[^\\]]*\\])+\\[path\\]$/',
    ),
  ),
),'rx','#^/(?![/\\x5c])#Ds',true,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'WordPress REST API nested batch (rest_route) - invalid sub-request path '. htmlentities($waf->matched_var_name, ENT_QUOTES, 'UTF-8') .': '. htmlentities($waf->matched_var, ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 210011, 'reason' => '', 'tags' => array (
  0 => 'wp-login-xss',
) );
try {
if($waf->match_targets(array (
),array (
  'request_filename' => 
  array (
  ),
),'ends_with','/wp-login.php',false,false)) {
$rule->reason = 'WordPress wp-login.php - HTML injection in the login username '. htmlentities($waf->matched_var_name, ENT_QUOTES, 'UTF-8') .' contained a tag opener';
if($waf->match_targets(array (
  0 => 'remove_nulls',
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/log/',
    ),
  ),
),'rx','#<#Ds',false,false)) {
$rule->reason = 'WordPress wp-login.php - HTML injection in the login username '. htmlentities($waf->matched_var_name, ENT_QUOTES, 'UTF-8') .' contained a tag opener';
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110081, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_filename' => 
  array (
  ),
),'rx','#(?i)/wp-json/wp/v2/media#Ds',false,false)) {
$rule->reason = 'REST raw upload named .pdf without PDF magic bytes at offset 0 cd='.$waf->meta('headers', 'content-disposition');
if($waf->match_targets(array (
),array (
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => 'content-disposition',
    ),
  ),
),'rx','#(?i)filename\\s*=\\s*\\x22?[^\\x22;]+\\.pdf\\x22?\\s*(?:;|$)#Ds',false,false)) {
$rule->reason = 'REST raw upload named .pdf without PDF magic bytes at offset 0 cd='.$waf->meta('headers', 'content-disposition');
if($waf->match_targets(array (
),array (
  'request_body' => 
  array (
  ),
),'rx','#\\A%PDF-#Ds',true,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'REST raw upload named .pdf without PDF magic bytes at offset 0 cd='.$waf->meta('headers', 'content-disposition');
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110082, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^[\\x20]*rest[_\\x2e\\x20\\x5b]route$/',
    ),
  ),
),'rx','#(?i)^/wp/v2/media#Ds',false,false)) {
$rule->reason = 'REST raw upload named .pdf without PDF magic bytes at offset 0 (rest_route) cd='.$waf->meta('headers', 'content-disposition');
if($waf->match_targets(array (
),array (
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => 'content-disposition',
    ),
  ),
),'rx','#(?i)filename\\s*=\\s*\\x22?[^\\x22;]+\\.pdf\\x22?\\s*(?:;|$)#Ds',false,false)) {
$rule->reason = 'REST raw upload named .pdf without PDF magic bytes at offset 0 (rest_route) cd='.$waf->meta('headers', 'content-disposition');
if($waf->match_targets(array (
),array (
  'request_body' => 
  array (
  ),
),'rx','#\\A%PDF-#Ds',true,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'REST raw upload named .pdf without PDF magic bytes at offset 0 (rest_route) cd='.$waf->meta('headers', 'content-disposition');
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110083, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'files' => 
  array (
  ),
),'rx','#(?i)\\.(?:dps|epi|eps[23fi]?|ept[23]?|ps[23]?|wpg)$#Ds',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Upload with a PostScript file extension file='. htmlentities($waf->matched_var, ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110084, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_filename' => 
  array (
  ),
),'rx','#(?i)/wp-json/wp/v2/media#Ds',false,false)) {
$rule->reason = 'REST raw upload of PostScript content (magic bytes at offset 0) cd='.$waf->meta('headers', 'content-disposition');
if($waf->match_targets(array (
),array (
  'request_body' => 
  array (
  ),
),'rx','#\\A(?:\\x04?%!|\\xC5\\xD0\\xD3\\xC6|\\xFFWPC)#Ds',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'REST raw upload of PostScript content (magic bytes at offset 0) cd='.$waf->meta('headers', 'content-disposition');
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110085, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'request_filename' => 
  array (
  ),
),'rx','#(?i)/wp-json/wp/v2/media#Ds',false,false)) {
$rule->reason = 'REST raw upload with a PostScript file extension cd='.$waf->meta('headers', 'content-disposition');
if($waf->match_targets(array (
),array (
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => 'content-disposition',
    ),
  ),
),'rx','#(?i)filename\\s*=\\s*\\x22?[^\\x22;]+\\.(?:dps|epi|eps[23fi]?|ept[23]?|ps[23]?|wpg)\\x22?\\s*(?:;|$)#Ds',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'REST raw upload with a PostScript file extension cd='.$waf->meta('headers', 'content-disposition');
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110086, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^[\\x20]*rest[_\\x2e\\x20\\x5b]route$/',
    ),
  ),
),'rx','#(?i)^/wp/v2/media#Ds',false,false)) {
$rule->reason = 'REST raw upload of PostScript content (rest_route) cd='.$waf->meta('headers', 'content-disposition');
if($waf->match_targets(array (
),array (
  'request_body' => 
  array (
  ),
),'rx','#\\A(?:\\x04?%!|\\xC5\\xD0\\xD3\\xC6|\\xFFWPC)#Ds',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'REST raw upload of PostScript content (rest_route) cd='.$waf->meta('headers', 'content-disposition');
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110087, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
),array (
  'args' => 
  array (
    'only' => 
    array (
      0 => '/^[\\x20]*rest[_\\x2e\\x20\\x5b]route$/',
    ),
  ),
),'rx','#(?i)^/wp/v2/media#Ds',false,false)) {
$rule->reason = 'REST raw upload with a PostScript file extension (rest_route) cd='.$waf->meta('headers', 'content-disposition');
if($waf->match_targets(array (
),array (
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => 'content-disposition',
    ),
  ),
),'rx','#(?i)filename\\s*=\\s*\\x22?[^\\x22;]+\\.(?:dps|epi|eps[23fi]?|ept[23]?|ps[23]?|wpg)\\x22?\\s*(?:;|$)#Ds',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'REST raw upload with a PostScript file extension (rest_route) cd='.$waf->meta('headers', 'content-disposition');
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110088, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/(?:post-new|post|site-editor|widgets)\\.php$#Ds',false,false)) {
$rule->reason = 'Block editor query argument HTML injection '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
  0 => 'remove_nulls',
  1 => 'html_entity_decode',
),array (
  'args_get' => 
  array (
    'only' => 
    array (
      0 => '/^\\]*u\\]*r\\]*l\\]*(?![a-z0-9_])/',
      1 => '/^\\]*t\\]*i\\]*t\\]*l\\]*e\\]*(?![a-z0-9_])/',
      2 => '/^\\]*t\\]*e\\]*x\\]*t\\]*(?![a-z0-9_])/',
      3 => '/^\\]*c\\]*o\\]*m\\]*m\\]*e\\]*n\\]*t\\]*_\\]*c\\]*o\\]*n\\]*t\\]*e\\]*n\\]*t\\]*(?![a-z0-9_])/',
      4 => '/^\\]*c\\]*o\\]*m\\]*m\\]*e\\]*n\\]*t\\]*_\\]*a\\]*u\\]*t\\]*h\\]*o\\]*r\\]*(?![a-z0-9_])/',
    ),
  ),
),'rx','#(?i)(?:<\\/?(?:iframe|object|embed|script|svg|math|form|meta|base|link|style|applet|frameset|frame|template|portal|marquee)[\\s/>]|srcdoc[\\s/]*=|(?:j\\s*a\\s*v\\s*a|v\\s*b)\\s*s\\s*c\\s*r\\s*i\\s*p\\s*t\\s*:|data:\\s*text/html)#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Block editor query argument HTML injection '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110089, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/(?:post-new|post|site-editor|widgets)\\.php$#Ds',false,false)) {
$rule->reason = 'Block editor url argument with a non-http scheme '. htmlentities($waf->matched_var_name, ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
  0 => 'remove_nulls',
  1 => 'html_entity_decode',
),array (
  'args_get' => 
  array (
    'only' => 
    array (
      0 => '/^\\]*u\\]*r\\]*l\\]*$/',
      1 => '/^\\]*u\\]*r\\]*l\\]*\\[/',
    ),
  ),
),'rx','#(?i)^[\\s\\x00-\\x20]*(?!https?[\\s\\x00-\\x20]*:)(?:[a-z0-9+.\\-]|&\\#x?[0-9a-f]+;?){1,40}(?::|&\\#x?0*3a;?|&\\#0*58;?)#Ds',false,false)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Block editor url argument with a non-http scheme '. htmlentities($waf->matched_var_name, ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 99110090, 'reason' => '', 'tags' => array (
) );
try {
if($waf->match_targets(array (
  0 => 'normalize_path',
),array (
  'request_filename' => 
  array (
  ),
),'rx','#/wp-admin/(?:post-new|post|site-editor|widgets)\\.php$#Ds',false,false)) {
$rule->reason = 'Block editor anchor with a disallowed href scheme '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
if($waf->match_targets(array (
  0 => 'remove_nulls',
  1 => 'html_entity_decode',
),array (
  'args_get' => 
  array (
    'only' => 
    array (
      0 => '/^\\]*t\\]*i\\]*t\\]*l\\]*e\\]*(?![a-z0-9_])/',
      1 => '/^\\]*t\\]*e\\]*x\\]*t\\]*(?![a-z0-9_])/',
      2 => '/^\\]*c\\]*o\\]*m\\]*m\\]*e\\]*n\\]*t\\]*_\\]*c\\]*o\\]*n\\]*t\\]*e\\]*n\\]*t\\]*(?![a-z0-9_])/',
      3 => '/^\\]*c\\]*o\\]*m\\]*m\\]*e\\]*n\\]*t\\]*_\\]*a\\]*u\\]*t\\]*h\\]*o\\]*r\\]*(?![a-z0-9_])/',
    ),
  ),
),'rx','#(?i)<a\\b[^>]*?href[\\s/]*=[\\s/]*[\\x22\']?[\\s\\x00-\\x20]*(?!(?:https?|mailto|tel)[\\s\\x00-\\x20]*:)(?:[a-z0-9+.\\-]|&\\#x?[0-9a-f]+;?){1,40}(?::|&\\#x?0*3a;?|&\\#0*58;?)#Ds',false,true)) {
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Block editor anchor with a disallowed href scheme '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 933110, 'reason' => '', 'tags' => array (
  0 => 'application-multi',
  1 => 'language-php',
  2 => 'platform-multi',
  3 => 'attack-injection-php',
  4 => 'paranoia-level/1',
  5 => 'owasp_crs',
  6 => 'capec/1000/152/242',
) );
try {
if($waf->match_targets(array (
  0 => 'lowercase',
),$waf->update_targets(array (
  'files' => 
  array (
  ),
  'request_headers' => 
  array (
    'only' => 
    array (
      0 => 'x-filename',
      1 => 'x_filename',
      2 => 'x.filename',
      3 => 'x-file-name',
    ),
  ),
), $rule->id, $rule->tags),'rx','#.*\\.(?:php\\d*|phtml)\\.*$#Ds',false,true)) {
$waf->inc_var('tx.php_injection_score',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'PHP Injection Attack: PHP Script File Upload Found Matched Data: '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') .' found within '. htmlentities($waf->matched_var_name, ENT_QUOTES, 'UTF-8') .': '. htmlentities($waf->matched_var, ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 941240, 'reason' => '', 'tags' => array (
  0 => 'application-multi',
  1 => 'language-multi',
  2 => 'platform-multi',
  3 => 'attack-xss',
  4 => 'paranoia-level/1',
  5 => 'owasp_crs',
  6 => 'capec/1000/152/242',
) );
try {
if($waf->match_targets(array (
  0 => 'utf8_to_unicode',
  1 => 'html_entity_decode',
  2 => 'lowercase',
  3 => 'remove_nulls',
),$waf->update_targets(array (
  'request_cookies' => 
  array (
    'except' => 
    array (
      0 => '/__utm/',
    ),
  ),
  'request_cookies_names' => 
  array (
  ),
  'args_names' => 
  array (
  ),
  'args' => 
  array (
  ),
), $rule->id, $rule->tags),'rx','#<[?]?import[\\s/+\\S]*?implementation[\\s/+]*?=#Ds',false,true)) {
$waf->inc_var('tx.xss_score',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'IE XSS Filters - Attack Detected Matched Data: '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') .' found within '. htmlentities($waf->matched_var_name, ENT_QUOTES, 'UTF-8') .': '. htmlentities($waf->matched_var, ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}
$rule = (object) array( 'id' => 942160, 'reason' => '', 'tags' => array (
  0 => 'application-multi',
  1 => 'language-multi',
  2 => 'platform-multi',
  3 => 'attack-sqli',
  4 => 'paranoia-level/1',
  5 => 'owasp_crs',
  6 => 'capec/1000/152/248/66',
) );
try {
if($waf->match_targets(array (
),$waf->update_targets(array (
  'request_basename' => 
  array (
  ),
  'request_cookies' => 
  array (
    'except' => 
    array (
      0 => '/__utm/',
    ),
  ),
  'request_cookies_names' => 
  array (
  ),
  'args_names' => 
  array (
  ),
  'args' => 
  array (
  ),
), $rule->id, $rule->tags),'rx','#(?i:sleep\\(\\s*?\\d*?\\s*?\\)|benchmark\\(.*?\\,.*?\\))#Ds',false,true)) {
$waf->inc_var('tx.sql_injection_score',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$waf->inc_var('tx.anomaly_score_pl1',htmlentities($waf->get_var('tx.critical_anomaly_score'), ENT_QUOTES, 'UTF-8') );
$rule->reason = 'Detects blind sqli tests using sleep() or benchmark() Matched Data: '.htmlentities($waf->get_var('tx.0'), ENT_QUOTES, 'UTF-8') .' found within '. htmlentities($waf->matched_var_name, ENT_QUOTES, 'UTF-8') .': '. htmlentities($waf->matched_var, ENT_QUOTES, 'UTF-8') ;
return $waf->block('block',$rule->id,$rule->reason,403);
}
} catch ( \Throwable $t ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $t );
} catch ( \Exception $e ) {
error_log( 'Rule ' . $rule->id . ' failed: ' . $e );
}