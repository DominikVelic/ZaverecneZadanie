# ZaverecneZadanie

# Zadanie projektu

## 1. Všeobecné pokyny

### Zloženie tímu
- Projekt musí byť dokončený v tímoch so štyrmi členmi.
- Úlohy by mali byť rovnomerne rozdelené medzi členov tímu.

### Termín odoslania
- Projekt je potrebné podať prostredníctvom MS Teams do **20. mája 2024 do 23:55**.
- Neskoré odoslanie bude penalizované 2 bodmi za deň na člena tímu.

### Hosting projektu
- Dokončený projekt musí byť umiestnený na školskom serveri.

### Formát odoslania
- Odošlite ZIP archív obsahujúci vyplnenú úlohu a odkaz/adresu na stránku na školskom serveri.
- Uistite sa, že stránka je optimalizovaná pre prehliadače Chrome a Firefox.

### Odporúčania
- Inšpirujte sa internetovými zdrojmi, ale vyhnite sa plagiátorstvu. Viac ako 10 riadkov skopírovaného kódu bude mať za následok nula bodov a môže viesť k zlyhaniu kurzu.

### Obrana
- Ak člen tímu nevie odpovedať na otázky, ako implementoval časť projektu pri obhajobe, bude sa považovať za nesplnenú.

### Publikácia
- Dobre zrealizované projekty alebo ich časti môžu byť zverejnené.

## 2. Podrobnosti úlohy

### Cieľ
Vytvorte aplikáciu, ktorá implementuje online hlasovací systém použiteľný počas prednášok, pričom zohľadní grafický dizajn, jednoduchú navigáciu a celkovú bezpečnosť.

### Požiadavky
1. Použite systém správy verzií, ako je GitHub, GitLab alebo Bitbucket.
2. Stránka musí byť dvojjazyčná (slovenský a anglický). Prepínanie jazykov by vás malo udržať na rovnakej stránke bez toho, aby ste sa predvolene vrátili na domovskú stránku.
3. Uistite sa, že web je responzívny, vrátane všetkej grafiky.
4. Aplikácia musí podporovať tri roly: neregistrovaný používateľ, registrovaný používateľ a správca.
5. Poskytnite používateľskú príručku vysvetľujúcu možnosti každej roly, ktorá by mala byť dostupná aj na stiahnutie vo formáte PDF.
6. Celú funkcionalitu vytvorenej aplikácie zdokumentujte prostredníctvom videa.
7. Domovská stránka by mala umožniť používateľom prihlásiť sa a zadať kód na zobrazenie otázok s hlasovaním.

## 3. Funkcie používateľských rolí

### Neregistrovaný používateľ
- Prístup k hlasovaniu naskenovaním verejného QR kódu alebo zadaním verejného prístupového kódu.
- Pozrite si výsledky hlasovania ako grafické znázornenie alebo ako slovný oblak.

### Registrovaný používateľ
- Všetky možnosti neregistrovaného používateľa.
- Možnosť prihlásiť sa pomocou osobných prihlasovacích údajov.
- Posielajte hlasy o otázkach s aktívnym hlasovaním.
- Pozrite si podrobné výsledky minulých hlasovaní a aktuálnych hlasovaní.
- Možnosť zmeniť osobné heslo.
- Stiahnite si používateľské príručky a ďalšie zdroje.

### Administrátor
- Všetky možnosti registrovaného užívateľa.
- Vytvárajte, upravujte a vymažte otázky s hlasovaním.
- Aktivujte alebo deaktivujte hlasovacie otázky.
- Generujte jedinečné QR kódy a prístupové kódy pre hlasovacie otázky.
- Správa používateľských rolí a povolení.
- Prístup ku všetkým administratívnym funkciám vrátane podrobnej analýzy hlasov.
- Exportujte údaje o hlasovaní a výsledky na ďalšiu analýzu.

## 4. Ďalšie požiadavky

### Podanie MS tímov
Do odoslania do MS Teams zahrňte nasledovné:
- **Technická dokumentácia**:
   - Prihlasovacie meno a heslo pre prístup správcu aj používateľa do aplikácie a databázy.
   - Rozdelenie úloh medzi členov tímu.
   - Jasne označte všetky úlohy, ktoré neboli dokončené.
- **Aplikácia**:
   - Zabalené súbory vrátane konfiguračného súboru so všetkými definovanými nastaveniami.
   - SQL súbor na naplnenie databázy.
   - Súbor Dockerfile a Docker Compose.
- **Vytvorené video**:
   - Poskytnite video dokumentáciu aplikácie.

### Návrh hodnotenia
Úlohy | Body
--- | ---
Dvojjazyčná schopnosť | 4
Funkcia prihlasovania (2 roly) | 8
GUI a funkčnosť pre neregistrovaného užívateľa | 12
GUI a funkčnosť pre registrovaného užívateľa | 24
GUI a funkcionalita pre administrátora | 12
Export do CSV a PDF | 10
Balíček Docker | 16
Používanie správy verzií všetkými členmi tímu | 18
Vyplnenie záverečnej žiadosti | 218
Video dokumentácia | 81
Minimálne 3 záväzky na člena | 2
Grafická úprava, štruktúra, navigácia, schéma databázy, kompletnosť zadania projektu atď. | Variabilné

## Kontakt
V prípade akýchkoľvek otázok alebo dodatočných informácií kontaktujte vedúceho kurzu alebo asistentov, ktorí sú k dispozícii na konzultácie počas celého semestra.
