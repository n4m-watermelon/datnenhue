<?php

namespace App\Http\Traits;
/**
 * Class GetPathTrait
 *
 * @package App\Http\Traits
 *
 */
trait GetPathTrait
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
        if (class_basename($this) == 'District' || class_basename($this) == 'Ward') {
            return [$this->slug_name, $this->get('id')];
        } else {
            $categorySet = array_column($this->category->getAncestorsAndSelf(['title_alias'])->toArray(), 'title_alias');
            return implode($separator, $categorySet) . $separator . $this->title_alias;
        }
    }

}
