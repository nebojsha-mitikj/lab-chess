<?php

namespace App\Http\Controllers\WebControllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Borad\BoardTheme;
use App\Models\Piece\PieceTheme;
use App\Models\User\UserConfiguration;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class DisplayController extends Controller
{
    /**
     * Read settings display view.
     * @return View
     */
    public function index(): View {
        return view('settings.display')->with([
            'boardThemes' => BoardTheme::all(),
            'pieceThemes' => PieceTheme::all(),
            'userConfiguration' =>  UserConfiguration::where('user_id',Auth::id())->with(['boardTheme', 'pieceTheme'])->first()
        ]);
    }
}
