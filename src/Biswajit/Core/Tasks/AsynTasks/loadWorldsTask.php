<?php

namespace Biswajit\Core\Tasks\AsynTasks;

use Biswajit\Core\API;
use Biswajit\Core\Managers\IslandManager;
use Biswajit\Core\Skyblock;
use pocketmine\scheduler\AsyncTask;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;
use pocketmine\utils\Internet;


class loadWorldsTask extends AsyncTask
{
       public function __construct(private string $url, private string $targetPath) {}

       public function onRun(): void {
                $data = Internet::getURL($this->url, 10, [], $err);
                if ($data !== null && $err === null) {
                    $this->setResult($data->getBody());
                } else {
                    $this->setResult(null);
                }
            }

            public function onCompletion(): void {
                if ($this->getResult() === null) {
                    Server::getInstance()->getLogger()->error("Failed to download file from URL: " . $this->url);
                    return;
                }

                file_put_contents($this->targetPath, $this->getResult());

                Skyblock::getInstance()->getScheduler()->scheduleTask(new ClosureTask(function() {
                    API::loadHub();
                    IslandManager::loadIslands();
                    API::setHubTime();
                }, 120));
       }
}