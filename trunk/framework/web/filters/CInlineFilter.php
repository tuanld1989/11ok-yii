<?php
/**
 * CInlineFilter class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CInlineFilter represents a filter defined as a controller method.
 *
 * CInlineFilter executes the 'filterXYZ($action)' method defined
 * in the controller, where the name 'XYZ' can be retrieved from the {@link name} property.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CInlineFilter.php 514 2009-01-12 17:00:00Z qiang.xue $
 * @package system.web.filters
 * @since 1.0
 */
class CInlineFilter extends CFilter
{
	/**
	 * @var string name of the filter. It stands for 'XYZ' in the filter method name 'filterXYZ'.
	 */
	public $name;

	/**
	 * Creates an inline filter instance.
	 * The creation is based on a string describing the inline method name
	 * and action names that the filter shall or shall not apply to.
	 * @param CController the controller who hosts the filter methods
	 * @param string the filter name
	 * @return CInlineFilter the created instance
	 * @throws CException if the filter method does not exist
	 */
	public static function create($controller,$filterName)
	{
		if(method_exists($controller,'filter'.$filterName))
		{
			$filter=new CInlineFilter;
			$filter->name=$filterName;
			return $filter;
		}
		else
			throw new CException(Yii::t('yii','Filter "{filter}" is invalid. Controller "{class}" does have the filter method "filter{filter}".',
				array('{filter}'=>$filterName, '{class}'=>get_class($controller))));
	}

	/**
	 * Performs the filtering.
	 * This method calls the filter method defined in the controller class.
	 * @param CFilterChain the filter chain that the filter is on.
	 */
	public function filter($filterChain)
	{
		$method='filter'.$this->name;
		$filterChain->controller->$method($filterChain);
	}
}