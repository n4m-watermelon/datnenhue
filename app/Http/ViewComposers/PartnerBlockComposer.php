<?php

namespace App\Http\ViewComposers;

use App\Repositories\Eloquent\PartnerEloquentResponsitory;
use Illuminate\View\View;

/**
 * Class PartnerBlockComposer
 *
 * @package App\Http\ViewComposers
 */
class PartnerBlockComposer
{
    /**
     * @var PartnerEloquentResponsitory
     */
    protected $partnerResponsitory;

    /**
     * PartnerBlockComposer constructor.
     *
     * @param PartnerEloquentResponsitory $partnerResponsitory
     */
    public function __construct(PartnerEloquentResponsitory $partnerResponsitory)
    {
        $this->partnerResponsitory = $partnerResponsitory;
    }

    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $parners = $this->partnerResponsitory->isPublished();
        $view->with('partners', $parners);
    }
}