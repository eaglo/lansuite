<?php
include_once('modules/mastersearch2/class_mastersearch2.php');
$ms2 = new mastersearch2();

$ms2->query['from'] = "{$config['tables']['user']} AS u LEFT JOIN {$config['tables']['party_user']} AS p ON u.userid = p.user_id";

$ms2->config['EntriesPerPage'] = 20;

$ms2->AddTextSearchField($lang['usrmgr']['userid'], array('u.userid' => 'exact'));
$ms2->AddTextSearchField($lang['usrmgr']['add_username'], array('u.username' => '1337'));
$ms2->AddTextSearchField($lang['usrmgr']['name'], array('u.name' => 'like', 'u.firstname' => 'like'));

$ms2->AddTextSearchDropDown($lang['usrmgr']['add_type'], 'u.type', array('' => $lang['usrmgr']['all'], '1' => $lang['usrmgr']['details_guest'], '!1' => 'Nicht Gast', '2' => $lang['usrmgr']['add_type_admin'], '3' => $lang['usrmgr']['add_type_operator'], '2,3' => 'Admin und Op'));
	
$party_list = array('' => 'Alle');
$row = $db->query("SELECT party_id, name FROM {$config['tables']['partys']}");
while($res = $db->fetch_array($row)) $party_list[$res['party_id']] = $res['name'];
$db->free_result($row);
$ms2->AddTextSearchDropDown('Party', 'p.party_id', $party_list, $party->party_id);

$ms2->AddTextSearchDropDown($lang['usrmgr']['add_paid'], 'p.paid', array('' => $lang['usrmgr']['all'], '0' => $lang['usrmgr']['add_paid_no'], '>1' => $lang['usrmgr']['details_paid']));
$ms2->AddTextSearchDropDown($lang['usrmgr']['checkin'], 'p.checkin', array('' => $lang['usrmgr']['all'], '0' => $lang['usrmgr']['checkin_no'], '>1' => $lang['usrmgr']['checkin']));
$ms2->AddTextSearchDropDown($lang['usrmgr']['checkout'], 'p.checkout', array('' => $lang['usrmgr']['all'], '0' => $lang['usrmgr']['checkout_no'], '>1' => $lang['usrmgr']['checkout']));


$ms2->AddResultField($lang['usrmgr']['add_username'], 'u.username');
$ms2->AddResultField($lang['usrmgr']['add_firstname'], 'u.firstname');
$ms2->AddResultField($lang['usrmgr']['add_lastname'], 'u.name');
$ms2->AddResultField($lang['usrmgr']['details_clan'], 'u.clan', 'http://', 'u.clanurl');
// If Party selected
if ($_POST["search_dd_input"][1] != '' or $_GET["search_dd_input"][1] != '') {
  $ms2->AddResultField('Bez.', 'p.paid', '', '', 'PaidIconLink');
  $ms2->AddResultField('In', 'p.checkin', '', '', 'GetDate');
  $ms2->AddResultField('Out', 'p.checkout', '', '', 'GetDate');
}

$ms2->AddIconField('details', 'u.userid', 'index.php?mod=usrmgr&action=details&userid=', 'Details');
if ($auth['type'] >= 2) $ms2->AddIconField('edit', 'u.userid', 'index.php?mod=usrmgr&action=change&step=1&userid=', 'Edit');
if ($auth['type'] >= 3) $ms2->AddIconField('delete', 'u.userid', 'index.php?mod=usrmgr&action=delete&step=2&userid=', 'Delete');

$ms2->PrintSearch('index.php?mod=usrmgr&action=search', 'u.userid');
?>