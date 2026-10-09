<?php

declare(strict_types=1);

require dirname(__DIR__) . "/src/NhanAZ/NoFlyWorld/FlightPolicy.php";

use NhanAZ\NoFlyWorld\FlightPolicy;

$cases = [
	[[], "world", "world", false],
	[["pvp"], "pvp", "arena", true],
	[["pvp"], "Arena", "pvp", true],
	[["pvp"], "PVP", "arena", false],
	[["pvp"], "survival", "world", false],
	[["pvp", "boss"], "boss", "world", true],
];

foreach ($cases as [$worlds, $display, $folder, $expected]) {
	$actual = FlightPolicy::fromConfig($worlds)->isRestricted($display, $folder);
	if ($actual !== $expected) {
		throw new RuntimeException("Unexpected flight policy result for $display and $folder.");
	}
}

$invalidCases = [null, "pvp", ["name" => "pvp"], [""], [42]];
foreach ($invalidCases as $worlds) {
	try {
		FlightPolicy::fromConfig($worlds);
	} catch (InvalidArgumentException) {
		continue;
	}
	throw new RuntimeException("Invalid world list was accepted.");
}

echo "PASS: 11 flight policy cases\n";
