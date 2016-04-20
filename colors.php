<?php

// @todo remove this file before production

$mens = array(
	'fae9a8',
	'000000',
	'890044',
	'315991',
	'f3e3be',
	'3d2517',
	'263b32',
	'eeb841',
	'b1b3b6',
	'474c53',
	'455865',
	'129a57',
	'd7ecf2',
	'b2b3ad',
	'898d6f',
	'ffd0db',
	'5e2635',
	'072161',
	'41472a',
	'f7efdb',
	'433574',
	'c6333f',
	'1e4097',
	'cfc6be',
	'6ad5f0',
	'16a3e0',
	'929b96',
	'ffffff'
);

$womens = array(
	'fae9a8',
	'000000',
	'8cedf9',
	'3d2517',
	'263b32',
	'eeb841',
	'b1b3b6',
	'474c53',
	'ea8ea7',
	'405162',
	'f3eed8',
	'129a57',
	'898d6f',
	'f7d9df',
	'5e2635',
	'072161',
	'3f326e',
	'c6333f',
	'1e4097',
	'890044',
	'6ad5f0',
	'16a3e0',
	'f7e45c',
	'929b96',
	'ffffff'
);

$final = array_intersect($mens, $womens);

foreach( $final as $color ) {
	echo '#' . $color;
	echo '<br />';
}