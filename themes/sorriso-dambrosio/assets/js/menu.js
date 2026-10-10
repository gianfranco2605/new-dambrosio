/**
 * Una sola correzione al menu di core: il primo Escape.
 *
 * Il blocco Navigation fa gia' tutto il resto — apre, chiude, intrappola il
 * fuoco, blocca lo scorrimento, tiene i sottomenu chiusi con il loro
 * indicatore. Ma quando un sottomenu e' aperto il **primo** Escape viene
 * assorbito dal gestore del sottomenu e non succede niente: serve premerlo
 * due volte. Misurato, non dedotto:
 *
 *   dopo espansione   pannello: true   sottomenu: true
 *   dopo Escape 1     pannello: true   sottomenu: true   ← perso
 *   dopo Escape 2     pannello: false  sottomenu: false
 *
 * Qui si intercetta Escape in fase di cattura, prima che il gestore di core
 * lo veda, e si chiude il pannello. Se il pannello non e' aperto non si fa
 * nulla e core resta padrone del suo comportamento.
 *
 * @package SorrisoDambrosio
 */
( function () {
	'use strict';

	document.addEventListener(
		'keydown',
		function ( event ) {
			if ( 'Escape' !== event.key ) {
				return;
			}

			var panel = document.querySelector(
				'.wp-block-navigation__responsive-container.is-menu-open'
			);

			if ( ! panel ) {
				return;
			}

			var close = panel.querySelector(
				'.wp-block-navigation__responsive-container-close'
			);

			if ( ! close ) {
				return;
			}

			event.stopPropagation();
			close.click();
		},
		true
	);
}() );
