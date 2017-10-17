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
	public function getCount($entryId, $daysRange = null)
	{
		return craft()->mostViewed->getCount($entryId, $daysRange);
	}

	/**
	 * Returns counted entries
	 *
	 * @return ElementCriteriaModel
	 */
	public function getEntries($daysRange = null)
	{
		return craft()->mostViewed->getEntries($daysRange);
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
