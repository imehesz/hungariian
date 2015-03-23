# Környezetbarát Egységtesztelés Meztelencsigákkal #

avagy, hogyan gyártsunk Egységtesztelős programot a **Yii**-vel.

## Mi ez? ##

Ha netán a címből nem lenne eléggé egyértelmű, a következőkben Egységteszetkről (Unit Test) lesz szó PHP-s környezetben. Ha a nyájas olvasó még nem olvasta a korábban megjelent [Yiiki al'a Yii](http://weblabor.hu/cikkek/wiki-a-la-yii) cikket, és semmilyen tapasztalata nincs a Yii keretrendszerrel, mindenképp érdemes rajta átfutni ugyanis itt nem fogunk belemenni az alapvető, _-Na most akkor, hogy is kezdjem?_ kérdésekbe.

## Mi lesz ez? ##

Egy nagyon egyszerű URL rövidítőt fogunk késziteni, mint pl a http://tinyurl.com vagy a http://vurl.me. Ha valaki nem ismerné, a lényeg az, hogy marha hosszú URL-eket lekicsinyítünk, és ha valahol egy felhasználó az általunk lekicsinyített linkre (slug) kattint, azt továbbítjuk a megfelelő oldalra.

## Bevezetés ##

### Környezet: ###

  * PHP 5.2.x
  * Apache (a PHP futtatásához)
  * SQLite (az egyszerűség kedvéért használjuk ezt, lehetne MySQL is)
  * Yii 1.1.3 (de elvileg működik az 1.1-es ág bármelyik csomagjával)
  * PHPUnit (tesztelést végrehajtó program: http://www.phpunit.de )

### Fogalmak: ###

**Test Driven Development (TDD)** - Tesztelésen alapuló fejlesztés.

Ezt talán úgy lehetne a legegyszerűbben bemutatni, hogy képzeljünk el egy adott problémát és próbáljuk meg visszafelé megoldani. Oké, lehet, hogy ez így elsőre hülyén hangzik, de talán ez az ábra segíthet.

![http://mehesz.net/downloads/ttd.png](http://mehesz.net/downloads/ttd.png)

  1. Írjunk tesztet
  1. Ellenőrizzük, hogy a tesztünk hibázik (**FAIL**)
  1. Írjuk meg (a legegyszerűbben!) a kívánt kódot úgy, hogy a tesztünk sikeres legyen.
  1. Ellenőrizzük, hogy a tesztünk sikeres e (**PASS**)
  1. Módosítsuk a kódunkat (úgy, hogy a teszt még mindig sikeres maradjon!)
  1. Kezdjük előlről az egészet.

**Unit Test** - Egységteszt.

Az egységek, komponensek tesztelése, hogy megbizonyosodjunk a működésének helyességéről. Cél, hogy feltárja nincsenek-e tévműködések, feltáratlan hibák a belső algoritmusban, adatkezelésben. A komponensek más rendszer komponensektől függetlenül vannak tesztelve. (http://www.dcs.vein.hu/)

Ez így elsőre, másodikra de talán még harmadikra is érdekesen hangozhat, de reméljük a cikk végén minden világos lesz.

(ja, itt ajánlanám [Erenon](http://weblabor.hu/cikkek/php-osztalyok-egysegtesztelese) írását.)

# Adatbázis #

Mint korábban említettük, SQLite3-at fogunk használni, de gyakorlatilag bármilyen adatbázis motorral is műküdik a dolog.

## Táblák ##
_protected/data/rovidke.db_
```
++++++++++++++++++++++++++
+ urls                   +
++++++++++++++++++++++++++
+ id INT (auto_increment)+
+ url TEXT               +
+ slug VARCHAR(50)       +
+ created INT            +
++++++++++++++++++++++++++
```

**SQLite3**
```
CREATE TABLE urls (created INTEGER, id INTEGER PRIMARY KEY AUTOINCREMENT, slug varchar(50), url TEXT);
```

Na, ennyi pepecselés után ugorjunk is bele a programozásba. Miután sikeresen létrehoztuk az alap alkalmazásunkat (pl: _yiic webapp rovidke_). Állítsuk is be egyből az adatbázis kapcsolatot.

_protected/config/main.php_
```
...
  'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/rovidke.db',
...
```

Itt szeretném megjegyezni, hogy többször is belefutottam a következő hibaüzenetbe (SQLite esetén, Linux-szal, lehet, hogy ez MS környezetben nem gond)

```
CDbCommand failed to execute the SQL statement: SQLSTATE[HY000]: General error: 8 attempt to write a readonly database
```

Ez annyit jelent, hogy az adatbázis file-t a program nem tudta írásra megnyitni, tehát át kell állítanunk a jogokat (ha csak a saját gépemen programozok, és nem foglalkozok a biztonsággal, egyszerűen 777-et adok neki :) )

# Itt a MVC #

## Modell ##

Első lépésként hozzuk is létre a **Url** modellünket. Ha valaki nagyon bátor, az használhatja az új csodafegyvert: [Gii](http://www.yiiframework.com/doc/guide/topics.gii), amit a _yiic_ utódjának szán a Yii fejlsztői csapat. Tulajdonképpen egy grafikus, bongészőbarát kódgeneráló eszköz. (a cikkben a _yiic_-et fogjuk használni)

```
./protected/yiic shell
Yii Interactive Tool v1.1 (based on Yii v1.1.3)
Please type 'help' for help. Type 'exit' to quit.
>> _
```

```
>> model Url urls
   generate models/Url.php
   generate fixtures/urls.php
   generate unit/UrlTest.php

The following model classes are successfully generated:
    Url

If you have a 'db' database connection, you can test these models now with:
    $model=Url::model()->find();
    print_r($model);
>>
```

Akiknek jobb a szeme, azok nyilván észrevették a teszt csomagot, amit a **Yii** alapból elkészít minden modellhez a _protected/tests/unit_ mappa alatt.
Gyakorlás képpen Futtassuk is le "tesztjeinket" (_protected/tests/_) ...
```
$phpunit unit
...
There was 1 error:

1) testCreate(UrlTest)
CDbException: The table "urls" for active record class "Url" cannot be found in the database.
...
```

Hoppá, hát az meg mi? Mi az, hogy nincs ilyen tábla az adatbázisban?

Azt fontos megjegyezni, hogy tesztelés közben nem egy, csak és kizárólag, a teszthez írt funkciót futtatunk, hanem az éles alkalmazás kódját. Ami azt jelenti, hogy a funkció az mindig ugyanaz marad és mindig ugyanúgy fut le. Hova is akarok ezzel kilyukadni? A lényeg, hogy amikor a teszteket futtatjuk, a program egy előre legyártott adat-tömbből (_fixtures_) veszi az adatokat, ezeket egy adatbázisba teszi, lefutattja a tesztet, és törli a DB táblákban tárolt bejegyzéseket. Tehát fontos, hogy **az éles adatbázis és a teszt adatbázis** különböző legyen!

Innen jött a hibaüzenet, ugyanis a teszt adatbázisunk még nem létezik. Csináljunk is egyet ...

_protected/data/rovidke\_test.db_
```
++++++++++++++++++++++++++
+ urls                   +
++++++++++++++++++++++++++
+ id INT (auto_increment)+
+ url TEXT               +
+ slug VARCHAR(50)       +
+ created INT            +
++++++++++++++++++++++++++
```

**SQLite3**
```
CREATE TABLE urls (created INTEGER, id INTEGER PRIMARY KEY AUTOINCREMENT, slug varchar(50), url TEXT);
```

... és módosítsuk a _teszt_(!) konfigurációs file-t az alábbiak szerint

_protected/config/test.php_
```
...
 'connectionString'=>'sqlite:' . dirname(__FILE__).'/../rovidke_test.db',
...
```

ha, most lefuttatjuk a tesztünket ...
_protected/tests/_
```
$phpunit unit
PHPUnit 3.3.16 by Sebastian Bergmann.

.

Time: 0 seconds

OK (1 test, 0 assertions)
```

minden OK! Birkabőr (_Juhéjj_)!

Kedvenc szövegszerkesztőnkkel nyissuk meg a _UrlTest_ a _protected/tests/unit/_ könyvtárból (aki [VIM](http://www.vim.org)-et hasznal +1 piros pontot kap). Ez elvileg üres, illetve, az osztályt a **Yii** már létrehozta, és talán akad is egy példa teszt. Ha van, ha nincs, a lényeg, hogy minden teszt funkciónak a _test_ cimkével kell kezdődnie. Nem történik semmi baj, ha nem kezdjük ezzel a tesztünket, de nem fog lefutni. A gyakorlás kedvéért csináljunk is két tesztet ...

_protected/tests/unit/UrlTest.php_
```
...
    public function testTrue()
    {
        $this->assertTrue( false );
    }

    public function testTrue2()
    {
        $this->assertTrue( true );
    }
...
```

Ez talán elég világos, de ha nem, akkor röviden annyi történik, hogy elsőben megvizsgáljuk, hogy az **IGAZ** az **HAMIS** e, a másodikban pedig, hogy az **IGAZ** az **IGAZ** e. Futtasuk is le ...

_protected/tests/_
```
$phpunit unit

PHPUnit 3.3.16 by Sebastian Bergmann.

F.

Time: 0 seconds

There was 1 failure:

1) testTrue(UrlTest)
Failed asserting that <boolean:false> is true.
/var/www/rovidke/protected/tests/unit/UrlTest.php:18

FAILURES!
Tests: 2, Assertions: 2, Failures: 1.

```

Jajj de remek, láthatjuk is, hogy egy darab **(F)** betűt kaptunk, ami a tévhitekkel ellentétben, az jelenti, hogy **FAIL** (ja, a pont **.** meg azt, hogy a teszt PASS(ED), azaz sikeresen lefutott.) Ha jobban megvizsgáljuk az üzenetet, akkor még az is kiderül, hogy _Failed asserting that_

&lt;boolean:false&gt;

 is true_, ami a Google fordító szerint annyit jelent, hogy_Elmulasztotta azt állítja, hogy hamis igaz_. Remélem lejön a lényeg :)_

Rengeteg ilyen "_a teszt azt állítja, hogy_" funkció létezik, ebben a példában mi csak néhányat fogunk bemutatni/használni. A teljes listáért [ide klikkoljatok](http://www.phpunit.de/manual/current/en/api.html#api.assert).

# Fixtures - Minta Adat #

Mint már korábban említettem, a "teszt csomag" egy úgynevezett adat-tömbből veszi a teszthez szükséges adatokat, most itt erről lesz szó (egy kicsit). Szerencsénkre **Yii** már létrehozta az alap könyvtárrendszert és a file-okat, sőt még néhány példa tömbböt is készített a _protected/tests/fixtures/urls.php_ file-ban. Pompás.

Módosítsuk is az értékeket, valahogy így:

```
return array(
	'sample1'=>array(
		'created' => '1234567890',
		'slug' => 'AA',
		'url' => 'http://weblabor.hu',
	),
	'sample2'=>array(
		'created' => '1234567890',
		'slug' => 'AB',
		'url' => 'http://google.com',
	),
);
```

Ugyan ezek az adat-tömbök elhanyagolhatóak, a gyakorlatban szinte mindig hasznaljuk őket.

# Megint Tesztelünk #

Nyissuk meg a **URL** teszt file-unkat és írjunk gyorsan egy számláló tesztet, ami megszámolja az összes linket a táblában:

_protected/tests/unit/UrlTest.php_
```
...
    public function testCountAll()
    {
        $urls_count = sizeof(Url::model()->findAll());
        $this->assertEquals( 2, $urls_count );
    }
...
```

A példa kedvéért itt az _assertEquals()_-t mutatom be, ami azt "_Feltételezi_", hogy a két megadott érték megegyezik. Ezzel lehet játszadozni, ha valami nem stimmel a tesztünk úgyis elbukik. PL:

_protected/tests_
```
$phpunit unit/
...
  Failed asserting that <integer:2> matches expected value <integer:1>.
...
```

Ugye milyen egyszerű? Az _assertEquals()_-ba nem csak szám értéket passzolhatunk ám, hanem akár szöveges változót is:

_protected/tests/unit/UrlTest.php_
```
...
    public function testActionView()
    {
        $url = Url::model()->findByAttributes( array( 'id' => 1 ) );

        $this->assertTrue( $url instanceof Url );
        $this->assertEquals( $url->url, 'http://altavizsla.hu' );
    }
...
```

ez nyilván elbubik, de ...:

_protected/tests/_
```
$phpunit unit
...
There was 1 failure:

expected string <http://weblabor.hu>
difference      <       xxxxxxxxxxx??>
got string      <http://altavizsla.hu>
...
```

Itt a **PHPUnit** ügyesen megmutatja nekünk, hogy pontosan hol is tértek el a szöveges változók. jajj, de jó :)

Na most nézzünk egy kicsit komolyabb tesztet. A **urls** táblába csak olyan rekordot szertnénk elmenteni, aminek mind a **url**, mind a **slug** értéke be van állítva, tehát nem lehet üres. Írjuk meg a tesztet:

_protected/tests/unit/UrlTest.php_
```
...
    public function testUrlRequired()
    {
        $this->assertTrue( Url::model()->isAttributeRequired( 'url' ) );
    }
...
```

Ha most lefuttatnánk a tesztünket, akkor nyilvánvalóan elbukna. Szerencsénkre a **Yii** modell osztálynak van egy ún. _rules()_ (szabályok) funkciója, amivel többek között azt is beállíthatjuk, hogy mely értékek nem lehetnek üresek. Ezt a _required_-del határozhatjuk meg.

_protected/models/Url.php_
```
...
  public function rules()
  {
    return array(
       ...
       array( 'url', 'required' ),
       ...
    );
  }
...
```

A tesztünk már majdnem sikeres ;) Hasonlóan az előző példához, most állítsuk be a **slug**-ot is ...

_protected/tests/unit/UrlTest.php_
```
...
    public function testSlugRequired()
    {
        $this->assertTrue( Url::model()->isAttributeRequired( 'slug' ) );
    }
...
```

TESZT: FAIL!

_protected/models/Url.php_
```
...
    return array(
       ...
       array( 'url,slug', 'required' ),
       ...
    );
...
```

Itt még azt is észrevehetjük, hogy milyen szépen lehet több értéket is megadni a **Yii** _rules()_-ban.

TESZT: PASS!

Most nézzünk egy olyan példát, ahol a tesztelésre szánt funkció még nem létezik. Például mi történik akkor, ha egy új URL-t (inkább slug-ot) szeretnénk készíteni. Ha most próbálnánk ki a programot, akkor kézzel kéne beállítanunk a lerövidített URL-t. Na ne má ... Tehát ha az adatbázisunk üres (nincs 1 darab URL sem) akkor az első slug legyen: **AA**. Na hogy is néz ez ki pontosan?

_protected/tests/unit/UrlTest.php_
```
...
    public function testCreateUrl()
    {
        $url = new Url();
        $url->setAttribute( 'url', 'http://altavizsla.hu' );
        $this->assertTrue( $url->save(), 'URL tarolas tesztelese' );
        $this->assertEquals( $url->slug, 'AA' );
    }
...
```

A tesztünk az feltételezi, hogy egy új URL esetén az új URL _slug_-ja az **AA** értékkel fog visszatérni. A teszt itt nyilván elbukik. (azt érdemes megjegyezni, hogy több _feltételezést_ is meg lehet adni 1 teszten belül).

Itt jön segítségünkre, a _beforeValidate()_ beépített függvény, ami arra szolgál, hogy még a mezők ellenőrzése előtt(!) futtassunk le valamit ...

_protected/models/Url.php_
```
    public function beforeValidate()
    {
        $this->slug = $this->createSlug();
        return parent::beforeValidate();
    }

    public function createSlug()
    {
       return 'AA'; 
    }
```

Azért nem tudtuk itt a _beforeSave()_-et használni, mert az ellenőrző szabályunk (rule) elbukna, ugyanis a _slug_ nem lehet üres. Most csak azt akarjuk elérni, hogy a tesztünk sikeresen lefusson, ugyanis a _createSlug()_ nem adhat mindig AA értéket, pl. ha több URL-t is szeretnénk tárolni az adatbázisunkban ;). Ha most lefuttatjuk a tesztünket akkor sikeresen lefut. Jajj, de jó. Na most módosítsuk a kódunkat úgy, hogy az tényleg azt csinálja amit akarunk ...

```
    public function createSlug()
    {
        $slug = Url::model()->find( array( 'order' => 'slug DESC' ) )->slug;

        if( ! $slug )
        {
            $slug = 'AA';
        }
        else
        {
            ++$slug;
        }

        return $slug; 
    }
```

Futtassuk a tesztet ...

_protected/tests/_
```
$phpunit unit/

...
Failed asserting that two strings are equal.
expected string <AC>
difference      < x>
got string      <AA>
...
```

Ez meg hogy lehet, az előbb még minden jó volt ;) Persze a fentiekből egyből kiderül, hogy a _teszt_ az **AA** értéket várta és **AC**-t kapott. Tehát módosítanunk kell a tesztünket. Itt jogosan merülhet fel a kérdés, hogy mi a ráknak ez a kerülőút? De ne feletjtsük el, tesztelünk.

_protected/tests/unit/UrlTest.php_
```
...
    public function testCreateUrl()
    {
        $url = new Url();
        $url->setAttribute( 'url', 'http://altavizsla.hu' );
        $this->assertTrue( $url->save(), 'URL tarolas tesztelese' );
        $this->assertEquals( $url->slug, 'AC' );
    }
...
```

Még itt gyorsan, mielőtt elfelejtem, a _createSlug()_-ot rövidíthetjük így is.

_protected/models/Url.php_
```
...
    public function createSlug()
    {
        $slug = Url::model()->find( array( 'order' => 'slug DESC' ) )->slug;
        return $slug ? ++$slug : 'AA';
    }
...
```

## Vezérlő és Nézet Cucc - CRUD - Létrehoz, Megjelenít, Frissít, Töröl ##

Az azért remélem feltűnt, hogy még egyszer sem ellenőriztük a programot a böngészőben. Mert nincs is rá szükség. Abban azonban biztosak lehetünk, hogy a **URL** modellre vonatkozó funkcióink majdnem 100%-osak. Azért nem mondom, hogy _majdnem_, mert több mindent lehetne, sőt, kellene(!) még tesztelnünk. Pl. mi van akkor, ha frissítünk, vagy törlünk egy linket az adatbázisból stb. Ezeket a fentiek alapján, remélem, kis gondolkodás után meg lehet csinálni. De még van néhány egyéb teendőnk is mielőtt a programunkat a felhasználók karmai közé eresztenénk.

Készítsük el az alap felhasználói _interfészt_ (formok, nézetek és egyebek) a _yiic_ segítségével.

_protected/yiic shell_
```
>> crud Url
   generate UrlController.php
   generate UrlTest.php
      mkdir /var/www/rovidke/protected/views/url
   generate create.php
   generate update.php
   generate index.php
   generate view.php
   generate admin.php
   generate _form.php
   generate _view.php
   generate _search.php

```

Ha egy új felhasználó érkezik az oldalra, azt szeretnénk, hogy minden klikkolgatás nélkül készíthessen egy rövidített linket (monnyuk ez nem csak **új** felhasználókra vonatkozik). Módosítsuk az alap kontrollerünket:

_protected/config/main.php_
```
...
    'defaultController'    => 'url/create',
...
```

Amikor a _Yii_ a hozzáférési szabályokat készíti, alapból a _létrehozás_ funkció (create) csak belépés után lehetséges. Ami jelen esetben nem elfogadható, hiszen mi azt szeretnénk, hogy a felhasználóink a lehető leggyorsabban létrehozhassanak rövidített linkeket. Módosítsuk tehát a

_protected/controllers/UrlController.php
```
...
   public function accessRules()
... 
   array('allow',  // allow all users to perform 'index' and 'view' actions
	'actions'=>array('index','view', 'create'),
	'users'=>array('*'),
   ),
   array('allow', // allow authenticated user to perform 'create' and 'update' actions
	'actions'=>array('update'),
	'users'=>array('@'),
   ),
...
```_

Ha most megnéznénk a kis programunkat, akkor mindenféle egyéb form mezőket látnánk, amit az okos (már tesztelt) **Modell** funkcióink szépen kitöltenek a URL mentése előtt. (pl. dátum mező, a rövidített URL azaz _slug_ mező). Tulajdonképpen csak egyetlen mezőre van szükségünk, a URL-re:

_protected/views/url/_form.php_```
...
    <?php /*
	<div class="row">
		<?php echo $form->labelEx($model,'created'); ?>
		<?php echo $form->textField($model,'created'); ?>
		<?php echo $form->error($model,'created'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'slug'); ?>
		<?php echo $form->textField($model,'slug',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'slug'); ?>
	</div>
    */ 
    ?>

	<div class="row">
		<?php echo $form->labelEx($model,'url'); ?>
		<?php echo $form->textField($model,'url', array( 'size' => 50 )); ?>
		<?php echo $form->error($model,'url'); ?>
	</div>
...
```_

Miután programunk elmenti az új _Url_-t, **Yii** automatikusan betölti a megjelenítő kódot (_viewAction()_). Mi azt szeretnénk, ha a felhasználónk valami ilyesmit látnának: http://rovidke.hu/QWD, és erre klikkelve (vagy a böngészőbe másolva) jutnának el az igazi _URL_-hez.

Először módosítsuk a megjenítő nézetet:

_protected/views/url/view.php_
```
...
<?php 
    echo 
        CHtml::link( 
            $_SERVER['SERVER_NAME'] . '/' . $model->slug, 
            $this->createUrl( '/' . $model->slug ) 
        );
?>
...
<?php 
/*
$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'created',
		'id',
		'slug',
		'updated',
		'url',
	),
)); 
*/
?>
```

No, és itt jön az alkalmazás egy másik érdekessége, az _átirányítás_ vagy _redirect_. Alapértelmezésben a rendszer a kapott URL alapján megpróbál egy Vezérlőt (Controller) keresni és átpasszolni neki a kapott változókat. Nekünk viszont az kéne, hogy ha valami ilyesmit kapunk (/QWD) akkor a program _ne_ keresse a QWD-t (nem találna!) hanem hívja meg a **Url** vezérlőt és adja át _QWD_-t mint egy változó értéket. (ugyebár a _slug_ azonosítója.) Ezt az alap konfigurációs file-ban tehetjük meg, a következő képpen (a szabályok sorrendje fontos):

_protected/config/main.php_
```
...
        'urlManager'=>array(
             'urlFormat'=>'path',
             'showScriptName' => false,
             'rules'=>array(
                 '/<slug:[A-Z]+>' => 'url/redirect',
                 '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                 '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                 '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
             ),
         ),
...
```

Persze mindez nem jöhetne létre ha nics _apache_ (.htaccess). Ha az alkalmazás ROOT könyvtárában valami oknál fogva nem lenne meg ez a file, akkor az alábbiak alapján kíszítsünk egyet.

_.htaccess_
```
Options +FollowSymLinks
IndexIgnore */*
RewriteEngine on

#Uncomment "RewriteBase /" when you upload this .htaccess to your web server, and comment it when on local web server

#NOTE: 

#If your application is in a folder, for example "application". Then, changing the "application" folder name, will require you to reset the RewriteBase /[your app folder]

#RewriteBase /[your app folder - optional]

# if a directory or a file exists, use it directly
RewriteCond %{REQUEST_FILENAME} -s [OR]
RewriteCond %{REQUEST_FILENAME} -l [OR]
RewriteCond %{REQUEST_FILENAME} -d

# otherwise forward it to index.php 
RewriteRule ^.*$ - [NC,L]
RewriteRule ^.*$ index.php [NC,L]
```

Na, most már tényleg csak egy dolog van hátra, megírni magát az átirányító funkciót _actionRedirect()_

_protected/controllers/UrlController.php_
```
...
    public function accessRules()
    {
       ...
          array('allow',  // allow all users to perform 'index' and 'view' actions
		  'actions'=>array('index','view','create','redirect'),
		  'users'=>array('*'),
		),    
       ...
    }
...
    public function actionRedirect()
    {
        $slug = Yii::app()->request->getParam( 'slug', null );
        if( $slug )
        {
            $url = Url::model()->findByAttributes( array( 'slug' => $slug ) );
            if( $url )
            {
                $this->redirect( Url::model()->findByAttributes( array( 'slug' => $slug ) )->url );
            }
        }

        throw new CHttpException( '404', 'hmmm ... slug not found!' );
    }
...
```

Még nagyon sok mindent lehetne finomítani a programunkon, de szerintem kezdetnek ez is elég. Azt pedig nagyon remélem, hogy a **PHP** közösség (végre) felébred és használni fogja a _Teszt Alapú_ (TDD) programozást.

A **Yii** legyen veletek.

![http://mehesz.net/downloads/shroomnail/shroomandail.png](http://mehesz.net/downloads/shroomnail/shroomandail.png)

_"Ismeret az nem cselekedet, csakis cselekedet a cselekedet"_ - tdc10