<?php

declare(strict_types=1);

namespace App\Application\Command;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Concerns\Confirmable;
use Hyperf\Contract\ApplicationInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\ConsoleOutput;

#[Command]
class MigrateCommand extends HyperfCommand
{
    use Confirmable;

    protected ?string $name = "app:migrate";

    protected function getArguments()
    {
        return [["domain", InputArgument::REQUIRED, "Informe o nome do domain"]];
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription("Run the database migrations for a specific domain");
    }

    public function handle()
    {
        if (!$this->confirmToProceed()) {
            return 0;
        }

        $domain = $this->input->getArgument("domain");

        $this->call("migrate", [
            "--path" => "migrations/$domain",
        ]);
    }
}
