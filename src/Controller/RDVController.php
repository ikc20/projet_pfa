<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Entity\Patient;
use App\Entity\TypeRdv;
use App\Repository\AppointmentRepository;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class RDVController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/rdv', name: 'rdv_index')]
        public
        function index(SessionInterface $session, AppointmentRepository $appointmentRepository): Response
        {

            if ($session->has('patient_id')) {

                $patientId = $session->get('patient_id');
                $patientId = $session->get('patient_id');
                $appointments = $this->entityManager->getRepository(Appointment::class)->findBy(['Patient' => $patientId]);
                //dd($appointments);
                return $this->render('rdv/index.html.twig', [
                    'appointments' => $appointments,
                ]);
            }

            return $this->render('Patient/login.html.twig');
        }

    #[Route('/rdv/Add', name: 'rdvCreate')]
    public
    function AddRdvForm(SessionInterface $session): Response
    {

        if ($session->has('patient_id')) {
         return $this->render('rdv/create.html.twig');
        }

        return $this->render('Patient/login.html.twig');
    }
    #[Route('/rdv/AddPost', name: 'PrendreRdv',methods: "POST")]
    public function PrendreRdv(SessionInterface $session,Request $request): Response
    {
        //dd($request);
        if ($session->has('patient_id')) {
           // dd($request);
            $Appointment = new Appointment();
            $createdAt = new \DateTimeImmutable(($request->request->get("Date")));
            $Appointment->setCreatedAt($createdAt);
            $Appointment->setHeureDebut($createdAt);
            $Appointment->setHeureFin($createdAt);
            $entityManage=$this->entityManager;
            $TypeRdv=$entityManage->getRepository(TypeRdv::class)->find(1);
            //  $createdAt = new DateTimeImmutable(($request->request->get("Date")));
            //!!!!!Pour le champs de CreatedAt par defaut va prendre la date actuel;
            $dateTime = new \DateTime();  // Obtenir la date et l'heure actuelles
            $dateTimeImmutable = \DateTimeImmutable::createFromMutable($dateTime);  // Créer un objet DateTimeImmutable à partir de DateTime

            $Appointment->setCreatedAt($dateTimeImmutable);
            $Appointment->setHeureDebut(new \DateTimeImmutable(($request->request->get("Date"))));
            $Appointment->setHeureFin(new \DateTimeImmutable(($request->request->get("Date"))));

            //Voous pouvez creer des attribut de type int pour le foreign key et affecter la valeur diirectement  ce sont des attribut chadeau
            //Type Rdv
            $TypeRdv=$entityManage->getRepository(TypeRdv::class)->find($request->request->get("typeRdv"));
            $Appointment->setTypeRdv($TypeRdv);

            //Doctor
            $DoctorId=$session->get('Doctor');
            $Doctor=$entityManage->getRepository(Doctor::class)->find($DoctorId);
            $Appointment->setDoctor($Doctor);

            //Patient
            $patientId = $session->get('patient_id');
            $Patient=$entityManage->getRepository(Patient::class)->find($patientId);
            $Appointment->setPatient($Patient);
             //dd($Appointment);
            if($Appointment){
                $entityManage->persist($Appointment);
                $entityManage->flush();
                return $this->redirectToRoute('rdv_index');
            }
        }
        return $this->redirectToRoute('rdv_index');
    }

    #[Route('/rdv/delete/{id}', name: 'rdvDelete')]
    public function deleteRdv($id)
    {
        $entityManager=$this->entityManager;
        $appointment = $entityManager->getRepository(Appointment::class)->find($id);

        if (!$appointment) {
            throw $this->createNotFoundException('Appointment not found');
        }

        $entityManager->remove($appointment);
        $entityManager->flush();
        return $this->redirectToRoute('rdv_index');

    }
    #[Route('/rdv/update/{id}', name: 'rdvupdate')]
<<<<<<< HEAD
<<<<<<< HEAD

    public function updateRdvForm($id,SessionInterface $session): Response
=======
    public
    function updateRdvForm($id,SessionInterface $session): Response
>>>>>>> parent of 0521d96 (Merge branch 'master' of https://github.com/ikc20/projet_pfa)
=======
    public
    function updateRdvForm($id,SessionInterface $session): Response
>>>>>>> parent of 0521d96 (Merge branch 'master' of https://github.com/ikc20/projet_pfa)
    {

        if ($session->has('patient_id')) {
            $entityManager = $this->entityManager;
            $appointment = $entityManager->getRepository(Appointment::class)->find($id);
            return $this->render('rdv/update.html.twig', [
                'appointment' => $appointment,
            ]);
        }

        return $this->render('Patient/login.html.twig');
    }
    #[Route('/rdv/AddPut/{id}', name: 'PrendreRdvUpdate',methods: "POST")]
    public function PrendreRdvupdate($id, SessionInterface $session,Request $request): Response
    {
        //dd($request);
        if ($session->has('patient_id')) {
            // dd($request);
            $entityManager = $this->entityManager;
            $Appointment = $entityManager->getRepository(Appointment::class)->find($id);
            //dd($Appointment);
            if($Appointment){
                $createdAt = new \DateTimeImmutable(($request->request->get("Date")));
                $Appointment->setCreatedAt($createdAt);
                $entityManager->persist($Appointment);
                $entityManager->flush();
                return $this->redirectToRoute('rdv_index');
            }
        }
        return $this->redirectToRoute('rdv_index');
    }



}
