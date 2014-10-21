<?php

namespace Demo\View;

use \Duality\Structure\HtmlDoc;
use \Duality\Structure\File\TextFile;

/**
 * The home view
 */
class Home extends HtmlDoc
{
    /**
     * Create the home view
     */
    public function __construct()
    {
        parent::__construct();
        $template = new TextFile('./data/template.html');
        $template->load();
        $this->loadFile($template);
    }

    /**
     * Set view Heading
     * 
     * @param string $heading The page heading
     * 
     * @return void
     */
    public function addHeading($heading)
    {
        $this->appendTo(
            '//div[@class="page-header"]',
            '<h1 id="title">' . $heading . '</h1>'
        );
    }    
}