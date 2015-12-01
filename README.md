# IISProject2015
=====

### Emering to-do's:
* Přidávání produktu/Editace produktu - filtrovat podkategorie v závislosti na kategorii do které patří (ajax pravděpodobně)
* Zobrazovat v UI hlášku o tom, že bez javascriptu může administrace fungovat s ručením omezeným
* Při nemožnosti využití ajaxu pro nahrátí kategorií, kontrolovat, že produkt může existovat v této kategorii
* Tam, kde se kontroluje jestli je uživatel přihlášený zároveň kontrolovat platnost jeho přihlášení
* Prověřit náchylnost na XSS napříč webem
* Zabránit opakovanému post-backu
* Při špatném post-backu vypsat data zpátky do formuláře, vyznačit políčko, které je špatně (zapracovat klientskou kontrolu, na některé pole
* Refactorovat (pře-třídit a okomentovat) nový kód v AddHelper.classe



ZADÁNÍ ZE ŠKOLY:

Rozsah implementace

Implementovaný systém by měl být prakticky použitelný pro účel daný zadáním. Mimo jiné to znamená:

Musí umožňovat vložení odpovídajících vstupů.
Musí poskytovat výstupy ve formě, která je v dané oblasti využitelná. Tedy nezobrazovat obsah tabulek databáze, ale prezentovat uložená data tak, aby byla pro danou roli uživatele a danou činnost užitečná (např. spojit data z více tabulek, je-li to vhodné, poskytnout odkazy na související data, apod).
Uživatelské rozhraní musí umožňovat snadno realizovat operace pro každou roli vyplývající z diagramu případů použití (use-case). Je-li cílem např. prodej zboží, musí systém implementovat odpovídající operaci, aby uživatel nemusel při každém prodeji ručně upravovat počty zboží na skladě, pamatovat si identifikátory položek a přepisovat je do objednávky a podobně.
Kromě vlastní funkcionality musí být implementovány následující funkce:

Správa uživatelů a jejich rolí (podle povahy aplikace, např. obchodník, zákazník, administrátor). Tím se rozumí přidávání nových uživatelů u jednotlivých rolí, stejně tak možnost editace a mazání nebo deaktivace účtů. Musí být k dispozici alespoň dvě různé role uživatelů.
Ošetření všech uživatelských vstupů tak, aby nebylo možno zadat nesmyslná nebo nekonzistentní data.
Povinná pole formulářů musí být odlišena od nepovinných.
Hodnoty ve formulářích, které nejsou pro fungování aplikace nezbytné, neoznačujte jako povinné (např. adresy, telefonní čísla apod.) Nenuťte uživatele (opravujícího) vyplňovat desítky zbytečných řádků.
Při odeslání formuláře s chybou by správně vyplněná pole měla zůstat zachována (uživatel by neměl být nucen vyplňovat vše znovu).
Pokud je vyžadován konkrétní formát vstupu (např. datum), měl by být u daného pole naznačen.
Pokud to v daném případě dává smysl, pole obsahující datum by měla být předvyplněna aktuálním datem.
Nemělo by být vyžadováno zapamatování a zadávání generovaných identifikátorů (cizích klíčů), jako např. ID položky na skladě. To je lépe nahradit výběrem ze seznamu. Výjimku tvoří případy, kdy se zadáním ID simuluje např. čtečka čipových karet v knihovně. V takovém případě prosím ušetřete opravujícímu práci nápovědou několika ID, která lze použít pro testování.
Žádné zadání nesmí způsobit nekonzistentní stav databáze (např. přiřazení objednávky neexistujícímu uživateli).
Přihlašování a odhlašování uživatelů přes uživatelské jméno a heslo. Automatické odhlášení po určité době nečinnosti.



DOPLNĚNÍ K IS ESHOP:
Pokyny k tématu Internetový obchod. Toto téma je ze značné části řešeno na přednáškách. Řešení z přednášek je v projektu možno využít. Aby však řešitelé tohoto tématu nebyli zvýhodněni vůči ostatním, bude v projektu požadováno následující rozšíření:
Zákazník bude mít možnost tisknout faktury k již ukončeným (dodaným) objednávkám. Faktura musí obsahovat alespoň údaje o dodavateli (internetovém obchodě), odběrateli, číslo objednávky a účtované položky.
Obsluha obchodu bude moci tisknout uskutečněné objednávky.
Systém bude evidovat dodavatele (externí společnost) pro každou nabízenou položku zboží. V obchodě může být nabízeno i zboží, které právě není skladem. Zákazník musí být informován o přibližné době dostupnosti. Tato doba bude v systému zadána pro každého dodavatele. Systém musí obsluze obchodu umožnit objednání zboží u dodavatele, pokud není na skladě, zaevidování zboží dodaného od dodavatele a vyřízení čekajících objednávek. Zákazník i obsluha musí mít možnost sledovat stav objednávky.




Information systems - Project in PHP with MySQL Database

*Within the project, we also developed wannabe framework which would simplify process of recreation of similar application. Both authors are well aware of disadvantages of
developing own framework in the World full of frameworks and reinventing the wheel as well as they are aware of educational benefits of such actions.*

### Authors

* Daniel Dušek <dusekdan@gmail.com>
* Anna Popková <popkova.ann@gmail.com>


