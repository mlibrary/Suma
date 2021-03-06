<?php

require_once 'BaseTransformer.php';

class AlcTransformer extends BaseTransformer
{
    private $_sessionHash = array();


    public function addRow($row)
    {
        $tier = null;
        if ($this->_byCounts == false)
        {
            if (! isset($this->_nestedArrays['initiative']['sessions']))
            {
                $this->_nestedArrays['initiative']['sessions'] = array();
            }
            $tier =& $this->addSession($row);
        }
        else
        {
            if (! isset($this->_nestedArrays['initiative']['activities']))
            {
                $this->_nestedArrays['initiative']['activities'] = array();
            }
            $tier =& $this->_nestedArrays['initiative'];
        }

        $activity =& $this->addActivity($row, $tier);
        $location =& $this->addLocation($row, $activity);
        $this->addCount($row, $location);
    }

    private function &addSession($row)
    {
        if (isset($this->_sessionHash[(int)$row['sid']]))
        {
            return $this->_sessionHash[(int)$row['sid']];
        }

        $addition = array('id'         =>  (int)$row['sid'],
                          'start'      =>  $row['start'],
                          'end'        =>  $row['end'],
                          'transId' => $row['transId'],
                          'transStart' => $row['transStart'],
                          'transEnd' => $row['transEnd'],
                          'activities' =>  array());

        $sessions =& $this->_nestedArrays['initiative']['sessions'];
        $sessions[] =& $addition;
        $this->_sessionHash[(int)$row['sid']] =& $addition;
        return $addition;
    }

    private function &addActivity($row, &$tier)
    {
        $activities =& $tier['activities'];

        if (isset($row['act']))
        {
            foreach($activities as &$activity)
            {
                if ($activity['id'] == $row['act'])
                {
                    $selected =& $activity;
                    unset($activity);
                    break;
                }
            }

            if (isset($selected))
            {
                return $selected;
            }

            $addition = array('id'        => (int)$row['act'],
                              'locations' => array());
            $activities[] =& $addition;
            return $addition;
        }
        else
        {
            foreach($activities as &$activity)
            {
                if ($activity['id'] == -1)
                {
                    $selected =& $activity;
                    unset($activity);
                    break;
                }
            }

            if (isset($selected))
            {
                return $selected;
            }

            $addition = array('id'        => -1,
                              'locations' => array());

            $activities[] =& $addition;
            return $addition;
        }

    }

    private function &addLocation($row, &$activity)
    {
        $locations =& $activity['locations'];

        foreach($locations as &$location)
        {
            if ($location['id'] == $row['loc'])
            {
                $selected =& $location;
                unset($location);
                break;
            }
        }

        if (isset($selected))
        {
            return $selected;
        }


        if ($this->_sum == true)
        {
            $addition = array('id'     => (int)$row['loc'],
                              'counts' => 0);
        }
        else
        {
            $addition = array('id'     => (int)$row['loc'],
                              'counts' => array());
        }

        $locations[] =& $addition;
        return $addition;
    }

    private function addCount($row, &$location)
    {
        $counts =& $location['counts'];

        if ($this->_sum == true)
        {
            $counts += (int)$row['cnum'];
        }
        else
        {
            $counts[] = array('id'    =>  (int)$row['cid'],
                             'time'   =>  $row['oc'],
                             'number' => (int)$row['cnum']);
        }
    }

}
