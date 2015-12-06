# IISProject2015

TODO:
===
* (!) Kontrolovat formát emailu, čísla, apod (klient i server) - DONE FOR EMPLOYEE
* (!) Vedení 2 moduly - statistiky systému, statistiky zaměstnanců
* (!) Kontrolovat duplicitní data (tam, kde to má smysl - mail/username...)
* (!) Umožnit zákazníkovi měnit informace o sobě
* (!) !LAST!Refactorovat (pře-třídit a okomentovat) nový kód ve všech nových dokumentech + heading commetaries


Done
===
* [x] Systém bude evidovat dodavatele (externí společnost) pro každou nabízenou položku zboží. V obchodě může být nabízeno i zboží, které právě není skladem. 
* [x] Zákazník musí být informován o přibližné době dostupnosti. Tato doba bude v systému zadána pro každého dodavatele. 
* [x] Systém musí obsluze obchodu umožnit objednání zboží u dodavatele, pokud není na skladě, zaevidování zboží dodaného od dodavatele a vyřízení čekajících objednávek. 
* [x] Nemělo by být vyžadováno zapamatování a zadávání generovaných identifikátorů (cizích klíčů), jako např. ID položky na skladě. To je lépe nahradit výběrem ze seznamu. Výjimku tvoří případy, kdy se zadáním ID simuluje např. čtečka čipových karet v knihovně. V takovém případě prosím ušetřete opravujícímu práci nápovědou několika ID, která lze použít pro testování.
* [x] Pokud to v daném případě dává smysl, pole obsahující datum by měla být předvyplněna aktuálním datem.
* [x] Při odeslání formuláře s chybou by správně vyplněná pole měla zůstat zachována (uživatel by neměl být nucen vyplňovat vše znovu).
* [x] Musí být k dispozici alespoň dvě různé role uživatelů.
* [x] Správa uživatelů a jejich rolí (podle povahy aplikace, např. obchodník, zákazník, administrátor).
* [x] Zákazník i obsluha musí mít možnost sledovat stav objednávky.
* [x] Zákazník bude mít možnost tisknout faktury k již ukončeným (dodaným) objednávkám. Faktura musí obsahovat alespoň údaje o dodavateli (internetovém obchodě), odběrateli, číslo objednávky a účtované položky.
* [x] Obsluha obchodu bude moci tisknout uskutečněné objednávky.
* [x] Tím se rozumí přidávání nových uživatelů u jednotlivých rolí, stejně tak možnost editace a mazání nebo deaktivace účtů.
* [x] Žádné zadání nesmí způsobit nekonzistentní stav databáze (např. přiřazení objednávky neexistujícímu uživateli).
* [x] Musí umožňovat vložení odpovídajících vstupů.
* [x] Musí poskytovat výstupy ve formě, která je v dané oblasti využitelná. Tedy nezobrazovat obsah tabulek databáze, ale prezentovat uložená data tak, aby byla pro danou roli uživatele a danou činnost užitečná (např. spojit data z více tabulek, je-li to vhodné, poskytnout odkazy na související data, apod).
* [x] Uživatelské rozhraní musí umožňovat snadno realizovat operace pro každou roli vyplývající z diagramu případů použití (use-case).
* [x] Je-li cílem např. prodej zboží, musí systém implementovat odpovídající operaci, aby uživatel nemusel při každém prodeji ručně upravovat počty zboží na skladě, pamatovat si identifikátory položek a přepisovat je do objednávky a podobně.
* [x] Kontrolovat formát emailu, čísla, apod (klient i server) - DONE FOR EMPLOYEE
* [x] Povinná pole formulářů musí být odlišena od nepovinných ===> Hodnoty ve formulářích, které nejsou pro fungování aplikace nezbytné, neoznačujte jako povinné (např. adresy, telefonní čísla apod.) Nenuťte uživatele (opravujícího) vyplňovat desítky zbytečných řádků.
* [x] Pokud je vyžadován konkrétní formát vstupu (např. datum), měl by být u daného pole naznačen.
* [x] Ošetření všech uživatelských vstupů tak, aby nebylo možno zadat nesmyslná nebo nekonzistentní data.
* [x] Přidávání produktu/Editace produktu - filtrovat podkategorie v závislosti na kategorii do které patří (ajax pravděpodobně)
* [x] Při nemožnosti využití ajaxu pro nahrátí kategorií, kontrolovat, že produkt může existovat v této kategorii
* [x] Zabránit opakovanému post-backu
* [x] Při špatném post-backu vypsat data zpátky do formuláře, vyznačit políčko, které je špatně (zapracovat klientskou kontrolu, na některé pole
* [x] Prověřit náchylnost na XSS napříč webem
* [x] DO NOT DO: DISABLE button který nejde použít ke smazání subkategorie/dodavatele apod + zobrazit informaci o tom, proč ne
* [x] DO NOT DO: Zobrazovat v UI hlášku o tom, že bez javascriptu může administrace fungovat s ručením omezeným
* [x] Načítat do selektů správné hodnoty - při zadání špatných informací, ale i při editaci (postbacks, editation)
* [x] Přihlašování a odhlašování uživatelů přes uživatelské jméno a heslo. Automatické odhlášení po určité době nečinnosti. Nečinnost byla nastavena na 30 minut 
* [x] Tam, kde se kontroluje jestli je uživatel přihlášený zároveň kontrolovat platnost jeho přihlášení (časovou) + oprávnění (_Zakázat popkový přístup do administrace kam nemá nárok lozit_)

# Information systems - Project in PHP with MySQL Database

### Authors

* Daniel Dušek <dusekdan@gmail.com>
* Anna Popková <popkova.ann@gmail.com>


