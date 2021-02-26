<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiConnections;
use App\Models\NotificationQuestionsAns;
use App\Models\User;
use App\Models\UserQuestionsAns;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Twilio\Rest\Client;

class WhatsappController extends Controller
{

    public function listenToReplies(Request $request)
    {

        $from = $request->input('From');
        $body = $request->input('Body');

        try {
            $messages = $this->get_question_for_user($body, $from);
            if ($messages) {
                $this->sendWhatsAppMessage($messages, $from);
            } else {
                $msg = 'Thanks for connecting with us Right now not any quetions for you, Please Contact admin for more details.';
                $this->sendWhatsAppMessage($msg, $from);
            }

        } catch (RequestException $th) {
            $response = json_decode($th->getResponse()->getBody());
            $error_msg = 'Opps ! Something Wrong Please contract admin';
            $this->sendWhatsAppMessage($error_msg, $from);
        }
        return;
    }

    /**
     * Sends a WhatsApp message  to user using
     * @param string $message Body of sms
     * @param string $recipient Number of recipient
     */
    public function sendWhatsAppMessage(string $message, string $recipient)
    {

        $phone = explode(':', $recipient);
        $phone = Str::substr($phone[1], -10);
        $user = User::whereIn('role', ['2', '3'])->where('phone_number', $phone)->first();

        if ($user) {

            $connection = ApiConnections::where('api_type', 3)->where('api_provider', 1)->first();

            $twilio_whatsapp_number = '+14155238886';

            if ($connection) {
                $account_sid = $connection->api_key;
                $auth_token = $connection->api_secret;
            }

            $user_id = Auth::id();
            $userinfo = User::find($user_id);

            $client = new Client($account_sid, $auth_token);
            return $client->messages->create($recipient, array('from' => "whatsapp:$twilio_whatsapp_number", 'body' => $message));
        }
    }

    public function get_question_for_user($message, $recipient)
    {

        $phone = explode(':', $recipient);
        $phone = Str::substr($phone[1], -10);

        $user = User::whereIn('role', ['2', '3'])->where('phone_number', $phone)->first();

        if ($user->whatsapp_subscription_status == 0) {
            $user->whatsapp_subscription_status = 1;
            $user->save();
        }

        if ($message == 'Hi' or $message == 'hi' or $message != '') {

            $next = $this->store_user_data($message, $user);

            return $this->send_question($next, $user);

        } else {
            return "Please Enter correct Answer";
        }
    }

    public function store_user_data($message, $user)
    {

        $columns = ['user_questions_ans.id', 'notification_questions.ans_type', 'user_questions_ans.question_status', 'user_questions_ans.user_id', 'user_questions_ans.answer_status', 'user_questions_ans.question_id'];

        $userans = UserQuestionsAns::select($columns)->leftjoin('notification_questions', 'notification_questions.id', 'user_questions_ans.question_id')->where([['user_questions_ans.answer_status', '=', '0'], ['user_questions_ans.user_id', '=', $user->id], ['user_questions_ans.question_status', '=', '1']])->orderBy('user_questions_ans.question_id', 'DESC')->first();

        if (isset($userans) && $userans != '') {

            if ($userans->ans_type == 1 && is_numeric($message)) {

                $userans->answer = ($message - 1);
                $userans->answer_status = 1;
                $userans->question_status = 2;
                $userans->save();

                return true;
            } elseif ($userans->ans_type == 2) {

                $righans = $this->check_comma_format($message);
                if ($righans == '1') {

                    $userans->answer = ($message - 1);
                    $userans->answer_status = 1;
                    $userans->question_status = 2;
                    $userans->save();

                    return true;
                }
            } elseif ($userans->ans_type == 3 && $message != '') {

                $userans->answer = $message;
                $userans->answer_status = 1;
                $userans->question_status = 2;
                $userans->save();

                return true;
            } else {
                return false;
            }
        }

    }

    public function check_comma_format($messages)
    {
        $re = '/^\d+(?:,\d+)*$/';
        $str = $messages;

        if (preg_match($re, $str)) {
            return 1;

        } else {
            return 0;
        }

    }

    public function send_question($next, $user)
    {

        $firstquestion = UserQuestionsAns::leftjoin('notification_questions', 'notification_questions.id', 'user_questions_ans.question_id', )->where([
            ['user_questions_ans.sent_status', '=', '1'],
            ['user_questions_ans.answer_status', '=', '0'],
            ['user_questions_ans.user_id', '=', $user->id]])->orderBy('user_questions_ans.question_id', 'DESC')->first();

        if ($firstquestion != '') {

            $question = '';

            $NotificationQuestionsAns = NotificationQuestionsAns::where('question_id', $firstquestion['question_id'])->get();

            $answer = '';
            foreach ($NotificationQuestionsAns as $key => $value) {
                $no = $key + 1;
                if ($firstquestion['ans_type'] == 1) {
                    $answer .= "\n *$no:* $value->answer\n";
                } else if ($firstquestion['ans_type'] == 2) {
                    $answer .= "\n *$no:* $value->answer\n";
                }

            }

            if ($firstquestion['ans_type'] == 1) {

                $answer .= "\n *Tip* : You can Type Any One Option for Answer";

            } else if ($firstquestion['ans_type'] == 2) {
                $answer .= "\n *Tip* : You can Type comma seprated Option Number for Answer";

            } else if ($firstquestion['ans_type'] == 3) {
                $answer .= "\n *Tip* : You Can Type Anything for give Answer";
            }

            $question = ucfirst($firstquestion['question']) . "\n" . $answer;

            $question_info = UserQuestionsAns::where([['question_id', '=', $firstquestion['question_id']], ['user_id', '=', $user->id]])->first();
            if ($question_info) {
                $question_info->question_status = 1;
                $question_info->save();
            }

            return $question;

        }

    }

}
