<?php

declare(strict_types=1);

namespace NhanAZ\NoFlyWorld;

final class FlightPolicy {

	/** @param list<string> $worlds */
	private function __construct(private readonly array $worlds) {}

	public static function fromConfig(mixed $worlds): self {
		if (!is_array($worlds) || !array_is_list($worlds)) {
			throw new \InvalidArgumentException("NoFlyWorld must be a list of world names.");
		}
		$validatedWorlds = [];
		foreach ($worlds as $world) {
			if (!is_string($world) || $world === "") {
				throw new \InvalidArgumentException("NoFlyWorld must contain nonempty world names.");
			}
			$validatedWorlds[] = $world;
		}
		return new self($validatedWorlds);
	}

	public function isRestricted(string $displayName, string $folderName): bool {
		return in_array($displayName, $this->worlds, true) || in_array($folderName, $this->worlds, true);
	}
}
