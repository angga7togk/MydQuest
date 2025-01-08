<?php

namespace angga7togk\mydquest\quest\reward;

enum RewardType: string
{
  case COMMAND = "COMMAND";
  case ITEM = "ITEM";
  case XP = "XP";
  case XP_LEVEL = "XP_LEVEL";
  case MONEY = "MONEY";
}
