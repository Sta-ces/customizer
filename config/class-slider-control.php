<?php
namespace StacesBuilder\Inc\Customizer;

if(!class_exists('\StacesBuilder\Inc\Customizer\ST_Slider_Control')){
	class ST_Slider_Control extends \WP_Customize_Control {
		public $type = 'range';

		public function render_content(): void{
			if ( empty( $this->label ) ) return;

			$prefix = (isset($this->input_attrs['prefix']) ? $this->input_attrs['prefix'] : "");
			$suffix = (isset($this->input_attrs['suffix']) ? $this->input_attrs['suffix'] : "");

			?>
			<style>
				*[data-prefix]:before{ content: attr(data-prefix); }
				*[data-suffix]:after{ content: attr(data-suffix); }
			</style>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<input type="range"
					<?php $this->link(); ?>
					value="<?php echo esc_attr( $this->value() ); ?>"
					min="<?php echo esc_attr( $this->input_attrs['min'] ?? 0 ); ?>"
					max="<?php echo esc_attr( $this->input_attrs['max'] ?? 10 ); ?>"
					step="<?php echo esc_attr( $this->input_attrs['step'] ?? 1 ); ?>"
					oninput="this.nextElementSibling.value = this.value"
				/>
				<output style="margin-left: 10px;" data-prefix="<?php echo esc_attr($prefix); ?>" data-suffix="<?php echo esc_attr($suffix); ?>"><?php echo esc_html( $this->value() ); ?></output>
			</label>
			<?php
		}
	}
}
