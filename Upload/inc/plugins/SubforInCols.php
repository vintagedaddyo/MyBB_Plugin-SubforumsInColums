<?php
/*
 * MyBB: Subforums In Columns
 *
 * File: SubforInCols.php
 * 
 * Authors: Edson Ordaz, Vintagedaddyo
 *
 * MyBB Version: 1.8
 *
 * Plugin Version: 1.2
 * 
 */

// Disallow direct access to this file for security reasons

if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

// Plugin Information

function SubforInCols_info()
{
    global $lang;

    $lang->load("subforum_columns");
    
    $lang->subforum_columns_Desc = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="float:right;">' .
        '<input type="hidden" name="cmd" value="_s-xclick">' . 
        '<input type="hidden" name="hosted_button_id" value="AZE6ZNZPBPVUL">' .
        '<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">' .
        '<img alt="" border="0" src="https://www.paypalobjects.com/pl_PL/i/scr/pixel.gif" width="1" height="1">' .
        '</form>' . $lang->subforum_columns_Desc;

    return Array(
        'name' => $lang->subforum_columns_Name,
        'description' => $lang->subforum_columns_Desc,
        'website' => $lang->subforum_columns_Web,
        'author' => $lang->subforum_columns_Auth,
        'authorsite' => $lang->subforum_columns_AuthSite,
        'version' => $lang->subforum_columns_Ver,
        'guid' => $lang->subforum_columns_GUID,
        'compatibility' => $lang->subforum_columns_Compat
    );
}

// Activate

function SubforInCols_activate()
{
	global $db, $lang;
	
	$SubforInCols = array(
		"gid"			=> "0",
		"name" => $lang->name_sic_0,
		"title" => $lang->title_sic_0,
		"description" => $lang->description_sic_0,
		"disporder"		=> "0",
		"isdefault"		=> "0",
	);
	$db->insert_query("settinggroups", $SubforInCols);
	$gid = $db->insert_id();
	
	$SubforInCols1 = array(
		"sid"			=> "0",
		"name" => $lang->name_sic_1,
		"title" => $lang->title_sic_1,
		"description" => $lang->description_sic_1,
		"optionscode"	=> "select \n50=".$lang->options_1."\n30=".$lang->options_2."\n20=".$lang->options_3."",
		"value"			=> "30",
		"disporder"		=> "1",
		"gid"			=> intval($gid),
	);
	$db->insert_query("settings", $SubforInCols1);
	$template_dp3 = array(
		"template" => $db->escape_string('<li>{$statusicon}<a href="forumdisplay.php?fid={$forum[\'fid\']}">{$forum[\'name\']}</a></li>')
	);
	$template_sf = array(
		"template" => $db->escape_string('<link rel="stylesheet" href="{$mybb->asset_url}/inc/plugins/SubforInCols/font-awesome/css/font-awesome.min.css"><style>
.alt_sbc {
	list-style: none; 
	margin: 0; 
	padding: 0;
}

.alt_sbc li {
	width: {$mybb->settings[\'SubforInCols\']}%; 
	float: left;
}

.sub-menu .fa-angle-down {    
 position: absolute;
 bottom: -1px;
 color: #0066A2;
 font-size: 20px;
 margin-top: 19px;
}

.sub-menu:hover .fa-angle-down {
 transform: scale(1) rotate(180deg) translate(0);
 transition: linear 0.5s;
}

.sub-menu{
    text-align:left;
    font-family: \'Lato\', sans-serif;
    color: #0066A2;
}

.sub-menu *{
 transition: all .2s ease-out 0s;
-o-transition: all .2s ease-out 0s;
-ms-transition: all .2s ease-out 0s;
-moz-transition: all .2s ease-out 0s;
-webkit-transition: all .2s ease-out 0s;}

.sub-menu ul {
    list-style:none;
    margin:0;
    padding:0;
}

h5 {
 font-size: 12px;
 text-transform: normal;
 color: #0F0F0F;
}

.sub-menu ul li {
    display:inline-block;
    perspective: 500px;
    -o-perspective: 500px;
    -ms-perspective: 500px;
    -moz-perspective: 500px;
    -webkit-perspective: 500px;
    position:relative;
    
}

.sub-menu li>ul {
    background: #F5F5F5;
    position:absolute;
    visibility:hidden;
    opacity:0;
    top: 100%;
    width: 300%;
    margin-top: 5px;
    margin-bottom: 5px;
    display:inline-block;
    color:#f5f5f5;
    border: 1px solid #ccc;
    border-radius: 6px;
    -moz-border-radius: 6px;
	-webkit-border-radius: 6px;
}

.sub-menu li:hover>ul {
    visibility:visible;
    opacity:1;
    transform: rotateX(0deg);
    -o-transform: rotateX(0deg);
    -ms-transform: rotateX(0deg);
    -moz-transform: rotateX(0deg);
    -webkit-transform: rotateX(0deg);
     margin-top:0;
}
</style>
<br /><div class="sub-menu"><ul class="alt_sbc"><li> {$lang->subforums}<i class="fa fa-angle-down" aria-hidden="true"></i>
            <ul>
        <h5>{$sub_forums}</h5>
            </ul>
        </li>
    </ul>
</div>')
	);
	$sett = array(
		"value"	=> "100"
	);
	$db->update_query("settings", $sett,"name='subforumsindex'");
	$db->update_query("templates", $template_dp3,"title='forumbit_depth3'");
	$db->update_query("templates", $template_sf,"title='forumbit_subforums'");
	rebuild_settings();
}

// Deactivate

function SubforInCols_deactivate()
{
	global $db;
	$template_dp3 = array(
		"template" => $db->escape_string('{$comma}{$statusicon}<a href="{$forum_url}" title="{$forum_viewers_text_plain}">{$forum[\'name\']}</a>')
	);
	$template_sub = array(
		"template" => $db->escape_string('<br />{$lang->subforums} {$sub_forums}')
	);
	$sett = array(
		"value"	=> "2"
	);
	$db->update_query("settings", $sett,"name='subforumsindex'");
	$db->update_query("templates", $template_dp3,"title='forumbit_depth3'");
	$db->update_query("templates", $template_sub,"title='forumbit_subforums'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name='subincol'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='SubforInCols'");
	rebuild_settings();
}
?>