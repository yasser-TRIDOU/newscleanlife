<?php
/**
 * module return as json_encode
 * http://www.aa-team.com
 * ======================
 *
 * @author		Andrei Dinca, AA-Team
 * @version		1.0
 */

if ( ! function_exists('__metaRobotsList') ) {
	function __metaRobotsList() {
		return array(
		'noindex'	=> 'noindex', //support by: Google, Yahoo!, MSN / Live, Ask
		'nofollow'	=> 'nofollow', //support by: Google, Yahoo!, MSN / Live, Ask
		'noarchive'	=> 'noarchive', //support by: Google, Yahoo!, MSN / Live, Ask
		'noodp'		=> 'noodp' //support by: Google, Yahoo!, MSN / Live
		);
	}
}
$__metaRobotsList = __metaRobotsList();

if ( ! function_exists('psp_CustomPosttypeTaxonomyMeta') ) {
	function psp_CustomPosttypeTaxonomyMeta( $istab = '', $is_subtab = '', $params = array() ) {
		global $psp;
	
		$fields_name = array(
		'title'			=> array(
			'name'			=>esc_html__('Title Format', 'smartSEO'),
			'std'				=> array(
				'posttype'		=> '', //'{title} | {site_title}',
				'taxonomy'	=> '', //'{title} | {site_title}',
			),
		),
		'desc'		=> array(
			'name'			=>esc_html__('Meta Description', 'smartSEO'),
			'std'				=> array(
				'posttype'		=> '', //'{short_description} | {site_description}',
				'taxonomy'	=> '', //'{term_description}',
			),
		),
		'kw'			=> array(
			'name'			=>esc_html__('Meta Keywords', 'smartSEO'),
			'std'				=> array(
				'posttype'		=> '', //'{keywords}',
				'taxonomy'	=> '', //'{keywords}',
			),
		),
		'robots'		=> array(
			'name'			=>esc_html__('Meta Robots', 'smartSEO'),
			'std'				=> array(
				'posttype'		=> array(),
				'taxonomy'	=> array(),
			),
		)
		);
	
		$params = array_merge(array(
		'builtin'			=> false,
		'what'			=> '',
		'field'				=> '',
		), $params);
		extract( $params );
	
		ob_start();
	
		$pms = array(
			'public'   => true,
		);
		if ( $builtin === true || $builtin === false  ) {
			$pms = array_merge($pms, array(
			'_builtin' => $builtin, // exclude post, page, attachment
			));
		}

		if ( 'posttype' == $what ) {
			$uniqueKey = 'posttype_custom';
			$post_types = get_post_types($pms, 'objects');
			//unset media - images | videos /they are treated as belonging to post, pages, custom post types
			unset($post_types['attachment'], $post_types['revision'], $post_types['nav_menu_item']);
		
			$field_desc = wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {id} {date} {description} {short_description} {parent} {author} {author_username} {author_nickname} {categories} {tags} {terms} {category} {category_description} {tag} {tag_description} {term} {term_description} {keywords} {focus_keywords} {multi_focus_keywords}' . '</span>';
		} else {
			$uniqueKey = 'taxonomy_custom';
			$post_types = get_taxonomies($pms, 'objects');
			unset($post_types['post_format'], $post_types['nav_menu'], $post_types['link_category']);
		
			$field_desc = wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {term} {term_description}' . '</span>';
		}
	
		if ( 'robots' == $field ) {
			$field_desc = esc_html__('if you do not select "noindex", then "index" is by default active; if you do not select "nofollow", then "follow" is by default active', 'smartSEO');
		}
	
		$options = $psp->get_theoption('psp_title_meta_format');

		foreach ($post_types as $key => $value) {
			$field_label = $value->labels->name;
			$field_label = $psp->get_taxonomy_nice_name( $field_label );
			$field_label = str_replace('_', ' ', $field_label);
			$field_label = ucfirst($field_label);
			$field_label = $field_label . '<br/><span>' . $fields_name["$field"]['name'] . ':</span>';
			?>

<div class="panel-body psp-panel-body psp-form-row<?php echo ( $istab!='' ? ' ' . $istab : '' ); ?><?php echo ( $is_subtab!='' ? ' ' . $is_subtab : '' ); ?>">
	<label class="psp-form-label psp-metatags-tagtitle"><?php echo $field_label; ?></label>
	<div class="psp-form-item large">

			<?php
			//:: start current value
			$val = '';
			if ( isset($fields_name["$field"], $fields_name["$field"]['std'], $fields_name["$field"]['std']["$what"]) ) {
				$val = $fields_name["$field"]['std']["$what"];
			}
			// compatibility with old version, where exists the following keys: product_(title|desc|kw|robots)
			if ( ( 'product' == $key ) && isset($options["product_{$field}"]) && ! empty($options["product_{$field}"]) ) {
				$val = $options["product_{$field}"];
			}
			if ( isset($options["$uniqueKey"]) && isset($options["$uniqueKey"][$field]) && isset($options["$uniqueKey"][$field][$key]) ) {
				$val = $options["$uniqueKey"][$field][$key];
			}
			//:: end current value
		
			if ( 'title' == $field || 'kw' == $field ) {
				?>
			<input type="text" id="<?php echo $uniqueKey; ?>[<?php echo $field; ?>][<?php echo $key; ?>]" name="<?php echo $uniqueKey; ?>[<?php echo $field; ?>][<?php echo $key; ?>]" value="<?php echo $val; ?>" style="width:400px;">
				<?php
			} else if ( 'desc' == $field ) {
				?>
			<textarea id="<?php echo $uniqueKey; ?>[<?php echo $field; ?>][<?php echo $key; ?>]" name="<?php echo $uniqueKey; ?>[<?php echo $field; ?>][<?php echo $key; ?>]" style="height:120px;" cols="120"><?php echo $val; ?></textarea>
				<?php
			} else if ( 'robots' == $field ) {
				?>
			<select multiple="multiple" size="6" id="<?php echo $uniqueKey; ?>[<?php echo $field; ?>][<?php echo $key; ?>]" name="<?php echo $uniqueKey; ?>[<?php echo $field; ?>][<?php echo $key; ?>][]" style="width:400px;">
				<?php
				foreach (array('noindex', 'nofollow', 'noarchive', 'noodp') as $metarobot_val) {
					$is_selected = in_array($metarobot_val, (array) $val) ? 'selected="selected"' : '';
					echo '<option value="' . $metarobot_val . '" ' . $is_selected . '>' . $metarobot_val . '</option>';
				}
				?>
			</select>
				<?php
			}
			?>
		<span class="psp-form-note"><?php echo $field_desc; ?></span>

	</div>
</div>
		
			<?php
		} // end foreach post_types
		?>

		<?php
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
}


global $psp;

//echo json_encode(
$__psp_mfo = 
	array(
		$tryed_module['db_alias'] => array(
			/* define the form_messages box */
			'title_meta_format' => array(
				'title' 	=>esc_html__('Title & Meta Formats', 'smartSEO'),
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> true, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> true, // true|false
				'style' 	=> 'panel', // panel|panel-widget

				// tabs
				'tabs'	=> array(
					'__tab1'	=> array(esc_html__('Format Tags List', 'smartSEO'), 'help_format_tags'),
					'__tab2'	=> array(esc_html__('Title Format', 'smartSEO'), 'force_title,home_title,post_title,page_title,posttype_title,product_title,category_title,tag_title,taxonomy_title,archive_title,author_title,search_title,404_title,pagination_title,use_pagination_title'),
					'__tab3'	=> array(esc_html__('Meta Description', 'smartSEO'), 'home_desc,post_desc,page_desc,posttype_desc,product_desc,category_desc,tag_desc,taxonomy_desc,archive_desc,author_desc,pagination_desc,use_pagination_desc'),
					'__tab4'	=> array(esc_html__('Meta Keywords', 'smartSEO'), 'home_kw,post_kw,page_kw,posttype_kw,product_kw,category_kw,tag_kw,taxonomy_kw,archive_kw,author_kw,pagination_kw,use_pagination_kw'),
					'__tab5'	=> array(esc_html__('Meta Robots', 'smartSEO'), 'home_robots,post_robots,page_robots,posttype_robots,product_robots,category_robots,tag_robots,taxonomy_robots,archive_robots,author_robots,search_robots,404_robots,pagination_robots,use_pagination_robots, help_meta_robots'),
				),
				
				// tabs
				'subtabs'	=> array(
					'__tab1'	=> array(
						'__subtab1' => array(
							esc_html__('Wordpress', 'smartSEO'), 'help_format_tags')),
					'__tab2'	=> array(
						'__subtab1' => array(
							esc_html__('Wordpress', 'smartSEO'), 'home_title,post_title,page_title,category_title,tag_title,archive_title,author_title,search_title,404_title,pagination_title,use_pagination_title'),
						'__subtab2' => array(
							esc_html__('Custom Post Type', 'smartSEO'), 'posttype_title, product_title'),
						'__subtab3' => array(
							esc_html__('Custom Taxonomy', 'smartSEO'), 'taxonomy_title')),
					'__tab3'	=> array(
						'__subtab1' => array(
							esc_html__('Wordpress', 'smartSEO'), 'home_desc,post_desc,page_desc,category_desc,tag_desc,archive_desc,author_desc,pagination_desc,use_pagination_desc'),
						'__subtab2' => array(
							esc_html__('Custom Post Type', 'smartSEO'), 'posttype_desc, product_desc'),
						'__subtab3' => array(
							esc_html__('Custom Taxonomy', 'smartSEO'), 'taxonomy_desc')),
					'__tab4'	=> array(
						'__subtab1' => array(
							esc_html__('Wordpress', 'smartSEO'), 'home_kw,post_kw,page_kw,category_kw,tag_kw,archive_kw,author_kw,pagination_kw,use_pagination_kw'),
						'__subtab2' => array(
							esc_html__('Custom Post Type', 'smartSEO'), 'posttype_kw, product_kw'),
						'__subtab3' => array(
							esc_html__('Custom Taxonomy', 'smartSEO'), 'taxonomy_kw')),
					'__tab5'	=> array(
						'__subtab1' => array(
							esc_html__('Wordpress', 'smartSEO'), 'home_robots,post_robots,page_robots,category_robots,tag_robots,archive_robots,author_robots,search_robots,404_robots,pagination_robots,use_pagination_robots, help_meta_robots'),
						'__subtab2' => array(
							esc_html__('Custom Post Type', 'smartSEO'), 'posttype_robots, product_robots, help_meta_robots'),
						'__subtab3' => array(
							esc_html__('Custom Taxonomy', 'smartSEO'), 'taxonomy_robots, help_meta_robots')),
					'__tab6'	=> array(
						'__subtab1' => array(
							esc_html__('General', 'smartSEO'), 'social_use_meta,social_include_extra,social_validation_type,social_site_title,social_default_img,social_fb_app_id'),
						'__subtab2' => array(
							esc_html__('Posts, Pages', 'smartSEO'), 'social_opengraph_default, social_customfield_post'),
						'__subtab3' => array(
							esc_html__('Categories, Tags', 'smartSEO'), 'social_opengraph_default_taxonomy, social_customfield_taxonomy'),
						'__subtab4' => array(
							esc_html__('Homepage - default', 'smartSEO'), 'social_home_title,social_home_desc,social_home_img,social_home_type, help_psp_social_home')),
					'__tab7'	=> array(
						'__subtab1' => array(
							esc_html__('General', 'smartSEO'), 'psp_twc_use_meta,psp_twc_website_account,psp_twc_website_account_id,psp_twc_creator_account,psp_twc_creator_account_id,psp_twc_default_img,psp_twc_thumb_sizes,psp_twc_thumb_crop'),
						'__subtab2' => array(
							esc_html__('Posts, Pages', 'smartSEO'), 'help_psp_twc_post,psp_twc_cardstype_default,psp_twc_apptype_default,psp_twc_image_find'),
						'__subtab3' => array(
							esc_html__('Categories, Tags', 'smartSEO'), 'help_psp_twc_taxonomy,psp_twc_cardstype_default_taxonomy,psp_twc_apptype_default_taxonomy,psp_twc_image_find_taxonomy'),
						'__subtab4' => array(
							esc_html__('Generic App Card Type for website', 'smartSEO'), 'psp_twc_site_app,help_psp_twc_app'),
						'__subtab5' => array(
							esc_html__('Homepage - default', 'smartSEO'), 'psp_twc_home_app,psp_twc_home_type,help_psp_twc_home'))
				),
				
				// create the box elements array
				'elements'	=> array(

					//=============================================================
					//== General options
					'force_title' => array(
						'type'      => 'select',
						'std'       => 'yes',
						'size'      => 'large',
						'force_width'=> '220',
						'title'     =>esc_html__('Force Title Meta tag: ', 'smartSEO'),
						'desc'      =>esc_html__('force title meta tag (in some cases where you don\'t see the meta title you\'ve set for you post|page, you need to try and see which one of these 2 options works)', 'smartSEO'),
						'options'   => array(
							'yes'   =>esc_html__('parse page content and replace', 'smartSEO'),
							'no'    =>esc_html__('use wp_title wordpress hook', 'smartSEO')
						)
					),

					//=============================================================
					//== help
					'help_format_tags' => array(
						'type' 		=> 'message',
						
						'html' 		=> wp_kses('
							<h2>Basic Setup</h2>
							<p>You can set the custom page title using defined formats tags.</p>
							<h3>Available Format Tags</h3>
							<ul>
								<li><code>{site_title}</code> : the website\'s title (global availability)</li>
								<li><code>{site_description}</code> : the website\'s description (global availability)</li>
								<li><code>{current_date}</code> : current date (global availability)</li>
								<li><code>{current_time}</code> : current time (global availability)</li>
								<li><code>{current_day}</code> : current day (global availability)</li>
								<li><code>{current_year}</code> : current year (global availability)</li>
								<li><code>{current_month}</code> : current month (global availability)</li>
								<li><code>{current_week_day}</code> : current day of the week (global availability)</li>


								<li><code>{title}</code> : the page|post title (global availability)</li>
								<li><code>{id}</code> : the page|post id (specific availability)</li>
								<li><code>{date}</code> : the page|post date (specific availability)</li>
								<li><code>{description}</code> : the page|post full description (specific availability)</li>
								<li><code>{short_description}</code> : the page|post excerpt or if excerpt does not exist, 200 character maximum are retrieved from description (specific availability)</li>
								<li><code>{parent}</code> : the page|post parent title (specific availability)</li>
								<li><code>{author}</code> : the page|post author name (specific availability)</li>
								<li><code>{author_username}</code> : the page|post author username (specific availability)</li>
								<li><code>{author_nickname}</code> : the page|post author nickname (specific availability)</li>
								<li><code>{author_description}</code> : the page|post author biographical Info (specific availability)</li>
								<li><code>{categories}</code> : the post categories names list separated by comma (specific availability)</li>
								<li><code>{tags}</code> : the post tags names list separated by comma (specific availability)</li>
								<li><code>{terms}</code> : the post custom taxonomies terms names list separated by comma (specific availability)</li>
								<li><code>{category}</code> : the category name or the post first found category name (specific availability)</li>
								<li><code>{category_description}</code> : the category description or the post first found category description (specific availability)</li>
								<li><code>{tag}</code> : the tag name or the post first found tag name (specific availability)</li>
								<li><code>{tag_description}</code> : the tag description or the post first found tag description (specific availability)</li>
								<li><code>{term}</code> : the term name or the post first found custom taxonomy term name (specific availability)</li>
								<li><code>{term_description}</code> : the term description or the post first found custom taxonomy term description (specific availability)</li>
								<li><code>{search_keyword}</code> : the word(s) used for search (specific availability)</li>
								<li><code>{keywords}</code> : the post|page meta keywords already defined (specific availability)</li>
								<li><code>{focus_keywords}</code> : the post|page primary focus keyword (first one from the list of focus keywords) already defined (specific availability)</li>
								<li><code>{multi_focus_keywords}</code> : the post|page list of focus keywords already defined separated by comma (specific availability)</li>
								<li><code>{totalpages}</code> : the total number of pages (if pagination is used), default value is 1 (specific availability)</li>
								<li><code>{pagenumber}</code> : the page number (if pagination is used), default value is 1 (specific availability)</li>
							</ul><br />
							', array( 'br' => array(), 'h2' => array(), 'h3' => array(), 'p' => array(), 'ul' => array(), 'li' => array(), 'code' => array() ) )
					),

				//                          <p>Info: when use {keywords}, if for a specific post|page {focus_keywords} is found then it is used, otherwise {keywords} remains active</p>

					//=============================================================
					//== title format
					'home_title' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '{site_title}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Homepage <br/><span>Title Format:</span>',array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=>esc_html__('Available here: (global availability) tags', 'smartSEO')
					),
					'post_title'			=> array(
						'type' 		=> 'text',
						'std' 		=> '{title} | {site_title}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Post <br/><span>Title Format:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {id} {date} {description} {short_description} {parent} {author} {author_username} {author_nickname} {categories} {tags} {terms} {category} {category_description} {tag} {tag_description} {term} {term_description} {keywords} {focus_keywords} {multi_focus_keywords}' . '</span>'
					),
					'page_title'	=> array(
						'type' 		=> 'text',
						'std' 		=> '{title} | {site_title}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Page <br/><span>Title Format:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {id} {date} {description} {short_description} {parent} {author} {author_username} {author_nickname} {categories} {tags} {terms} {category} {category_description} {tag} {tag_description} {term} {term_description} {keywords} {focus_keywords} {multi_focus_keywords}' . '</span>'
					),
					'category_title'=> array(
						'type' 		=> 'text',
						'std' 		=> '{title} | {site_title}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Category <br/><span>Title Format:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {category} {category_description}' . '</span>'
					),
					'tag_title'=> array(
						'type' 		=> 'text',
						'std' 		=> '{title} | {site_title}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Tag <br/><span>Title Format:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {tag} {tag_description}' ,'</span>'
					),
					'archive_title'=> array(
						'type' 		=> 'text',
						'std' 		=> '{title} ' .esc_html__('Archives', 'smartSEO') . ' | {site_title}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Archives <br/><span>Title Format:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {date} ' . '</span>' .esc_html__('- is based on archive type: per year or per month,year or per day,month,year', 'smartSEO')
					),
					'author_title'	=> array(
						'type' 		=> 'text',
						'std' 		=> '{title} | {site_title}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Author <br/><span>Title Format:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {author} {author_username} {author_nickname}' . '</span>'
					),
					'search_title'	=> array(
						'type' 		=> 'text',
						'std' 		=>esc_html__('Search for ', 'smartSEO') . '{search_keyword} | {site_title}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Search <br/><span>Title Format:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {search_keyword}' . '</span>'
					),
					'404_title'		=> array(
						'type' 		=> 'text',
						'std' 		=>esc_html__('404 Page Not Found |', 'smartSEO') . ' {site_title}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('404 Page Not Found <br/><span>Title Format:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=>esc_html__('Available here: (global availability) tags', 'smartSEO')
					),
					'pagination_title'=> array(
						'type' 		=> 'text',
						'std' 		=> '{title} ' .esc_html__('- Page', 'smartSEO') . ' {pagenumber} ' .esc_html__('of', 'smartSEO') . ' {totalpages} | {site_title}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Pagination <br/><span>Title Format:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {totalpages} {pagenumber}' . '</span>'
					),
					'use_pagination_title' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=>esc_html__('Use Pagination:', 'smartSEO'),
						'desc' 		=>esc_html__('Choose Yes if you want to use Pagination Title Format in pages where it can be applied!', 'smartSEO'),
						'options'	=> array(
							'yes' 	=>esc_html__('YES', 'smartSEO'),
							'no' 	=>esc_html__('NO', 'smartSEO')
						)
					),

					'posttype_title'	=> array(
						'type' 		=> 'text',
						'std' 		=> '{title} | {site_title}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('--Generic-- <br/><span>Title Format:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {id} {date} {description} {short_description} {parent} {author} {author_username} {author_nickname} {categories} {tags} {terms} {category} {category_description} {tag} {tag_description} {term} {term_description} {keywords} {focus_keywords} {multi_focus_keywords}' . '</span>'
					),
					/*'product_title'	=> array(
						'type' 		=> 'text',
						'std' 		=> '{title} | {site_title}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Product <br/><span>Title Format:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {id} {date} {description} {short_description} {parent} {author} {author_username} {author_nickname} {categories} {tags} {terms} {category} {category_description} {tag} {tag_description} {term} {term_description} {keywords} {focus_keywords} {multi_focus_keywords}' . '</span>'
					),*/
					'posttype_custom_title_html' => array(
						'type' 		=> 'html',
						'html' 		=> psp_CustomPosttypeTaxonomyMeta( '__tab2', '__subtab2', array(
							'what'		=> 'posttype',
							'field'			=> 'title',
						))
					),

					'taxonomy_title'=> array(
						'type' 		=> 'text',
						'std' 		=> '{title} | {site_title}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('--Generic-- <br/><span>Title Format:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {term} {term_description}' . '</span>'
					),
					'taxonomy_custom_title_html' => array(
						'type' 		=> 'html',
						'html' 		=> psp_CustomPosttypeTaxonomyMeta( '__tab2', '__subtab3', array(
							'what'		=> 'taxonomy',
							'field'			=> 'title',
						))
					),
					
					//=============================================================
					//== meta description
					'home_desc' 	=> array(
						'type' 		=> 'textarea',
						'std' 		=> '{site_description}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Homepage <br/><span>Meta Description:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() ))
					),
					'post_desc'			=> array(
						'type' 		=> 'textarea',
						'std' 		=> '{short_description} | {site_description}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Post <br/><span>Meta Description:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {id} {date} {description} {short_description} {parent} {author} {author_username} {author_nickname} {categories} {tags} {terms} {category} {category_description} {tag} {tag_description} {term} {term_description} {keywords} {focus_keywords} {multi_focus_keywords}' . '</span>'
					),
					'page_desc'	=> array(
						'type' 		=> 'textarea',
						'std' 		=> '{short_description} | {site_description}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Page <br/><span>Meta Description:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {id} {date} {description} {short_description} {parent} {author} {author_username} {author_nickname} {categories} {tags} {terms} {category} {category_description} {tag} {tag_description} {term} {term_description} {keywords} {focus_keywords} {multi_focus_keywords}' . '</span>'
					),
					'category_desc'=> array(
						'type' 		=> 'textarea',
						'std' 		=> '{category_description}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Category <br/><span>Meta Description:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {category} {category_description}' . '</span>'
					),
					'tag_desc'=> array(
						'type' 		=> 'textarea',
						'std' 		=> '{tag_description}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Tag <br/><span>Meta Description:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {tag} {tag_description}' . '</span>'
					),
					'archive_desc'=> array(
						'type' 		=> 'textarea',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Archives <br/><span>Meta Description:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {date} ' . '</span>' . wp_kses('- is based on archive type: per year or per month,year or per day,month,year', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() ))
					),
					'author_desc'	=> array(
						'type' 		=> 'textarea',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Author <br/><span>Meta Description:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {author} {author_username} {author_nickname} {author_description}' . '</span>'
					),
					'pagination_desc'=> array(
						'type' 		=> 'textarea',
						'std' 		=>esc_html__('Page {pagenumber}', 'smartSEO'),
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Pagination <br/><span>Meta Description:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {totalpages} {pagenumber}' . '</span>'
					),
					'use_pagination_desc' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=>esc_html__('Use Pagination:', 'smartSEO'),
						'desc' 		=>esc_html__('Choose Yes if you want to use Pagination Meta Description in pages where it can be applied!', 'smartSEO'),
						'options'	=> array(
							'yes' 	=>esc_html__('YES', 'smartSEO'),
							'no' 	=>esc_html__('NO', 'smartSEO')
						)
					),
					
					'posttype_desc'	=> array(
						'type' 		=> 'textarea',
						'std' 		=> '{short_description} | {site_description}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('--Generic-- <br/><span>Meta Description:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {id} {date} {description} {short_description} {parent} {author} {author_username} {author_nickname} {categories} {tags} {terms} {category} {category_description} {tag} {tag_description} {term} {term_description} {keywords} {focus_keywords} {multi_focus_keywords}' . '</span>'
					),
					/*'product_desc'	=> array(
						'type' 		=> 'textarea',
						'std' 		=> '{short_description} | {site_description}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Product <br/><span>Meta Description:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {id} {date} {description} {short_description} {parent} {author} {author_username} {author_nickname} {categories} {tags} {terms} {category} {category_description} {tag} {tag_description} {term} {term_description} {keywords} {focus_keywords} {multi_focus_keywords}' . '</span>'
					),*/
					'posttype_custom_desc_html' => array(
						'type' 		=> 'html',
						'html' 		=> psp_CustomPosttypeTaxonomyMeta( '__tab3', '__subtab2', array(
							'what'		=> 'posttype',
							'field'			=> 'desc',
						))
					),

					'taxonomy_desc'=> array(
						'type' 		=> 'textarea',
						'std' 		=> '{term_description}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('--Generic-- <br/><span>Meta Description:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {term} {term_description}' . '</span>'
					),
					'taxonomy_custom_desc_html' => array(
						'type' 		=> 'html',
						'html' 		=> psp_CustomPosttypeTaxonomyMeta( '__tab3', '__subtab3', array(
							'what'		=> 'taxonomy',
							'field'			=> 'desc',
						))
					),
					
					//=============================================================
					//== meta keywords
					'home_kw' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '{keywords}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Homepage <br/><span>Meta Keywords:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() ))
					),
					'post_kw'			=> array(
						'type' 		=> 'text',
						'std' 		=> '{keywords}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Post <br/><span>Meta Keywords:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {id} {date} {description} {short_description} {parent} {author} {author_username} {author_nickname} {categories} {tags} {terms} {category} {category_description} {tag} {tag_description} {term} {term_description} {keywords} {focus_keywords} {multi_focus_keywords}' . '</span>'
					),
					'page_kw'	=> array(
						'type' 		=> 'text',
						'std' 		=> '{keywords}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Page <br/><span>Meta Keywords:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {id} {date} {description} {short_description} {parent} {author} {author_username} {author_nickname} {categories} {tags} {terms} {category} {category_description} {tag} {tag_description} {term} {term_description} {keywords} {focus_keywords} {multi_focus_keywords}' . '</span>'
					),
					'category_kw'=> array(
						'type' 		=> 'text',
						'std' 		=> '{keywords}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Category <br/><span>Meta Keywords:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {category} {category_description}' . '</span>'
					),
					'tag_kw'=> array(
						'type' 		=> 'text',
						'std' 		=> '{keywords}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Tag <br/><span>Meta Keywords:</span>', 'smartSEO'),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', 'smartSEO') . ' {tag} {tag_description}' . '</span>'
					),
					'archive_kw'=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Archives <br/><span>Meta Keywords:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', 'smartSEO') . ' {date} ' . '</span>' . wp_kses('- is based on archive type: per year or per month,year or per day,month,year', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() ))
					),
					'author_kw'	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Author <br/><span>Meta Keywords:</span>', 'smartSEO'),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', 'smartSEO') . ' {author} {author_username} {author_nickname}' . '</span>'
					),
					'pagination_kw'=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Pagination <br/><span>Meta Keywords:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {totalpages} {pagenumber}' . '</span>'
					),
					'use_pagination_kw' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=>esc_html__('Use Pagination:', 'smartSEO'),
						'desc' 		=>esc_html__('Choose Yes if you want to use Pagination Meta Keywords in pages where it can be applied!', 'smartSEO'),
						'options'	=> array(
							'yes' 	=>esc_html__('YES', 'smartSEO'),
							'no' 	=>esc_html__('NO', 'smartSEO')
						)
					),
					
					'posttype_kw'	=> array(
						'type' 		=> 'text',
						'std' 		=> '{keywords}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('--Generic-- <br/><span>Meta Keywords:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {id} {date} {description} {short_description} {parent} {author} {author_username} {author_nickname} {categories} {tags} {terms} {category} {category_description} {tag} {tag_description} {term} {term_description} {keywords} {focus_keywords} {multi_focus_keywords}' . '</span>'
					),
					/*'product_kw'	=> array(
						'type' 		=> 'text',
						'std' 		=> '{keywords}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('Product <br/><span>Meta Keywords:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {id} {date} {description} {short_description} {parent} {author} {author_username} {author_nickname} {categories} {tags} {terms} {category} {category_description} {tag} {tag_description} {term} {term_description} {keywords} {focus_keywords} {multi_focus_keywords}' . '</span>'
					),*/
					'posttype_custom_kw_html' => array(
						'type' 		=> 'html',
						'html' 		=> psp_CustomPosttypeTaxonomyMeta( '__tab4', '__subtab2', array(
							'what'		=> 'posttype',
							'field'			=> 'kw',
						))
					),

					'taxonomy_kw'=> array(
						'type' 		=> 'text',
						'std' 		=> '{keywords}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> wp_kses('--Generic-- <br/><span>Meta Keywords:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=> wp_kses('Available here: (global availability) tags; (specific availability) tags:<span class="psp-tags-specific-availability">', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )) . ' {term} {term_description}' . '</span>'
					),
					'taxonomy_custom_kw_html' => array(
						'type' 		=> 'html',
						'html' 		=> psp_CustomPosttypeTaxonomyMeta( '__tab4', '__subtab3', array(
							'what'		=> 'taxonomy',
							'field'			=> 'kw',
						))
					),
					
					//=============================================================
					//== meta robots
					'help_meta_robots' => array(
						'type' 		=> 'message',

						// <!--<li><code>NOSNIPPET</code><span> : tag tells Google not to show a snippet (description) under your Google listing, it will also not show a cached link in the search results</span></li>-->						
						'html' 		=> wp_kses('
							<h2>What it means:</h2>
							<ul>
								<li><code>NOINDEX</code><span> : tag tells Google not to index a specific page</span></li>
								<li><code>NOFOLLOW</code><span> : tag tells Google not to follow the links on a specific page</span></li>
								<li><code>NOARCHIVE</code><span> : tag tells Google not to store a cached copy of your page</span></li>
								<li><code>NOODP</code><span> : tag can prevent Google from using the meta-title and description for this page in <a href="http://www.dmoz.org/" target="_blank">DMOZ</a> (Open Directory Project) as the snippet for your page in the search results.<br/><span style="color: red;">But as of Mar 17, 2017, DMOZ is no longer available, so this tag is considered deprecated and we\'ll remove it in a future plugin version.</span></span></li>
							</ul>
							', array( 'br' => array(), 'h2' => array(), 'h3' => array(), 'p' => array(), 'ul' => array(), 'li' => array(), 'code' => array() ) )
					),

					'home_robots' 	=> array(
						'type' 		=> 'multiselect',
						'std' 		=> array(),
						'size' 		=> 'small',
						'force_width'=> '400',
						'title' 	=> wp_kses('Homepage <br/><span>Meta Robots:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=>esc_html__('if you do not select "noindex", then "index" is by default active; if you do not select "nofollow", then "follow" is by default active', 'smartSEO'),
						'options'	=> $__metaRobotsList
					),
					'post_robots'	=> array(
						'type' 		=> 'multiselect',
						'std' 		=> array(),
						'size' 		=> 'small',
						'force_width'=> '400',
						'title' 	=> wp_kses('Post <br/><span>Meta Robots:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=>esc_html__('if you do not select "noindex", then "index" is by default active; if you do not select "nofollow", then "follow" is by default active', 'smartSEO'),
						'options'	=> $__metaRobotsList
					),
					'page_robots'	=> array(
						'type' 		=> 'multiselect',
						'std' 		=> array(),
						'size' 		=> 'small',
						'force_width'=> '400',
						'title' 	=> wp_kses('Page <br/><span>Meta Robots:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=>esc_html__('if you do not select "noindex", then "index" is by default active; if you do not select "nofollow", then "follow" is by default active', 'smartSEO'),
						'options'	=> $__metaRobotsList
					),
					'category_robots'=> array(
						'type' 		=> 'multiselect',
						'std' 		=> array(),
						'size' 		=> 'small',
						'force_width'=> '400',
						'title' 	=> wp_kses('Category <br/><span>Meta Robots:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=>esc_html__('if you do not select "noindex", then "index" is by default active; if you do not select "nofollow", then "follow" is by default active', 'smartSEO'),
						'options'	=> $__metaRobotsList
					),
					'tag_robots'=> array(
						'type' 		=> 'multiselect',
						'std' 		=> array(),
						'size' 		=> 'small',
						'force_width'=> '400',
						'title' 	=> wp_kses('Tag <br/><span>Meta Robots:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=>esc_html__('if you do not select "noindex", then "index" is by default active; if you do not select "nofollow", then "follow" is by default active', 'smartSEO'),
						'options'	=> $__metaRobotsList
					),
					'archive_robots'=> array(
						'type' 		=> 'multiselect',
						'std' 		=> array('noindex','nofollow','noarchive','noodp'),
						'size' 		=> 'small',
						'force_width'=> '400',
						'title' 	=> wp_kses('Archives <br/><span>Meta Robots:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=>esc_html__('if you do not select "noindex", then "index" is by default active; if you do not select "nofollow", then "follow" is by default active', 'smartSEO'),
						'options'	=> $__metaRobotsList
					),
					'author_robots'	=> array(
						'type' 		=> 'multiselect',
						'std' 		=> array('noindex','nofollow','noarchive','noodp'),
						'size' 		=> 'small',
						'force_width'=> '400',
						'title' 	=> wp_kses('Author <br/><span>Meta Robots:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=>esc_html__('if you do not select "noindex", then "index" is by default active; if you do not select "nofollow", then "follow" is by default active', 'smartSEO'),
						'options'	=> $__metaRobotsList
					),
					'search_robots'	=> array(
						'type' 		=> 'multiselect',
						'std' 		=> array('noindex','nofollow','noarchive','noodp'),
						'size' 		=> 'small',
						'force_width'=> '400',
						'title' 	=> wp_kses('Search <br/><span>Meta Robots:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=>esc_html__('if you do not select "noindex", then "index" is by default active; if you do not select "nofollow", then "follow" is by default active', 'smartSEO'),
						'options'	=> $__metaRobotsList
					),
					'404_robots'		=> array(
						'type' 		=> 'multiselect',
						'std' 		=> array('noindex','nofollow','noarchive','noodp'),
						'size' 		=> 'small',
						'force_width'=> '400',
						'title' 	=> wp_kses('404 Page Not Found <br/><span>Meta Robots:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=>esc_html__('if you do not select "noindex", then "index" is by default active; if you do not select "nofollow", then "follow" is by default active', 'smartSEO'),
						'options'	=> $__metaRobotsList
					),
					'pagination_robots'=> array(
						'type' 		=> 'multiselect',
						'std' 		=> array('noindex','nofollow','noarchive','noodp'),
						'size' 		=> 'small',
						'force_width'=> '400',
						'title' 	=> wp_kses('Pagination <br/><span>Meta Robots:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=>esc_html__('if you do not select "noindex", then "index" is by default active; if you do not select "nofollow", then "follow" is by default active', 'smartSEO'),
						'options'	=> $__metaRobotsList
					),
					'use_pagination_robots' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=>esc_html__('Use Pagination:', 'smartSEO'),
						'desc' 		=>esc_html__('Choose Yes if you want to use Pagination Meta Robots in pages where it can be applied!', 'smartSEO'),
						'options'	=> array(
							'yes' 	=>esc_html__('YES', 'smartSEO'),
							'no' 	=>esc_html__('NO', 'smartSEO')
						)
					),
					
					'posttype_robots'	=> array(
						'type' 		=> 'multiselect',
						'std' 		=> array(),
						'size' 		=> 'small',
						'force_width'=> '400',
						'title' 	=> wp_kses('--Generic-- <br/><span>Meta Robots:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=>esc_html__('if you do not select "noindex", then "index" is by default active; if you do not select "nofollow", then "follow" is by default active', 'smartSEO'),
						'options'	=> $__metaRobotsList
					),
					'posttype_custom_robots_html' => array(
						'type' 		=> 'html',
						'html' 		=> psp_CustomPosttypeTaxonomyMeta( '__tab5', '__subtab2', array(
							'what'		=> 'posttype',
							'field'			=> 'robots',
						))
					),

					'taxonomy_robots'=> array(
						'type' 		=> 'multiselect',
						'std' 		=> array(),
						'size' 		=> 'small',
						'force_width'=> '400',
						'title' 	=> wp_kses('--Generic-- <br/><span>Meta Robots:</span>', array( 'br' => array(), 'p' => array(), 'span' => array(), 'div' => array() )),
						'desc' 		=>esc_html__('if you do not select "noindex", then "index" is by default active; if you do not select "nofollow", then "follow" is by default active', 'smartSEO'),
						'options'	=> $__metaRobotsList
					),
					'taxonomy_custom_robots_html' => array(
						'type' 		=> 'html',
						'html' 		=> psp_CustomPosttypeTaxonomyMeta( '__tab5', '__subtab3', array(
							'what'		=> 'taxonomy',
							'field'			=> 'robots',
						))
					),

				)
			)
		)
	)
//)
;

//var_dump('<pre>', $__psp_mfo, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;    
echo json_encode(
	$__psp_mfo
);
