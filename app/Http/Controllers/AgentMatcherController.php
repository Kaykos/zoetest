<?php

namespace App\Http\Controllers;


use App\AgentMatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AgentMatcherController extends Controller
{
    private $AgentMatcher;

    public function __construct()
    {
        $this->AgentMatcher = new AgentMatcher();
    }

    public function home()
    {
        return view('home', [
            'contacts' => $this->AgentMatcher->getContacts()
        ]);
    }

    public function match(Request $request)
    {
        $this->AgentMatcher->setAgentsData($request->input('agent1_zip_code'), $request->input('agent2_zip_code'));

        $dataValidation = $this->AgentMatcher->checkForValidAgentsInformation();

        if (!$dataValidation['valid']) {
            return Redirect::to('/')->withErrors($dataValidation['errors']);
        }

        $matches = $this->AgentMatcher->getMatches();

        return view('match_results', [
            'results' => $matches
        ]);
    }
}
