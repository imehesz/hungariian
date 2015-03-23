Ebben a rövidke leírásban azt szeretnénk bemutatni, hogy milyen egyszerű elkészíteni egy elegáns web-es alkalmazást a **Yii** segítségével. Ha a későbbiekben igény lesz rá, természetesen belevághatunk kényesebb témákba is, mint például a biztonság, cache-elés, komolyabb AR (ActiveRecord) használat stb. A helyesírásért már itt szeretnék elnézést kérni ...

# 0. Bevezetés #

Itt csak felsorolnék néhány alapvető (és igen fontos) fogalmat, amelyek ismerete szükséges :

- **Objektum Orientált Programozás (PHP 5)**: A jelenleg stabil Yii 1.1-es verzió a PHP 5.1-en alapszik, tehát annak ismerete mindenképp szükséges. ( http://hu.wikipedia.org/wiki/Objektumorientált_programozás )

- **Adatbázis (SQL)**: A Yii, alapból sokféle adatbázis formátumot támogat. (Az itteni példában én a SQLite-ot választottam, mert nem igényel különösebben bonyolult szerver oldali beállítást) ( http://hu.wikipedia.org/wiki/SQL )

- **MVC (Model-View-Controller) avagy Modell-Nézet-Vezérlő**: A Yii az egy MVC-t szorosan követő keretrendszer. Dióhéjban annyit jelent, hogy az alkalmazás jól elkülöníthető 3 részre. Az M vagy modell, ami az adatbázis jellegű lekérdezéseket végzi, a C vagy vezérlő ami kapcsolatot teremt és feldolgoz felhasználók által bevitt információt és végül a V vagy nézet, ami pedig magát a megjelenítést végzi. Ilyen többek között a Ruby On Rails rendszer is.( http://hu.wikipedia.org/wiki/MVC )

- **Active Record** - ami tulajdonképpen "megszűnteti" a függőséget a különböző adatbázisok között és az adatbázis táblákat objektumokként kezeli. Ez a gyakorlatban azt jelenti, hogy mondjuk fejlesztői környezetben használhatunk SQLite-ot élesben pedig MySQL-t vagy MsSQL-t stb. ( angol: http://en.wikipedia.org/wiki/Active_record_pattern )

Apache, Nginx és egyéb web szerverek beállításába nem szeretnénk itt belemenni. A programot a következő konfigurációval készítettük:

_- Ubuntu Linux (9.10)_

- Apache 2.x

- SQLite 3

- PHP 5.2.x

- Yii 1.1.x

- Csomagoljuk ki a letöltött Yii-t a web szerverünk fő könyvtárába (pl. /var/www/yii ), a forráskód majd szintén a /var/www alá kerül (pl: /var/www/yiiki).

Az is fontos, hogy a Yii rendszer a parancssorból is futtatható legyen! (_PHP CLI_)

# 1. Adatbázis Felépítése #

**Adatbázis neve:** yiiki

**Táblák:**
```
++++++++++++++++++++++++++
+ pages                  +
++++++++++++++++++++++++++
+ id INT (auto_increment)+
+ title VARCHAR(125)     +
+ body TEXT              +
+ revision INT           +
+ created INT            +
++++++++++++++++++++++++++
```

**SQLite3:** CREATE TABLE pages (body TEXT, created integer, id INTEGER PRIMARY KEY, revision integer, title varchar(125));

# 2. A WEB-es alkalmazás létrehozása #

Az alap alkalmazás létrehozása rendkívül egyszerű, csak adjuk ki a kovetkező utasítást (a web szerver _root_ könyvtárában állva, (pl /var/www).

`./yii/framework/yiic webapp yiiki`


```
imehesz@imehesz-laptop:/var/www$ ./yii/framework/yiic webapp yiiki
Create a Web application under '/var/www/yiiki'? [Yes|No] yes
      mkdir /var/www/yiiki
      mkdir /var/www/yiiki/themes
      mkdir /var/www/yiiki/themes/classic
      mkdir /var/www/yiiki/themes/classic/views
      mkdir /var/www/yiiki/themes/classic/views/layouts
      mkdir /var/www/yiiki/themes/classic/views/system
   generate themes/classic/views/.htaccess
      mkdir /var/www/yiiki/themes/classic/views/site
   generate index.php
      mkdir /var/www/yiiki/css
   generate css/bg.gif
   generate css/form.css
   generate css/screen.css
   generate css/main.css
   generate css/ie.css
   generate css/print.css
      mkdir /var/www/yiiki/assets
      mkdir /var/www/yiiki/images
      mkdir /var/www/yiiki/protected
      mkdir /var/www/yiiki/protected/extensions
   generate protected/yiic.bat
      mkdir /var/www/yiiki/protected/models
   generate protected/models/LoginForm.php
   generate protected/models/ContactForm.php
   generate protected/yiic.php
      mkdir /var/www/yiiki/protected/data
   generate protected/data/schema.sqlite.sql
   generate protected/data/schema.mysql.sql
   generate protected/data/testdrive.db
      mkdir /var/www/yiiki/protected/controllers
   generate protected/controllers/SiteController.php
   generate protected/yiic
      mkdir /var/www/yiiki/protected/config
   generate protected/config/main.php
   generate protected/config/test.php
   generate protected/config/console.php
      mkdir /var/www/yiiki/protected/commands
      mkdir /var/www/yiiki/protected/commands/shell
      mkdir /var/www/yiiki/protected/messages
   generate protected/.htaccess
      mkdir /var/www/yiiki/protected/views
      mkdir /var/www/yiiki/protected/views/layouts
   generate protected/views/layouts/main.php
      mkdir /var/www/yiiki/protected/views/site
   generate protected/views/site/login.php
      mkdir /var/www/yiiki/protected/views/site/pages
   generate protected/views/site/pages/about.php
   generate protected/views/site/index.php
   generate protected/views/site/contact.php
   generate protected/views/site/error.php
      mkdir /var/www/yiiki/protected/runtime
      mkdir /var/www/yiiki/protected/tests
   generate protected/tests/WebTestCase.php
   generate protected/tests/phpunit.xml
   generate protected/tests/bootstrap.php
      mkdir /var/www/yiiki/protected/tests/report
      mkdir /var/www/yiiki/protected/tests/functional
   generate protected/tests/functional/SiteTest.php
      mkdir /var/www/yiiki/protected/tests/fixtures
      mkdir /var/www/yiiki/protected/tests/unit
      mkdir /var/www/yiiki/protected/components
   generate protected/components/Controller.php
   generate protected/components/UserIdentity.php
   generate index-test.php

Your application has been created successfully under /var/www/yiiki.

```

Ha minden jól sikerült, akkor a _Your application has been created successfully under /var/www/yiiki_ - szerű üzenet jelenik meg, ami röviden annyit jelent, hogy az alap program sikeresen létrehozva a megadott mappa alatt.

Azt már itt érdemes megjegyezni, hogy a programunk már ebben a fázisban működőképes. Ha megtekintjük a URL-t egy böngészőben (pl http://yiiki.peldaprogram.local) akkor egy (remélhetőleg) üdvözlő képernyő fogad bennünket.

# 3. Az alkalmazás összekapcsolása az adatbázissal #

A Wiki-k többnyire nagyon egyszerű, monthatnánk butácska, programok, amik semmi mást nem csinálnak, csak oldalakat tárolnak és mutatnak meg. A problémát többféleképpen is meg lehetne oldani, még adatbázis használata nélkül is! De mi szeretnénk bemutatni, hogy mennyire egyszerű adatbázisokat kezelni Yii-vel.

A fő konfigurációs file a protected/config mappa alatt található és main.php a neve. Nyissuk is meg a kedvenc szövegszerkeztőnkkel. (mámrmint amelyiket programozásra használunk). Aki egyből a VIM-re gondolt, az kaphat egy piros pontot. Módosítsuk is az alábbiak szerint

(**Fontos**: az adatbázis file-t a _protected/data_ alá tettük)

```
protected/config/main.php

...
 'db'=>array(                                                                                                         
              'connectionString' => 'sqlite:protected/data/yiiki.sqlite',
         ),
...
```

És ennyi ...

# 4. A `yiic` avagy a Yii varázspálcája #

Természetesen lehetne a fejlesztést a _yiic_ teljes hanyagolásával folytatni, de  használata mindenképpen megkönnyíti a programozást. A `__ROOT__` könyvtárunkban egyszerűen adjuk ki a következő utasítást:

`./protected/yiic shell`

```
imehesz@imehesz-laptop:/var/www/yiiki$ ./protected/yiic shell
Yii Interactive Tool v1.1 (based on Yii v1.1.0)
Please type 'help' for help. Type 'exit' to quit.
>> _
```

Ha járatosak vagyunk valamelyik SQL parancssori programjával, akkor ez ismerős lehet. Segítséget a `help [utasitas]`-sal lehet kérni. Vágjunk is bele.

## 4.1 A `Pages` (oldalak) modell létrehozása a yiic segítségével ##

Egyszerűen adjuk ki a következő parancsot (még mindig a `shell`-ben vagyunk!)

```
>> model Page pages
   generate models/Page.php
   generate fixtures/pages.php
   generate unit/PageTest.php

The following model classes are successfully generated:
    Page

If you have a 'db' database connection, you can test these models now with:
    $model=Page::model()->find();
    print_r($model);

>> 
```

Nos, itt elég sok minden történt, szűrjük ki ami nekünk fontos lehet.

`model/Page.php` - ez nagyon fontos, ez lesz a modellünk, ami majd az adatbázisos lekérdezéseket végzi.

```
fixtures/pages.php
unit/PageTest.php
```
- sajnos ez utóbbi kettővel nem fogunk foglalkozni (legalábbis ebben a cikkben nem), annyit érdemes megjegyezni, hogy a TDD-hez nyújt segítséget. (Test Driven Development - talán _Tesztelésen Alapuló Fejlesztés_-nek lehetne nevezni - http://en.wikipedia.org/wiki/Test-driven_development )

A parancs lefutása után a program még arról is értesít minket, hogy ha be van állítva az adatbázis kapcsolatunk, ha akarjuk ki is próblhatjuk. Egyelőre tudjuk, hogy semmi nincs az adatbázisunkban, szóval nem sok értelme lenne a lekérdezésnek :)

# 5. Mi is az a CRUD? #

Azzal, hogy elészítettük a _Page_ modellünket, még semmi, böngészőben látható eredményt nem sikerült produkálnunk. No és itt jön a híres **CRUD** a képbe,

Create - Létrehoz

Read - (Be)olvas

Update - Frissít

Delete - Töröl

Ezeket az utasításokat pedig az adatbázisunk (és Pages táblánk) egy-egy rekordjához fogjuk használni.

## 5.1 A Vezérlő és Nézet létrehozása a CRUD paranccsal ##

Még mindig a `shell`-ben állva adjuk ki a következő parancsot:

```
>> crud Page
   generate PageController.php
   generate PageTest.php
      mkdir /var/www/yiiki/protected/views/page
   generate create.php
   generate update.php
   generate index.php
   generate view.php
   generate admin.php
   generate _form.php
   generate _view.php

Crud 'page' has been successfully created. You may access it via:
http://hostname/path/to/index.php?r=page

>> 
```

Aha, itt már lényegesen több minden történt mint a _modell_ létrehozásánál. Láthatjuk, hogy maga a _Controller_ (vezérlő) is elkészült, egy újabb teszt file, a `view/page` mappa is, ahol a rendszer fogja tárolni a szükséges nézet file-okat (create.php, update.php, _view.php stb)._

**Megj:** ( A vezérlő külön is elkészíthető a `controller page` paranccsal.

# 6. Gyors áttekintés #

A programunk jelenlegi állapotában mindenre kész. Tudunk oldalakat listázni, készíteni és törölni. Hogy megbizonyosodjunk róla, böngészőnkkel látogassuk meg a következő URL-t:

`http://yiiki.lenny.local/index.php?r=page`

Na de mi nem elégszünk meg ennyivel, hiszen egy igazi Wiki-t szeretnénk készíteni. Jöhet a testreszabás ...

# 7. Alkalmazásunk testreszabása #

## 7.1 A beviteli form módositasa ##

Ha most probálnánk egy _Oldalt_ készíteni, akkor azonnal észlelnénk, hogy a beviteli form-on egy csomó olyan mező van, amit nem szeretnénk kézzel minden egyes alkalommal kitölteni, illetve elvárnánk a programunktól, hogy saját maga töltse ki. Ilyen például a _created_ vagy a _revision_ mező.

http://yiiki.lenny.local/index.php?r=page/create

Módosítsuk is a form-unkat (`protected/views/page/_form.php`) az alábbiak szerint.

```
<div class="form">

<?php echo CHtml::beginForm(); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo CHtml::errorSummary($model); ?>

    <div class="row">
		<?php echo CHtml::activeLabelEx($model,'title'); ?>
		<?php echo CHtml::activeTextField($model,'title',array('size'=>60,'maxlength'=>125)); ?>
		<?php echo CHtml::error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'body'); ?>
		<?php echo CHtml::activeTextArea($model,'body',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo CHtml::error($model,'body'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php echo CHtml::endForm(); ?>

</div><!-- form -->
```

## 7.2 A Page Modell szabályainak beállítása ##
A _Page_ (Oldal) modellünkre is ráfér egy kis farigcsálás. A _yiic_ többnyire kitalálta, hogy milyen szabályokat hozzon létre a különböző mezőkhöz, a gondolatolvasástól azért még messze van. Mi ugyanis azt szeretnénk, ha az Oldalunk címébe csakis betűk, számok, vagy aláhúzás jel szerepelhessen. (Egy jó kis bővítés lenne, ha a programunk automatikusan felülírná a bevitt a címet, de az egyszerűség kedvéért itt egy szabályt hozunk létre)

protected/models/Page.php

```
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('created, revision', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>125),
                        array('title', 'required', 'message'=>'Cím nem lehet üres!'),
                        array('title', 'unique'),
                        array('title',
                                'match',
                                'pattern'=>'/^[A-Za-z0-9_]+$/',
                                'message' => 'Csak számokat, betűket és `_` jelet használhatsz! Hehe' ),
	                array('body', 'safe'),
		);
	}
```

Az itt használt szabályok elég egyértelműek, de ha valaki jobban bele szeretne mélyülni itt olvashat róluk bővebben: http://www.yiiframework.com/doc/cookbook/56/

Itt még azt is megfigyelhetjük, hogy hogyan kell a programunkat magyarul tanítani ;)

## 7.3 A _beforeSave()_ és _save()_ modell függvények használata/felülírása ##

A modelljeink, amik a programunkban az adatbázis lekérdezéseket kezelik, mindenféle függyvényekkel fel vannak fegyverkezve, hogy nekünk ne kelljen védőkesztyű nélkül SQL-kódban kotorászni. Az egyik ilyen függvény a _save()_ (elment) és hű csatlósa a _beforeSave()_ (mielőtt elment). A _save()_ egy adatbázis rekordot köteles elmenteni vagy felülírni.

A _pages_ adatbázis táblánkban van egy olyan mező, hogy _created_ (készítve), ami egy dátum mező. (a példánkban UNIX Timestamp-et használunk!) Ez a mező tartja nyilván, hogy az adott oldal mikor lett készítve/felülírva. Ugyan használhatnánk a _save()_ függvényt arra, hogy ezt az értéket beállítsuk, a példa kedvéért ezt a _beforeSave()_-vel oldjuk meg.

(Az fontos, hogy itt a szülő _beforeSave()_-jével térjünk vissza, `return parent::beforeSave()` )


protected/models/Page.php
```
      /**
       *
       */
      public function beforeSave()
      {
         // and setting the created date ...
         $this->created=time();

         return parent::beforeSave();
      }
```

A _save()_ függvénynél már kicsit más a helyzet. Először vegyük sorra, hogy mit is szeretnénk a programunktól, amikor elmentünk egy oldalt. Ha minden igaz, említettük az elején, hogy a jövőben szeretnénk egy **revert** opciót, ami annyit jelent, hogy az odalalainkat, ha akarjuk, visszaállíthatjuk egy korábbi változatba. Ezt többféle módon is meg lehet valósítani, de talán a legrövidebb megoldás, ha az oldalakat mindig újként mentjük el, és csak növeljük a revision azaz verziószámot.

protected/models/Page.php
```
     /**
      *
      * @return <type>
      */
     public function save( $validate = true )
     {
         if( $this->isNewRecord )
         {
             // we increase the revision number ...
             $this->revision = $this->revision+1;
             return parent::save( $validate );
         }
         else
         {
             // by setting `save` to false, it will skip the validation,
             // so we can save the page with the same title
             // also, update is not really an update because every single change
             // will be a "new" page, so we can keep history ...
             $newpage = new Page();
             $newpage->attributes = $this->attributes;
             $newpage->save(false);
             return true;
         }
     }
```

**Yii** olyan finomságokkal lát el bennünket, mint például a `$this->isNewRecord` ami egyszerűen megmondja nekünk, hogy most egy új rekordról van e szó, vagy csak felülírunk egyet. Mi persze tudjuk, hogy nekünk arra van szükségünk, hogy minden egyes oldal újként legyen elmentve, tehát ha az oldal nem új, akkor egyszerűen gyártunk egyet ami a `$newpage->attributes=$this->attributes` segítségével felveszi az eredeti oldal értékeit.

Még annyit érdemes megjegyezni, hogy ha a _save()_ függvényt _false_ paraméterrel hívjuk meg, akkor az AR szabályai nem vonatkoznak az adott rekordra.

## 7.4 Az oldalakat lekérő SQL módosítása és a hozzá tartozó View (Nézet) testreszabása ##

Ha most néznénk meg az Oldalak listáját, azt vennénk észre, hogy programunk, az egyes Oldalakhoz tartozó összes reviziót megmutatja. Ez ideáig rendben is van, hiszen minden egyes változatot "új"-ként mentettünk el, azonban ez elég zavaró lehet, meg bugyután is néz ki. Ha eddig még nem említettük volna, majd minden **Controller**-nek van egy úgynevezett _actionIndex()_-je ami az alapértelmezett függvény. Jelen esetünkben ez szolgáltatja az Oldalak listáját. Keressük is meg a _PageController_-ben és módosítsuk a következők szerint:

protected/controllers/PageController.php - actionIndex()

```
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
        $criteria = new CDbCriteria();
        $criteria->group = 'title';
        $criteria->order = 'created DESC';

		$dataProvider=new CActiveDataProvider('Page', array(
			'pagination'=>array(
				'pageSize'=>self::PAGE_SIZE,
			),
            'criteria' => $criteria,
		));

		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
```

SQL-ben valamennyire jártasabbak egyből kiszúrhatják, hogy mi is történik. Itt létrehozunk egy úgynevezett _CDBCriteria()_ osztályt aminek segítségével különböző SQL paramétereket állíthatunk be, és ez által módosíthatjuk a lekérdezéseinket, ami ebben az esetben az Oldalak csoportosítását jelenti a _title_ azaz cím szerint és rendezést a létrehozás dátuma szerint.

Az alábbi file-okat pedig módosítsuk izlésünk szerint (illetve az én izlésem szerint). Ha még emlékszünk, a **PageController**-t kicsit átalakítottuk, hogy az adott Oldal azonosító ID-ja helyett a _title_ mező értékével dolgozzon. Ez nagyon szép és jó, de a program többi részét is kicsit át kell alakítanunk. Ilyen például az _actionCreate()_ és _actionUpdate()_ funkciókban használt _redirect()_ ami azt a célt szolgálja, hogy bizonyos események után böngészőnket egy meghatározott oldalra küldje.

protected/controllers/PageController.php

```
	public function actionCreate()
	{
		$model=new Page;
		if(isset($_POST['Page']))
		{
			$model->attributes=$_POST['Page'];
			if($model->save())
				$this->redirect(array('view','title'=>$model->title));
                        // $this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
```


A következő file-ok pedig a megjelenítést szolgálják ...

protected/views/page/index.php

```
<div class="view">
	<?php echo CHtml::link( CHtml::encode($data->title), $this->createUrl('page/view', array( 'title' => $data->title ) ) ); ?>
	(Rev.: <?php echo CHtml::encode($data->revision); ?>)
	<i><?php echo date( 'Y-m-d H:i', CHtml::encode($data->created) ); ?></i>
</div>
```

protected/views/page/view.php

```
<?php
$this->breadcrumbs=array(
	'Pages'=>array('index'),
	$model->title,
);
?>
<h1><?php echo strtoupper($model->title); ?></h1>

<div>
	<?php echo CHtml::link('Oldalak Listaja',array('index')); ?>
	<?php echo CHtml::link('Oldal Frissitese',array('update','id'=>$model->id)); ?>
	<?php echo CHtml::linkButton('Oldal Torlese',array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Biztos, hogy toroljem?')); ?>
</div>

<div style="margin-top:20px;">
	<?php echo $model->body; ?>
</div>
```

Jajj de jó, már lassan készen is vagyunk. Mondjuk a megjelenített oldal egyáltalán nem úgy néz ki mint egy Wiki oldal. A következő fejezetben megnézzük, hogyan lehet külső modulokat behúzni a Yii rendszerbe, pl. egy Wiki értelmezőt, hogy megússzuk a sok _str\_replace()_-t ;)

# 8. Külső modulok, kiterjesztések használata #

Alkotásaink fejlesztése közben gyakran fordul elő olyan helyzet, hogy valaki (lehet, hogy mi magunk) valahol máshol (pl. egy másik keretrendszer alatt) már megoldotta a felmerülő problémát és szeretnénk a kódot újra használni. Erre _Yii_ barátunk többféle lehetőséget ad. Használhatunk, kifejezetten Yii-nek szánt _kiterjeszések_ -et (extensions: http://www.yiiframework.com/extensions/ ) vagy teljesen Yii-től független csomagokat. Ilyen például a _ZendFramework_ -be beépített _RSS_ kezelő rendszer. Nekünk jelenleg erre nincs szükségünk, de ha valakit bővebben érdekel, az itten járhat utána: http://www.yiiframework.com/doc/cookbook/20/ .

Már nagyon közel vagyunk ahhoz, hogy egy használható kis Wiki-nk legyen, már csak maga a Wiki "_nyelvértelmező_" hiányzik. Persze írhatnánk egyet magunktól, de inkább bizzunk benne, hogy valaki más már megírta - és láss csodát, így is van: http://www.ivan.fomichev.name/2010/02/php-creole-10-wiki-markup-parser.html (Ez a rendszer a _WikiCreole_ markup nyelvet használja: http://www.wikicreole.org/wiki/Creole1.0 ). Persze nem kötelező ezt használni, de az egyszerűség kedvéért mi ezt választottuk.

Mint bizonyára észrevettük a URL-ből, ennek a csomagnak az ég világon semmi köze nincs a Yii-hez, ez csak egy egyszerű PHP osztály, tehát nekünk a második megközelítés lesz a legjobb. Töltsük is le az összecsomagolt állományt és másoljuk a _creole.php_ -t be az _application/vendors/_ mappába (ha nincs ilyen mappánk, csináljunk egyet).

Attól még, hogy a file ott heverészik a _vendors_ könyvárba, még a Yii nem tudja, hogy mi ezt használni is szeretnénk. Itt jön a _Yii::import()_ statikus függvény a képbe.

```
Yii::import('application.vendors.*');
```

Ami kb. annyit jelent, hogy a továbbiakban Yii rendszerünk ebben a könyvtárban is keresgélni fog PHP osztályok és file-ok után. (Ezt egyebként beállíthatjuk a _protected/config/main.php_ file-ban is az _import_ tömb segítségével.)

Most már csak annyit kell tennünk, hogy a megfelelő **Nézet**-ben meghívjuk magát a Wiki értelmezőt és átpasszoljuk neki azt a szöveget amit HTML nyelven szeretnénk megmutatni a felhasználóknak. Ez a **Nézet** vagy **View** pedig itt található:
_protected/views/page/view.php_

```
<?php $wiki = new creole();echo $wiki -> parse( $model->body ); ?>
```

# Ja, még valami #

Már csak egy apróság van hátra. A rendszer, alapból, a _SiteController_ -nek adja át a vezérlést, ha valaki meglátogatja az oldalunkat. Ez szép és jó, de sajnos azon az oldalon csak annyi szerepel, "_Welcome to My Web Application_" és semmilyen link nem utal arra, hogy hol is vannak az oldalaink. Ezt a legegyszerűbben úgy lehet elkerülni, hogy megváltoztatjuk az alapértelmezett **Vezérlő**-t és a _SiteController_ helyett beállítjuk a _PageController_-t, hiszen az a felelős az Oldalak (Page) kezeléséért. Ja, és ha már úgyis a konfigurációs _protected/config/main.php_ file-ban kell kotorásznunk, akkor be is állíthatjuk a programunk nevét (ami több más helyen is feltűnik, pl. a böngésző _title_ -jében is)

```
  return array(
  ....
    'name'=>'Yiiki - Wikis rendszer Yii-vel',
    'defaultController' => 'Page',
  ...);
```


No, és készen is lennénk. Persze nagyon NAGYON sok mindenen lehetne javítani-szépíteni, pl. biztonság/adatbázis védelem, magyarosítás, egységtesztek (unit test) használata, caching-elés stb. de talán majd máskor.

Kezdetnek reméljük jó lesz. Sok sikert!

# Linkek #

A fő **Yii Framework** oldal: http://yiiframework.com

A **Yii Radiio** oldal, ami a legújabb Yii-vel kapcsolatos finomságokat foglalja össze: http://yiiradiio.mehesz.net

**Magyar fórum**: http://www.yiiframework.com/forum/index.php?/forum/33-hungarian/