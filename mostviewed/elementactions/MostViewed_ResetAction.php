<?php
namespace Craft;

/**
 * Entry Count Reset Action
 */
class MostViewed_ResetAction extends BaseElementAction
{
	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName()
	{
		return Craft::t('Reset Entry Count');
	}

	/**
	 * Is destructive
	 *
	 * @return bool
	 */
	public function isDestructive()
	{
		return true;
	}

	/**
	 * Perform action
	 *
	 * @param ElementCriteriaModel $criteria
	 *
	 * @return bool
	 */
	public function performAction(ElementCriteriaModel $criteria)
	{
		$entries = $criteria->find();

		foreach ($entries as $entry)
		{
			craft()->mostViewed->reset($entry->id);
		}

		$this->setMessage(Craft::t('Entry Count Successfully Reset'));

		return true;
	}
}
