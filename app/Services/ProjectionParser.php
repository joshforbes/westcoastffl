<?php

namespace App\Services;


class ProjectionParser
{
    private $filename = '/public/projections.csv';
    private $players = [];
    private $projections = [];

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

    public function __construct()
    {
        $this->filename = base_path() . $this->filename;
    }

    public function parse()
    {
        $file = fopen($this->filename, 'r');

        $this->getPlayersFromCsv($file);

        foreach ($this->players as $player) {
            $playerName = $this->extractNameFromPlayer($player);

            $playerName = $this->formatDefenseNames($playerName);

            $this->projections[] = [
                'name' => $this->formatPlayerName($playerName),
                'points' => $player['Pts'],
                'position' => $player['Position']
            ];
        }

        return $this->projections;
    }

    /**
     * Read the csv and add its contents to the players array
     *
     * @param $file
     */
    public function getPlayersFromCsv($file)
    {
        $header = null;
        while ($row = fgetcsv($file)) {
            if ($header === null) {
                $header = $row;
                continue;
            }
            if (count($row) !== 16) {
                continue;
            }
            $this->players[] = array_combine($header, $row);
        }
    }

    /**
     * @param $player
     *
     * @return array
     */
    public function extractNameFromPlayer($player)
    {
        return explode(',', $player['Player']);
    }

    /**
     * @param $playerName
     *
     * @return mixed
     */
    public function formatDefenseNames($playerName)
    {
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

        return $playerName;
    }

    /**
     * @param $playerName
     *
     * @return string
     */
    public function formatPlayerName($playerName)
    {
        return ltrim(implode(' ', array_reverse($playerName)));
    }
}
