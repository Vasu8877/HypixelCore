<?php

namespace Biswajit\Core\Menus;

use pocketmine\Server;
use jojoe77777\FormAPI\Form;
use dktapps\pmforms\FormIcon;
use dktapps\pmforms\MenuForm;
use pocketmine\player\Player;
use dktapps\pmforms\MenuOption;
use jojoe77777\FormAPI\SimpleForm;

class Recipes extends MenuForm
{
    public function __construct()
    {
        parent::__construct("§6RECIPES BOOK", "§bUse Only Custom Crafting Table To Craft Things,\n\n§bDo /invcraft", [
            new MenuOption("§eMINION RECIPES", new FormIcon("https://cdn-icons-png.flaticon.com/128/891/891978.png", FormIcon::IMAGE_TYPE_URL)),
            new MenuOption("§eARMOR RECIPES", new FormIcon("https://cdn-icons-png.flaticon.com/128/361/361761.png", FormIcon::IMAGE_TYPE_URL)),
            new MenuOption("§eSWORD RECIPES", new FormIcon("https://i.imgur.com/EEaIm1N.png", FormIcon::IMAGE_TYPE_URL)),
            new MenuOption("§eAXE RECIPES", new FormIcon("https://i.imgur.com/PcovRG3.png", FormIcon::IMAGE_TYPE_URL)),
            new MenuOption("§ePICKAXE RECIPES", new FormIcon("https://i.imgur.com/Ao5AHLx.png", FormIcon::IMAGE_TYPE_URL)),
            new MenuOption("§eRUNES RECIPES", new FormIcon("https://i.imgur.com/PEqPR8j.png", FormIcon::IMAGE_TYPE_URL)),
            new MenuOption("§eITEMS RECIPES", new FormIcon("https://cdn-icons-png.flaticon.com/128/487/487551.png", FormIcon::IMAGE_TYPE_URL)),
            new MenuOption("§eENCHANTED ITEMS", new FormIcon("https://cdn-icons-png.flaticon.com/128/3556/3556661.png", FormIcon::IMAGE_TYPE_URL)),
            new MenuOption("§eFOOD RECIPES", new FormIcon("https://cdn-icons-png.flaticon.com/128/2921/2921822.png", FormIcon::IMAGE_TYPE_URL)),
            new MenuOption("§eHOE RECIPES", new FormIcon("https://cdn-icons-png.flaticon.com/128/521/521021.png", FormIcon::IMAGE_TYPE_URL)),
            new MenuOption("§eTALISMAN", new FormIcon("https://cdn-icons-png.flaticon.com/128/1625/1625674.png", FormIcon::IMAGE_TYPE_URL)),
            new MenuOption("§eORE GENERATOR", new FormIcon("https://cdn-icons-png.flaticon.com/128/4831/4831062.png", FormIcon::IMAGE_TYPE_URL)),
            new MenuOption("§eCUSTOM CRAFTING TABLE", new FormIcon("textures/blocks/crafting_table_top", FormIcon::IMAGE_TYPE_PATH))
        ], function (Player $sender, int $selected): void {
            switch ($selected) {
                case 0:
                    $this->minions($sender);
                    break;

                case 1:
                     $sender->sendTitle("§r§l§eCOMMING SOON");
                    break;

                case 2:
                    $this->sword($sender);
                    break;

                case 3:
                    $sender->sendTitle("§r§l§eCOMMING SOON");
                    break;

                case 4:
                    $this->pickaxe($sender);
                    break;

                case 5:
                    $this->runes($sender);
                    break;

                case 6:
                    $this->items($sender);
                    break;

                case 7:
                    $this->eblocks($sender);
                    break;

                case 8:
                    $sender->sendTitle("§r§l§eCOMMING SOON");
                    break;

                case 9:
                    $this->hoe($sender);
                    break;

                case 10:
                    $sender->sendTitle("§r§l§eCOMMING SOON");
                    break;

                case 11:
                    $this->ore($sender);
                    break;

                case 12:
                    Server::getInstance()->dispatchCommand($sender, "invcraft");
                    break;
            }
        });
    }

