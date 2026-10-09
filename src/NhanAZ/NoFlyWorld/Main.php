<?php

declare(strict_types=1);

namespace NhanAZ\NoFlyWorld;

use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\permission\DefaultPermissions;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;

class Main extends PluginBase implements Listener {

	private FlightPolicy $policy;

	protected function onEnable(): void {
		$this->saveDefaultConfig();
		try {
			$this->policy = FlightPolicy::fromConfig($this->getConfig()->get("NoFlyWorld"));
		} catch (\InvalidArgumentException $e) {
			$this->getLogger()->error($e->getMessage());
			$this->getServer()->getPluginManager()->disablePlugin($this);
			return;
		}
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onEntityTeleport(EntityTeleportEvent $event): void {
		$entity = $event->getEntity();
		if ($entity instanceof Player) {
			$this->getScheduler()->scheduleDelayedTask(new ClosureTask(function() use ($entity): void {
				if ($entity->isConnected()) {
					$this->applyPolicy($entity);
				}
			}), 1);
		}
	}

	private function applyPolicy(Player $player): void {
		$world = $player->getWorld();
		$isAdmin = $player->hasPermission("noflyworld.bypass") || $player->hasPermission(DefaultPermissions::ROOT_OPERATOR) || $player->getGamemode()->equals(GameMode::CREATIVE());
		if (!$isAdmin && $this->policy->isRestricted($world->getDisplayName(), $world->getFolderName()) && ($player->isFlying() || $player->getAllowFlight())) {
			$player->setFlying(false);
			$player->setAllowFlight(false);
		}
	}
}
