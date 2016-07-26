<?php

namespace smtech\StMarksSmarty;

use Battis\BootstrapSmarty\BootstrapSmarty;
use Battis\DataUtilities;

/**
 * A wrapper for Smarty to set (and maintain) defaults
 *
 * @author Seth Battis <SethBattis@stmarksschool.org>
 **/
class StMarksSmarty extends BootstrapSmarty
{

    const KEY = 'StMarks-BootstrapSmarty';

    private $isFramed = false;

    /**
     * @inheritDoc
     *
     * @param string $template
     * @param string $config (Optional)
     * @param string $compile (Optional)
     * @param string $cache (Optional)
     */
    public function __construct($template = null, $config = null, $compile = null, $cache = null)
    {
        parent::__construct($template, $config, $compile, $cache);

        $this->addTemplateDir(__DIR__ . '/../templates', self::KEY);
        $this->addStylesheet(DataUtilities::URLfromPath(__DIR__ . '/../css/StMarksSmarty.css'), self::KEY);
    }

    /**
     * Is this app displayed inside an `IFRAME`?
     *
     * @param boolean $isFramed
     */
    public function setFramed($isFramed)
    {
        $this->isFramed = ($isFramed == true);
    }

    /**
     * Is this app displayed inside an `IFRAME`?
     *
     * @return boolean
     */
    public function isFramed()
    {
        return $this->isFramed;
    }

    /**
     * @inheritDoc
     *
     * @param string $template (Optional)
     * @param string $cache_id (Optional)
     * @param string $compile_id (Optional)
     * @param string $parent (Optional)
     * @return void
     */
    public function display($template = 'page.tpl', $cache_id = null, $compile_id = null, $parent = null)
    {
        if ($this->isFramed()) {
            $this->addStylesheet(
                DataUtilities::URLfromPath(__DIR__ . '/../css/StMarksSmarty.css') . '?isFramed=true',
                self::KEY
            );
        }
        $this->assign('isFramed', $this->isFramed());

        parent::display($template, $cache_id, $compile_id, $parent);
    }
}
