#!/bin/bash
# Verifica — nessun vecchio indirizzo deve rispondere 404.
#
# Uso:  bash docs/migration/verifica-url.sh [base]
#
# L'elenco e' quello dell'inventario della fase A: le 14 pagine e i 3 articoli
# pubblicati, piu' tutte le 45 voci del vecchio menu con la loro ancora, piu'
# gli indirizzi nuovi. Ogni riga deve finire con 200, da sola o dopo un 301.

BASE="${1:-http://sorrisodambrosio.local}"
FAIL=0
TOT=0

check() {
	local path="$1"
	local url="$BASE$path"
	local code loc hop=0
	TOT=$((TOT + 1))

	while :; do
		read -r code loc <<< "$(curl -s -o /dev/null -w '%{http_code} %{redirect_url}' "$url")"
		if [ "$code" = "301" ] || [ "$code" = "302" ]; then
			hop=$((hop + 1))
			[ "$hop" -gt 3 ] && break
			url="$loc"
			continue
		fi
		break
	done

	if [ "$code" = "200" ]; then
		printf '  200%s  %s\n' "$([ $hop -gt 0 ] && echo " (dopo $hop redirect)" || echo "      ")" "$path"
	else
		printf '  %-3s      !!  %s\n' "$code" "$path"
		FAIL=$((FAIL + 1))
	fi
}

echo "— Le vecchie pagine e gli articoli"
for p in / /studio-dentistico/ /lo-studio/ /implantologia/ /ortodonzia/ /estetica-dentale/ \
         /denti-e-postura/ /trattamenti/ /tecnologie/ /casi-studi/ /70-anni-attivita/ /404-2/ \
         /magnetic-mallet/ /tooth-transformer/ /impianti-dentali/; do check "$p"; done

echo "— Le voci rotte del vecchio menu"
for p in /estetica/ /implantologia/implantologia/ /ortodonzia/ortodonzia/ /trattamenti/trattamenti/; do check "$p"; done

echo "— I nuovi indirizzi"
for p in /contatti/ /professionisti/ /prestazioni/ \
         /implantologia/carico-immediato/ /implantologia/chirurgia-computer-guidata/ \
         /implantologia/chirurgia-minimamente-invasiva/ /implantologia/post-estrattiva/ \
         /implantologia/senza-lembo-e-suture/ \
         /ortodonzia/ortodonzia-fissa/ /ortodonzia/ortodonzia-linguale/ \
         /ortodonzia/ortodonzia-invisibile/ /ortodonzia/ortodonzia-mobile/ \
         /estetica-dentale/sbiancamento-professionale/ /estetica-dentale/igiene-e-profilassi-avanzata/ \
         /estetica-dentale/faccette-estetiche/ /estetica-dentale/intarsi-in-ceramica/ \
         /estetica-dentale/inesti-in-ceramica/ /estetica-dentale/corone-estetiche-in-ceramica/ \
         /denti-e-postura/pedana-stabilometrica/ /denti-e-postura/trattamenti-con-il-bite/ \
         /denti-e-postura/gnatologia/ \
         /trattamenti/chirurgia-rigenerativa/ /trattamenti/chirurgia-per-l-ortodonzia/ \
         /trattamenti/laser-terapia/ /trattamenti/parodontologia/ /trattamenti/prevenzione-e-profilassi/ \
         /trattamenti/protesi-fissa-estetica/ /trattamenti/protesi-removibili-parziali/ \
         /trattamenti/protesi-removibili-totali/ /trattamenti/protesi-fissa-su-impianto/ \
         /trattamenti/endodonzia/ /trattamenti/conservativa/ /trattamenti/cannini-inclusi/ \
         /tecnologie/tc-cone-beam/ /tecnologie/rx-digitale-intraorale/ \
         /tecnologie/software-chirurgia-guidata/ /tecnologie/scanner-intraorale/ \
         /tecnologie/chirurgia-piezo-elettrica/ /tecnologie/fotosan/ \
         /tecnologie/ortopantomografo/ /tecnologie/sterilizzazione/ \
         /professionisti/nicoletta-dambrosio/ /professionisti/stefania-dambrosio/ \
         /professionisti/giuseppe-dambrosio/ \
         /casi-studi/paziente-m-g-41-anni/ /casi-studi/paziente-f-m-41-anni/ \
         /casi-studi/paziente-a-i-71-anni/ /casi-studi/paziente-n-t-72-anni/ \
         /casi-studi/paziente-f-n-41-anni/ /casi-studi/paziente-r-p-21-anni/ \
         /casi-studi/paziente-a-n-14-anni/ /casi-studi/paziente-d-c-11-anni/; do check "$p"; done

echo
echo "Controllati: $TOT · falliti: $FAIL"
[ "$FAIL" -eq 0 ] || exit 1
