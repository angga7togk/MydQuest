<p align="right">
  <img src="https://raw.githubusercontent.com/angga7togk/PowerEssentials/refs/heads/main/img/indonesia.png" height="23px">
</p>

<p align="center">
  <a href="https://github.com/angga7togk/MydQuest">
    <img src="https://raw.githubusercontent.com/angga7togk/MydQuest/refs/heads/master/quest.png" width="25%">
  </a>
</p>

<h1 align="center">RPG MydQuest (BETA)</h1>
<p align="center"><strong>The Ultimate RPG Daily Quest Plugin for PocketMine-MP – Enhance Gameplay, Boost Player Engagement, and Create Immersive Adventures.</strong></p>

---

<br>

**User-friendly, highly customizable RPG quest plugin for PocketMine-MP servers.**

- 🛠️ **Fully Customizable Quests**: Add, edit, disable, or delete quests easily via JSON files or directly through the plugin.
- 🌍 **Multi-language Support**: Provide quests in multiple languages for a global player base.
- 🎯 **Action/Event Listeners**: Supports events like BREAK, PLACE, CONSUME, ENCHANT, JUMP, DEAD, and more for quest customization.
- 💾 **Flexible Data Storage**: Compatible with MySQL and SQLite for quest and player data.
- 📆 **Daily, Weekly, Monthly Quests**: Configure quests on a schedule that fits your server’s needs.
- 🎁 **Customizable Rewards**: Set unique rewards and restrict worlds during quest activities.
- 🖼️ **Custom GUI**: Fully customizable menus via JSON files for seamless integration.
- ⏱️ **Time Zone Customization**: Automatically reset player quest progress based on their local time zone.
- 🏆 **Leaderboard Integration**: Display rankings via GUI and floating text for competitive fun.
- 🛡️ **World Protection Support**: Compatible with land and world protection plugins to prevent exploits.
- 🔔 **Quest Notifications**: Display quest completion messages in the action bar or title.
- 🧩 **Developer API**: Expand functionality with a robust API for developers.
- 🚀 **And More**: Discover countless ways to enhance your server with this plugin.

## 🔨 **Action JSON Quest Types**

Take your quests to the next level with these flexible and fully customizable actions. Each action can include conditional settings for added complexity—for example, triggering a **PLAYER_DEATH** quest only when the player is underwater.

| **Type**            | **Description**                                                                     | **Status**     |
| ------------------- | ----------------------------------------------------------------------------------- | -------------- |
| 🛠️ **BREAK**        | Breaking specific blocks.                                                           | ✅ Done |
| 🧱 **PLACE**        | Placing blocks.                                                                     | ⏳ In Progress |
| ⚔️ **KILL**         | Kill an entity or player.                                                           | ⏳ In Progress |
| 🍳 **COOK**         | Cooking items in a furnace or smoker.                                               | ⏳ In Progress |
| ✨ **ENCHANT**      | Enchant items at an enchantment table.                                              | ⏳ In Progress |
| 🥤 **CONSUME**      | Consume potions, food, or milk buckets.                                             | ⏳ In Progress |
| 🛒 **PICKUP**       | Picking up dropped items.                                                           | ⏳ In Progress |
| 🛠️ **CRAFT**        | Crafting items at a crafting table.                                                 | ⏳ In Progress |
| 🗺️ **LOCATION**     | Travel to specific coordinates.                                                     | ⏳ In Progress |
| 🌱 **FARMING**      | Harvest crops when fully grown.                                                     | ⏳ In Progress |
| 💀 **PLAYER_DEATH** | Player death with customizable conditions (e.g., underwater, specific biome, etc.). | ⏳ In Progress |

### ✨ **Advanced Features**

- **Conditional Triggers**: Add conditions to actions for deeper gameplay. For example:
  - **BREAK**: Restrict to specific tools or block types.
  - **KILL**: Define conditions like "kill only in a specific biome" or "kill using a bow."
  - **PLAYER_DEATH**: Trigger quests only when dying under certain conditions, like in water, in lava, or while poisoned.
  - **LOCATION**: Require visiting coordinates during specific times or while carrying specific items.

With these detailed actions and conditions, you have full control over creating complex and engaging quests that suit your server's unique RPG experience!

## 🎁 **Reward Types**

Create diverse and exciting rewards for your quests using these fully customizable reward types. Mix and match to enhance player satisfaction and engagement.

| **Type**        | **Description**                                          |
| --------------- | -------------------------------------------------------- |
| 🖥️ **COMMAND**  | Execute server commands upon quest completion.           |
| 🎒 **ITEM**     | Reward players with specific in-game items.              |
| 💰 **MONEY**    | Grant virtual currency for use in your server's economy. |
| ⭐ **XP**       | Reward experience points for character progression.      |
| ⭐ **XP_LEVEL** | Reward level points for character progression.           |

## 🚀 **Plugin Supports**

Enhance compatibility and functionality by integrating with these supported plugins:

- 🪄 **PiggyCustomEnchantments**: Add custom enchantments to item rewards.
- 💰 **EconomyAPI**: Provide money rewards for completed quests.
- 🏦 **BedrockEconomy**: Support for offering money rewards.
- 🏘️ **EconomyLand**: Prevent exploitation and validate land ownership.
- 🛡️ **WorldGuard**: Ensure quests respect world protection rules.
- 💠 **ScoreHud**: ScoreHud support.

## 📂 **Installation**

1. Download the latest release from the [releases page](https://github.com/angga7togk/MydQuest).
2. Place the `MydQuest.phar` file into your server's `plugins` folder.
3. Restart your server.

## 🛠️ **Contribution**

We welcome contributions! Feel free to open issues or submit pull requests to help improve MydQuest.

## 📜 **Credits**

- Icon by [Flaticon](https://www.flaticon.com/)
- Language Translation [Texter](https://github.com/fuyutsuki/Texter/blob/122f9b45a4896c51eb5b7f4fc0aa479ea0df56a7/src/jp/mcbe/fuyutsuki/Texter/i18n/TexterLang.php)
