<?php
/**
 * Test plugin for Craft CMS
 *
 * Test Task
 *
 * --snip--
 * Tasks let you run background processing for things that take a long time, dividing them up into steps.  For
 * example, Asset Transforms are regenerated using Tasks.
 *
 * Keep in mind that tasks only get timeslices to run when Craft is handling requests on your website.  If you
 * need a task to be run on a regular basis, write a Controller that triggers it, and set up a cron job to
 * trigger the controller.
 *
 * https://craftcms.com/classreference/services/TasksService
 * --snip--
 *
 */

namespace Craft;

class MostViewedTask extends BaseTask
{
	/**
	 * Defines the settings.
	 *
	 * @access protected
	 * @return array
	 */

	protected function defineSettings()
	{
		return array(
			'entryId' => AttributeType::Number
		);
	}

	/**
	 * Returns the default description for this task.
	 *
	 * @return string
	 */
	public function getDescription()
	{
		return 'Most Viewed Cleanup task';
	}

	/**
	 * Gets the total number of steps for this task.
	 *
	 * @return int
	 */
	public function getTotalSteps()
	{
		return 1;
	}

	/**
	 * Runs a task step.
	 *
	 * @param int $step
	 * @return bool
	 */
	public function runStep($step)
	{
		craft()->mostViewed->cleanup($this->getSettings()->entryId);
		return true;
	}
}
