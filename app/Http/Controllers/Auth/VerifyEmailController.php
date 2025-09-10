<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the user's email address as verified via signed link.
     */
    public function __invoke(Request $request, $id, $hash): RedirectResponse
    {
        $user = User::findOrFail($id);

        // Validate the hash matches the user's email
        if (! hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            abort(403, 'Invalid verification link.');
        }

        $query = http_build_query([
            'verified' => 1,
            'name' => $user->name,
        ]);

        if ($user->hasVerifiedEmail()) {
            return redirect()->to(config('app.frontend_url') . "/verified?$query");
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return redirect()->to(config('app.frontend_url') . "/verified?$query");
    }
}
