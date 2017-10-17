<?php
namespace Craft;

/**
 * Entry Count Record
 */
class MostViewedRecord extends BaseRecord
{
	/**
	 * Get table name
	 *
	 * @return string
	 */
	public function getTableName()
	{
		return 'mostviewed';
	}

	/**
	 * Define table columns
	 *
	 * @return array
	 */
	public function defineAttributes()
	{
		return array(
			'count' => array(AttributeType::Number, 'default' => 0)
		);
	}

	/**
	 * Define relationships with other tables
	 *
	 * @return array
	 */
	public function defineRelations()
	{
		return array(
			'entry' => array(static::BELONGS_TO, 'EntryRecord', 'required' => true, 'onDelete' => static::CASCADE)
		);
	}

	/**
	 * Define table indexes
	 *
	 * @return array
	 */
	public function defineIndexes()
	{
		return array(
			array('columns' => array('entryId'))
		);
	}
}
