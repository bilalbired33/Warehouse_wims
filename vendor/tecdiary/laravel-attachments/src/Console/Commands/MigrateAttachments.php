<?php

namespace Tecdiary\Laravel\Attachments\Console\Commands;

use Log;
use Lang;
use Exception;
use Throwable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Tecdiary\Laravel\Attachments\Attachment;
use Symfony\Component\Console\Input\InputArgument;

class MigrateAttachments extends Command
{
    protected $signature = 'attachments:migrate';

    public function __construct()
    {
        parent::__construct();

        $this->setDescription(Lang::get('attachments::messages.console.migrate_description'));

        $this->getDefinition()->addArgument(new InputArgument(
            'from',
            InputArgument::REQUIRED,
            Lang::get('attachments::messages.console.migrate_option_from')
        ))
        ;

        $this->getDefinition()->addArgument(new InputArgument(
            'to',
            InputArgument::REQUIRED,
            Lang::get('attachments::messages.console.migrate_option_to')
        ))
        ;
    }

    public function handle()
    {
        if ($this->argument('from') === $this->argument('to')) {
            $this->error(Lang::get('attachments::messages.console.migrate_error_missing'));

            return;
        }

        if (empty(config(sprintf('filesystems.disks.%s', $this->argument('from'))))) {
            $this->error(Lang::get('attachments::messages.console.migrate_error_from'));

            return;
        }

        if (empty(config(sprintf('filesystems.disks.%s', $this->argument('to'))))) {
            $this->error(Lang::get('attachments::messages.console.migrate_error_to'));

            return;
        }

        try {
            Storage::disk($this->argument('from'))
                ->has('.')
            ;
        } catch (Exception $e) {
            $this->error(Lang::get('attachments::messages.console.migrate_invalid_from'));
        }
        try {
            Storage::disk($this->argument('to'))
                ->has('.')
            ;
        } catch (Exception $e) {
            $this->error(Lang::get('attachments::messages.console.migrate_invalid_to'));
        }

        $query = Attachment::query()
            ->where('disk', '=', $this->argument('from'));

        $this
            ->getOutput()
            ->progressStart($query->count())
        ;

        do {
            $deferred = [];
            $continue = true;

            try {
                $items = $query
                    ->take(10)
                    ->get()
                    ->each(function (Attachment $attachment) use (&$deferred) {
                        if ($this->move($attachment, $deferred)) {
                            $attachment->disk = $this->argument('to');

                            $attachment->save();
                        }

                        $this
                            ->getOutput()
                            ->progressAdvance()
                        ;
                    });
            } catch (Exception $e) {
                $continue = false;

                $this->error($e->getMessage());
                Log::error($e);
            }

            foreach ($deferred as $callable) {
                try {
                    $callable();
                } catch (Exception | Throwable $e) {
                    $this->warn(sprintf('Failed to clean source file : %s', $e->getMessage()));
                    Log::error($e);
                }
            }
        } while ($continue && $items->isNotEmpty());

        $this
            ->getOutput()
            ->progressFinish()
        ;
    }

    private function move(Attachment $attachment, &$deferred)
    {
        $from = $attachment->disk;
        $to = $this->argument('to');
        $filepath = $attachment->filepath;

        if (!Storage::disk($from)->exists($filepath)) {
            return true;
        }

        Storage::disk($to)
            ->put($filepath, Storage::disk($from)->get($filepath))
        ;

        $deferred[] = function () use ($from, $filepath) {
            Storage::disk($from)
                ->delete($filepath)
            ;
        };

        return true;
    }
}
