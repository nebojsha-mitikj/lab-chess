<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserDeleteAccountRequest extends Model
{
    use HasFactory;

    public static $successMessage =
        "We've sent you an email with a deletion link. Your entire data, including your progress
    and personal information, will get deleted as soon as you click the link in the email. If you've changed your mind,
    ignore the email.";

    public static $errorMessage =
        "You've recently made a deletion request. Please wait at least 24 hours before submitting another.";

    protected $table = 'user_delete_account_request';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'user_id',
        'token'
    ];

    /**
     * Creates new userDeleteAccountRequest row.
     * @return UserDeleteAccountRequest
     */
    public static function createRow() : UserDeleteAccountRequest {
        $userDeleteAccountRequest = new UserDeleteAccountRequest();
        $userDeleteAccountRequest->user_id = Auth::id();
        $userDeleteAccountRequest->token = Str::random(42);
        $userDeleteAccountRequest->save();
        return $userDeleteAccountRequest;
    }
}
