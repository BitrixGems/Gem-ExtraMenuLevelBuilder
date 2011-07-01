<?php
/**
 * Костылик, позволяющий отстроить дополнительные уровни стандартного административного меню Битрикс.
 *
 * @author		Vladimir Savenkov <iVariable@gmail.com>
 *
 */
class BitrixGem_ExtraMenuLevelBuilder extends BaseBitrixGem{

	protected $aGemInfo = array(
		'GEM'			=> 'ExtraMenuLevelBuilder',
		'AUTHOR'		=> 'Vladimir Savenkov',
		'AUTHOR_LINK'	=> 'http://bitrixgems.ru/',
		'DATE'			=> '23.12.2010',
		'VERSION'		=> '0.1.1',
		'NAME' 			=> 'ExtraMenuLevelBuilder',
		'DESCRIPTION' 	=> 'Костылик, позволяющий отстроить дополнительные уровни стандартного административного меню Битрикс.',
		'CHANGELOG'		=> 'Теперь гем корректно работает с сайтами не только в UTF-8',
		'REQUIREMENTS'	=> 'jQuery',
	);


	/**
	 * Возвращает список сконфигурированных уровней меню.
	 */
	protected function getConfiguredMenuLevels(){
		$aLevels = include( dirname(__FILE__).'/options/configuredMenuLevels.php' );
		if( !is_array( $aLevels ) ) $aLevels = array();
		return $aLevels;
	}
	
	protected function setConfiguredMenuLevels( $aLevels ){
		return file_put_contents(
			dirname(__FILE__).'/options/configuredMenuLevels.php',
			'<?php return '.var_export( $aLevels, true ).';?>'
		);
	}

	protected function json_fix_cyr($var){
		if( strtoupper( LANG_CHARSET )!='UTF-8' ){
			if (is_array($var)) {
				$new = array();
				foreach ($var as $k => $v) {
					$new[$this->json_fix_cyr($k)] = $this->json_fix_cyr($v);
				}
				$var = $new;
			} elseif (is_object($var)) {
				$vars = get_object_vars($var);
				foreach ($vars as $m => $v) {
					$var->$m =$this-> json_fix_cyr($v);
				}
			} elseif (is_string($var)) {
				$var = iconv(LANG_CHARSET, 'utf-8//IGNORE', $var);
			}
		}
		return $var;
	}
	
	public function initGem(){
		if( defined( 'ADMIN_SECTION' ) ){
			global $APPLICATION;
			$APPLICATION->AddHeadScript( '/bitrix/js/iv.bitrixgems/'.$this->getName().'/ExtraMenuLevelBuilder.gem.js' );
			
			$sInitString = '
<script type="text/javascript">
/*Dirty antivirushack: form_tbl_dump*/
jQuery(function(){
	extraMenuLevelBuilder = new ExtraMenuLevelBuilder();
	var levels = '.json_encode( $this->json_fix_cyr($this->getConfiguredMenuLevels()) ).';
	extraMenuLevelBuilder.setLevels( levels ).rebuildMenu( "setActive" );
});
</script>
			';			
			$APPLICATION->AddHeadString( $sInitString );
		}
	}

	public function needAdminPage(){
		return true;
	}
	public function showAdminPage(){
		$aConfiguredMenuLevels = $this->getConfiguredMenuLevels();
		require_once( dirname(__FILE__).'/options/adminOptionPage.php' );
	}
	public function processAdminPage( $aOptions ){
		$aLevels = array();
		if( !empty( $aOptions['extraMenuLevelBuilder']['prefix'] ) ){
			foreach( $aOptions['extraMenuLevelBuilder']['prefix'] as $iKey => $sPrefix ){
				if( empty( $sPrefix ) ) continue;
				$aLevels[ $sPrefix ] = array(
					'description' => $aOptions['extraMenuLevelBuilder']['description'][$iKey],
					'levelID' => $aOptions['extraMenuLevelBuilder']['levelID'][$iKey],
				);
			}
		}
		$this->setConfiguredMenuLevels( $aLevels );
	}
	
	public function installGem(){		
		CopyDirFiles( dirname(__FILE__).'/js/', $_SERVER["DOCUMENT_ROOT"]."/bitrix/js/iv.bitrixgems/".$this->getName()."/", true, true);
		if( !file_exists( dirname(__FILE__).'/options/configuredMenuLevels.php' ) ) file_put_contents( dirname(__FILE__).'/options/configuredMenuLevels.php', '');
		return true;
	}
	
	public function unInstallGem(){
		DeleteDirFilesEx("/bitrix/js/iv.bitrixgems/".$this->getName().'/');
		return true;
	}

	public function updateGem($aOldVersion){
		DeleteDirFilesEx("/bitrix/js/iv.bitrixgems/".$this->getName().'/');
		CopyDirFiles( dirname(__FILE__).'/js/', $_SERVER["DOCUMENT_ROOT"]."/bitrix/js/iv.bitrixgems/".$this->getName()."/", true, true);
	}
}
?>