<?php

namespace App\Http\Controllers;

use App\Services\ProjectionParser;
use Illuminate\Http\Request;

use App\Http\Requests;
use Goutte\Client;
use App\Http\Controllers\Controller;

class ProjectionController extends Controller
{
    private $defensePositions = [
        'LB', 'DB', 'DL'
    ];

    /**
     * @var
     */
    private $projectionParser;

    /**
     * ProjectionController constructor.
     *
     * @param ProjectionParser $projectionParser
     */
    public function __construct(ProjectionParser $projectionParser)
    {

        $this->projectionParser = $projectionParser;
    }

    /**
     * Display a listing of the starters.
     *
     * @return Response
     */
    public function index()
    {
        $client = new Client();

        $teams = $this->getStarters($client);

        $projections = $this->projectionParser->parse();

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

        $projections = $this->projectionParser->parse();

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

        $projections = $this->projectionParser->parse();

        $freeAgents = $this->removeRosteredFromProjections($projections, $teams);

        return view('free-agents', [
            'freeAgents' => $freeAgents
        ]);
    }

    /**
     * Displays the dfs salaries and projects
     *
     * @return \Illuminate\View\View
     */
    public function dfs()
    {
        $projections = $this->parseProjections();
        $salaries = $this->parseSalaries();

        $players = collect($this->combineProjectionsWithSalaries($projections, $salaries));

        $players = $players->reject(function ($item) {
            return !isset($item['points']) || $item['DPS'] <= 0;
        });

        $players = $players->sortBy('DPS');

        return view('dfs', [
            'players' => $players
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
    private function parseSalaries()
    {
        $filename = base_path() . '/public/salaries.csv';
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
            $players[] = array_combine($header, $row);
        }

        $salaries = [];

        foreach ($players as $player) {

            $salaries[] = [
                'name' => $player['Name'],
                'salary' => $player['Salary'],
                'position' => $player['Position']
            ];
        }

        return $salaries;
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
     * Combine the projections with the provided teams
     *
     * @param $projections
     * @param $salaries
     * @return mixed
     */
    private function combineProjectionsWithSalaries($projections, $salaries)
    {
        foreach ($salaries as &$salary) {
            foreach ($projections as $projection) {
                if (str_contains(strtolower($salary['name']), strtolower($projection['name']))) {
                    $salary['points'] = is_numeric($projection['points']) ? $projection['points']  : 0;
                    $salary['DPS'] = $projection['points'] > 0 ?  $salary['salary'] / $projection['points']  : 0;
                }
            }
        }

        return $salaries;
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

                    if (in_array($projection['position'], $this->defensePositions)) {
                        unset($projections[$key]);
                    }
                }
            }
        }

        return $projections;
    }
}
