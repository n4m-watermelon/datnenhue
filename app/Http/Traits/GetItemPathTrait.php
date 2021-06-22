<?php

namespace App\Http\Traits;
/**
 * Class GetPathTrait
 *
 * @package App\Http\Traits
 *
 */
trait GetItemPathTrait
{
    /**
     * getPath()
     *
     * @param string $separator
     * @return string
     */
    public function getPath($separator = '/')
    {
        $categorySet = array_column($this->category->getAncestorsAndSelf(['title'])->toArray(), 'title');
        return implode($separator, $categorySet) . $separator . $this->title;
    }

    /**
     *
     * getPathAlias()
     *
     * @param string $separator
     * @return string
     */
    public function getPathAlias($separator = '/')
    {
        $categorySet = array_column($this->category->getAncestorsAndSelf(['title_alias'])->toArray(), 'title_alias');
        return implode($separator, $categorySet) . $separator . $this->title_alias;
    }

}