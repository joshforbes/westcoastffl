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
     * Display a listing of the resource.
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
                'points' => $player['Pts']
            ];
        }

        return $projections;
    }

    private function combineProjectionsWithTeams($projections, $teams)
    {
        foreach ($teams as $teamName => $team) {
            foreach ($team as $player) {
                foreach ($projections as $projection) {
                    if ($player == $projection['name']) {
                        $teams[$teamName][$player] = is_numeric($projection['points']) ? $projection['points']  : 0;
                    }
                }
            }
        }

        return $teams;
    }
}
