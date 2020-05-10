<?php

namespace App\DataFixtures;

use App\Entity\Appointment;
use App\Entity\Arrival;
use App\Entity\Auto;
use App\Entity\Carry;
use App\Entity\Courier;
use App\Entity\Delivery;
use App\Entity\DeliveryType;
use App\Entity\Employee;
use App\Entity\Item;
use App\Entity\ItemCategory;
use App\Entity\Payments;
use App\Entity\Receiver;
use App\Entity\StatusCodes;
use App\Entity\StatusHistory;
use App\Entity\User;
use App\Entity\Vendor;
use App\Entity\Warehouse;
use App\Entity\WorkShift;
use App\Service\ClientOperationService;
use App\Service\DatabaseService;
use App\Service\RandomCodeGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $db;
    private $passwordEncoder;
    private $entityQuantity = [
        'auto' => 5,
        'employee' => 7,
        'courier' => 8,
        'vendor' => 5,
        'client' => 80,
        'warehouses' => 4,
        'appointment' => 5,
        'delivery_types' => 5,
        'items_cat' => 12,
        'workshifts' => 10,
        'deliveries' => 100,
        'items' => 300,
        'carry_quantity' => 50,
        'arrival_coef' => 5,
        'statuses' => 5,
        'status_coef' => 6
    ];
    private $names = ['John', 'Steven', 'Tom', 'Ann', 'Marie', 'Bill'];
    private $surnames = ['Stevenson', 'White', 'Brave', 'Davidson', 'Johnson'];
    private $countries = ['Russia', 'Spain', 'USA', 'France', 'Italy', 'Japan', 'PRC'];

    private function randval($title) {
        return $title . random_int(1, 100);
    }
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, DatabaseService $db)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->db = $db;
    }
    public function load(ObjectManager $manager)
    {

        // adding auto
        $autos = [];
        for ($i = 0; $i != $this->entityQuantity['auto']; ++$i)
        {
            $auto = new Auto();
            $auto->setNumber($this->getRandomString(8));
            $models = ['Isuzu', 'Fiat', 'Mercedes-Benz', 'FAW'];
            $auto->setModel($models[array_rand($models)]);
            $cats = ['A', 'B', 'C', 'D', 'M', 'A1', 'B1', 'C1', 'C1E', 'D1', 'D1E', 'BE', 'CE', 'DE'];
            $auto->setRequiredDriveCat($cats[array_rand($cats)]);
            $auto->setCapacity(random_int(1000, 10000));
            $auto->setIsFunctional(array_rand([true, false]) == 1);
            $autos[] = $auto;
        }
        $this->db->insertMultiply($autos);

        // adding warehouses
        $warehouses = [];
        for ($i=0; $i != $this->entityQuantity['warehouses']; ++$i)
        {
            $warehouse = new Warehouse();
            $warehouse->setCountry($this->countries[array_rand($this->countries)]);
            $warehouse->setCity($this->randval('city'));
            $warehouse->setRegion($this->randval('region'));
            $warehouse->setStreet($this->randval('street'));
            $warehouse->setBuilding($this->randval(''));
            $warehouse->setMaxContain(random_int(200, 500));
            $warehouses[] = $warehouse;
        }
        $this->db->insertMultiply($warehouses);

        // adding appointments
        $appointments = [];
        for ($i=0; $i != $this->entityQuantity['appointment']; ++$i)
        {
            $appmnt = new Appointment();
            $appmnt->setAppointmentName($this->randval('appointment_'));
            $appmnt->setSalary(random_int(100, 5000));
            $appointments[] = $appmnt;
        }
        $this->db->insertMultiply($appointments);

        // adding users

        $employeesUsers = $this->createUsersWithRoles($this->entityQuantity['employee'], [User::WAREHOUSE_EMPLOYEE]);
        $employeeEntities = $this->createEmployees($employeesUsers, $appointments, $warehouses);
        $this->db->insertMultiply($employeesUsers);
        $this->db->insertMultiply($employeeEntities);

        $courierUsers = $this->createUsersWithRoles($this->entityQuantity['courier'], [User::WAREHOUSE_EMPLOYEE, User::COURIER]);
        $couriersEmployees = $this->createEmployees($courierUsers, $appointments, $warehouses);
        $couriersEntities = $this->createCouriers($couriersEmployees);
        $this->db->insertMultiply($courierUsers);
        $this->db->insertMultiply($couriersEmployees);
        $this->db->insertMultiply($couriersEntities);

        $vendorUsers = $this->createUsersWithRoles($this->entityQuantity['vendor'], [User::VENDOR]);
        $vendorEntities = $this->createVendors($vendorUsers);
        $this->db->insertMultiply($vendorUsers);
        $this->db->insertMultiply($vendorEntities);

        $clientUsers = $this->createUsersWithRoles($this->entityQuantity['client'], [User::CLIENT]);
        $clientsEntities = $this->createClients($clientUsers);
        $this->db->insertMultiply($clientUsers);
        $this->db->insertMultiply($clientsEntities);

        // adding workshifts
        $workshifts = [];
        for($i=0; $i != $this->entityQuantity['workshifts']; ++$i)
        {
            $workshift = new WorkShift();
            $workshift->setAutoNum($autos[array_rand($autos)]);
            $workshift->setCourier($couriersEntities[array_rand($couriersEntities)]);
            $workshift->setActive(random_int(1, 100) % 3 != 0);
            if ($workshift->getActive())
            {
                $workshift->setEndTime((new \DateTime())->setTime(mt_rand(0, 23), mt_rand(0, 59)));
                $workshift->setStartTime((new \DateTime())->setTime(mt_rand(0, 23), mt_rand(0, 59)));
            }
            $workshifts[] = $workshift;
        }
        $this->db->insertMultiply($workshifts);

        // adding delivery types
        $deliveryTypes = [];
        for($i=0; $i != $this->entityQuantity['delivery_types']; ++$i)
        {
            $deliveryType = new DeliveryType();
            $deliveryType->setName($this->randval('type_'));
            $deliveryType->setMaxDistance(random_int(100, 10000));
            $deliveryType->setPrice(random_int(5, 5000));
            $deliveryTypes[] = $deliveryType;
        }
        $this->db->insertMultiply($deliveryTypes);

        // adding items categories
        $itemsCategories = [];
        for ($i=0; $i!=$this->entityQuantity['items_cat']; ++$i)
        {
            $itemsCategory = new ItemCategory();
            $itemsCategory->setName($this->randval('item_category_'));
            $isHasCategory = array_rand([true, false]);
            if ($isHasCategory && !empty($itemsCategories))
            {
                $itemsCategory->setParentIdCat($itemsCategories[array_rand($itemsCategories)]);
            }
            $itemsCategory->setExplosive(array_rand([true, false]) == 1);
            $itemsCategory->setFireDanger(array_rand([true, false]) == 1);
            $itemsCategory->setToxic(array_rand([true, false]) == 1);
            $itemsCategories[] = $itemsCategory;
        }
        $this->db->insertMultiply($itemsCategories);

        // adding deliveries
        $deliveries = [];
        for ($i=0; $i!=$this->entityQuantity['deliveries']; ++$i)
        {
            $delivery = new Delivery();
            $delivery->setType($deliveryTypes[array_rand($deliveryTypes)]);
            $delivery->setDepBuilding(random_int(1, 200));
            $delivery->setDepCity($this->randval('city_'));
            $delivery->setDepCountry($this->randval('country_'));
            $delivery->setDepFlat(random_int(1, 200));
            $delivery->setDepPostcode($this->getRandomNumericString(6));
            $delivery->setDepStreet($this->randval('street_'));
            $delivery->setDestCity($this->randval('city_'));
            $delivery->setDestCountry($this->randval('country_'));
            $delivery->setDestFlat(random_int(1, 200));
            $delivery->setDestHouse(random_int(1, 200));
            $delivery->setDestPostcode($this->getRandomNumericString(6));
            $delivery->setDestStreet($this->randval('street_'));
            $delivery->setReceiver($clientsEntities[array_rand($clientsEntities)]);
            $delivery->setRouteLength(random_int(1, $delivery->getType()->getMaxDistance()));
            $delivery->setVendor($vendorEntities[array_rand($vendorEntities)]);
            $deliveries[] = $delivery;
        }
        $this->db->insertMultiply($deliveries);

        // adding items
        $items = [];
        for ($i=0; $i!=$this->entityQuantity['items']; ++$i)
        {
            $item = new Item();
            $item->setName($this->randval('item'));
            $item->setCategory($itemsCategories[array_rand($itemsCategories)]);
            $item->setWeight(random_int(1, 1000) + 0.001*random_int(1, 999));
            $item->setDelivery($deliveries[array_rand($deliveries)]);
            $items[] = $item;
        }
        $this->db->insertMultiply($items);

        // adding carries
        $carries = [];
        foreach ($workshifts as $workshift)
        {
            $addedDeliveries = [];
            for($i=0; $i!=$this->entityQuantity['carry_quantity']; ++$i)
            {
                $carry = new Carry();
                do {
                    $choosedDelivery = $deliveries[array_rand($deliveries)];
                }
                while (in_array($choosedDelivery, $addedDeliveries));
                $addedDeliveries[] = $choosedDelivery;
                $carry->setDelivery($choosedDelivery);
                $carry->setFromWarehouse($warehouses[array_rand($warehouses)]);
                do
                {
                    $carry->setToWarehouse($warehouses[array_rand($warehouses)]);
                }
                while ($carry->getFromWarehouse()->getId() !== $carry->getToWarehouse()->getId());
                $carry->setWorkshift($workshift);
                $carries[] = $carry;
            }
        }
        $this->db->insertMultiply($carries);

        // adding arrivals
        // every delivery has 1..arrival_coef arrivals
        // last arrival has nullable departure date, its mean this delivery is situated on this warehouse
        $arrivals = [];
        foreach ($deliveries as $transferredDelivery)
        {
            $arrivalsQuan = random_int(1, $this->entityQuantity['arrival_coef']);
            for($i=0; $i!=$arrivalsQuan; ++$i)
            {
                $arrival = new Arrival();
                $arrival->setDelivery($transferredDelivery);
                $arrival->setWarehouse($warehouses[array_rand($warehouses)]);
                $y = random_int(2017, 2020);
                $m = random_int(1, 12);
                $d = random_int(1, 27);
                $arrival->setArrivalDate(new \DateTime($y . "-" . $m . "-" . $d));
                if ($i != $arrivalsQuan - 1)
                {
                    $arrival->setDepartureDate(new \DateTime($y . "-" . $m . "-" . strval($d + 1)));
                }
                $arrival->setShelf(random_int(1,1000));
                $arrival->setStorage(random_int(1, 1000));
                $arrival->setPlace(random_int(1, 1000000));
                $arrivals[] = $arrival;
            }
        }
        $this->db->insertMultiply($arrivals);

        // adding payments
        foreach ($deliveries as $delivery)
        {
            $payment = new Payments();
            $payment->setDelivery($delivery);
            $payment->setStatus(array_rand([true, false]) == 1);
            $payment->setSum($delivery->getRouteLength() * $delivery->getType()->getPrice());
            $payment->setUip($this->getRandomString(25));
            $this->db->insert($payment);
        }

        // adding statuses
        $statuses = [];
        for($i=0; $i!=$this->entityQuantity['statuses']; ++$i)
        {
            $status = new StatusCodes();
            $status->setScode($i);
            $status->setTitle($this->randval('status_'));
            $statuses[] = $status;
        }
        $this->db->insertMultiply($statuses);

        // adding statuses history
        foreach ($deliveries as $delivery)
        {
            $statusesQuan = random_int(1, $this->entityQuantity['status_coef']);
            for ($i=0; $i!= $statusesQuan; ++$i)
            {
                $statusNote = new StatusHistory();
                $statusNote->setDelivery($delivery);
                $statusNote->setStatusCode($statuses[array_rand($statuses)]);
                $statusNote->setStatusComment($this->randval('status_text_'));
                $statusNote->setStatusSetDate(new \DateTime());
                $this->db->insert($statusNote);
            }
        }

    }

    private function createUsersWithRoles(int $quan, array $roles): array
    {
        $users = [];
        for ($i = 0; $i != $quan; ++$i)
        {
            $user = new User();
            $hostnames = ['gmail.com', 'bing.com', 'yahoo.com'];
            $user->setEmail($this->getRandomString(8) . '@' . $hostnames[array_rand($hostnames)]);
            $user->setUsername($this->getRandomString(6));
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'default_password'));
            foreach ($roles as $role)
            {
                $user->addRole($role);
            }
            $users[] = $user;
        }
        return $users;
    }

    private function createEmployees(array $empUsers, array $appointments, array $warehouses): array
    {
        $employees = [];
        foreach ($empUsers as $user)
        {
            $employee = new Employee();
            $employee->setPassport($this->getRandomNumericString(10));
            $employee->setName($this->names[array_rand($this->names)]);
            $employee->setSurname($this->surnames[array_rand($this->surnames)]);
            $employee->setBirthday(new \DateTime(
                random_int(1, 28) . '-' . random_int(1, 12) . '-' . random_int(1970, 1999) ));
            $employee->setOmsNum($this->getRandomString(16));
            $employee->setInn($this->getRandomString(12));
            $employee->setUserId($user);
            $employee->setAppointment($appointments[array_rand($appointments)]);
            $employee->setWarehouse($warehouses[array_rand($warehouses)]);
            $employees[] = $employee;
        }
        return $employees;
    }

    private function createClients(array $clientUsers): array
    {
        $clients = [];
        foreach ($clientUsers as $user)
        {
            $client = new Receiver();
            $client->setName($this->names[array_rand($this->names)]);
            $client->setSurname($this->surnames[array_rand($this->surnames)]);
            $client->setPassport($this->getRandomString(10));
            $client->setPhone($this->getRandomString(11));
            $client->setUserId($user);
            $clients[] = $client;
        }
        return $clients;
    }

    private function createVendors(array $vendorUsers): array
    {
        $vendors = [];
        foreach ($vendorUsers as $vendorUser)
        {
            $vendor = new Vendor();
            $vendor->setOgrn($this->getRandomNumericString(13));
            $vendor->setName($this->randval('vendor'));
            $vendor->setCorAcc($this->getRandomNumericString(20));
            $vendor->setBik($this->getRandomNumericString(9));
            $vendor->setBankCity($this->randval('city'));
            $vendor->setKpp($this->getRandomNumericString(9));
            $vendor->setInn($this->getRandomNumericString(12));
            $vendor->setUserId($vendorUser);
            $vendors[] = $vendor;
        }
        return $vendors;
    }

    private function createCouriers(array $courierEmployees): array
    {
        $driveCats = ['A', 'B', 'C', 'D', 'M', 'A1', 'B1', 'C1', 'C1E', 'D1', 'D1E', 'BE', 'CE', 'DE'];
        $couriers = [];
        foreach ($courierEmployees as $employee)
        {
            $courier = new Courier();
            $courier->setEmpId($employee);
            $courier->setDriveCat([$driveCats[array_rand($driveCats)]]);
            $couriers[] = $courier;
        }
        return $couriers;
    }

    private function getRandomString(int $length)
    {
        $str = (new RandomCodeGenerator())->generate($length);
        return substr($str, 0, $length);
    }

    private function getRandomNumericString(int $length)
    {
        $digits = [1,2,3,4,5,6,7,8,9,0];
        $randStr = '';
        for ($i=0; $i!=$length; ++$i) {
            $randStr .= $digits[array_rand($digits)];
        }
        return $randStr;
    }
}
