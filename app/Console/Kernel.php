<?php

namespace App\Console;

use App\Helpers\ImageHelper;
use App\Mail\NotifyMail;
use App\Models\NotificationQuestions;
use App\Models\User;
use App\Models\UserQuestionsAns;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $questionary = NotificationQuestions::get();

        if ($questionary) {
            foreach ($questionary as $key => $value) {

                if ($value && $value->repeat_value == 'day') {
                    $schedule->call(function () {
                        $this->question_store_for_user($value);
                    })->daily();
                } else if ($value && $value->repeat_value == 'week') {
                    $schedule->call(function () {
                        $this->question_store_for_user($value);
                    })->weeklyOn(1, '8:00');
                } else if ($value && $value->repeat_value == 'month') {
                    $schedule->call(function () {
                        $this->question_store_for_user($value);
                    })->monthlyOn(4, '15:00');
                }
            }

            $scronjob = User::where('role', '1')->first();
            if ($scronjob->reminder == 1) {

                $option = explode('_', $scronjob->repeat_option);

                if ($scronjob->repeat_value == 'day') {
                    $schedule->call(function () {
                        $this->send_whatsapp_subscription_notification();
                    })->dailyAt('13:00');
                } else if ($scronjob->repeat_value == 'week') {

                    $weekInYearNumber = (int) date('W');
                    $weekDayNumber = (int) date('w');
                    if ($weekInYearNumber == $option[1] && $weekDayNumber == '01') {
                        $schedule->call(function () {
                            $this->send_whatsapp_subscription_notification();
                        })->weeklyOn(1, '8:00');

                    }

                } else if ($scronjob->repeat_value == 'month') {
                    $weekDayNumber = (int) date('w');
                    $month = date('m');
                    if ($weekDayNumber == '01' && $month == $option[1]) {
                        $schedule->call(function () {
                            $this->send_whatsapp_subscription_notification();
                        })->monthlyOn(1, '15:00');
                    }

                }

            }

        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    public function send_whatsapp_subscription_notification()
    {

        $users = User::where('whatsapp_subscription_status', 0)->whereIn('role', ['2', '3'])->whereNotNull('phone_number')->get();
        if ($users) {
            foreach ($users as $key => $value) {
                $this->sendemail($value->email, $value->user_name);
            }
        }
    }

    public function sendemail($user_email, $username)
    {

        $Admin = User::where('role', '1')->first();
        $logo_url = ImageHelper::getProfileImage($Admin->logo_image);

        $userMailData = array(
            'name' => $username,
            'email' => $user_email,
            'user_name' => $username,
            'form_name' => 'Support@gmail.com',
            'schedule_name' => 'weBOOK',
            'template' => 'whatsapp_signup',
            'subject' => 'Subscribe Whatsapp Notification ',
            'message' => " +14155238886 ",
            'base_url' => url('/login'),
            'logo_url' => $logo_url,
        );
        if (!empty($userMailData) && !empty($user_email && !is_null($user_email))) {
            Mail::to($user_email)->send(new NotifyMail($userMailData));
        } else {
            return false;
        }
    }

    /**
     * Store question to user record for send notifiction
     * @param string $message Body of
     * @param string $recipient Number of recipient
     */
    public function question_store_for_user($value)
    {
        if ($value->user_type == 2) {
            $users = User::where('role', 2)->get();
            if ($users) {
                foreach ($users as $ukey => $uvalue) {

                    // delete old question before send notification questions
                    $oldquestionans = UserQuestionsAns::where('user_id', $uvalue->id)->where('question_id', $value->id)->first();
                    if ($oldquestionans) {
                        $oldquestionans->delete();
                    }

                    // send new question to user
                    $questionans = new UserQuestionsAns();
                    $questionans->question_id = $value->id;
                    $questionans->user_id = $uvalue->id;
                    $questionans->sent_status = 1;
                    $questionans->save();

                }
            }
        } else if ($value->user_type == 3) {
            $users = User::where('role', 3)->get();
            if ($users) {
                foreach ($users as $ukey => $uvalue) {

                    // delete old question before send notification questions
                    $oldquestionans = UserQuestionsAns::where('user_id', $uvalue->id)->where('question_id', $value->id)->first();
                    if ($oldquestionans) {
                        $oldquestionans->delete();
                    }

                    //send new question to user
                    $questionans = new UserQuestionsAns();
                    $questionans->question_id = $value->id;
                    $questionans->user_id = $uvalue->id;
                    $questionans->sent_status = 1;
                    $questionans->save();

                }
            }
        }
    }
}
