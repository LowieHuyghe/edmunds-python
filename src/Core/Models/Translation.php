<?php

/**
 * LH Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */

namespace LH\Core\Models;
use Faker\Generator;

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
 * @property string gn		Guaraní
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
 * @property string nb		Norwegian Bokmål
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
 * @property string vo		Volapük
 * @property string wa		Walloon
 * @property string wo		Wolof
 * @property string xh		Xhosa
 * @property string yi		Yiddish
 * @property string yo		Yoruba
 * @property string za		Zhuang, Chuang
 * @property string zh		Chinese
 * @property string zu		Zulu
 */
class Translation extends BaseModel
{
	/**
	 * Timestamps in the table
	 * @var bool|array
	 */
	public $timestamps = true;

	/**
	 * Add the validation of the model
	 * @param ValidationHelper $validator
	 */
	protected static function addValidationRules(&$validator)
	{
		$validator->required('hash');
		$validator->max('hash', 32);

		$validator->required('original');
		$validator->max('original', 21800);

		$validator->max('aa', 21800);
		$validator->max('ab', 21800);
		$validator->max('ae', 21800);
		$validator->max('af', 21800);
		$validator->max('ak', 21800);
		$validator->max('am', 21800);
		$validator->max('an', 21800);
		$validator->max('ar', 21800);
		$validator->max('as', 21800);
		$validator->max('av', 21800);
		$validator->max('ay', 21800);
		$validator->max('az', 21800);
		$validator->max('ba', 21800);
		$validator->max('be', 21800);
		$validator->max('bg', 21800);
		$validator->max('bh', 21800);
		$validator->max('bi', 21800);
		$validator->max('bm', 21800);
		$validator->max('bn', 21800);
		$validator->max('bo', 21800);
		$validator->max('br', 21800);
		$validator->max('bs', 21800);
		$validator->max('ca', 21800);
		$validator->max('ce', 21800);
		$validator->max('ch', 21800);
		$validator->max('co', 21800);
		$validator->max('cr', 21800);
		$validator->max('cs', 21800);
		$validator->max('cu', 21800);
		$validator->max('cv', 21800);
		$validator->max('cy', 21800);
		$validator->max('da', 21800);
		$validator->max('de', 21800);
		$validator->max('dv', 21800);
		$validator->max('dz', 21800);
		$validator->max('ee', 21800);
		$validator->max('el', 21800);
		$validator->max('en', 21800);
		$validator->max('eo', 21800);
		$validator->max('es', 21800);
		$validator->max('et', 21800);
		$validator->max('eu', 21800);
		$validator->max('fa', 21800);
		$validator->max('ff', 21800);
		$validator->max('fi', 21800);
		$validator->max('fj', 21800);
		$validator->max('fo', 21800);
		$validator->max('fr', 21800);
		$validator->max('fy', 21800);
		$validator->max('ga', 21800);
		$validator->max('gd', 21800);
		$validator->max('gl', 21800);
		$validator->max('gn', 21800);
		$validator->max('gu', 21800);
		$validator->max('gv', 21800);
		$validator->max('ha', 21800);
		$validator->max('he', 21800);
		$validator->max('hi', 21800);
		$validator->max('ho', 21800);
		$validator->max('hr', 21800);
		$validator->max('ht', 21800);
		$validator->max('hu', 21800);
		$validator->max('hy', 21800);
		$validator->max('hz', 21800);
		$validator->max('ia', 21800);
		$validator->max('id', 21800);
		$validator->max('ie', 21800);
		$validator->max('ig', 21800);
		$validator->max('ii', 21800);
		$validator->max('ik', 21800);
		$validator->max('io', 21800);
		$validator->max('is', 21800);
		$validator->max('it', 21800);
		$validator->max('iu', 21800);
		$validator->max('ja', 21800);
		$validator->max('jv', 21800);
		$validator->max('ka', 21800);
		$validator->max('kg', 21800);
		$validator->max('ki', 21800);
		$validator->max('kj', 21800);
		$validator->max('kk', 21800);
		$validator->max('kl', 21800);
		$validator->max('km', 21800);
		$validator->max('kn', 21800);
		$validator->max('ko', 21800);
		$validator->max('kr', 21800);
		$validator->max('ks', 21800);
		$validator->max('ku', 21800);
		$validator->max('kv', 21800);
		$validator->max('kw', 21800);
		$validator->max('ky', 21800);
		$validator->max('la', 21800);
		$validator->max('lb', 21800);
		$validator->max('lg', 21800);
		$validator->max('li', 21800);
		$validator->max('ln', 21800);
		$validator->max('lo', 21800);
		$validator->max('lt', 21800);
		$validator->max('lu', 21800);
		$validator->max('lv', 21800);
		$validator->max('mg', 21800);
		$validator->max('mh', 21800);
		$validator->max('mi', 21800);
		$validator->max('mk', 21800);
		$validator->max('ml', 21800);
		$validator->max('mn', 21800);
		$validator->max('mr', 21800);
		$validator->max('ms', 21800);
		$validator->max('mt', 21800);
		$validator->max('my', 21800);
		$validator->max('na', 21800);
		$validator->max('nb', 21800);
		$validator->max('nd', 21800);
		$validator->max('ne', 21800);
		$validator->max('ng', 21800);
		$validator->max('nl', 21800);
		$validator->max('nn', 21800);
		$validator->max('no', 21800);
		$validator->max('nr', 21800);
		$validator->max('nv', 21800);
		$validator->max('ny', 21800);
		$validator->max('oc', 21800);
		$validator->max('oj', 21800);
		$validator->max('om', 21800);
		$validator->max('or', 21800);
		$validator->max('os', 21800);
		$validator->max('pa', 21800);
		$validator->max('pi', 21800);
		$validator->max('pl', 21800);
		$validator->max('ps', 21800);
		$validator->max('pt', 21800);
		$validator->max('qu', 21800);
		$validator->max('rm', 21800);
		$validator->max('rn', 21800);
		$validator->max('ro', 21800);
		$validator->max('ru', 21800);
		$validator->max('rw', 21800);
		$validator->max('sa', 21800);
		$validator->max('sc', 21800);
		$validator->max('sd', 21800);
		$validator->max('se', 21800);
		$validator->max('sg', 21800);
		$validator->max('si', 21800);
		$validator->max('sk', 21800);
		$validator->max('sl', 21800);
		$validator->max('sm', 21800);
		$validator->max('sn', 21800);
		$validator->max('so', 21800);
		$validator->max('sq', 21800);
		$validator->max('sr', 21800);
		$validator->max('ss', 21800);
		$validator->max('st', 21800);
		$validator->max('su', 21800);
		$validator->max('sv', 21800);
		$validator->max('sw', 21800);
		$validator->max('ta', 21800);
		$validator->max('te', 21800);
		$validator->max('tg', 21800);
		$validator->max('th', 21800);
		$validator->max('ti', 21800);
		$validator->max('tk', 21800);
		$validator->max('tl', 21800);
		$validator->max('tn', 21800);
		$validator->max('to', 21800);
		$validator->max('tr', 21800);
		$validator->max('ts', 21800);
		$validator->max('tt', 21800);
		$validator->max('tw', 21800);
		$validator->max('ty', 21800);
		$validator->max('ug', 21800);
		$validator->max('uk', 21800);
		$validator->max('ur', 21800);
		$validator->max('uz', 21800);
		$validator->max('ve', 21800);
		$validator->max('vi', 21800);
		$validator->max('vo', 21800);
		$validator->max('wa', 21800);
		$validator->max('wo', 21800);
		$validator->max('xh', 21800);
		$validator->max('yi', 21800);
		$validator->max('yo', 21800);
		$validator->max('za', 21800);
		$validator->max('zh', 21800);
		$validator->max('zu', 21800);

		$validator->date('created_at');
		$validator->date('updated_at');
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
			'en' => $faker->realText(100, 5),
			'nl' => $faker->realText(100, 5),
		);
	}

}
