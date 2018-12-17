<?php
/**
 * Created by PhpStorm.
 * User: Faisal Alam
 * Date: 16-12-2018
 * Time: 01:11
 */

namespace App\Controllers\User;


use App\Controllers\Controller;
use App\Models\User;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class TwoStepController extends Controller
{
    public function index(Request $request, Response $response)
    {
        $userQuestions = User::find($_SESSION['temp']['user_id'])
            ->questions()->with('question')
            ->inRandomOrder()
            ->limit(4)
            ->get()->toArray();

        $questions = array();
        if ($userQuestions && gettype($userQuestions) === 'array') {
            foreach ($userQuestions as $key => $value) {
                $questions['data'][] = [
                    'id' => $value['question']['id'],
                    'description' => $value['question']['description'],
                    'answer' => $value['answer'],
                ];
            }

            $_SESSION['temp']['two_factor'] = $questions['data'];

            return $this->view->render($response, '/auth/two-step.twig', $questions);
        }

        unset($_SESSION['temp']);
        return $response->withStatus(404, "Page not found.");
    }

    public function verify(Request $request, Response $response)
    {
        if ($_SESSION['temp']['attempt'] === 2) {
            $u = User::where('id', $_SESSION['temp']['user_id'])->update(['is_blocked' => 1]);
            unset($_SESSION['temp']);
            $_SESSION['errors'] = ['email' => array("Your account has been temporarily locked.")];
            return $response->withRedirect($this->router->pathFor('auth.login'));
        }

        try {
            $userRequest = $request->getParsedBody();

            if (! isset($userRequest['answer'])) {
                throw new \Exception("Please provide all the required inputs.");
            }

            $answers = $userRequest['answer'];

            foreach ($_SESSION['temp']['two_factor'] as $key => $value) {

                if (! isset($answers[$value['id']])) {
                    throw new \Exception("Please provide all the required inputs.");
                }

                $storedAnswer = $value['answer'];
                $userAnswer = $answers[$value['id']];

                if (strcasecmp($storedAnswer, $userAnswer)) {
                    $_SESSION['temp']['attempt'] += 1;
                    throw new \Exception("Answers don't match our record.");
                }
            }

            $this->auth->authorize($_SESSION['temp']['user_id']);
            unset($_SESSION['temp']);
        } catch (\Exception $exception) {
            $_SESSION['errors'] = ['two_factor' => array($exception->getMessage())];
            return $response->withRedirect($this->router->pathFor('two_factor_step'));
        }

        return $response->withRedirect($this->router->pathFor('home'));
    }
}