function ExtraMenuLevelBuilder(){
		
	this.renameProcessed = true;
	this.levels = {};
	
	this.setLevels = function( levels ){
		this.levels = levels;
		var builder = this;
		$.each( this.levels, function( i, level ){
			builder.levels[i]['levels'] = {};
		} );
		return this;
	}
	
	this.addLevelDescription = function( level, levelID, description ){
		this.levels[ level ] = { 'description': description, 'levelID':levelID, 'levels':{} };
		return this;
	};
	
	this.rebuildInProgress = false;
	
	this.rebuildMenu = function(){
		if( this.rebuildInProgress ) return;
		this.rebuildInProgress = true;
		var menuBuilder = this;
		var $elems = $('#menucontainer .menuline:visible:not(.processedByExtraLevelBuilder)');
		$('#menucontainer').hide();
		$elems.each(function(){
			var menuLine = $(this);
			var menuName = $( 'td.menutext:first a', menuLine ).html();
			var itemLevel = $( 'table:first td', menuLine ).length - 3;
			var $parent = menuLine.parent();
			if( menuName == null || itemLevel == null )return;
			$.each( menuBuilder.levels, function( level, description ){			 
				if (menuName.indexOf(level) === 0) {
					if (menuBuilder.renameProcessed) $('td.menutext:first a', menuLine).html(menuName.substr(level.length));
					$('table:first tr:first', menuLine).prepend('<td><div class="menuindent"></div></td>');
					menuBuilder.addToMenuLevel($parent, level, menuLine, itemLevel);
				}			
			} );
		});
		var levels = this.levels;
		$.each( levels, function( level, description ){
			$.each( description['levels'], function( id, elem ){
				if( elem['appended'] )return;
				elem['level'].appendTo( $( elem['parent'] ) );
				levels[ level ]['levels'][id]['appended'] = true;
			})
		})
		$('#menucontainer').show();
		if( arguments.length > 0 ) $('#menucontainer a.active').parents('.menuline').find('table~div').show();
		this.rebuildInProgress = false;		
	};
	
	this.nextParentID = 0;
	
	this.nextLevelId = 0;
	
	this.addToMenuLevel = function( $parent, originalLevel, $item, itemLevel ){
		level = this.levels[originalLevel];
		if($parent.attr('id')=='')$parent.attr('id','autoid'+(this.nextParentID++));
		var levelID = level.levelID+'_'+$parent.attr('id')+'_'+itemLevel;
		if( typeof level['levels'][levelID] == 'undefined' ){			
			var containerID = 'extraMenuCont'+ (this.nextLevelId++);
			var sNewElem = '<div id="'+levelID+'" class="menuline processedByExtraLevelBuilder"><table cellspacing="0"><tbody><tr>';
			for( var i=0; i < itemLevel; i++ )sNewElem += '<td><div class="menuindent"></div></td>';
			sNewElem += '<td><div onclick="JsAdminMenu.ToggleDynSection(this, \'extraMenuLevelBuilder\', \''+containerID+'_extracontainer\', '+(itemLevel+1)+')" class="sign signplus"></div></td>'+
						'<td class="menuicon"><a id="'+$( '.menuicon:first a', $item ).attr('id')+'"></a></td>'+
						'<td class="menutext"><a title="'+level.description+'" href="#">'+level.description+'</a></td>'+
						'</tr></tbody></table>'+
						'<div style="display: none;" id="'+containerID+'_extracontainer"></div></div>'
			$level = $(
				sNewElem
			);//.appendTo( $parent );
			this.levels[originalLevel]['levels'][levelID]	= {
				'level': $level,
				'parent': $parent,
				'appended': false,
				'itemsContainer':$( '#'+containerID+'_extracontainer', $level ) 
			}
		}
		var $itemsContainer = this.levels[originalLevel]['levels'][levelID]['itemsContainer'];
		$item.find('table~div:first').css('padding-left','16px');
		$item.addClass('processedByExtraLevelBuilder').appendTo( $itemsContainer );
	};
	
	this.init = function(){
		if( typeof JsAdminMenu == 'undefined' ) return;
		JsAdminMenu.extraMenuLevelBuilder = this;
		JsAdminMenu.request._OnDataReady_extendedByExtraMenuLevelBuilder = JsAdminMenu.request._OnDataReady;
		JsAdminMenu.request._OnDataReady = function( result ){
			var res = this._OnDataReady_extendedByExtraMenuLevelBuilder.apply(this,arguments);
			JsAdminMenu.extraMenuLevelBuilder.rebuildMenu();
			return res;
		}
		JsAdminMenu.ToggleMenu_extendedByExtraMenuLevelBuilder = JsAdminMenu.ToggleMenu;
		JsAdminMenu.ToggleMenu = function(){
			var res = this.ToggleMenu_extendedByExtraMenuLevelBuilder.apply(this,arguments);
			JsAdminMenu.extraMenuLevelBuilder.rebuildMenu();
			return res;
		}
		JsAdminMenu.ToggleDynSection_extendedByExtraMenuLevelBuilder = JsAdminMenu.ToggleDynSection;
		JsAdminMenu.ToggleDynSection = function(){
			var res = this.ToggleDynSection_extendedByExtraMenuLevelBuilder.apply(this,arguments);
			JsAdminMenu.extraMenuLevelBuilder.rebuildMenu();
			return res;
		}
		
	}
	
	this.init();

}