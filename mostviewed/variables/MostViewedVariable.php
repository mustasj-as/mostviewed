<?php
namespace Craft;

/**
 * Entry Count Variable
 */
class MostViewedVariable
{
	/**
	 * Returns count
	 *
	 * @param int $entryId
	 *
	 * @return MostViewedModel
	 */
	public function getCount($entryId)
	{
		return craft()->mostViewed->getCount($entryId);
	}

	/**
	 * Returns counted entries
	 *
	 * @return ElementCriteriaModel
	 */
	public function getEntries()
	{
		return craft()->mostViewed->getEntries();
	}

	/**
	 * Increment count
	 *
	 * @param int $entryId
	 */
	public function increment($entryId)
	{
		craft()->mostViewed->increment($entryId);
	}

}
