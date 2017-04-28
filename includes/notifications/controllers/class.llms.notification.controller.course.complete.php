<?php
/**
 * Notification Controller: Course Complete
 * @since    3.8.0
 * @version  3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class LLMS_Notification_Controller_Course_Complete extends LLMS_Abstract_Notification_Controller {

	/**
	 * Trigger Identifier
	 * @var  [type]
	 */
	public $id = 'course_complete';

	/**
	 * Number of accepted arguments passed to the callback function
	 * @var  integer
	 */
	protected $action_accepted_arguments = 2;

	/**
	 * Action hooks used to trigger sending of the notification
	 * @var  array
	 */
	protected $action_hooks = array( 'lifterlms_course_completed' );

	/**
	 * Callback function called when a course is completed by a student
	 * @param    int     $student_id  WP User ID of a LifterLMS Student
	 * @param    int     $course_id   WP Post ID of a LifterLMS Course
	 * @return   void
	 * @since    3.8.0
	 * @version  3.8.0
	 */
	public function action_callback( $student_id = null, $course_id = null ) {

		$this->user_id = $student_id;
		$this->post_id = $course_id;
		$this->course = llms_get_post( $course_id );

		$this->send();

	}

	/**
	 * Takes a subscriber type (student, author, etc) and retrieves a User ID
	 * @param    string     $subscriber  subscriber type string
	 * @return   int|false
	 * @since    3.8.0
	 * @version  3.8.0
	 */
	protected function get_subscriber( $subscriber ) {

		switch ( $subscriber ) {

			case 'course_author':
				$uid = $this->course->get( 'author' );
			break;

			case 'student':
				$uid = $this->user_id;
			break;

			default:
				$uid = false;

		}

		return $uid;

	}

	/**
	 * Get the translateable title for the notification
	 * used on settings screens
	 * @return   string
	 * @since    3.8.0
	 * @version  3.8.0
	 */
	public function get_title() {
		return __( 'Course Complete', 'lifterlms' );
	}

	/**
	 * Setup the subscriber options for the notification
	 * @param    string     $type  notification type id
	 * @return   array
	 * @since    3.8.0
	 * @version  3.8.0
	 */
	protected function set_subscriber_options( $type ) {

		$options = array();

		switch ( $type ) {

			case 'basic':
				$options[] = array(
					'enabled' => 'yes',
					'id' => 'student',
					'title' => __( 'Student', 'lifterlms' ),
				);
			break;

			case 'email':
				$options[] = array(
					'enabled' => 'no',
					'id' => 'student',
					'title' => __( 'Student', 'lifterlms' ),
				);
				$options[] = array(
					'enabled' => 'yes',
					'id' => 'course_author',
					'title' => __( 'Course Author', 'lifterlms' ),
				);
				$options[] = array(
					'description' => __( 'Enter additional email addresses which will recieve this notification. Separate multilpe addresses with commas.', 'lifterlms' ),
					'enabled' => 'no',
					'id' => 'custom',
					'title' => __( 'Additional Recipients', 'lifterlms' ),
				);
			break;

		}

		return $options;

	}

}

return LLMS_Notification_Controller_Course_Complete::instance();