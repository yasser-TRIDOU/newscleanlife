/**
 * Admin utilities (for internal use only!)
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.0
 */

/* global jQuery:false */
/* global TRX_ADDONS_STORAGE:false */

(function() {

	"use strict";

	if (typeof TRX_ADDONS_STORAGE == 'undefined') window.TRX_ADDONS_STORAGE = {};
	
	jQuery(document).ready(function() {
	
		// Trigger 'admin_action.init_hidden_elements' on meta box hide and show
		jQuery(document).on('postbox-toggled', function() {
			jQuery(document).trigger('admin_action.init_hidden_elements');
		});
		// Trigger 'admin_action.init_hidden_elements' on sorting meta boxes
		jQuery('.meta-box-sortables').on( 'sortstop', function() {
			jQuery(document).trigger('admin_action.init_hidden_elements');
		});



		// Check fields dependencies in widgets
		//-----------------------------------------------------------------------------------

		var in_widgets = false;

		// Check dependencies in widgets in admin menu
		jQuery( '.widget-liquid-right form,.widgets-holder-wrap.inactive-sidebar form,.edit-widgets-block-editor' )
			.each( function () {
				in_widgets = true;
				trx_addons_admin_check_dependencies(jQuery(this));
			} )
			.on( 'change', '[data-param-name]', function() {
				trx_addons_admin_check_dependencies(jQuery(this).parents('form'));
			} );

		// Check dependencies in widgets inside Customize sections (before open for dinamically created sections)
		jQuery( '#customize-theme-controls .control-section' )
			// WordPress 5.7- (classic widgets panel)
			.on( 'click', '.widget-title', function() {
				var widget = jQuery( this ).parents('.widget').eq(0).find('.form');
				if ( widget.length > 0 ) {
					trx_addons_admin_check_dependencies( widget );
				}
			} )
			// WordPress 5.8+ (widgets block editor)
			.on( 'click', '.wp-block-legacy-widget__edit-preview', function() {
				setTimeout( function() {
					var widget = jQuery( this ).siblings('.wp-block-legacy-widget__edit-form').eq(0).find('.form');
					if ( widget.length > 0 ) {
						trx_addons_admin_check_dependencies( widget );
					}
				}, 500 );
			} );
		jQuery('#customize-theme-controls .control-section')
			.on( 'change', '[data-param-name]', function() {
				trx_addons_admin_check_dependencies(jQuery(this).parents('.form').eq(0));
			} );

		// Check dependencies after the widget added or updated
		jQuery(document).on( 'widget-added widget-updated', function(e, widget) {
			if ( widget ) {
				var widget_form = jQuery(widget).find('form');
				if ( widget_form.length > 0 ) {
					trx_addons_admin_check_dependencies( widget_form );
				}
			}
		} );

		// Check for dependencies
		function trx_addons_admin_check_dependencies(cont) {
			cont.find('[data-param-name]').each( function() {
				var ctrl = jQuery(this),
					id = ctrl.data('param-name'),
					depend = ctrl.data('param-dependency');
				if ( !id || !depend) return;
				if ( depend ) {
					var dep_cnt = 0, dep_all = 0;
					var dep_cmp = typeof depend.compare != 'undefined' ? depend.compare.toLowerCase() : 'and';
					var dep_strict = typeof depend.strict != 'undefined';
					var fld=null, val='', name='', subname='', i;
					var parts = '', parts2 = '';
					for (i in depend) {
						if (i == 'compare' || i == 'strict') continue;
						dep_all++;
						name = i;
						subname = '';
						if (name.indexOf('[') > 0) {
							parts = name.split('[');
							name = parts[0];
							subname = parts[1].replace(']', '');
						}
						fld = cont.find('[data-param-name="'+name+'"]');
						if (fld.length > 0) {
							val = trx_addons_admin_get_field_value(fld);
							if (subname !== '') {
								parts = val.split('|');
								for (var p=0; p < parts.length; p++) {
									parts2 = parts[p].split('=');
									if (parts2[0]==subname) {
										val = parts2[1];
									}
								}
							}
							for (var j in depend[i]) {
								if ( 
									   (depend[i][j]=='not_empty' && val !== '')	// Main field value is not empty - show current field
									|| (depend[i][j]=='is_empty' && val === '')		// Main field value is empty - show current field
									|| (val !== '' && (!isNaN(depend[i][j]) 		// Main field value equal to specified value - show current field
														? val==depend[i][j]
														: (dep_strict 
																? val==depend[i][j]
																: (''+val).indexOf(depend[i][j])===0
															)
													)
										)
									|| (val !== '' && (""+depend[i][j]).charAt(0) == '^' && (''+val).indexOf(depend[i][j].substr(1))==-1)
																				// Main field value not equal to specified value - show current field
								) {
									dep_cnt++;
									break;
								}
							}
						} else {
							dep_all--;
						}
						if (dep_cnt > 0 && dep_cmp == 'or')
							break;
					}
					if (((dep_cnt > 0 || dep_all === 0) && dep_cmp == 'or') || (dep_cnt == dep_all && dep_cmp == 'and')) {
						ctrl.parents('[class^="widget_field_type_"]').slideDown().removeClass('trx_addons_options_no_use');
					} else {
						ctrl.parents('[class^="widget_field_type_"]').slideUp().addClass('trx_addons_options_no_use');
					}
				}
			});
		}

		function trx_addons_admin_get_field_value(fld) {
			var ctrl = fld.parents('[class^="widget_field_type_"]');
			var val = fld.attr('type')=='checkbox' || fld.attr('type')=='radio' 
						? (ctrl.find('input[type="'+fld.attr('type')+'"]:checked').length > 0
							? (ctrl.find('input[type="'+fld.attr('type')+'"]:checked').val() !== ''
								&& ''+ctrl.find('input[type="'+fld.attr('type')+'"]:checked').val() !== '0'
									? ctrl.find('input[type="'+fld.attr('type')+'"]:checked').val()
									: 1
								)
							: 0
							)
						: fld.val();
			if (val===undefined || val===null) val = '';
			return val;
		}



		// Allow insert containers inside inner columns
		// Attention! Used vc_map_update() instead this method
		// window.vc && window.vc.map && (vc.map['vc_column_inner'].allowed_container_element = true);
			
		// Create VC wrappers for the VcRowView and VcColumnView and for our shortcodes-containers
		// to wrap vc_admin_label to the container and move it after the title
		window.VcColumnView
			&& (vc.map['vc_column_inner'].allowed_container_element = true)		// Allow insert containers inside inner columns
			&& (vc.shortcode_view.prototype.renderContentOld = vc.shortcode_view.prototype.renderContent)
			&& (vc.shortcode_view.prototype.renderContent = function() {
					this.renderContentOld();
					if (this.$el.hasClass('wpb_content_element'))
						this.moveAdminLabelsAfterTitle();
				})
			&& (vc.shortcode_view.prototype.moveAdminLabelsAfterTitle = function() {
					var wrapper = this.$el.find('> .wpb_element_wrapper');
					if (wrapper.length == 0) return;
					var labels = wrapper.find('> .vc_admin_label');
					if (labels.length == 0) return;
					var labels_wrap, title = wrapper.find('> .wpb_element_title');
					// If title present
					if (title.length > 0) {
						// Single element
						if (this.$el.hasClass('wpb_content_element')) {
							var wpb_vc_param_value = wrapper.find('> .wpb_vc_param_value');
							// Single element with params - move params after labels
							if (wpb_vc_param_value.length == 1)
								wpb_vc_param_value.insertAfter(labels.eq(labels.length-1));
						// Container
						} else if (this.$el.hasClass('vc_shortcodes_container')) {
							labels_wrap = title.find('> .vc_admin_labels');
							if (labels_wrap.length == 0) {
								title.append('<div class="vc_admin_labels"></div>');
								labels_wrap = title.find('> .vc_admin_labels');
							} else
								labels_wrap.empty();
							labels.clone().appendTo(labels_wrap);
						}
					// Elements without title - just wrap labels
					} else {
						if (this.$el.hasClass('wpb_content_element')) {
							if (!this.$el.hasClass('wpb_content_element_without_title')) 
								this.$el.addClass('wpb_content_element_without_title');
							var wpb_vc_param_value = wrapper.find('> .wpb_vc_param_value');
							// Single element with params - move params before labels
							if (wpb_vc_param_value.length == 1)
								wpb_vc_param_value.insertBefore(labels.eq(0));
						}
						labels_wrap = wrapper.find('> .vc_admin_labels');
						if (labels_wrap.length == 0) {
							wrapper.append('<div class="vc_admin_labels"></div>');
							labels_wrap = wrapper.find('> .vc_admin_labels');
						} else
							labels_wrap.empty();
						labels.clone().appendTo(labels_wrap);
					}
				})
			&& (window.VcColumnView.prototype.buildDesignHelpersOld = window.VcColumnView.prototype.buildDesignHelpers)
			&& (window.VcColumnView.prototype.buildDesignHelpers = function() {
					this.buildDesignHelpersOld();
					this.moveAdminLabelsAfterTitle();
				})
			&& (window.VcColumnView.prototype.changeShortcodeParamsOld = window.VcColumnView.prototype.changeShortcodeParams)
			&& (window.VcColumnView.prototype.changeShortcodeParams = function(model) {
					this.changeShortcodeParamsOld(model);
					this.moveAdminLabelsAfterTitle();
				})
			&& (window.VcRowView.prototype.buildDesignHelpersOld = window.VcRowView.prototype.buildDesignHelpers)
			&& (window.VcRowView.prototype.buildDesignHelpers = function() {
					this.buildDesignHelpersOld();
					this.moveAdminLabelsAfterTitle();
				})
			&& (window.VcRowView.prototype.changeShortcodeParamsOld = window.VcRowView.prototype.changeShortcodeParams)
			&& (window.VcRowView.prototype.changeShortcodeParams = function(model) {
					this.changeShortcodeParamsOld(model);
					this.moveAdminLabelsAfterTitle();
				})				
			&& (window.VcTrxAddonsContainerView = window.VcColumnView.extend({
				}));
			
		// Refresh taxonomies and terms lists when post type is changed in widgets mode
		jQuery('.widget-liquid-right,.widgets-holder-wrap,.customize-control-widget_form,.edit-widgets-block-editor,.customize-control-sidebar_block_editor')
			.on('change', '.trx_addons_post_type_selector,.trx_addons_taxonomy_selector', function() {
				var field = jQuery(this),
					num = 0;
				field
					.parent()
					.nextAll()
					.find( field.attr('class').indexOf('_post_type_selector') > 0 ? 'select[class*="_taxonomy_selector"]' : 'select[class*="_terms_selector"]')
					.each(function() {
						var cat_fld = jQuery(this);
						var cat_lbl = cat_fld.prev('label');
						setTimeout(function(){
							trx_addons_refresh_list(cat_fld.attr('class').indexOf('_taxonomy_selector') > 0
													? 'taxonomies'
													: 'terms',
												field.val(),
												cat_fld,
												cat_lbl);
						}, 300*num);
						num++;
					});
				return false;
			});
	
		// Refresh taxonomies and terms lists when post type is changed in ThemeREX Addons Options
		jQuery('.trx_addons_options')
			.on('change', '.trx_addons_post_type_selector,.trx_addons_taxonomy_selector', function() {
				var field_container = jQuery(this).parents('.trx_addons_options_item');
				var cat_fld = field_container.next().find('[class*="_selector"]');
				var cat_lbl = field_container.next().find('.trx_addons_options_item_title');
				if (cat_fld.length > 0) {
					trx_addons_refresh_list(cat_fld.hasClass('trx_addons_taxonomy_selector')
												? 'taxonomies' 
												: 'terms',
											jQuery(this).val(),
											cat_fld,
											cat_lbl);
				}
				return false;
			});
		// Refresh taxonomies and terms lists when post type is changed in VC editor
		jQuery('body').on('change', 'select.post_type,select.taxonomy', function () {
			var cat_fld = jQuery(this).parents('.vc_shortcode-param').next().find('select');
			if (cat_fld.length > 0) {
				var cat_lbl = jQuery(this).parents('.vc_shortcode-param').next().find('.wpb_element_label');
				trx_addons_refresh_list(cat_fld.hasClass('taxonomy')
											? 'taxonomies'
											: 'terms',
										jQuery(this).val(),
										cat_fld,
										cat_lbl);
			}
			return false;
		});
		// Refresh taxonomies and terms lists when post type is changed in SOW editor
		jQuery('body').on('change', 'select[name*="post_type"],select[name*="taxonomy"]', function () {
			var cat_fld = jQuery(this).parents('.siteorigin-widget-field,[class*="widget_field_type_"]').next().find('select');
			if (cat_fld.length > 0) {
				var cat_lbl = jQuery(this).parents('.siteorigin-widget-field,[class*="widget_field_type_"]').next().find('.siteorigin-widget-field-label,label.widget_field_title');
				trx_addons_refresh_list(cat_fld.attr('name').indexOf('taxonomy') > 0
											? 'taxonomies'
											: 'terms',
										jQuery(this).val(),
										cat_fld,
										cat_lbl);
			}
			return false;
		});

		// Refresh link on the post editor when select with layout is changed in VC editor
		jQuery('body').on('change', 'select.layout', function () {
			var a = jQuery(this).next('.vc_description').find('a.trx_addons_post_editor');
			var id = jQuery(this).val();
			if (a.length > 0 && id > 0)
				a.attr('href', a.attr('href').replace(/post=[0-9]{1,5}/, "post="+id));
		});
		
		// Prepare media selector params
		TRX_ADDONS_STORAGE['media_id'] = '';
		TRX_ADDONS_STORAGE['media_frame'] = [];
		TRX_ADDONS_STORAGE['media_link'] = [];

		// First run init fields
		trx_addons_admin_init_fields();
		jQuery(document).on('action.init_hidden_elements', trx_addons_admin_init_fields);
	});

	

	// Init fields at first run and after clone group
	// -------------------------------------------------------------------------------------
	function trx_addons_admin_init_fields(e, container) {
		
		if (container === undefined) container = jQuery('body');
	
		// Standard WP Color Picker
		if (container.find('.trx_addons_color_selector:not(.inited)').length > 0) {
			container.find('.trx_addons_color_selector:not(.inited)').addClass('inited').wpColorPicker({
				// you can declare a default color here,
				// or in the data-default-color attribute on the input
				//defaultColor: false,
		
				// a callback to fire whenever the color changes to a valid color
				change: function(e, ui){
					jQuery(e.target).val(ui.color).trigger('change');
				},
		
				// a callback to fire when the input is emptied or an invalid color
				clear: function(e) {
					jQuery(e.target).prev().trigger('change')
				},
		
				// hide the color picker controls on load
				//hide: true,
		
				// show a group of common colors beneath the square
				// or, supply an array of colors to customize further
				//palettes: true
			});
		}
		
		// Icon selector
		// Attention! Init container, because icon_selector appear in a cloneable blocks
		if (!container.hasClass('trx_addons_icon_selector_inited')) {
			container.addClass('trx_addons_icon_selector_inited')
				.on('click', '.trx_addons_icon_selector', function(e) {
					var selector = jQuery(this);
					var list = selector.next('.trx_addons_list_icons');
					if (list.length > 0) {
						if (list.css('display') == 'block')
							list.slideUp();
						else {
							var css_obj = {};
							if (selector.parents('#trx_addons_meta_box').length > 0) {
								css_obj = { 
									'position': 'relative',
									'left': 'auto',
									'top': 'auto'
								};
							} else {
								var pos = selector.position();
								css_obj = {
									'position': 'absolute',
									'left': pos.left,
									'top': pos.top+selector.height()+4
								};
							}
							list.find('.trx_addons_list_icons_search').val('');
							list.find('span').removeClass('trx_addons_list_hidden');
							list.css(css_obj).slideDown(function() {
																	list.find('.trx_addons_list_icons_search').focus();
																});
						}
					}
					e.preventDefault();
					return false;
				})
				.on('keyup', '.trx_addons_list_icons_search', function(e) {
					var list = jQuery(this).parent(),
						val = jQuery(this).val();
					list.find('span').removeClass('trx_addons_list_hidden');
					if (val!='') list.find('span:not([data-icon*="'+val+'"])').addClass('trx_addons_list_hidden');
				})
				.on('click', '.trx_addons_list_icons > span', function(e) {
					var list = jQuery(this).parent();
					list.find('.trx_addons_active').removeClass('trx_addons_active');
					var selector = list.prev('.trx_addons_icon_selector');
					var input = selector.length==1 ? selector.prev('input') : list.prev('input');
					var icon = jQuery(this).addClass('trx_addons_active').data('icon');
					input.val(icon).trigger('change');
					if (selector.length > 0) {
						if (selector.data('style') == 'icons')
							selector.attr('class', trx_addons_chg_icon_class(selector.attr('class'), icon));
						else
							selector.css('background-image', jQuery(this).css('background-image'));
						list.fadeOut();
					}
					e.preventDefault();
					return false;
				});
		}
		
		// Media selector
		container.find('#customize-theme-controls:not(.inited)'
						+',.widget-liquid-right:not(.inited)'
						+',.widgets-holder-wrap:not(.inited)'
						+',.widget_field_type_image:not(.inited)'
						+',.form-field:not(.inited)'
						+',.postbox-container:not(.inited)'
						+',.trx_addons_options_item_field:not(.inited)'
						+',.edit-widgets-block-editor:not(.inited)'
						)
			.addClass('inited')
			.on('click', '.trx_addons_media_selector', function(e) {
				trx_addons_show_media_manager(this);
				e.preventDefault();
				return false;
			})
			.on('click', '.trx_addons_media_selector_preview > span', function(e) {
				var image = jQuery(this);
				var button = image.parent().prev('.trx_addons_media_selector');
				var field = jQuery('#'+button.data('linked-field'));
				if (field.length == 0) return;
				if (button.data('multiple')==1) {
					var val = field.val().split('|');
					val.splice(image.index(), 1);
					field.val(val.join('|'));
					image.remove();
				} else {
					field.val('');
					image.remove();
				}
				e.preventDefault();
				return false;
			});
	}
	
	
	// Show WP Media manager to select image
	// -------------------------------------------------------------------------------------
	function trx_addons_show_media_manager(el) {
	
		TRX_ADDONS_STORAGE['media_id'] = jQuery(el).attr('id');
		TRX_ADDONS_STORAGE['media_link'][TRX_ADDONS_STORAGE['media_id']] = jQuery(el);
		// If the media frame already exists, reopen it.
		if ( TRX_ADDONS_STORAGE['media_frame'][TRX_ADDONS_STORAGE['media_id']] ) {
			TRX_ADDONS_STORAGE['media_frame'][TRX_ADDONS_STORAGE['media_id']].open();
			return false;
		}
	
		// Create the media frame
		var type = TRX_ADDONS_STORAGE['media_link'][TRX_ADDONS_STORAGE['media_id']].data('type') 
						? TRX_ADDONS_STORAGE['media_link'][TRX_ADDONS_STORAGE['media_id']].data('type') 
						: 'image';
		var args = {
			// Set the title of the modal.
			title: TRX_ADDONS_STORAGE['media_link'][TRX_ADDONS_STORAGE['media_id']].data('choose'),
			// Multiple choise
			multiple: TRX_ADDONS_STORAGE['media_link'][TRX_ADDONS_STORAGE['media_id']].data('multiple')==1 
						? 'add' 
						: false,
			// Customize the submit button.
			button: {
				// Set the text of the button.
				text: TRX_ADDONS_STORAGE['media_link'][TRX_ADDONS_STORAGE['media_id']].data('update'),
				// Tell the button not to close the modal, since we're
				// going to refresh the page when the image is selected.
				close: true
			}
		};
		// Allow sizes and filters for the images
		if (type == 'image') {
			args['frame'] = 'post';
		}
		// Tell the modal to show only selected post types
		if (type == 'image' || type == 'audio' || type == 'video') {
			args['library'] = {
				type: type
			};
		}
		TRX_ADDONS_STORAGE['media_frame'][TRX_ADDONS_STORAGE['media_id']] = wp.media(args);
	
		// When an image is selected, run a callback.
		TRX_ADDONS_STORAGE['media_frame'][TRX_ADDONS_STORAGE['media_id']].on( 'insert select', function(selection) {
			// Grab the selected attachment.
			var field = jQuery("#"+TRX_ADDONS_STORAGE['media_link'][TRX_ADDONS_STORAGE['media_id']].data('linked-field')).eq(0);
			var attachment = null, attachment_url = '';
			if (TRX_ADDONS_STORAGE['media_link'][TRX_ADDONS_STORAGE['media_id']].data('multiple')==1) {
				TRX_ADDONS_STORAGE['media_frame'][TRX_ADDONS_STORAGE['media_id']].state().get('selection').map( function( att ) {
					attachment_url += (attachment_url ? "|" : "") + att.toJSON().url;
				});
				var val = field.val();
				attachment_url = val + (val ? "|" : '') + attachment_url;
			} else {
				attachment = TRX_ADDONS_STORAGE['media_frame'][TRX_ADDONS_STORAGE['media_id']].state().get('selection').first().toJSON();
				attachment_url = attachment.url;
				var sizes_selector = jQuery('.media-modal-content .attachment-display-settings select.size');
				if (sizes_selector.length > 0) {
					var size = trx_addons_get_listbox_selected_value(sizes_selector.get(0));
					if (size != '') attachment_url = attachment.sizes[size].url;
				}
			}
			// Display images in the preview area
			var preview = field.siblings('.trx_addons_media_selector_preview');
			if (preview.length == 0) {
				jQuery('<span class="trx_addons_media_selector_preview"></span>').insertAfter(field);
				preview = field.siblings('.trx_addons_media_selector_preview');
			}
			if (preview.length != 0) preview.empty();
			var images = attachment_url.split("|");
			for (var i=0; i < images.length; i++) {
				if (preview.length != 0) {
					var ext = trx_addons_get_file_ext(images[i]);
					preview.append('<span>'
									+ (ext=='gif' || ext=='jpg' || ext=='jpeg' || ext=='png' 
											? '<img src="'+images[i]+'">'
											: '<a href="'+images[i]+'">'+trx_addons_get_file_name(images[i])+'</a>'
										)
									+ '</span>');
				}
			}
			// Update field
			field.val(attachment_url).trigger('change');
		});
	
		// Finally, open the modal.
		TRX_ADDONS_STORAGE['media_frame'][TRX_ADDONS_STORAGE['media_id']].open();
		return false;
	}

})();