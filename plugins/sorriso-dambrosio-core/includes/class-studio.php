<?php
/**
 * Lo studio: orari e recapiti. Unica fonte.
 *
 * @package SorrisoDambrosio\Core
 */

declare( strict_types = 1 );

namespace SD\Core;

defined( 'ABSPATH' ) || exit;

/**
 * I dati dello studio, in un posto solo.
 *
 * Stavano nel `functions.php` del tema. Sono contenuto, non presentazione: se
 * cancellando il tema si perde un'informazione, quell'informazione sta nel
 * posto sbagliato. Da qui li leggono la fascia orari della homepage, il footer,
 * la pagina dei contatti e il JSON-LD: quattro usi, una riga sola da cambiare.
 */
final class Studio {

	/**
	 * I recapiti.
	 *
	 * @return array<string, string>
	 */
	public static function contacts(): array {
		return array(
			'name'          => 'Studio Odontoiatrico D\'Ambrosio',
			'phone'         => '+39 06 322 5754',
			'phone_href'    => 'tel:063225754',
			'phone2'        => '+39 06 361 0625',
			'phone2_href'   => 'tel:063610625',
			'mobile'        => '+39 375 552 4596',
			'mobile_href'   => 'tel:+393755524596',
			'email'         => 'studio@sorrisodambrosio.it',
			'email_href'    => 'mailto:studio@sorrisodambrosio.it',
			'address'       => 'Lungotevere Flaminio n. 34 &#8211; 00196 &#8211; Roma (RM)',
			'address_short' => 'Lungotevere Flaminio 34, Roma',
			'street'        => 'Lungotevere Flaminio 34',
			'postal_code'   => '00196',
			'city'          => 'Roma',
			'region'        => 'RM',
			'country'       => 'IT',
			'map_href'      => 'https://maps.app.goo.gl/jsVVDPiXFAFLCiE18',
			'whatsapp'      => 'https://api.whatsapp.com/send/?phone=3755524596&text&type=phone_number&app_absent=0',
			'instagram'     => 'https://www.instagram.com/studioodontoiatricodambrosio/',
			'facebook'      => 'https://www.facebook.com/studioodontoiatricodambrosio/',
		);
	}

	/**
	 * Gli orari, indicizzati come `date('N')` (1 = lunedi').
	 *
	 * La chiave e' il numero del giorno proprio per poter evidenziare oggi
	 * senza doverlo dedurre dal nome.
	 *
	 * @return array<int, array{name: string, morning: string, afternoon: string}>
	 */
	public static function hours(): array {
		return array(
			1 => array( 'name' => 'Lunedi', 'morning' => '9:00 &#8211; 13:00', 'afternoon' => '14:00 &#8211; 19:00' ),
			2 => array( 'name' => 'Martedi', 'morning' => '9:00 &#8211; 13:00', 'afternoon' => 'Chiuso' ),
			3 => array( 'name' => 'Mercoled&igrave;', 'morning' => '9:00 &#8211; 13:00', 'afternoon' => '14:00 &#8211; 19:00' ),
			4 => array( 'name' => 'Gioved&igrave;', 'morning' => '9:00 &#8211; 13:00', 'afternoon' => '14:00 &#8211; 19:00' ),
			5 => array( 'name' => 'Venerd&igrave;', 'morning' => '9:00 &#8211; 13:00', 'afternoon' => '14:00 &#8211; 19:00' ),
			6 => array( 'name' => 'Sabato', 'morning' => 'Chiuso', 'afternoon' => 'Chiuso' ),
			7 => array( 'name' => 'Domenica', 'morning' => 'Chiuso', 'afternoon' => 'Chiuso' ),
		);
	}

	/**
	 * Gli stessi orari in forma leggibile da una macchina.
	 *
	 * Serve al JSON-LD. Un turno «Chiuso» non produce nessuna voce: dire a
	 * Google quando si e' chiusi non e' informazione, e' rumore.
	 *
	 * @return array<int, array{day: string, opens: string, closes: string}>
	 */
	public static function hours_machine(): array {
		$days = array(
			1 => 'Monday',
			2 => 'Tuesday',
			3 => 'Wednesday',
			4 => 'Thursday',
			5 => 'Friday',
			6 => 'Saturday',
			7 => 'Sunday',
		);

		$out = array();

		foreach ( self::hours() as $n => $day ) {
			foreach ( array( $day['morning'], $day['afternoon'] ) as $shift ) {
				$plain = html_entity_decode( $shift, ENT_QUOTES | ENT_HTML5, 'UTF-8' );

				if ( ! preg_match( '/(\d{1,2}:\d{2})\s*[–-]\s*(\d{1,2}:\d{2})/u', $plain, $m ) ) {
					continue;
				}

				$out[] = array(
					'day'    => $days[ $n ],
					'opens'  => self::pad( $m[1] ),
					'closes' => self::pad( $m[2] ),
				);
			}
		}

		return $out;
	}

	/** «9:00» non e' un orario ISO; «09:00» si'. */
	private static function pad( string $time ): string {
		[ $h, $m ] = array_pad( explode( ':', $time ), 2, '00' );

		return str_pad( $h, 2, '0', STR_PAD_LEFT ) . ':' . $m;
	}

	/**
	 * Il giorno di oggi secondo il fuso del sito.
	 *
	 * `wp_date()` e non `date()`: il secondo userebbe il fuso del server, che
	 * su un hosting non e' detto sia quello di Roma.
	 */
	public static function today(): int {
		return (int) wp_date( 'N' );
	}
}
