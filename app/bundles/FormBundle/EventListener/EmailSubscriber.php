<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\FormBundle\EventListener;

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\EmailBundle\EmailEvents;
use Mautic\EmailBundle\Event\EmailBuilderEvent;

/**
 * Class EmailSubscriber
 */
class EmailSubscriber extends CommonSubscriber
{

    /**
     * {@inheritdoc}
     */
    static public function getSubscribedEvents ()
    {
        return array(
            EmailEvents::EMAIL_ON_BUILD   => array('onEmailBuild', 0)
        );
    }

    /**
     *
     * @param EmailBuilderEvent $event
     */
    public function onEmailBuild (EmailBuilderEvent $event)
    {
        //add AB Test Winner Criteria
        $formSubmissions = array(
            'group'    => 'mautic.form.abtest.criteria',
            'label'    => 'mautic.form.abtest.criteria.submissions',
            'callback' => '\Mautic\FormBundle\Helper\AbTestHelper::determineSubmissionWinner'
        );
        $event->addAbTestWinnerCriteria('form.submissions', $formSubmissions);

        //add email token
        $content = $this->templating->render('MauticFormBundle:SubscribedEvents\EmailToken:token.html.php');
        $event->addTokenSection('form.submissions', 'mautic.form.forms', $content);
    }
}
