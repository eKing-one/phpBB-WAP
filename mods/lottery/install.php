<?php
/**
* @package phpBB-WAP
* @copyright (c) phpBB Group
* @Оптимизация под WAP: Гутник Игорь ( чел ).
* @简体中文：中文phpBB-WAP团队
* @license http://opensource.org/licenses/gpl-license.php
**/

/**
* 这是一款自由软件, 您可以在 Free Software Foundation 发布的
* GNU General Public License 的条款下重新发布或修改; 您可以
* 选择目前 version 2 这个版本（亦可以选择任何更新的版本，由
* 你喜欢）作为新的牌照.
**/
$sql='
CREATE TABLE phpbb_lottery (
  id int(10) unsigned NOT NULL auto_increment,
  user_id int(10) NOT NULL,
  PRIMARY KEY  (id),
  KEY user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE phpbb_lottery_history (
  id int(10) unsigned NOT NULL auto_increment,
  user_id int(10) NOT NULL,
  amount int(10) NOT NULL,
  currency char(32) NOT NULL,
  time int(10) NOT NULL,
  PRIMARY KEY  (id),
  KEY user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
// SQL 检测

run_query($sql);

$finish = true;


?>