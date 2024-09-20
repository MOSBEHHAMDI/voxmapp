<?php

namespace App\Console;

use Symfony\Bundle\FrameworkBundle\Console\Application as BaseApplication;
class Application extends BaseApplication
{
    final public function getLongVersion(): string
    {
        return sprintf('%s <info>%s</info>', $this->getName(), $this->getVersion()).sprintf(' (env: <comment>%s</>, debug: <comment>%s</>)', $this->getKernel()->getEnvironment(), $this->getKernel()->isDebug() ? 'true' : 'false');
    }
}