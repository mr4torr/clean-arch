<?php

declare(strict_types=1);

namespace Core\Application\Command;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Concerns\Confirmable;
use Symfony\Component\Console\Input\InputArgument;

#[Command]
class RollbackCommand extends HyperfCommand
{
    use Confirmable;

    protected ?string $name = "app:migrate:rollback";

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

        $this->call("migrate:rollback", [
            "--path" => "migrations/$domain",
        ]);
    }
}
