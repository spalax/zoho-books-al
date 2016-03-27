<?php
namespace ZohoBooksAL\Code\Annotation;
use Zend\Code\Annotation\AnnotationInterface;
use ZohoBooksAL\Code\Annotation\Exception\InvalidAttributeValueException;

class Column implements AnnotationInterface
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var bool
     */
    protected $required = false;

    /**
     * @var string
     */
    protected $type = null;

    /**
     * @var string
     */
    protected $format = '';

    /**
     * @var bool
     */
    protected $readonly = false;

    /**
     * Initialize
     *
     * @param string $content
     */
    public function initialize($content)
    {
        if (preg_match("/name\s*?=\s*?[\"|\'](?P<name>.+?)[\"|\']/", $content, $matches)) {
            $this->name = $matches['name'];
        }

        if (preg_match("/required\s*?=\s*?[\"|\'](?P<required>.+?)[\"|\']/", $content, $matches)) {
            $this->required = (boolean)$matches['required'];
        }

        if (preg_match("/type\s*?=\s*?[\"|\'](?P<type>.+?)[\"|\']/", $content, $matches)) {
            $this->type = $matches['type'];
        }

        if (preg_match("/format\s*?=\s*?[\"|\'](?P<format>.+?)[\"|\']/", $content, $matches)) {
            $this->format = $matches['format'];
        }

        if (preg_match("/readonly\s*?=\s*?[\"|\'](?P<readonly>.+?)[\"|\']/", $content, $matches)) {
            $this->readonly = (boolean)$matches['readonly'];
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @return \Closure
     */
    public function getHydrator()
    {
        return function ($data) {
            if (!array_key_exists($this->name, $data)) {
                $value = null;
            } else {
                $value = $data[$this->name];
            }

            if ($this->type === 'DateTime') {
                return new \DateTime($value);
            } else {
                return $value;
            }
        };
    }

    /**
     * @return \Closure
     */
    public function getExtractor()
    {
        return function ($value) {
            if ($this->type === 'DateTime' && $value instanceof \DateTime) {
                if (empty($this->format)) {
                    return (string)$value;
                } else {
                    return $value->format($this->format);
                }
            } else {
                return $value;
            }
        };
    }

    /**
     * @return boolean
     */
    public function isReadonly()
    {
        return $this->readonly;
    }
}