    public function tools(Player $sender): Form
    {
        $form = new SimpleForm(function (Player $sender, int $data = null) {
            if ($data === null) {
                return;
            }
            switch ($data) {
                case 0:
                    $this->sword($sender);
                    break;

                case 1:
                    $sender->sendTitle("§r§l§eCOMMING SOON");
                    break;

                case 2:
                    $this->pickaxe($sender);
                    break;

                case 3:
                    $this->items($sender);
                    break;

                case 4:
                    $this->hoe($sender);
                    break;

                case 5:
                    $sender->sendTitle("§r§l§eCOMMING SOON");
                    break;

                case 6:
                    $sender->sendForm(new RecipesForm());
                    break;
            }
        });
        $form->setTitle("§l§6CUSTOM ITEMS RECIPES");
        $form->setContent("§dSelect The Which Tool Recipe You Want:");
        $form->addButton("§l§bSWORDS\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/2466/2466942.png");
        $form->addButton("§l§bAXE\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/6769/6769130.png");
        $form->addButton("§l§bPICKAXE\n§9»» §r§6Tap To View", 1, "https://i.imgur.com/l4cLq8v.png");
        $form->addButton("§l§bITEMS\n§9»» §r§6Tap To View", 1, "https://i.imgur.com/c4BNzS7.png");
        $form->addButton("§l§bHOE\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/521/521021.png");
        $form->addButton("§l§bWANDS\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/3204/3204021.png");
        $form->addButton("§l§aBACK\n§9»» §r§bTap To Go Back", 0, "textures/ui/icon_import");
        $sender->sendForm($form);
        return $form;
    }

    public function helpme(Player $sender): Form
    {
        $form = new SimpleForm(function (Player $sender, int $data = null) {
            if ($data === null) {
                return;
            }
            switch ($data) {
                case 0:
                    $sender->sendForm(new RecipesForm());
                    break;
            }
        });
        $name = $sender->getName();
        $form->setTitle("§l§6RECIPES HELP");
        $form->setContent("§bHi,§e $name \n\n§l§a» §6VIDEO MODE:§r §eFirst Go To Settings > Video > UI Profile > Classic§r\n\n§l§a» §6COMMAND:§r §eDo /customtable To Open Custom Table§r\n\n§l§a» §6ERROR:§r §eIf You Are Unable To Open Recipe Join Discord And See Recipe Channel");
        $form->addButton("§l§aBACK\n§9»» §r§bTap To Go Back", 0, "textures/ui/icon_import");
        $sender->sendForm($form);
        return $form;
    }

