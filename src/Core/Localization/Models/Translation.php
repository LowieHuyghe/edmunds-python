<?php

/**
 * Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */

namespace Core\Localization\Models;

use Core\Localization\Format\DateTime;
use Core\Bases\Models\BaseModel;
use Faker\Generator;
use Core\Validation\Validation;

/**
 * The model for translations
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @property string hash
 * @property string original
 * @property int $used
 * @property string aa		Afar
 * @property string ab		Abkhaz
 * @property string ae		Avestan
 * @property string af		Afrikaans
 * @property string ak		Akan
 * @property string am		Amharic
 * @property string an		Aragonese
 * @property string ar		Arabic
 * @property string as		Assamese
 * @property string av		Avaric
 * @property string ay		Aymara
 * @property string az		Azerbaijani
 * @property string ba		Bashkir
 * @property string be		Belarusian
 * @property string bg		Bulgarian
 * @property string bh		Bihari
 * @property string bi		Bislama
 * @property string bm		Bambara
 * @property string bn		Bengali, Bangla
 * @property string bo		Tibetan Standard, Tibetan, Central
 * @property string br		Breton
 * @property string bs		Bosnian
 * @property string ca		Catalan
 * @property string ce		Chechen
 * @property string ch		Chamorro
 * @property string co		Corsican
 * @property string cr		Cree
 * @property string cs		Czech
 * @property string cu		Old Church Slavonic, Church Slavonic, Old Bulgarian
 * @property string cv		Chuvash
 * @property string cy		Welsh
 * @property string da		Danish
 * @property string de		German
 * @property string dv		Divehi, Dhivehi, Maldivian
 * @property string dz		Dzongkha
 * @property string ee		Ewe
 * @property string el		Greek (modern)
 * @property string en		English
 * @property string eo		Esperanto
 * @property string es		Spanish
 * @property string et		Estonian
 * @property string eu		Basque
 * @property string fa		Persian (Farsi)
 * @property string ff		Fula, Fulah, Pulaar, Pular
 * @property string fi		Finnish
 * @property string fj		Fijian
 * @property string fo		Faroese
 * @property string fr		French
 * @property string fy		Western Frisian
 * @property string ga		Irish
 * @property string gd		Scottish Gaelic, Gaelic
 * @property string gl		Galician
 * @property string gn		Guaran�
 * @property string gu		Gujarati
 * @property string gv		Manx
 * @property string ha		Hausa
 * @property string he		Hebrew (modern)
 * @property string hi		Hindi
 * @property string ho		Hiri Motu
 * @property string hr		Croatian
 * @property string ht		Haitian, Haitian Creole
 * @property string hu		Hungarian
 * @property string hy		Armenian
 * @property string hz		Herero
 * @property string ia		Interlingua
 * @property string id		Indonesian
 * @property string ie		Interlingue
 * @property string ig		Igbo
 * @property string ii		Nuosu
 * @property string ik		Inupiaq
 * @property string io		Ido
 * @property string is		Icelandic
 * @property string it		Italian
 * @property string iu		Inuktitut
 * @property string ja		Japanese
 * @property string jv		Javanese
 * @property string ka		Georgian
 * @property string kg		Kongo
 * @property string ki		Kikuyu, Gikuyu
 * @property string kj		Kwanyama, Kuanyama
 * @property string kk		Kazakh
 * @property string kl		Kalaallisut, Greenlandic
 * @property string km		Khmer
 * @property string kn		Kannada
 * @property string ko		Korean
 * @property string kr		Kanuri
 * @property string ks		Kashmiri
 * @property string ku		Kurdish
 * @property string kv		Komi
 * @property string kw		Cornish
 * @property string ky		Kyrgyz
 * @property string la		Latin
 * @property string lb		Luxembourgish, Letzeburgesch
 * @property string lg		Ganda
 * @property string li		Limburgish, Limburgan, Limburger
 * @property string ln		Lingala
 * @property string lo		Lao
 * @property string lt		Lithuanian
 * @property string lu		Luba-Katanga
 * @property string lv		Latvian
 * @property string mg		Malagasy
 * @property string mh		Marshallese
 * @property string mi		M?ori
 * @property string mk		Macedonian
 * @property string ml		Malayalam
 * @property string mn		Mongolian
 * @property string mr		Marathi (Mar??h?)
 * @property string ms		Malay
 * @property string mt		Maltese
 * @property string my		Burmese
 * @property string na		Nauru
 * @property string nb		Norwegian Bokm�l
 * @property string nd		Northern Ndebele
 * @property string ne		Nepali
 * @property string ng		Ndonga
 * @property string nl		Dutch
 * @property string nn		Norwegian Nynorsk
 * @property string no		Norwegian
 * @property string nr		Southern Ndebele
 * @property string nv		Navajo, Navaho
 * @property string ny		Chichewa, Chewa, Nyanja
 * @property string oc		Occitan
 * @property string oj		Ojibwe, Ojibwa
 * @property string om		Oromo
 * @property string or		Oriya
 * @property string os		Ossetian, Ossetic
 * @property string pa		Panjabi, Punjabi
 * @property string pi		P?li
 * @property string pl		Polish
 * @property string ps		Pashto, Pushto
 * @property string pt		Portuguese
 * @property string qu		Quechua
 * @property string rm		Romansh
 * @property string rn		Kirundi
 * @property string ro		Romanian
 * @property string ru		Russian
 * @property string rw		Kinyarwanda
 * @property string sa		Sanskrit (Sa?sk?ta)
 * @property string sc		Sardinian
 * @property string sd		Sindhi
 * @property string se		Northern Sami
 * @property string sg		Sango
 * @property string si		Sinhala, Sinhalese
 * @property string sk		Slovak
 * @property string sl		Slovene
 * @property string sm		Samoan
 * @property string sn		Shona
 * @property string so		Somali
 * @property string sq		Albanian
 * @property string sr		Serbian
 * @property string ss		Swati
 * @property string st		Southern Sotho
 * @property string su		Sundanese
 * @property string sv		Swedish
 * @property string sw		Swahili
 * @property string ta		Tamil
 * @property string te		Telugu
 * @property string tg		Tajik
 * @property string th		Thai
 * @property string ti		Tigrinya
 * @property string tk		Turkmen
 * @property string tl		Tagalog
 * @property string tn		Tswana
 * @property string to		Tonga (Tonga Islands)
 * @property string tr		Turkish
 * @property string ts		Tsonga
 * @property string tt		Tatar
 * @property string tw		Twi
 * @property string ty		Tahitian
 * @property string ug		Uyghur
 * @property string uk		Ukrainian
 * @property string ur		Urdu
 * @property string uz		Uzbek
 * @property string ve		Venda
 * @property string vi		Vietnamese
 * @property string vo		Volap�k
 * @property string wa		Walloon
 * @property string wo		Wolof
 * @property string xh		Xhosa
 * @property string yi		Yiddish
 * @property string yo		Yoruba
 * @property string za		Zhuang, Chuang
 * @property string zh		Chinese
 * @property string zu		Zulu
 * @property DateTime $created_at
 * @property DateTime $updated_at
 */
