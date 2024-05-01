<?php

namespace App\common;

use Carbon\Carbon;
use Seshac\Otp\Otp;
use Seshac\Otp\Models\Otp AS OTPModel;
use Illuminate\Support\Facades\Mail;
use App\Mail\Email;

class Helpers {
    public static function saveData($model, $data) {
        foreach ($data as $key => $value) {
            $model->{$key} = $value;
        }

        Helpers::emptyToNull($model);
        $model->save();

        return $model;
    }


    public static function emptyToNull($model) {
        foreach ($model->getAttributes() as $attribute => $value) {
            if ($value === "") {
                $model->{$attribute} = NULL;
            }
        }
    }


    public static function SendMail($email, $user, $content) {
        Mail::to($email)->send(new Email($user, $content));
    }

    public static function generateOTP($email, $expirationMinutes)
    {
        $otp = Otp::generate($email);

        $expiredTime = Carbon::now()->addMinutes($expirationMinutes);

        $OtpRecord = Otp::findOrFail(['identifier' => $email]);

        if(empty($OtpRecord)) {
            $OtpRecord = new OTPModel(['identifier' => $email]);
        }

        $OtpRecord->expiration = $expiredTime;
        $OtpRecord->save();

        return $otp;
    }

    public static function validateOTP($email, $otp)
    {
        $storedOtp = Otp::where('email', $email)->first();

        if (!$storedOtp || $storedOtp->otp !== $otp) {
            return false;
        }

        if (Carbon::now()->gt($storedOtp->expiration)) {
            return false;
        }

        return true;
    }
}
