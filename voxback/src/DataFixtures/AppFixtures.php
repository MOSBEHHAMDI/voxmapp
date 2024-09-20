<?php

namespace App\DataFixtures;

use App\Entity\AccessType;
use App\Entity\AutorizationType;
use App\Entity\Chat;
use App\Entity\City;
use App\Entity\Client;
use App\Entity\Country;
use App\Entity\DataPointType;
use App\Entity\District;
use App\Entity\Group;
use App\Entity\Language;
use App\Entity\MediaType;
use App\Entity\Metadata;
use App\Entity\ProblemType;
use App\Entity\ProjectType;
use App\Entity\Province;
use App\Entity\QuestionnaireType;
use App\Entity\QuestionType;
use App\Entity\SourceType;
use App\Entity\Status;
use App\Entity\SubscriptionType;
use App\Entity\TaskType;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    private function exists(ObjectManager $manager, $className, $field, $value)
    {
        return $manager->getRepository($className)->findOneBy([$field => $value]);
    }

    public function load(ObjectManager $manager): void
    {
        $connection = $manager->getConnection();
        if (!$this->exists($manager, AutorizationType::class, "name", "default")) {
            $autorizationType = new AutorizationType();
            $autorizationType->setName("default");
            $autorizationType->setDescription("Default autorization type for test");
            $manager->persist($autorizationType);
        }

        if (!$this->exists($manager, AccessType::class, "name", "default")) {
            $accessType = new AccessType();
            $accessType->setName("default");
            $accessType->setDescription("Default access type for test");
            $manager->persist($accessType);
        }

        if (!($super_admin = $this->exists($manager, Group::class, "label", "Super Admin"))) {
            $super_admin = new Group();
            $super_admin->setLabel("Super Admin");
            $super_admin->setRoles(['ROLE_SUPER_ADMIN']);
            $manager->persist($super_admin);
        }

        if (!($client = $this->exists($manager, Client::class, "name", "Voxmapp"))) {
            $client = new Client();
            $client->setName('Voxmapp');
            $manager->persist($client);
        }

        // Create admin user
        if (!$this->exists($manager, User::class, "userName", "admin")) {
            $admin = new User();
            $admin->setUserName('admin')->setEmail('admin@voxmapp.com');
            $hashedPassword = $this->passwordHasher->hashPassword($admin, "__Voxmapp123");
            $admin->setPassword($hashedPassword);
            $admin->addGroup($super_admin);
            $manager->persist($admin);
            $client->addUser($admin);
            $manager->persist($client);
        }

        // Create test user
        if (!$this->exists($manager, User::class, "userName", "test")) {
            $testUser = new User();
            $testUser->setUserName('test')->setEmail('test@rayensoft.com');
            $hashedPassword = $this->passwordHasher->hashPassword($testUser, "123456");
            $testUser->setPassword($hashedPassword);
            $testUser->addGroup($super_admin); // Assuming the test user also belongs to the same group
            $manager->persist($testUser);
            $client->addUser($testUser);
            $manager->persist($client);
        }

        $languages = ["English" => "En", "Pashto" => "Pa", "Dari" => "Da", "French" => "Fr", "Spanish" => "Es"];
        foreach ($languages as $name => $code) {
            $language = $this->exists($manager, Language::class, "name", $name);
            if (!$language) {
                $language = new Language();
                $language->setName($name);
            }
            $language->setCode($code);
            $manager->persist($language);
        }

        $subscriptionTypes = ['Free users', 'Private users', 'Space hacking users', 'Analysis users', 'Communication aspects users'];
        foreach ($subscriptionTypes as $type) {
            $subscription = $manager->getRepository(SubscriptionType::class)->findOneBy(['name' => $type]);
            if (!$subscription) {
                $SubscriptionType = new SubscriptionType();
                $SubscriptionType->setName($type);
                $manager->persist($SubscriptionType);
            }
        }

        $mediaTypes = ['text', 'image', 'video', 'sound', 'csv', 'excel'];
        foreach ($mediaTypes as $type) {
            $mediaType = $manager->getRepository(MediaType::class)->findOneBy(['label' => $type]);
            if (!$mediaType) {
                $newMediaType = new MediaType();
                $newMediaType->setLabel($type);
                $manager->persist($newMediaType);
            }
        }

        $questionTypes = ['File', 'Choices', 'Matrix', 'Text', 'Slider', 'QRCode', 'FacilityList', 'Date', 'QuestionnaireCrossCutting', 'GeoPoint', 'Table', 'DropDowns', 'RandomProducts', 'ListOfEntries'];
        foreach ($questionTypes as $type) {
            $questionType = $manager->getRepository(QuestionType::class)->findOneBy(['label' => $type]);
            if (!$questionType) {
                $newQuestionType = new QuestionType();
                $newQuestionType->setLabel($type);
                $manager->persist($newQuestionType);
            }
        }

        $questionnaireTypes = ['Asset', 'Wave', 'Baseline'];
        foreach ($questionnaireTypes as $type) {
            $questionnaireType = $manager->getRepository(QuestionnaireType::class)->findOneBy(['label' => $type]);
            if (!$questionnaireType) {
                $newQuestionnnaireType = new QuestionnaireType();
                $newQuestionnnaireType->setLabel($type);
                $manager->persist($newQuestionnnaireType);
            }
        }

        $metadataNames = array("GPS Start" => "gps_start", "GPS Finish" => "gps_finish", "Altitude" => "alt", "Date start questionnaire" => "dsq", "Time start questionnaire" => "tsq", "Time end questionnaire" => "teq", "Date end questionnaire" => "deq", "User name" => "user_name", "Type of synchronization (SMS/WIFI/3G)" => "synch_type", "Date of synchronization" => "synch_date", "Time of synchronization" => "synch_time", "Synchronization reference number" => "ref", "Audio Recording" => "audio_recording",);
        foreach ($metadataNames as $label => $code) {
            $metadata = $manager->getRepository(Metadata::class)->findOneBy(['label' => $label]);
            if (!$metadata) {
                $newMetaData = new Metadata();
                $newMetaData->setLabel($label);
                $newMetaData->setCode($code);
                $manager->persist($newMetaData);
            }
        }

        $projectTypes = ['Data collection projects', 'Analysis projects', 'Localization projects',];
        foreach ($projectTypes as $type) {
            $projectType = $manager->getRepository(ProjectType::class)->findOneBy(['name' => $type]);
            if (!$projectType) {
                $newProjectType = new ProjectType();
                $newProjectType->setName($type);
                $manager->persist($newProjectType);
            }
        }

        $appGroups = [['label' => 'Baseline user', 'roles' => ['ROLE_BASELINE_USER']], ['label' => 'Pre Prod Admin Super Admin', 'roles' => ['ROLE_PRE_PROD_ADMIN_SUPER_ADMIN']], ['label' => 'Admin Access', 'roles' => ['ROLE_ADMIN_ACCESS']], ['label' => 'Health Facility Manager', 'roles' => ['ROLE_HEALTH_FACILITY_MANAGER']], ['label' => 'Facility Creation', 'roles' => ['ROLE_FACILITY_CREATION']], ['label' => 'MOPH Internal monitor team', 'roles' => ['ROLE_MOPH_INTERNAL_MONITOR_TEAM']], ['label' => 'SR Operation Dashboard', 'roles' => ['ROLE_SR_OPERATION_DASHBOARD']],];
        foreach ($appGroups as $group) {
            if (!$this->exists($manager, Group::class, "label", $group['label'])) {
                $newGroup = new Group;
                $newGroup->setLabel($group['label']);
                $newGroup->setRoles($group['roles']);
                $manager->persist($newGroup);
            }
        }

        $accessTypes = [['name' => 'Free Access', 'description' => '5 users'], ['name' => 'Payment Access', 'description' => '$4 per user for 6 - 20 users'], ['name' => 'Payment discount Access', 'description' => '$2 per user for 21 users-100 users'],];
        foreach ($accessTypes as $accessTypeInfo) {
            if (!$this->exists($manager, AccessType::class, "name", $accessTypeInfo['name'])) {
                $newAccessType = new AccessType();
                $newAccessType->setName($accessTypeInfo['name']);
                $newAccessType->setDescription($accessTypeInfo['description']);
                $manager->persist($newAccessType);
            }
        }

        $sourceTypes = ['database', 'questionnaire', 'external'];
        foreach ($sourceTypes as $typeName) {
            if (!$this->exists($manager, SourceType::class, "name", $typeName)) {
                $sourceType = new SourceType();
                $sourceType->setName($typeName);
                $manager->persist($sourceType);
            }
        }

        $problemTypesData = [['name' => 'error', 'priority' => 1, 'description' => 'Description for error type'], ['name' => 'mobile', 'priority' => 2, 'description' => 'Description for mobile type']];
        foreach ($problemTypesData as $data) {
            if (!$this->exists($manager, ProblemType::class, "name", $data['name'])) {
                $problemType = new ProblemType();
                $problemType->setName($data['name']);
                $problemType->setPriority($data['priority']);
                $problemType->setDescription($data['description']);
                $manager->persist($problemType);
            }
        }

        $dataPointStatusesData = ['Active', 'Inactive', 'Pending'];
        foreach ($dataPointStatusesData as $data) {
            if (!$this->exists($manager, Status::class, "name", $data)) {
                $dataPointStatus = new Status();
                $dataPointStatus->setName($data);
                $manager->persist($dataPointStatus);
            }
        }

        $dataPointTypes = ['Health Facility', 'Monument', 'School', 'Warehouse', 'Judiciary'];
        foreach ($dataPointTypes as $type) {
            if (!$this->exists($manager, DataPointType::class, "name", $type)) {
                $dataPointType = new DataPointType();
                $dataPointType->setName($type);
                $manager->persist($dataPointType);
            }
        }

        $chatsData = [['label' => 'General Chat',], ['label' => 'Support Chat',], ['label' => 'Random Chat',],];
        foreach ($chatsData as $data) {
            if (!$this->exists($manager, Chat::class, "label", $data['label'])) {
                $chat = new Chat();
                $chat->setLabel($data['label']);
                $manager->persist($chat);
            }
        }

        $taskTypes = ['Data Entry', 'Search and Filter', 'Task Management', 'File Management', 'Data point Management', 'Payment Processing', 'Surveys', 'Analytics and Reporting', 'Settings and Preferences',
        ];
        foreach ($taskTypes as $type) {
            if (!$this->exists($manager, TaskType::class, "name", $type)) {
                $newTaskType = new TaskType();
                $newTaskType->setName($type);
                $manager->persist($newTaskType);
            }
        }

        //For test purposes
        $country = $manager->getRepository(Country::class)->findOneBy(['name' => 'Tunisia']);
        if (!$country) {
            $country = new Country();
            $country->setName('Tunisia');
            $manager->persist($country);
            $province = new Province();
            $province->setCountry($country);
            $province->setName('Zaghouan');
            $manager->persist($province);
            $district = new District();
            $district->setProvince($province);
            $district->setName('Zaghouan');
            $manager->persist($district);
            $city = new City();
            $city->setName('Zriba Hammam');
            $city->setDistrict($district);
            $manager->persist($city);
        }
        $manager->flush();
        $stmt = $connection->prepare("CREATE EXTENSION IF NOT EXISTS citext;");
        $stmt->execute();
    }
}
