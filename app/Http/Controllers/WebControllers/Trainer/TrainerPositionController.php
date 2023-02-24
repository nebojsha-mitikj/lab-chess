<?php

namespace App\Http\Controllers\WebControllers\Trainer;

use App\Helpers\TrainerPositions;
use App\Http\Controllers\Controller;
use App\Models\Trainer\TrainerPosition;
use App\Models\Trainer\TrainerVariant;
use App\Models\User\UserConfiguration;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TrainerPositionController extends Controller
{

    /**
     * Go to trainer position page.
     * @param string $code
     * @param int $variantNumber
     * @param int $positionNumber
     * @return Application|Factory|View|RedirectResponse
     */
    public function index(string $code, int $variantNumber, int $positionNumber){
        $trainer = TrainerPositions::getTrainerByCodeOrFail($code);
        $variant = TrainerVariant::where('trainer_id',$trainer->id)->where('number', $variantNumber)->firstOrFail();
        $position = TrainerPosition::where('variant_id', $variant->id)->where('number', $positionNumber)->firstOrFail();
        $positionsCount = TrainerPosition::where('variant_id', $variant->id)->count();
        $subscription = Auth::user()->subscription(env('PADDLE_SUBSCRIPTION'));
        if(
            ($subscription == null || !$subscription->active())
            &&
            ($position->number > $this->getLockFromPosition($positionsCount))
        ){
            return redirect()->route('premium');
        }

        $nextPosition = TrainerPosition::nextUnfinishedPosition($position->id);
        $userConfiguration = UserConfiguration::where('user_id',Auth::id())->with(['boardTheme', 'pieceTheme'])->first();

        return view('trainer.trainer-position')->with([
            'userConfiguration' => $userConfiguration,
            'trainer' => json_encode($trainer),
            'variant' => $variant,
            'position' => $position,
            'nextPosition' => json_encode($nextPosition)
        ]);
    }

    /**
     * Get locked from position.
     * @param int $positionsCount
     * @return int
     */
    private function getLockFromPosition(int $positionsCount) : int {
        return floor($positionsCount / 2) > 20 ? 20 : floor($positionsCount / 2);
    }

}