class Translation extends BaseModel
{

    /**
     * The primary key for the model.
     * @var string
     */
    protected $primaryKey = 'hash';

	/**
	 * Add the validation of the model
	 */
	protected function addValidationRules(&$validator)
	{
		parent::addValidationRules($validator);

		$this->required = array_merge($this->required, array('hash', 'original', 'used'));

		$validator->value('hash')->max(32);
		$validator->value('original')->max(21800);
		$validator->value('used')->integer();

		$validator->value('aa')->max(21800);
		$validator->value('ab')->max(21800);
		$validator->value('ae')->max(21800);
		$validator->value('af')->max(21800);
		$validator->value('ak')->max(21800);
		$validator->value('am')->max(21800);
		$validator->value('an')->max(21800);
		$validator->value('ar')->max(21800);
		$validator->value('as')->max(21800);
		$validator->value('av')->max(21800);
		$validator->value('ay')->max(21800);
		$validator->value('az')->max(21800);
		$validator->value('ba')->max(21800);
		$validator->value('be')->max(21800);
		$validator->value('bg')->max(21800);
		$validator->value('bh')->max(21800);
		$validator->value('bi')->max(21800);
		$validator->value('bm')->max(21800);
		$validator->value('bn')->max(21800);
		$validator->value('bo')->max(21800);
		$validator->value('br')->max(21800);
		$validator->value('bs')->max(21800);
		$validator->value('ca')->max(21800);
		$validator->value('ce')->max(21800);
		$validator->value('ch')->max(21800);
		$validator->value('co')->max(21800);
		$validator->value('cr')->max(21800);
		$validator->value('cs')->max(21800);
		$validator->value('cu')->max(21800);
		$validator->value('cv')->max(21800);
		$validator->value('cy')->max(21800);
		$validator->value('da')->max(21800);
		$validator->value('de')->max(21800);
		$validator->value('dv')->max(21800);
		$validator->value('dz')->max(21800);
		$validator->value('ee')->max(21800);
		$validator->value('el')->max(21800);
		$validator->value('en')->max(21800);
		$validator->value('eo')->max(21800);
		$validator->value('es')->max(21800);
		$validator->value('et')->max(21800);
		$validator->value('eu')->max(21800);
		$validator->value('fa')->max(21800);
		$validator->value('ff')->max(21800);
		$validator->value('fi')->max(21800);
		$validator->value('fj')->max(21800);
		$validator->value('fo')->max(21800);
		$validator->value('fr')->max(21800);
		$validator->value('fy')->max(21800);
		$validator->value('ga')->max(21800);
		$validator->value('gd')->max(21800);
		$validator->value('gl')->max(21800);
		$validator->value('gn')->max(21800);
		$validator->value('gu')->max(21800);
		$validator->value('gv')->max(21800);
		$validator->value('ha')->max(21800);
		$validator->value('he')->max(21800);
		$validator->value('hi')->max(21800);
		$validator->value('ho')->max(21800);
		$validator->value('hr')->max(21800);
		$validator->value('ht')->max(21800);
		$validator->value('hu')->max(21800);
		$validator->value('hy')->max(21800);
		$validator->value('hz')->max(21800);
		$validator->value('ia')->max(21800);
		$validator->value('id')->max(21800);
		$validator->value('ie')->max(21800);
		$validator->value('ig')->max(21800);
		$validator->value('ii')->max(21800);
		$validator->value('ik')->max(21800);
		$validator->value('io')->max(21800);
		$validator->value('is')->max(21800);
		$validator->value('it')->max(21800);
		$validator->value('iu')->max(21800);
		$validator->value('ja')->max(21800);
		$validator->value('jv')->max(21800);
		$validator->value('ka')->max(21800);
		$validator->value('kg')->max(21800);
		$validator->value('ki')->max(21800);
		$validator->value('kj')->max(21800);
		$validator->value('kk')->max(21800);
		$validator->value('kl')->max(21800);
		$validator->value('km')->max(21800);
		$validator->value('kn')->max(21800);
		$validator->value('ko')->max(21800);
		$validator->value('kr')->max(21800);
		$validator->value('ks')->max(21800);
		$validator->value('ku')->max(21800);
		$validator->value('kv')->max(21800);
		$validator->value('kw')->max(21800);
		$validator->value('ky')->max(21800);
		$validator->value('la')->max(21800);
		$validator->value('lb')->max(21800);
		$validator->value('lg')->max(21800);
		$validator->value('li')->max(21800);
		$validator->value('ln')->max(21800);
		$validator->value('lo')->max(21800);
		$validator->value('lt')->max(21800);
		$validator->value('lu')->max(21800);
		$validator->value('lv')->max(21800);
		$validator->value('mg')->max(21800);
		$validator->value('mh')->max(21800);
		$validator->value('mi')->max(21800);
		$validator->value('mk')->max(21800);
		$validator->value('ml')->max(21800);
		$validator->value('mn')->max(21800);
		$validator->value('mr')->max(21800);
		$validator->value('ms')->max(21800);
		$validator->value('mt')->max(21800);
		$validator->value('my')->max(21800);
		$validator->value('na')->max(21800);
		$validator->value('nb')->max(21800);
		$validator->value('nd')->max(21800);
		$validator->value('ne')->max(21800);
		$validator->value('ng')->max(21800);
		$validator->value('nl')->max(21800);
		$validator->value('nn')->max(21800);
		$validator->value('no')->max(21800);
		$validator->value('nr')->max(21800);
		$validator->value('nv')->max(21800);
		$validator->value('ny')->max(21800);
		$validator->value('oc')->max(21800);
		$validator->value('oj')->max(21800);
		$validator->value('om')->max(21800);
		$validator->value('or')->max(21800);
		$validator->value('os')->max(21800);
		$validator->value('pa')->max(21800);
		$validator->value('pi')->max(21800);
		$validator->value('pl')->max(21800);
		$validator->value('ps')->max(21800);
		$validator->value('pt')->max(21800);
		$validator->value('qu')->max(21800);
		$validator->value('rm')->max(21800);
		$validator->value('rn')->max(21800);
		$validator->value('ro')->max(21800);
		$validator->value('ru')->max(21800);
		$validator->value('rw')->max(21800);
		$validator->value('sa')->max(21800);
		$validator->value('sc')->max(21800);
		$validator->value('sd')->max(21800);
		$validator->value('se')->max(21800);
		$validator->value('sg')->max(21800);
		$validator->value('si')->max(21800);
		$validator->value('sk')->max(21800);
		$validator->value('sl')->max(21800);
		$validator->value('sm')->max(21800);
		$validator->value('sn')->max(21800);
		$validator->value('so')->max(21800);
		$validator->value('sq')->max(21800);
		$validator->value('sr')->max(21800);
		$validator->value('ss')->max(21800);
		$validator->value('st')->max(21800);
		$validator->value('su')->max(21800);
		$validator->value('sv')->max(21800);
		$validator->value('sw')->max(21800);
		$validator->value('ta')->max(21800);
		$validator->value('te')->max(21800);
		$validator->value('tg')->max(21800);
		$validator->value('th')->max(21800);
		$validator->value('ti')->max(21800);
		$validator->value('tk')->max(21800);
		$validator->value('tl')->max(21800);
		$validator->value('tn')->max(21800);
		$validator->value('to')->max(21800);
		$validator->value('tr')->max(21800);
		$validator->value('ts')->max(21800);
		$validator->value('tt')->max(21800);
		$validator->value('tw')->max(21800);
		$validator->value('ty')->max(21800);
		$validator->value('ug')->max(21800);
		$validator->value('uk')->max(21800);
		$validator->value('ur')->max(21800);
		$validator->value('uz')->max(21800);
		$validator->value('ve')->max(21800);
		$validator->value('vi')->max(21800);
		$validator->value('vo')->max(21800);
		$validator->value('wa')->max(21800);
		$validator->value('wo')->max(21800);
		$validator->value('xh')->max(21800);
		$validator->value('yi')->max(21800);
		$validator->value('yo')->max(21800);
		$validator->value('za')->max(21800);
		$validator->value('zh')->max(21800);
		$validator->value('zu')->max(21800);
	}

	/**
	 * Define-function for the instance generator
	 * @param Generator $faker
	 * @return array
	 */
	protected static function factory($faker)
	{
		return array(
			'hash' => str_random(60),
			'original' => $faker->realText(100, 5),
			'used' => rand(1, 3),
			'en' => $faker->realText(100, 5),
			'nl' => $faker->realText(100, 5),
		);
	}

}
