<?php 

namespace Wpjscc\Twig\Classes;

use Twig\Source;
use Wpjscc\Twig\Models\Twig as TwigModel;

class DatabaseTwigLoader implements \Twig\Loader\LoaderInterface
{

    protected $groups;

    public function getSourceContext(string $name): Source
    {
        if (false === $source = $this->getValue('source', $name)) {
            throw new \Twig\Error\LoaderError(sprintf('Template "%s" does not exist.', $name));
        }

        return new \Twig\Source($source, $name);
    }

    public function exists(string $name)
    {
        return $name === $this->getValue('name', $name);
    }

    public function getCacheKey(string $name): string
    {
        return $name;
    }

    public function isFresh(string $name, int $time): bool
    {
        if (false === $lastModified = $this->getValue('updated_at', $name)) {
            return false;
        }

        return $lastModified <= $time;
    }

    protected function getValue($column, $name)
    {

        list($group, $name) = explode('.', $name, 2);

        if (!isset($this->groups[$group])) {
            $this->groups[$group] = TwigModel::where('group', $group)->get();
        }

        foreach ($this->groups[$group] as $twig) {
            if ($twig->name === $name) {
                return $twig->{$column};
            }
        }

        return false;
    }
}