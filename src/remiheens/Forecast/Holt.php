<?php

namespace Forecast;

class Holt
{

    private $stats;
    
    private $mu;
    private $lambda;
    
    private $previsions = array();
    private $levels = array();
    private $gradients = array();

    public function __construct($stats = array(), $mu = 0.3, $lambda = 0.3)
    {
        if(!is_array($stats))
        {
            throw new \Exception('you must provide an array of values');
        }
        $this->stats = $stats;
        $this->mu = $mu;
        $this->lambda = $lambda;
        $this->generate();
    }

    private function generate()
    {
        $lastValue = end($this->stats);
        $firstValue = reset($this->stats);

        if($lastValue !== false && $firstValue !== false)
        {
            $countValues = count($this->stats);
            if($countValues - 1 === 0)
            {
                $originGradient = 0;
            }
            else
            {
                $originGradient = (($lastValue - $firstValue) / ($countValues - 1));
            }

            $originLevel = $firstValue - (0.5 * $originGradient);

            $i = 0;
            foreach($this->stats as $stat)
            {
                if($i === 0)
                {
                    $this->previsions[$i] = $originLevel + $originGradient;
                    $this->levels[$i] = $this->lambda * $stat + (1 - $this->lambda) * $this->previsions[$i];
                    $this->gradients[$i] = $this->mu * ($this->levels[$i] - $originLevel)
                            + (1 - $this->mu) * $originGradient;
                }
                else
                {
                    $this->previsions[$i] = $this->levels[($i - 1)] + $this->gradients[($i
                            - 1)];
                    $this->levels[$i] = $this->lambda * $stat + (1 - $this->lambda) * $this->previsions[$i];
                    $this->gradients[$i] = $this->mu * ($this->levels[$i] - $this->levels[($i
                            - 1)]) + (1 - $this->mu) * $this->gradients[($i - 1)];
                }
                $i++;
            }
        }
    }

    public function next()
    {
        return end($this->levels) + end($this->gradients);
    }

}
