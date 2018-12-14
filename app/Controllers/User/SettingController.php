<?php
/**
 * Created by PhpStorm.
 * User: Faisal Alam
 * Date: 13-12-2018
 * Time: 14:04
 */

namespace App\Controllers\User;


use App\Controllers\Controller;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Capsule\Manager as DB;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class SettingController extends Controller
{
    public function index(Request $request, Response $response)
    {
        return $this->view->render($response, 'user/settings.twig');
    }

    public function update(Request $request, Response $response)
    {
        try {
            $userId = $this->auth->user()->id;

            $query = $request->getParsedBody();
            $questions = $query['data']['questions'];
            $twoStep = $query['data']['two_step'];

            if (! $twoStep or gettype($twoStep) !== "string" or ! in_array($twoStep, ['on', 'off'])) {
                throw new \Exception("Invalid request.");
            }

            if ($twoStep === 'off') {
                DB::table('user_questions')->where('user_id', $userId)->delete();
                User::where('id', $userId)->update(['two_step_enabled' => 0]);

                return $response->withJSON(json_decode(json_encode([
                    'status' => 'success',
                    'message' => 'Successfully updated.',
                ])));
            }

            if (! $questions or gettype($questions) !== "array") {
                throw new \Exception("Invalid request.");
            }

            $dataToInsert = array();
            foreach ($questions as $key => $value) {
                if (! isset($value['id'], $value['answer'])) {
                    throw new \Exception("Invalid request");
                }

                $dataToInsert[] = [
                    'user_id' => $userId,
                    'question_id' => $value['id'],
                    'answer' => $value['answer']
                ];
            }

            DB::transaction(function () use($userId, $dataToInsert) {
                try {
                    DB::table('user_questions')->where('user_id', $userId)->delete();
                    DB::table('user_questions')->insert($dataToInsert);
                    User::where('id', $userId)->update(['two_step_enabled' => 1]);
                } catch (\Exception $exception) {
                    throw new \Exception($exception->getMessage());
                }
            });
        } catch (\Illuminate\Database\QueryException $exception) {
            throw new \Exception("Query Exception: " . $exception->getMessage());
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
            die();
            $exceptionMessage = $exception->getMessage();
            // TODO: Log exception

            return $response->withJSON(json_decode(json_encode([
                'status' => 'error',
                'message' => 'Something went wrong.',
            ])));
        }

        return $response->withJSON(json_decode(json_encode([
            'status' => 'success',
            'message' => 'Successfully updated.',
        ])));
    }

    public function getQuestions(Request $request, Response $response)
    {
        $user = $this->auth->user();
        $userTwoStep = $user->two_step_enabled;
        $userId = $user->id;

        $questions = Question::get()->toArray();

        $json = array(
            'status' => 'success', // success | error
            'questions' => $questions,
            'selected' => [],
            'error' => '',
        );

        if ($userTwoStep === 1) {
            $userQuestions = User::find($userId)->questions()->with('question')->get()->toArray();

            $selected = array();
            foreach ($userQuestions as $key => $value) {
                $selected[] = [
                    "id" => $value['question']['id'],
                    "question" => $value['question']['description'],
                    "answer" => $value['answer']
                ];
            }

            $json['selected'] = $selected;
        }

        return $response->withJSON(json_decode(json_encode($json)));
    }
}