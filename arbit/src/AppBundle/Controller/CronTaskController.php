<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Crontask;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CronTaskController extends Controller
{
    /**
     * @Route("addTask")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function newCronTask(Request $request)
    {
        try {
            $enabled = $request->get("enabled");
            $task = $request->get("task");
            $period = $request->get("period");

            $new_task = new Crontask();

            $new_task->setEnabled($enabled);
            $new_task->setPeriod($period);
            $new_task->setTask($task);
            $new_task->setLastStart(time());

            $em = $this
                ->getDoctrine()
                ->getManager();

            $em->persist($new_task);
            $em->flush();

            return new JsonResponse(true);
        } catch (\Exception $e) {
            $err = array(
                $e->getMessage(),
                $e->getLine(),
                $e->getFile(),
            );
            return new JsonResponse($err);
        }
    }

    /**
     * @Route("startStopTask")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function startStopTask(Request $request)
    {
        try {
            $task_id = $request->get("id");

            $task = $this
                ->getDoctrine()
                ->getRepository("AppBundle:Crontask")
                ->find($task_id);
            $state = $task->getEnabled();
            $task->setEnabled(!$state);

            $em = $this
                ->getDoctrine()
                ->getManager();
            $em->persist($task);
            $em->flush();

            return new JsonResponse(true);
        } catch (\Exception $e) {
            $err = array(
                $e->getMessage(),
                $e->getLine(),
                $e->getFile(),
            );
            return new JsonResponse($err);
        }
    }

    /**
     * @Route("deleteTask")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function deleteTask(Request $request)
    {
        try {
            $task_id = $request->get("id");

            $task = $this
                ->getDoctrine()
                ->getRepository("AppBundle:Crontask")
                ->find($task_id);
            $em = $this
                ->getDoctrine()
                ->getManager();
            $em->remove($task);
            $em->flush();

            return new JsonResponse(true);
        } catch (\Exception $e) {
            $err = array(
                $e->getMessage(),
                $e->getLine(),
                $e->getFile(),
            );
            return new JsonResponse($err);
        }
    }

    /**
     * @Route("viewTasks")
     *
     * @return JsonResponse
     */
    public function viewTasks()
    {
        try {
            $em = $this
                ->getDoctrine()
                ->getManager();
            $qb = $em->createQueryBuilder('t');
            $qb->select('t.id, t.enabled, t.task, t.period, t.lastStart')
                ->from('AppBundle:Crontask', 't');
            $query = $qb->getQuery();
            $tasks = $query->getArrayResult();

            return new JsonResponse($tasks);
        } catch (\Exception $e) {
            $err = array(
                $e->getMessage(),
                $e->getLine(),
                $e->getFile(),
            );
            return new JsonResponse($err);
        }
    }
}
