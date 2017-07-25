<?php
/**
 * Creates the post type for Workflows.
 *
 * @since {{VERSION}}
 *
 * @package WorkflowManager
 * @subpackage WorkflowManager/core/postTypes
 */

defined( 'ABSPATH' ) || die();

/**
 * Class WorkflowManager_PostType_Workflow
 *
 * Creates the post type for Workflows.
 *
 * @since {{VERSION}}
 *
 * @package WorkflowManager
 * @subpackage WorkflowManager/core/postTypes
 */
class WorkflowManager_PostType_Workflow {

	/**
	 * WFM_PostType_Workflow constructor.
	 *
	 * @since {{VERSION}}
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'rest_api_init', array( $this, 'register_rest_custom_fields' ) );
	}

	/**
	 * Registers this post type.
	 *
	 * @since {{VERSION}}
	 * @access private
	 */
	function register_post_type() {

		/**
		 * Labels for the workflow post type.
		 *
		 * @since {{VERSION}}
		 */
		$post_labels = apply_filters( 'wfm_posttype_labels_workflow', array(
			/* translators: Label "name" for the post type "workflow" */
			'name'               => __( 'Workflows', 'workflow-manager' ),
			/* translators: Label "singular_name" for the post type "workflow" */
			'singular_name'      => __( 'Workflow', 'workflow-manager' ),
			/* translators: Label "menu_name" for the post type "workflow" */
			'menu_name'          => __( 'Workflows', 'workflow-manager' ),
			/* translators: Label "name_admin_bar" for the post type "workflow" */
			'name_admin_bar'     => __( 'Workflow', 'workflow-manager' ),
			/* translators: Label "add_new" for the post type "workflow" */
			'add_new'            => __( "Add New", 'workflow-manager' ),
			/* translators: Label "add_new_item" for the post type "workflow" */
			'add_new_item'       => __( "Add New Workflow", 'workflow-manager' ),
			/* translators: Label "new_item" for the post type "workflow" */
			'new_item'           => __( "New Workflow", 'workflow-manager' ),
			/* translators: Label "edit_item" for the post type "workflow" */
			'edit_item'          => __( "Edit Workflow", 'workflow-manager' ),
			/* translators: Label "view_item" for the post type "workflow" */
			'view_item'          => __( "View Workflow", 'workflow-manager' ),
			/* translators: Label "all_items" for the post type "workflow" */
			'all_items'          => __( "All Workflows", 'workflow-manager' ),
			/* translators: Label "search_items" for the post type "workflow" */
			'search_items'       => __( "Search Workflows", 'workflow-manager' ),
			/* translators: Label "parent_item_colon" for the post type "workflow" */
			'parent_item_colon'  => __( "Parent Workflows:", 'workflow-manager' ),
			/* translators: Label "not_found" for the post type "workflow" */
			'not_found'          => __( "No Workflows found.", 'workflow-manager' ),
			/* translators: Label "not_found_in_trash" for the post type "workflow" */
			'not_found_in_trash' => __( "No Workflows found in Trash.", 'workflow-manager' ),
		) );

		/**
		 * Arguments for the workflow post type.
		 */
		$post_args = apply_filters( 'wfm_posttype_args_workflow', array(
			'labels'             => $post_labels,
			'public'             => false,
			'publicly_queryable' => true,
			'show_ui'            => false,
			'show_in_menu'       => false,
			'show_in_rest'       => true,
			'query_var'          => true,
			'supports'           => array( 'title', 'custom-fields' ),
			'capability_type'    => 'workflow',
		) );

		register_post_type( 'workflow', $post_args );
	}

	/**
	 * Registers custom fields for the rest api.
	 *
	 * @since {{VERSION}}
	 * @access private
	 */
	function register_rest_custom_fields() {

		/**
		 * Custom fields to register with the rest api.
		 *
		 * @since {{VERSION}}
		 */
		$custom_meta = apply_filters( 'wfm_rest_custom_fields', array(
			'post_types',
			'submission_users',
			'submission_roles',
			'approval_users',
			'approval_roles',
		));

		foreach ( $custom_meta as $field ) {

			register_rest_field( 'workflow', $field, array(
				'get_callback'    => array( $this, 'rest_get_field' ),
				'update_callback' => array( $this, 'rest_update_field' ),
			) );
		}
	}

	/**
	 * Gets custom fields for the rest api.
	 *
	 * @since {{VERSION}}
	 * @access private
	 *
	 * @param $object
	 * @param $field
	 *
	 * @return mixed
	 */
	function rest_get_field( $object, $field ) {

		$post_id = $object['id'];

		return get_post_meta( $post_id, $field, true );
	}

	/**
	 * Updates custom fields for the rest api.
	 *
	 * @since {{VERSION}}
	 * @access private
	 *
	 * @param $meta
	 * @param $post
	 * @param $field
	 */
	function rest_update_field( $meta, $post, $field ) {

		update_post_meta( $post->ID, $field, $meta );
	}

	/**
	 * Gets workflows.
	 *
	 * @since {{VERSION}}
	 *
	 * @param array $args Post arguments.
	 *
	 * @return array|bool Workflows, if any.
	 */
	public static function get_workflows( $args = array() ) {

		$args['post_type'] = 'workflow';

		$workflows = get_posts( $args );

		/**
		 * Filters returned workflows.
		 *
		 * @since {{VERSION}}
		 */
		$workflows = apply_filters( 'wfm_get_workflows', $workflows );

		return $workflows;
	}
}