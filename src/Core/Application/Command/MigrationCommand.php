<?php

declare(strict_types=1);

namespace Core\Application\Command;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Concerns\Confirmable;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

#[Command]
class MigrationCommand extends HyperfCommand
{
    use Confirmable;

    protected ?string $name = "app:migration";

    protected function getArguments()
    {
        return [
            ["domain", InputArgument::REQUIRED, "Informe o nome do domain"],
            ["name", InputArgument::REQUIRED, "Informe o nome da migration"],
        ];
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription("Run the database migrations for a specific domain");
        $this->addOption('table', 't', InputOption::VALUE_OPTIONAL, 'Informe o nome da migration');
    }

    public function handle()
    {
        if (!$this->confirmToProceed()) {
            return 0;
        }

        $domain = $this->input->getArgument("domain");
        $argName = $this->input->getArgument("name");

        $arguments = ["--path" => "migrations/$domain"];
        if ($table = $this->input->getOption('table')) {
            $arguments['--table'] = $table;
        }

        $arguments["name"] = $argName;

        $this->call("gen:migration", $arguments);
    }
}
