<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Goutte\Client;
use App\Http\Controllers\Controller;

class ProjectionController extends Controller
{
    private $mascots = [
        'Cardinals', 'Falcons', 'Ravens',
        'Bills', 'Panthers', 'Bears',
        'Bengals', 'Browns', 'Cowboys',
        'Broncos', 'Lions', 'Packers',
        'Texans', 'Colts', 'Jaguars',
        'Chiefs', 'Dolphins', 'Vikings',
        'Patriots', 'Saints', 'Giants',
        'Jets', 'Raiders', 'Eagles',
        'Steelers', 'Chargers', '49ers',
        'Seahawks', 'Rams', 'Buccaneers',
        'Titans', 'Redskins'
    ];

    /**
     * Display a listing of the starters.
     *
     * @return Response
     */
    public function index()
    {
        $client = new Client();

        $teams = $this->getStarters($client);

        $projections = $this->parseProjections($client);

        $teams = $this->combineProjectionsWithTeams($projections, $teams);

        return view('projections', [
            'teams' => $teams
        ]);
    }

    /**
     * Displays the full team view
     *
     * @return \Illuminate\View\View
     */
    public function team()
    {
        $client = new Client();

        $teams = $this->getFullTeam($client);

        $projections = $this->parseProjections($client);

        $teams = $this->combineProjectionsWithTeams($projections, $teams);

        return view('team', [
            'teams' => $teams
        ]);
    }

    /**
     * Displays players that are not rostered
     *
     * @return \Illuminate\View\View
     */
    public function freeAgent()
    {
        $client = new Client();

        $teams = $this->getFullTeam($client);

        $projections = $this->parseProjections($client);

        $freeAgents = $this->removeRosteredFromProjections($projections, $teams);

        return view('free-agents', [
            'freeAgents' => $freeAgents
        ]);
    }

    /**
     * Parses the starters from the given url
     *
     * @param $client
     * @return array
     */
    private function getStarters($client)
    {
        $teams = [];
        $teamName = '';

        $crawler = $client->request('GET', 'https://fantasybowl.com/2015/?LeagueID=28588&Page=LineupCard');

        $nodes = $crawler->filter('td.tinh1, td.tinyc')->each(function ($node) {
            return $node->text();
        });

        foreach ($nodes as $node) {
            if (preg_match('#[0-9]#', $node)) {
                $teamName = $node;
            } elseif (strlen($node) < 3) {
                continue;
            } else {
                $teams[$teamName][$node] = $node;
            }
        }

        return $teams;
    }

    /**
     * Parses the full team from the given url
     *
     * @param $client
     * @return array
     */
    private function getFullTeam($client)
    {
        $teams = [];
        $teamName = '';

        $crawler = $client->request('GET', 'https://fantasybowl.com/2015/?LeagueID=28588&Page=RosterCard');

        $nodes = $crawler->filter('td.tinh1, td.tinyc')->each(function ($node) {
            return $node->text();
        });

        foreach ($nodes as $node) {
            if (preg_match('#[0-9]#', $node)) {
                $teamName = $node;
            } elseif (strlen($node) < 3) {
                continue;
            } else {
                $teams[$teamName][$node] = $node;
            }
        }

        return $teams;
    }

    /**
     * Parses the projections from the given csv
     *
     * @return array
     */
    private function parseProjections()
    {
        $filename = base_path() . '/public/projections.csv';
        $file = fopen($filename, 'r');

        $players = [];
        $header = null;

        while ($row = fgetcsv($file))
        {
            if ($header === null)
            {
                $header = $row;
                continue;
            }
            if (count($row) !== 16) {
                continue;
            }
            $players[] = array_combine($header, $row);
        }

        $projections = [];

        foreach ($players as $player) {
            $playerName = explode(',', $player['Player']);

            if ($playerName[0] == 'Jets') {
                $playerName[1] = 'NY Jets';
            }

            if ($playerName[0] == 'Giants') {
                $playerName[1] = 'NY Giants';
            }

            foreach ($this->mascots as $mascot) {
                if ($mascot == $playerName[0]) {
                    $playerName[0] = 'D';
                }
            }

            $projections[] = [
                'name' => ltrim(implode(' ', array_reverse($playerName))),
                'points' => $player['Pts'],
                'position' => $player['Position']
            ];
        }

        return $projections;
    }

    /**
     * Combine the projections with the provided teams
     *
     * @param $projections
     * @param $teams
     * @return mixed
     */
    private function combineProjectionsWithTeams($projections, $teams)
    {
        foreach ($teams as $teamName => $team) {
            foreach ($team as $player) {
                foreach ($projections as $projection) {
                    if (strtolower($player) == strtolower($projection['name'])) {
                        $teams[$teamName][$player] = is_numeric($projection['points']) ? $projection['points']  : 0;
                    }
                }
            }
        }

        return $teams;
    }

    /**
     * Remove the players on the provided teams from the projections
     *
     * @param $projections
     * @param $teams
     * @return mixed
     */
    private function removeRosteredFromProjections($projections, $teams)
    {
        foreach ($teams as $teamName => $team) {
            foreach ($team as $player) {
                foreach ($projections as $key => $projection) {
                    if (strtolower($player) == strtolower($projection['name'])) {
                        unset($projections[$key]);
                    }
                }
            }
        }

        return $projections;
    }
}
