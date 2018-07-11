<?php namespace Aldea\Travel\Classes;

class Helper
{
	public function customerTypeOptions()
    {
    	return [
    		1 => e(trans('aldea.travel::lang.customerTypeOptions.particular')),
    		2 => e(trans('aldea.travel::lang.customerTypeOptions.labor')),
    		3 => e(trans('aldea.travel::lang.customerTypeOptions.other')),
    	];
    }	
}
