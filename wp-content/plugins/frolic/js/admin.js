jQuery(document).ready(function() {
	
	jQuery('.pluginbuddy_tip').tooltip({ 
		track: true, 
		delay: 0, 
		showURL: false, 
		showBody: " - ", 
		fade: 250 
	});
	
	if (typeof jQuery.tableDnD !== 'undefined') { // If tableDnD function loaded.
		jQuery('.pb_reorder').tableDnD({
			onDrop: function(tbody, row) {
				var new_order = new Array();
				var rows = tbody.rows;
				for (var i=0; i<rows.length; i++) {
					new_order.push( rows[i].id.substring(11) );
				}
				new_order = new_order.join( ',' );
				jQuery( '#pb_order' ).val( new_order )
			},
			dragHandle: "pb_draghandle"
		});
	}
	
});