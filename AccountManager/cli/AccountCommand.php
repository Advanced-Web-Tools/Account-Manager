<?php

namespace AccountManager\cli;

use AccountManager\service\AccountRecords;
use cli\interfaces\CLICommand;
use database\exceptions\ProviderException;
use InvalidArgumentException;
use PDOException;
use RuntimeException;

abstract class AccountCommand implements CLICommand
{
    private string $output = '';

    public function __construct(private readonly ?AccountRecords $records = null) {}

    final public function execute(string $command, array $args = []): void
    {
        $this->output = '';
        if (PHP_SAPI !== 'cli') {
            $this->output = 'Account commands are available only from the command line.';
            return;
        }
        try {
            $input = $this->parse($args);
            $this->output = $this->run($this->records ?? new AccountRecords(), $input);
        } catch (InvalidArgumentException $exception) {
            $this->output = 'Error: ' . $exception->getMessage();
        } catch (ProviderException|PDOException|RuntimeException $exception) {
            // Database errors may contain input values, including passwords.
            $this->output = 'Error: Unable to complete the account command. Check the database and any related records.';
        }
    }

    final public function result(): string
    {
        return $this->output;
    }

    /** @return array<string, string> Supported named arguments and help text. */
    abstract public function getArguments(): array;

    abstract protected function run(AccountRecords $records, array $input): string;

    protected function accountId(array $input): int
    {
        $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if (!$id) {
            throw new InvalidArgumentException('Provide a positive account id.');
        }
        return $id;
    }

    private function parse(array $arguments): array
    {
        $allowed = $this->getArguments();
        $input = [];
        foreach ($arguments as $argument) {
            if (!is_string($argument) || !str_contains($argument, ':')) {
                throw new InvalidArgumentException('Use named arguments such as username:alice. Run help ' . $this->getCommand() . ' for usage.');
            }
            [$key, $value] = explode(':', $argument, 2);
            if (!array_key_exists($key, $allowed)) {
                throw new InvalidArgumentException('Unsupported argument. Run help ' . $this->getCommand() . ' for usage.');
            }
            if (array_key_exists($key, $input)) {
                throw new InvalidArgumentException('Each argument may be supplied only once.');
            }
            $input[$key] = $value;
        }
        return $input;
    }
}
