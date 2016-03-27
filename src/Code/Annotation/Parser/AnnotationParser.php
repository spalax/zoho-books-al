<?php
namespace ZohoBooksAL\Code\Annotation\Parser;

use Zend\Code\Annotation\Parser\GenericAnnotationParser;

class AnnotationParser extends GenericAnnotationParser
{
    public function __construct()
    {
        $this->registerAnnotations(array('ZohoBooksAL\\Code\\Annotation\\Id'));
        $this->registerAnnotations(array('ZohoBooksAL\\Code\\Annotation\\Column'));
        $this->registerAnnotations(array('ZohoBooksAL\\Code\\Annotation\\Service'));
        $this->registerAnnotations(array('ZohoBooksAL\\Code\\Annotation\\Entity'));
        $this->registerAnnotations(array('ZohoBooksAL\\Code\\Annotation\\OneToMany'));

        $this->setAlias('\\ZOHO\\Id', 'ZohoBooksAL\\Code\\Annotation\\Id');
        $this->setAlias('\\ZOHO\\Column', 'ZohoBooksAL\\Code\\Annotation\\Column');
        $this->setAlias('\\ZOHO\\Service', 'ZohoBooksAL\\Code\\Annotation\\Service');
        $this->setAlias('\\ZOHO\\Entity', 'ZohoBooksAL\\Code\\Annotation\\Entity');
        $this->setAlias('\\ZOHO\\OneToMany', 'ZohoBooksAL\\Code\\Annotation\\OneToMany');
    }

    /**
     * Normalize an alias name
     *
     * @param  string $alias
     * @return string
     */
    protected function normalizeAlias($alias)
    {
        $alias = substr($alias, strpos($alias, '\\ZOHO'), strlen($alias));
        return parent::normalizeAlias($alias);
    }
}
