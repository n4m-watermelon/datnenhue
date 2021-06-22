<?php
namespace App\Http\Traits;
/**
 * 
 */
trait GetPublicTrait
{
    /**
	 * getPublicAttribute()
	 * 
	 * @param mixed $value
	 * @return
	 */
	public function getPublicAttribute($value) {
        if (is_null($value)) {
            $value = 1;
        }
        return $value;
    }
}
