<?php
/**
 * CMysqlTableSchema class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CMysqlTableSchema represents the metadata for a MySQL table.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CMysqlTableSchema.php 434 2008-12-30 23:14:31Z qiang.xue $
 * @package system.db.schema.mysql
 * @since 1.0
 */
class CMysqlTableSchema extends CDbTableSchema
{
	/**
	 * @var string name of the schema (database) that this table belongs to.
	 * Defaults to null, meaning no schema (or the current database).
	 */
	public $schemaName;
}
