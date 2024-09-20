<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Answer;
use App\Entity\DataPoint;
use App\Entity\MediaType;
use App\Entity\SourceType;
use App\Entity\Media;
use App\Entity\SurveyResult;
use App\Service\Utils;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\Common\EventArgs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Lcobucci\JWT\Exception;
use Symfony\Component\PropertyAccess\PropertyAccess;

class CreateDataPointFromSurvey implements EventSubscriberInterface
{
    /**
     * @var Utils
     */
    private $utils;
    private $em;

    public function __construct(EntityManagerInterface $em, Utils $utils)
    {
        $this->em = $em;
        $this->utils = $utils;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postUpdate,
            Events::postPersist,
        ];
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->createDataPoint(Events::postUpdate, $args->getObject());
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->createDataPoint(Events::postPersist, $args->getObject());
    }

    public function getMappingEntry($args, $value)
    {
        return 'test';
    }

    /**
     * @throws MappingException
     */
    public function createDataPoint($method, $surveyResult): void
    {
        $logFile = 'Create New DataPoint';
        $mappedBy_answers_array = [];
        $dataPointFields = $this->utils->getDataPointMetadata();
        try {
            if (($surveyResult instanceof SurveyResult) && $surveyResult->getQuestionnaire()?->getCreateDataPoint()) {
                $propertyAccessor = PropertyAccess::createPropertyAccessor();
                $this->utils->logFile('New Data Point', $logFile);
                $newDataPoint = new DataPoint();
                $user = $surveyResult->getUserEnumerator();
                if ($user) {
                    $this->utils->logFile('User: ' . $user->getEmail(), $logFile);
                    $newDataPoint->setCreatedBy($user);
                } else {
                    $this->utils->logFile('No user associated, this might be a problem, check survey result id: ' . $surveyResult->getId(), $logFile);
                }
                $newDataPoint->setCreationDate($surveyResult->getFinishDate());
                $newDataPoint->setProject($surveyResult->getQuestionnaire()?->getProject());
                /** @var Answer $answer */
                foreach ($surveyResult->getAnswers() as $answer) {
                    $config = $answer->getQuestion()?->getConfig();
                    if (($mappedBy = $config['mappedBy']) && $dataPointFields[$mappedBy]) {
                        if ($dataPointFields[$mappedBy]['association']) {
                            //this will work on any entity, the function will look if we have the entry or to create a new one in case of association true
                            $entry = $this->getMappingEntry($dataPointFields[$mappedBy]['targetEntity'], $answer->getValue());
                        } else {
                            $entry = $answer->getValue();
                        }
                        //todo test on images
                        $propertyAccessor->setValue($dataPoint, $mappedBy, $entry);
                    }
                }

                $this->em->persist($dataPoint);
                $surveyResult->setDataPoint($dataPoint);
                $this->em->persist($surveyResult);
                $this->em->flush();
                $this->utils->logFile('6/ DataPoint Created Successful id: ' . $dataPoint->getId(), $logFile);

            }
        } catch (\Exception $e) {
            $this->utils->logFile('An Error Occured', $logFile);
            $this->utils->logFile($e->getMessage(), $logFile);
        }

    }
}
