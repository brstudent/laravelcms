<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Models\Page;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $visitsCount = 0;
        $onlineCount = 0;
        $pageCount = 0;
        $userCount = 0;
        $dayInterval = $request->input('filter', 30);
        $dayLimit = 0;
        
        // Contagem de Visitas
        ////$visitsCount = Visitor::count();

        // Pega a Informação por período de dias escolhido pelo usuário)
        // data e hora atual
        if($dayInterval > 120) {
            $dayInterval = 120;
        }

        $dayLimit = date('Y/m/d H:i:s', strtotime("-$dayInterval days"));
        $visitsCount = Visitor::where('date_access', '>=', $dayLimit)->count();

        // Contagem de Usuários Online
        $dateLimit = date('Y-m-d H:i:s', strtotime('-5 minutes'));
        $onlineList = Visitor::select('ip')->where('date_access', '>=', $dateLimit)->groupBy('ip')->get();
        $onlineCount = count($onlineList);

        // Contagem de Páginas
        $pageCount = Page::count();

        // Contagem de Usuários
        $userCount = User::count();

        // Contagem para o pagePie
        $pagePie = [];
        $visitsAll = Visitor::selectRaw('page, count(page) as c')
            ->where('date_access', '>=', $dayLimit)
            ->groupBy('page')
            ->get();
        foreach($visitsAll as $visit) {
            $pagePie[$visit['page']] = intval($visit['c']);
        }

        $pageLabels = json_encode(array_keys($pagePie));
        $pageValues = json_encode(array_values($pagePie));

        return view('admin.home', [
            'visitsCount' => $visitsCount,
            'onlineCount' => $onlineCount,
            'pageCount' => $pageCount,
            'userCount' => $userCount,
            'pageLabels' => $pageLabels,
            'pageValues' => $pageValues,
            'dayInterval' => $dayInterval,
            'dayLimit' => $dayLimit
        ]);
    }

}