    public function minions(Player $sender): Form
    {
        $form = new SimpleForm(function (Player $sender, int $data = null) {
            if ($data === null) {
                return;
            }
            switch ($data) {
                case 0:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view minion1");
                    break;

                case 1:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view minion2");
                    break;

                case 2:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view minion3");
                    break;

                case 3:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view minion4");
                    break;

                case 4:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view minion5");
                    break;

                case 5:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view minion6");
                    break;

                case 6:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view minion7");
                    break;

                case 7:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view minion8");
                    break;

                case 8:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view minion9");
                    break;

                case 9:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view minion10");
                    break;

                case 10:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view minion11");
                    break;

                case 11:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view minion12");
                    break;

                case 12:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view minion13");
                    break;

                case 13:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view minion14");
                    break;

                case 14:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view minion15");
                    break;

                case 15:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view minion16");
                    break;

                case 16:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view minion17");
                    break;

                case 17:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view minion18");
                    break;

                case 18:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view minion19");
                    break;

                case 19:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view minion20");
                    break;

                case 20:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view minion21");
                    break;

                case 21:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view minion22");
                    break;

                case 22:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view minion23");
                    break;

                case 23:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view minion24");
                    break;

                case 24:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view minion25");
                    break;
                    
                case 25:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view minion26");

                case 26:
                    $sender->sendForm(new RecipesForm());
                    break;
            }
        });
        $form->setTitle("§l§6MINION RECIPES");
        $form->setContent("§dSelect The Which Minion Recipe You Want:");
        $form->addButton("§l§bCOBBLESTONE MINION\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/891/891978.png");
        $form->addButton("§l§bCOAL MINION\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/891/891978.png");
        $form->addButton("§l§bIRON MINION\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/891/891978.png");
        $form->addButton("§l§bGOLD MINION\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/891/891978.png");
        $form->addButton("§l§bLAPIS MINION\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/891/891978.png");
        $form->addButton("§l§bREDSTONE MINION\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/891/891978.png");
        $form->addButton("§l§bDIAMOND MINION\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/891/891978.png");
        $form->addButton("§l§bEMERALD MINION\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/891/891978.png");
        $form->addButton("§l§bNETHER QUARTZ MINION\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/891/891978.png");
        $form->addButton("§l§bNETHERRACK MINION\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/891/891978.png");
        $form->addButton("§l§bENDSTONE MINION\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/891/891978.png");
        $form->addButton("§l§bWHEAT MINION\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/891/891978.png");
        $form->addButton("§l§bCARROT MINION\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/891/891978.png");
        $form->addButton("§l§bPOTATO MINION\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/891/891978.png");
        $form->addButton("§l§bMELON MINION\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/891/891978.png");
        $form->addButton("§l§bPUMPKIN MINION\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/891/891978.png");
        $form->addButton("§l§bDIRT MINION\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/891/891978.png");
        $form->addButton("§l§bSAND MINION\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/891/891978.png");
        $form->addButton("§l§bOAK LOG MINION\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/891/891978.png");
        $form->addButton("§l§bACACIA LOG MINION\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/891/891978.png");
        $form->addButton("§l§bBIRCH LOG MINION\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/891/891978.png");
        $form->addButton("§l§bSPRUCE LOG MINION\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/891/891978.png");
        $form->addButton("§l§bJUNGLE LOG MINION\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/891/891978.png");
        $form->addButton("§l§bDARK OAK MINION\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/891/891978.png");
        $form->addButton("§l§bSNOW MINION\n§9»» §r§6Tap To View", 1, "https://cdn-icons-png.flaticon.com/128/891/891978.png");
        $form->addButton("§l§bMINER MINION", 1, "https://cdn-icon-png.flaticon.com/138/891/891978.png");
        $form->addButton("§l§aBACK\n§9»» §r§bTap To Go Back", 0, "textures/ui/icon_import");
        $sender->sendForm($form);
        return $form;
    }

