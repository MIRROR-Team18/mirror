<?php
// Making this a whole file is likely overkill, but... it's late, and I'm tired.

/*
 * object => name
 * cost => price of object
 * historical => is the cost inflated to today's money because it's so old?
 */

$allFacts = [
	0 => [
		'object' => "Eiffel Tower",
		'cost' => 1500000,
		'historical' => true,
	],
	1 => [
		'object' => "Empire State Building",
		'cost' => 40948900,
		'historical' => true,
	],
	2 => [
		'object' => "Wembley Stadium",
		'cost' => 798000000,
		'historical' => false,
	],
	3 => [
		'object' => "White House",
		'cost' => 4007000,
		'historical' => true,
	],
	4 => [
		'object' => "Icon Of The Seas Cruise Ship",
		'cost' => 1600000000,
		'historical' => false,
	],
	6 => [
		'object' => "Big Ben",
		'cost' => 1500000,
		'historical' => true,
	],
	7 => [
		'object' => "Large Hadron Collider",
		'cost' => 4750000000,
		'historical' => false,
	],
	8 => [
		'object' => "International Space Station",
		'cost' => 150000000000,
		'historical' => false,
	],
	9 => [
		'object' => "The Smiler Rollercoaster",
		'cost' => 18000000,
		'historical' => false,
	],
	10 => [
		'object' => "iPhone 15 Pro Max",
		'cost' => 1200,
		'historical' => false,
	],
	11 => [
		'object' => "Google Pixel 8 Pro",
		'cost' => 999,
		'historical' => false,
	],
	12 => [
		'object' => "typical cheap hairbrush",
		'cost' => 5,
		'historical' => false,
	],
	13 => [
		'object' => "Casio FX-991CW Scientific Calculator",
		'cost' => 36,
		'historical' => false,
	],
	14 => [
		'object' => "Amazon Echo (4th Gen)",
		'cost' => 110,
		'historical' => false,
	],
	15 => [
		'object' => "Amazon Echo (4th Gen) (during the Spring Sale)",
		'cost' => 55,
		'historical' => false,
	],
];

/**
 * Gets a random fact from the list.
 * @return array { object => string, cost => int, historical => bool
 */
function getRandomFact(): array {
	global $allFacts;
	return $allFacts[array_rand($allFacts)];
};