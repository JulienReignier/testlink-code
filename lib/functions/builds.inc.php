<?php
/** TestLink Open Source Project - http://testlink.sourceforge.net/ 
* $Id: builds.inc.php,v 1.22 2007/01/20 18:45:39 franciscom Exp $
* 
* @author Martin Havlat
*
* Functions for Test Plan management - build related
*
* 20070120 - franciscom - changes to getBuilds()
*
*/
require_once('../../config.inc.php');
require_once("../functions/common.php");

/**
 * Collect all builds for the Test Plan
 *
 * 20070120 - franciscom
 * 
 * args:
 *
 *       [active]: default:null -> all, 1 -> active, 0 -> inactive
 *       [open]  : default:null -> all, 1 -> open  , 0 -> closed/completed
 */
function getBuilds(&$db,$idPlan, $order_by="ORDER BY builds.id DESC",$active=null,$open=null)
{
 	$sql = "SELECT builds.id, name FROM builds WHERE testplan_id = " . $idPlan;
 	
 	
 	// 20070120 - franciscom
 	if( !is_null($active) )
 	{
 	   $sql .= " AND active=" . intval($active) . " ";   
 	}
 	if( !is_null($open) )
 	{
 	   $sql .= " AND open=" . intval($open) . " ";   
 	}
 		
 	
 	if (strlen(trim($order_by)))
 	{
 		$sql .= " " . $order_by;
 	}
	return getBuildInfo($db,$sql);
}

/**
 * @author kl - 10/13/2005
 * return a comma delimited list of build.id's which are part of a test plan
 *
 */
function DEPR_get_cs_builds(&$db,$idPlan, $order_by="ORDER BY builds.id DESC")
{
  $comma_separated = null;
  $arrAllBuilds = getBuilds($db,$idPlan, $order_by);
  if ($arrAllBuilds){
    $arrAllKeys = array_keys($arrAllBuilds);
    $comma_separated = implode("','", $arrAllKeys);
    // add single quotes to front and back
    $comma_separated = "'" . $comma_separated . "'";
  }
  return $comma_separated;
}

function getBuildInfo(&$db,$sql)
{
	$arrBuilds = $db->fetchColumnsIntoMap($sql,'id','name');

 	return $arrBuilds;
}

function getBuild_by_id(&$db,$buildID)
{
	$sql = "SELECT builds.* FROM builds WHERE builds.id = " . $buildID;
	$result = $db->exec_query($sql);
	$myrow = $db->fetch_array($result);

	return $myrow;
}

function delete_build(&$db,$build_id)
{
	//DEPENDENT DATA?
	$sql = "DELETE FROM builds WHERE builds.id = {$build_id}";
	$result = $db->exec_query($sql);
	
	return $result ? 1 : 0;
}
?>