    public function armors(Player $sender): Form
    {
        $form = new SimpleForm(function (Player $sender, int $data = null) {
            if ($data === null) {
                return;
            }
            switch ($data) {
                case 0:
                    $this->armor1($sender);
                    break;

                case 1:
                    $this->armor2($sender);
                    break;

                case 2:
                    $this->armor3($sender);
                    break;

                case 3:
                    $this->armor4($sender);
                    break;

                case 4:
                    $this->armor5($sender);
                    break;

                case 5:
                    $this->armor6($sender);
                    break;

                case 6:
                    $this->armor7($sender);
                    break;

                case 7:
                    $this->armor8($sender);
                    break;

                case 8:
                    $this->armor9($sender);
                    break;

                case 9:
                    $this->armor10($sender);
                    break;

                case 10:
                    $this->armor11($sender);
                    break;

                case 11:
                    $this->armor12($sender);
                    break;

                case 12:
                    $this->armor13($sender);
                    break;

                case 13:
                    $this->armor14($sender);
                    break;

                case 14:
                    $this->armor15($sender);
                    break;

                case 15:
                    $this->armor16($sender);
                    break;

                case 16:
                    $this->armor17($sender);
                    break;

                case 17:
                    $this->armor18($sender);
                    break;

                case 18:
                    $this->armor19($sender);
                    break;

                case 19:
                    $this->armor20($sender);
                    break;

                case 20:
                    $sender->sendForm(new RecipesForm());
                    break;
            }
        });
        $form->setTitle("§l§6ARMOR RECIPES");
        $form->setContent("§dSelect The Which Armor Recipe You Want:");
        $form->addButton("§l§bGOD ARMOR\n§9»» §r§6Tap To Open", 1, "https://cdn-icons-png.flaticon.com/128/6010/6010434.png");
        $form->addButton("§l§bMINER ARMOR\n§9»» §r§6Tap To Open", 1, "https://cdn-icons-png.flaticon.com/128/6010/6010434.png");
        $form->addButton("§l§bFARMER ARMOR\n§9»» §r§6Tap To Open", 1, "https://cdn-icons-png.flaticon.com/128/6010/6010434.png");
        $form->addButton("§l§bLAPIS ARMOR\n§9»» §r§6Tap To Open", 1, "https://cdn-icons-png.flaticon.com/128/6010/6010434.png");
        $form->addButton("§l§bEND ARMOR\n§9»» §r§6Tap To Open", 1, "https://cdn-icons-png.flaticon.com/128/6010/6010434.png");
        $form->addButton("§l§bGOLEM ARMOR\n§9»» §r§6Tap To Open", 1, "https://cdn-icons-png.flaticon.com/128/6010/6010434.png");
        $form->addButton("§l§bPUMPKIN ARMOR\n§9»» §r§6Tap To Open", 1, "https://cdn-icons-png.flaticon.com/128/6010/6010434.png");
        $form->addButton("§l§bNETHER ARMOR\n§9»» §r§6Tap To Open", 1, "https://cdn-icons-png.flaticon.com/128/6010/6010434.png");
        $form->addButton("§l§bOAK ARMOR\n§9»» §r§6Tap To Open", 1, "https://cdn-icons-png.flaticon.com/128/6010/6010434.png");
        $form->addButton("§l§bICE ARMOR\n§9»» §r§6Tap To Open", 1, "https://cdn-icons-png.flaticon.com/128/6010/6010434.png");
        $form->addButton("§l§bASSASSIN ARMOR\n§9»» §r§6Tap To Open", 1, "https://cdn-icons-png.flaticon.com/128/6010/6010434.png");
        $form->addButton("§l§bTANK ARMOR\n§9»» §r§6Tap To Open", 1, "https://cdn-icons-png.flaticon.com/128/6010/6010434.png");
        $form->addButton("§l§bWISE ARMOR\n§9»» §r§6Tap To Open", 1, "https://cdn-icons-png.flaticon.com/128/6010/6010434.png");
        $form->addButton("§l§bEMERALD ARMOR\n§9»» §r§6Tap To Open", 1, "https://cdn-icons-png.flaticon.com/128/6010/6010434.png");
        $form->addButton("§l§bREDSTONE ARMOR\n§9»» §r§6Tap To Open", 1, "https://cdn-icons-png.flaticon.com/128/6010/6010434.png");
        $form->addButton("§l§bUNSTABLE ARMOR\n§9»» §r§6Tap To Open", 1, "https://cdn-icons-png.flaticon.com/128/6010/6010434.png");
        $form->addButton("§l§bSPIDER ARMOR\n§9»» §r§6Tap To Open", 1, "https://cdn-icons-png.flaticon.com/128/6010/6010434.png");
        $form->addButton("§l§bDIGGER ARMOR\n§9»» §r§6Tap To Open", 1, "https://cdn-icons-png.flaticon.com/128/6010/6010434.png");
        $form->addButton("§l§bTECHNO ARMOR\n§9»» §r§6Tap To Open", 1, "https://cdn-icons-png.flaticon.com/128/6010/6010434.png");
        $form->addButton("§l§bLIQUED ARMOR\n§9»» §r§6Tap To Open", 1, "https://cdn-icons-png.flaticon.com/128/6010/6010434.png");
        $form->addButton("§l§aBACK\n§9»» §r§bTap To Go Back", 0, "textures/ui/icon_import");
        $sender->sendForm($form);
        return $form;
    }

    public function eblocks(Player $sender): Form
    {
        $form = new SimpleForm(function (Player $sender, int $data = null) {
            if ($data === null) {
                return;
            }
            switch ($data) {
                case 0:
                    Server::getInstance()->dispatchCommand($sender, "invcraft view ec1");
                    break;

                case 1:
                    Server::getInstance()->dispatchCommand($sender, "in
