<?php
namespace Craft;

/**
 * Entry Count Controller
 */
class MostViewedController extends BaseController
{
	/**
	 * Reset count
	 */
	public function actionReset()
	{
		$entryId = craft()->request->getRequiredParam('entryId');

		craft()->mostViewed->reset($entryId);

		craft()->userSession->setNotice(Craft::t('Entry count reset.'));

		$this->redirect('mostviewed');
	}

}
