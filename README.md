# NoFlyWorld

NoFlyWorld disables a player's flight after teleporting into a configured Axolotl-PM world. It checks the player's actual world on the next server tick, so a canceled or redirected teleport does not apply the destination from an earlier event handler.

## Requirements and compatibility

- Axolotl-PM API 5.0.0, as declared in [`plugin.yml`](plugin.yml). PHPStan at maximum level passes against the 5.0.0 and 5.49.1 source revisions. Intermediate releases have not been tested.
- PHP 8.1 or newer, as required by those Axolotl-PM source revisions.
- No external plugin or runtime library dependency.

The exact release PHAR has been checked on Axolotl-PM 5.49.1 with a connected offline Bedrock protocol client. A canceled teleport left the player in the original world with flight enabled. A successful teleport into a configured world turned off both active flight and permission to fly. The test covers one player and one restricted world. Bypass behavior, other server revisions, and a real game client remain unverified.

## Installation

Download the PHAR from [GitHub Releases](https://github.com/NhanAZ-Plugins/NoFlyWorld/releases), put it in the server's `plugins` directory, and restart Axolotl-PM.

## Configuration and behavior

```yaml
NoFlyWorld:
  - pvp
  - boss
```

The default [`resources/config.yml`](resources/config.yml) contains an empty list, so no world is restricted until you add one. Entries can match a world's display name or folder name. Matching is case-sensitive. A malformed list disables the plugin at startup with an error, allowing you to correct the configuration before players teleport.

After a player teleports into a listed world, NoFlyWorld turns off active flight and permission to fly. Players with `noflyworld.bypass`, server operator permission, or creative game mode are exempt. The `noflyworld.bypass` permission defaults to operators. Teleport cancellation or redirection is handled by checking the player's actual world on the next tick. This plugin does not continuously police flight gained later, or flight at initial login without a teleport.

## Build and verification

The [build workflow](.github/workflows/build.yml) runs world matching tests and PHPStan at maximum level. It then uses [DevTools](https://github.com/NhanAZ/DevTools) to build and inspect a downloadable PHAR. The workflow pins DevTools and Axolotl-PM source to reviewed commits. Run local policy cases with `php tests/flight-policy.php`.

For a local build, use the [DevTools CLI guide](https://github.com/NhanAZ/DevTools/blob/main/docs/cli.md) with this repository as the project directory. A passing PHAR build or clean server startup alone does not prove the teleport behavior. The connected test result above is specific to the released PHAR and tested server revision.

## Support and license

Report bugs in [GitHub Issues](https://github.com/NhanAZ-Plugins/NoFlyWorld/issues) or contact [NhanAZ Discord](https://discord.gg/j2X83ujT6c). The existing [GPL-3.0 license](LICENSE) remains in force.
