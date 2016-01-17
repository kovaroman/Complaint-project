<?php defined('CHECK') OR exit('No direct script access allowed');
/*-------------------------------------------------------
*
*   Roman Kovalyshyn
*   Copyright © 2016
*
*---------------------------------------------------------
*/

/*
 * Viwer module to load templates and extract VARs
 */
 class ModuleViewer extends Module {

	public function Init($bLocal=false) {

	}
	
	public function GetView($sTemplate, $aData = array(), $bReturn = false){
		extract($aData);
		if ($bReturn === false) {
			require(rtrim(Config::Get('path.root.views'), '/').'/'.strtolower($sTemplate).'.view.php');
		} else {
			ob_start();
			include(rtrim(Config::Get('path.root.views'), '/').'/'.strtolower($sTemplate).'.view.php');
			$buffer = ob_get_contents();
			@ob_end_clean();
			return $buffer;
		}
	}
}
?>