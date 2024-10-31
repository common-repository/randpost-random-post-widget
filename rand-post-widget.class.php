<?
	/*
		Plugin Name: RandPost
		Plugin URI: http://www.devscript.ru/
		Description: Simple output random post. (Widget)
		Version: 1.0
		Author: Fishy (Exclusive)
		Author URI: http://devscript.ru/
	*/
	class RandPost extends WP_Widget {
		public function __construct() {
			parent::__construct(
				'randPost_widget',
				__('Случайный пост', 'text_domain'),
				array('description' => __( 'Выводит случайный пост в боковую панель.', 'text_domain' ),)
			);			
		}
		public function widget($args, $instance) { // Показывает виджет.
			$title = apply_filters('widget_title', $instance['title']);
			echo $args['before_widget'];
			if(!empty($title)) {
				echo $args['before_title'].$title.$args['after_title'];
			}
			$count = wp_count_posts();
			if(!$count->publish) {
				echo __('Посты не найдены.', 'text_domain');
			}
			else {
				query_posts(array(
					'orderby' => 'rand',
					'showposts' => $instance['count']
				));
				if($instance['count'] < 2) {
					the_post();
					echo __('<p><a href="'.get_permalink().'">'.the_title('', '', false).'</a></p>', 'text_domain');
				}
				else {
					while(have_posts()) {
						the_post();
						echo __('<p><a href="'.get_permalink().'">'.the_title('', '', false).'</a></p>', 'text_domain');
					}
				}
			}	
			echo $args['after_widget'];
		}
		public function form($instance) { // Показывает форму виджета в админ-панели.
			if(isset($instance['title'])) {
				$title = $instance['title'];
			}
			else {
				$title = __('Случайный пост', 'text_domain');
			}
			echo '
				<p>
					<label for="'.$this->get_field_id('title').'">'._e( 'Title:' ).'</label>
					<input class="widefat" name="'.$this->get_field_name('title').'" type="text" value="'.$title.'"/>
					<label for"'.$this->get_field_id('title').'">Количество выводимых постов:</label>
					<input class="widefat" name="'.$this->get_field_name('count').'" type="text" value="'.(empty($instance['count']) ? '1' : $instance['count']).'"/>
				</p>'
			;	
		}
		public function update($new_instance, $old_instance) { // Событые вызывается при сохранении изменений виджета.
			$instance = array();
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['count'] = empty($new_instance['count']) ? '1' : strip_tags(strval($new_instance['count']));
			return $instance;
		}		
	}
	add_action('widgets_init', function(){
	     register_widget('RandPost');
	});	
?>