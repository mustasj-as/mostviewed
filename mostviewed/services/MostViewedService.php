<?php
namespace Craft;

/**
 * Entry Count Service
 */
class MostViewedService extends BaseApplicationComponent
{
	/**
	 * Returns count
	 *
	 * @param int $entryId
	 *
	 * @return int
	 */
	public function getCount($entryId, $daysRange)
	{
		// create new model
		$totalCount = 0;

		// get record from DB
		$mostViewedRecords = MostViewedRecord::model()->findAllByAttributes(array('entryId' => $entryId));

		if ($mostViewedRecords)
		{
			$settings = craft()->plugins->getPlugin('mostViewed')->getSettings();
			// populate model from record

			foreach ($mostViewedRecords as $mv)
			{
				if ($daysRange && $this->_daysSince($mv->dateCreated) > $daysRange)
				{
					continue;
				}
				$mostViewedModel = MostViewedModel::populateModel($mv);
				$totalCount += $mostViewedModel->count;
			}
		}

		return $totalCount;
	}

	/**
	 * Returns counted entries
	 *
	 * @return ElementCriteriaModel
	 */
	public function getEntries($daysRange)
	{
		// get all records from DB ordered by count descending
		$mostViewedRecords = MostViewedRecord::model()->findAll();

		// get entry ids from records
		$totalCounts = array();

		foreach ($mostViewedRecords as $mostViewedRecord)
		{
			$eId = $mostViewedRecord->entryId;
			if (!array_key_exists($eId, $totalCounts))
			{
				$totalCounts[$eId] = 0;
			}

			if ($daysRange && $this->_daysSince($mostViewedRecord->dateCreated) > $daysRange)
			{
				continue;
			}
			$totalCounts[$eId] += $mostViewedRecord->count;
		}

		uasort($totalCounts, function($a, $b)
		{
			if ($a == $b)
			{
				return 0;
			}
			return ($a > $b) ? -1 : 1;
		});


		$entryIds = [];
		foreach ($totalCounts as $k => $v)
		{
			$entryIds[] = $k;
		}
		//var_dump($entryIds);

		// create criteria for entry element type
		$criteria = craft()->elements->getCriteria('Entry');

		// filter by entry ids
		$criteria->id = $entryIds;

		// enable fixed order
		$criteria->fixedOrder = true;

		return $criteria;
	}

	/**
	 * Increment count
	 *
	 * @param int $entryId
	 */
	public function increment($entryId)
	{
		// check if action should be ignored
		if ($this->_ignoreAction())
		{
			return;
		}

		craft()->tasks->createTask('MostViewed', 'Cleaning up most viewed rows', array(
			'entryId' => $entryId
		));

		// get record from DB
		$mostViewedRecord = MostViewedRecord::model()->findBySql('SELECT * FROM craft_mostviewed WHERE DATE(dateCreated) = DATE(NOW()) AND entryId = ' . $entryId);

		// if exists then increment count
		if ($mostViewedRecord)
		{
			$mostViewedRecord->setAttribute('count', $mostViewedRecord->getAttribute('count') + 1);
		}

		// otherwise create a new record
		else
		{
			$mostViewedRecord = new MostViewedRecord;
			$mostViewedRecord->setAttribute('entryId', $entryId);
			$mostViewedRecord->setAttribute('count', 1);
			$mostViewedRecord->setAttribute('dateCreated', new DateTime());
		}

		// save record in DB
		$mostViewedRecord->save();
	}

	/**
	 * Reset count
	 *
	 * @param int $entryId
	 */
	public function reset($entryId)
	{
		// get record from DB
		$mostViewedRecords = MostViewedRecord::model()->findAllByAttributes(array('entryId' => $entryId));

		// if record exists then delete
		if ($mostViewedRecords)
		{
			foreach ($mostViewedRecords as $mv) {
				// delete record from DB
				$mv->delete();
			}
		}

		// log reset
		MostViewedPlugin::log(
			'Entry count with entry ID '.$entryId.' reset by '.craft()->userSession->getUser()->username,
			LogLevel::Info,
			true
		);

		// fire an onResetCount event
		$this->onResetCount(new Event($this, array('entryId' => $entryId)));
	}

	/**
	 * On reset count
	 *
	 * @param Event $event
	 */
	public function onResetCount($event)
	{
		$this->raiseEvent('onResetCount', $event);
	}

	/**
	 * Cleanup rows for entryId
	 *
	 * @param int $entryId
	 */

	public function cleanup($entryId)
	{
		$mostViewedRecords = MostViewedRecord::model()->findAllByAttributes(array('entryId' => $entryId));

		if ($mostViewedRecords)
		{
			$settings = craft()->plugins->getPlugin('mostViewed')->getSettings();
			// populate model from record
			foreach ($mostViewedRecords as $mv)
			{
				$mostViewedModel = MostViewedModel::populateModel($mv);
				$days = $this->_daysSince($mostViewedModel->dateCreated);
				if ($days > $settings->daysToKeep)
				{
					$didDelete = $mv->delete();
					if (!$didDelete)
					{
						MostViewedPlugin::log('Unable to clean up entry id: ' . $entryId);
					}
				}
			}
		}
	}

	// Helper methods
	// =========================================================================

	/**
	 * Check if action should be ignored
	 */
	private function _ignoreAction()
	{
		// get plugin settings
		$settings = craft()->plugins->getPlugin('mostViewed')->getSettings();

		// check if logged in users should be ignored based on settings
		if ($settings->ignoreLoggedInUsers AND craft()->userSession->isLoggedIn())
		{
			return true;
		}
	}

	/**
	 * Check if record is old enough to be deleted
	 */
	private function _daysSince($date)
	{
		$now = time();
		$datediff = $now - $date->getTimestamp();
		return floor($datediff / (60 * 60 * 24));
	}
}
