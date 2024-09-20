<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\DataPoint;
use App\Entity\Media;
use App\Entity\Question;
use App\Entity\Questionnaire;
use App\Entity\Section;
use App\Entity\User;
use App\Repository\ChatRepository;
use App\Repository\UserRepository;
use App\Service\questionnaire\ExportService;
use App\Service\Utils;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\MappingException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class DefaultController extends AbstractController
{

    protected $entityManager;

    /**
     * @var Utils
     */
    private $utils;

    public function __construct(EntityManagerInterface $entityManager, Utils $utils)
    {
        $this->entityManager = $entityManager;
        $this->utils = $utils;
    }

    /*
     * @throws \Doctrine\DBAL\Exception
     */

    /*  #[Route('/api/home_page_info/{current_client}', name: 'home_page_info')]
      public function homePageInfo(Request $r, SerializerInterface $serializer): Response
      {
          $dql = 'SELECT u FROM App\Entity\User u ';
          $user = $this->utils->getCurrentUser();
          $super_admin_info['users'] = ['entity' => 'User', 'groups' => 'user'];
          $super_admin_info['clients'] = ['entity' => 'Client', 'groups' => 'client'];
          $super_admin_info['projects'] = ['entity' => 'Project', 'groups' => 'project'];
          $super_admin_info['groups'] = ['entity' => 'Group', 'groups' => 'group'];
          $super_admin_info['languages'] = ['entity' => 'Language', 'groups' => 'language'];
          $super_admin_info['subscriptionType'] = ['entity' => 'subscriptionType', 'groups' => 'SubscriptionType', 'iri' => 'subscription_type'];
          $user_data = [];
          if ($user->hasRole("ROLE_SUPER_ADMIN")) {
              foreach ($super_admin_info as $key => $data) {
                  if ($data['groups'] !== '') {
                      $item = $this->entityManager->getRepository('App\\Entity\\' . $data['entity'])->findAll();
                      $res = $serializer->normalize($item, null, ['groups' => $data['groups'] . ':read']);
                      foreach ($res as &$e) {
                          $e["iri"] = "/api/" . ($data['iri'] ?? $data['groups']) . "s/" . $e['id'];
                      }
                      $user_data[$key] = $res;
                  }
              }
              return new JsonResponse($user_data);
          } else {
              $query = $this->utils->createQuery($dql, $r->get("current_client"));
              $result = $query->getArrayResult();
          }

          return new JsonResponse($user_data);
      }*/

    /**
     * @Route("api/upload", name="file_upload")
     */
    public function upload(Request $request, UserRepository $userRepository): JsonResponse
    {
        try {
            if ($uploadedFile = $request->files->get("file")) {
                $media = $this->utils->saveFile($uploadedFile, $request->get("subDir"));
                return new JsonResponse(['result' => true, 'message' => 'File uploaded successfully.', 'mediaId' => $media->getId()]);
            } else {
                $message = 'No file uploaded.';
            }
        } catch (Exception $e) {
            // Log or print the exception message for debugging
            $message = $e->getMessage();
        }

        return new JsonResponse(['result' => false, 'message' => $message]);
    }

    /**
     * @Route("api/download/{id}", name="download", methods={"GET"})
     */
    public function download($id): Response
    {
        try {
            /** @var Media $media */
            if ($media = $this->entityManager->getRepository(Media::class)->find($id)) {
                $path = $media->getFilePath();
                if ($file = $this->utils->getFile($path)) {
                    return $this->file($file, null, ResponseHeaderBag::DISPOSITION_INLINE);
                }
            }
        } catch (Exception $e) {
            echo($e->getMessage());
        }
        return new Response("File not found", 404);
    }

    /**
     * @Route("api/log_error", name="log_error", methods={"POST"})
     */
    public function logFrontError(Request $r): Response
    {
        $message = $r->get('message');
        $ip = $r->getClientIp();
        $this->utils->logFile($ip . ": " . $message, 'front_errors');
        return new Response();
    }

    /**
     * @Route("api/get_questionnaires", name="get_questionnaires")
     */
    public function getQuestionnaires(Request $request, SerializerInterface $serializer)
    {
        $finalResult = [];
        try {
            if ($questionnaires = $this->entityManager->getRepository(Questionnaire::class)->findAll()) {
                /** @var Questionnaire $questionnaire */
                foreach ($questionnaires as $questionnaire) {
                    /** @var Section $section */
                    foreach ($questionnaire->getSections() as $section) {
                        /** @var Question $question */
                        foreach ($section->getQuestions() as $question) {
                            if (($type = $question->getQuestiontype()) && ($type->getLabel() === 'ListOfEntries') && ($entity = $question->getConfig()['entry'])) {
                                $entries = $this->entityManager->getRepository('App\\Entity\\' . $entity)->findAll();
                                $question->setEntries($serializer->normalize($entries));
                            }
                        }
                    }
                }
                $finalResult["questionnaires"] = $serializer->normalize($questionnaires, null, ['groups' => 'Questionnaire:create']);
            }
        } catch (\Exception $e) {
            $finalResult = ['error' => true, 'msg' => $e->getTraceAsString()];
        }
        return new JsonResponse($finalResult);
    }

    /**
     * @Route("api/getChatList/{userId}", name="getChatList", methods={"GET"})
     */
    public function getChatList(Request $request, SerializerInterface $serializer, $userId)
    {
        $finalResult = [];
        try {
            $user = $this->entityManager->getRepository(User::class)->find($userId);

            if (!$user) {
                throw new \Exception('User not found');
            }

            $chatsData = [];
            $chats = $user->getChats();
            /** @var Chat $chat */
            foreach ($chats as $chat) {
                $chatData = $serializer->normalize($chat, null, ['groups' => 'Chat:create']);
                $chatData['id'] = '/api/chats/' . $chat->getId();
                unset($chatData['messages']);
                if ($latestMessage = $chat->getLatestMessage()) {
                    $chatData['latest_message'] = $serializer->normalize($latestMessage);
                }
                /** @var ChatRepository $chatRepository */
                $chatRepository = $this->entityManager->getRepository(Chat::class);
                $unseenMsgsCount = $chatRepository->countUnseenMessages($user, $chat);
                $chatData['unseen_messages'] = $unseenMsgsCount;
                $chatsData[] = $chatData;
            }

            $finalResult["chats"] = $chatsData;
        } catch (\Exception $e) {
            $finalResult = ['error' => true, 'msg' => $e->getMessage()];
        }

        return new JsonResponse($finalResult);
    }


    /**
     * @Route("api/exportAsXLSForm/{id}", name="exportAsXLSForm")
     */
    public function xlsForm(Request $request, ExportService $exportService, SerializerInterface $serializer): JsonResponse
    {
        $finalResult = [];
        $id = $request->get("id");
//dd($id);
        try {
            $questionnaire = $this->entityManager->getRepository(Questionnaire::class)->find($id);

            if (!$questionnaire) {
                throw new \Exception('Questionnaire not found');
            }


            $serializedData = $exportService->exportDataAsXLSForm($questionnaire);

            $finalResult = ['questionnaire' => json_decode($serializedData), 'xlsFormData' => $serializedData];

        } catch (\Exception $e) {
            $finalResult = ['error' => true, 'msg' => $e->getMessage()];
        }

        return new JsonResponse($finalResult);
    }

    /**
     * @Route("api/getDataPointColumns", name="getDataPointColumns")
     * @throws MappingException
     */
    public function getColumns(): JsonResponse
    {
        $dataPointCols = [];
        $exceptions = ["id", "uploads", "users", "tasks", "history", "createdBy"];
                $columnsName = $this->utils->getDataPointMetadata();
                foreach ($columnsName as $data) {
                    if (!in_array($data, $exceptions, true)) {
                        $dataPointCols[] = $data;
                    }
                }
        return new JsonResponse($dataPointCols);
    }

}