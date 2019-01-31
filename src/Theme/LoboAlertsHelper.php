<?php
namespace Digraph\Modules\unm_digraph_theme\Theme;

use Digraph\Helpers\AbstractHelper;

class LoboAlertsHelper extends AbstractHelper
{
    public function current()
    {
        $cache = $this->cms->cache();
        $id = 'loboalerts.'.md5($this->cms->config['unm.loboalerts.source']);
        //try to find in cache
        if ($cache && $cache->hasItem($id)) {
            return $cache->getItem($id)->get();
        }
        //read source file
        if ($file = file_get_contents($this->cms->config['unm.loboalerts.source'])) {
            if ($parsed = json_decode($file, true)) {
                //save to cache and return
                $citem = $cache->getItem($id);
                $citem->expiresAfter($this->cms->config['unm.loboalerts.cachettl']);
                $citem->set($parsed);
                $cache->save($citem);
                return $parsed;
            }
        }
        return [];
    }

    public function active()
    {
        return @$this->current()['alert'] != 'none';
    }
}
