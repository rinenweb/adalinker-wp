<?php
/**
 * Plugin Name: ADALinker
 * Plugin URI: https://github.com/rinenweb/adalinker-wp
 * Description: Μετατρέπει αναφορές τύπου "ΑΔΑ: ΧΧΧΧ-ΧΧΧ" σε συνδέσμους προς τη Δι@ύγεια.
 * Version: 1.0.0
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Author: Rinenweb
 * Author URI: https://www.rinenweb.eu
 * License: GPLv3 or later
 * Text Domain: adalinker
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class ADALinker_WP {
	private const OPTION_NAME = 'adalinker_options';

	public static function init(): void {
		add_filter( 'the_content', array( __CLASS__, 'link_ada_codes' ), 12 );
		add_filter( 'the_excerpt', array( __CLASS__, 'link_ada_codes' ), 12 );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
		add_action( 'admin_menu', array( __CLASS__, 'add_settings_page' ) );
	}

	public static function activate(): void {
		if ( false === get_option( self::OPTION_NAME ) ) {
			add_option( self::OPTION_NAME, self::defaults() );
		}
	}

	private static function defaults(): array {
		return array(
			'link_class' => '',
			'url_type'   => 'page',
		);
	}

	private static function options(): array {
		return wp_parse_args( (array) get_option( self::OPTION_NAME, array() ), self::defaults() );
	}

	public static function link_ada_codes( string $content ): string {
		if ( is_admin() || '' === $content ) {
			return $content;
		}

		$options    = self::options();
		$link_class = trim( (string) $options['link_class'] );
		$url_type   = 'file' === $options['url_type'] ? 'file' : 'page';

		// Skip existing links, so we do not create nested <a> tags.
		$parts = preg_split( '~(<a\b[^>]*>.*?</a>)~is', $content, -1, PREG_SPLIT_DELIM_CAPTURE );

		if ( ! is_array( $parts ) ) {
			return $content;
		}

		$pattern = '~(?<![\p{L}\p{N}_])ΑΔΑ:\s*([\p{L}\p{N}]+(?:-[\p{L}\p{N}]+)?)(?![\p{L}\p{N}_-])~iu';

		foreach ( $parts as $index => $part ) {
			if ( preg_match( '~^<a\b~i', $part ) ) {
				continue;
			}

			$parts[ $index ] = preg_replace_callback(
				$pattern,
				static function ( array $matches ) use ( $link_class, $url_type ): string {
					$ada = $matches[1];
					$url = self::create_url( $ada, $url_type );

					$attributes = sprintf(
						'href="%s" title="%s" target="_blank" rel="noopener noreferrer"',
						esc_url( $url ),
						esc_attr( $ada )
					);

					if ( '' !== $link_class ) {
						$attributes .= ' class="' . esc_attr( $link_class ) . '"';
					}

					return 'ΑΔΑ: <a ' . $attributes . '>' . esc_html( $ada ) . '</a>';
				},
				$part
			);
		}

		return implode( '', $parts );
	}

	private static function create_url( string $ada, string $url_type ): string {
		$base_url = 'https://diavgeia.gov.gr/';

		if ( 'file' === $url_type ) {
			return $base_url . 'doc/' . rawurlencode( $ada ) . '?inline=true';
		}

		return $base_url . 'decision/view/' . rawurlencode( $ada );
	}

	public static function register_settings(): void {
		register_setting(
			'adalinker_settings',
			self::OPTION_NAME,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( __CLASS__, 'sanitize_options' ),
				'default'           => self::defaults(),
			)
		);

		add_settings_section(
			'adalinker_main',
			'Ρυθμίσεις ADALinker',
			'__return_false',
			'adalinker'
		);

		add_settings_field(
			'link_class',
			'Κλάση συνδέσμου',
			array( __CLASS__, 'render_link_class_field' ),
			'adalinker',
			'adalinker_main'
		);

		add_settings_field(
			'url_type',
			'Τύπος συνδέσμου',
			array( __CLASS__, 'render_url_type_field' ),
			'adalinker',
			'adalinker_main'
		);
	}

	public static function sanitize_options( array $input ): array {
		$output = self::defaults();

		if ( isset( $input['link_class'] ) ) {
			// Allows one or more CSS classes separated by spaces.
			$output['link_class'] = preg_replace( '/[^A-Za-z0-9_\- ]/', '', sanitize_text_field( wp_unslash( $input['link_class'] ) ) );
		}

		if ( isset( $input['url_type'] ) && in_array( $input['url_type'], array( 'page', 'file' ), true ) ) {
			$output['url_type'] = $input['url_type'];
		}

		return $output;
	}

	public static function add_settings_page(): void {
		add_options_page(
			'ADALinker',
			'ADALinker',
			'manage_options',
			'adalinker',
			array( __CLASS__, 'render_settings_page' )
		);
	}

	public static function render_settings_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1>ADALinker</h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'adalinker_settings' );
				do_settings_sections( 'adalinker' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	public static function render_link_class_field(): void {
		$options = self::options();
		?>
		<input type="text" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[link_class]" value="<?php echo esc_attr( $options['link_class'] ); ?>" class="regular-text" />
		<p class="description">Προαιρετική CSS κλάση για τον σύνδεσμο, π.χ. <code>ada-link</code>.</p>
		<?php
	}

	public static function render_url_type_field(): void {
		$options = self::options();
		?>
		<select name="<?php echo esc_attr( self::OPTION_NAME ); ?>[url_type]">
			<option value="page" <?php selected( $options['url_type'], 'page' ); ?>>Προβολή σελίδας στη Δι@ύγεια</option>
			<option value="file" <?php selected( $options['url_type'], 'file' ); ?>>Προβολή αρχείου</option>
		</select>
		<?php
	}
}

register_activation_hook( __FILE__, array( 'ADALinker_WP', 'activate' ) );
ADALinker_WP::init();
