<?php
/**
 * Страница опций для extraMenuLevelBuilder
 *
 * @author	Vladimir Savenkov
 */
?>
<form method="post" action="">
<?=bitrix_sessid_post()?>
<table>
<tr>
	<td valign="top"><?=GetMessage('BITRIXCRUTCH_MENU_LEVELS')?></td>
	<td valign="top" nowrap="nowrap" class="list-table">
		<style type="text/css">
			TD.list-table TABLE.list-table TR TD INPUT {
				width:100%;
			}
		</style>
		<table class="list-table">
			<tr class="head" valign="middle" align="center">
				<td>Префикс</td>
				<td>Название уровня</td>
				<td>ID уровня в DOM-дереве</td>
			</tr>
		
			<?if( !empty( $aConfiguredMenuLevels ) ):?>
				<?foreach( $aConfiguredMenuLevels as $sPrefix => $aDescription ):?>
					<tr>
						<td><input type="text" name="extraMenuLevelBuilder[prefix][]" value="<?=$sPrefix?>"></td>
						<td><input type="text" name="extraMenuLevelBuilder[description][]" value="<?=$aDescription['description']?>"></td>
						<td><input type="text" name="extraMenuLevelBuilder[levelID][]" value="<?=$aDescription['levelID']?>"></td>
					</tr>
				<?endforeach;?>
			<?endif;?>
			<tr>
				<td><input type="text" name="extraMenuLevelBuilder[prefix][]" value=""></td>
				<td><input type="text" name="extraMenuLevelBuilder[description][]" value=""></td>
				<td><input type="text" name="extraMenuLevelBuilder[levelID][]" value=""></td>
			</tr>
			<tr>
				<td><input type="text" name="extraMenuLevelBuilder[prefix][]" value=""></td>
				<td><input type="text" name="extraMenuLevelBuilder[description][]" value=""></td>
				<td><input type="text" name="extraMenuLevelBuilder[levelID][]" value=""></td>
			</tr>
			<tr>
				<td><input type="text" name="extraMenuLevelBuilder[prefix][]" value=""></td>
				<td><input type="text" name="extraMenuLevelBuilder[description][]" value=""></td>
				<td><input type="text" name="extraMenuLevelBuilder[levelID][]" value=""></td>
			</tr>
			<tr>
				<td><input type="text" name="extraMenuLevelBuilder[prefix][]" value=""></td>
				<td><input type="text" name="extraMenuLevelBuilder[description][]" value=""></td>
				<td><input type="text" name="extraMenuLevelBuilder[levelID][]" value=""></td>
			</tr>
			<tr>
				<td><input type="text" name="extraMenuLevelBuilder[prefix][]" value=""></td>
				<td><input type="text" name="extraMenuLevelBuilder[description][]" value=""></td>
				<td><input type="text" name="extraMenuLevelBuilder[levelID][]" value=""></td>
			</tr>
			
		</table>
	</td>
</tr>
</table>
<input type="submit" value="Сохранить"/>
</form>