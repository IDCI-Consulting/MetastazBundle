<?php
namespace Metastaz\Bundle\MetastazTemplateBundle\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\HttpKernel\Util\Filesystem;
use Metastaz\Bundle\MetastazTemplateBundle\Entity\MetastazTemplate;

/**
 * Generates a form class based on a MetastazTemplate.
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @licence: GPL
 */
class MetastazTemplateFormGenerator extends Generator
{
    private $filesystem;
    private $skeletonDir;
    private $className;
    private $classPath;

    public function __construct(Filesystem $filesystem, $skeletonDir)
    {
        $this->filesystem = $filesystem;
        $this->skeletonDir = $skeletonDir;
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function getClassPath()
    {
        return $this->classPath;
    }

    /**
     * Generates the template form if it does not exist.
     *
     * @param MetastazTemplate $template
     * @param string $path
     */
    public function generate(MetastazTemplate $template, $dir, $overwrite = false)
    {
        $this->className = $template->getName().'MetastazTemplateType';
        $this->classPath = $dir.DIRECTORY_SEPARATOR.$this->className.'.php';

        if (file_exists($this->classPath) && !$overwrite) {
            return false;
        }

        $this->renderFile($this->skeletonDir, 'FormType.php', $this->classPath, array(
            'fields'           => $template->getFormFields(),
            'namespace'        => 'Application\\Form',
            'form_class'       => $this->className,
            'form_type_name'   => 'application_'.strtolower($this->className),
        ));

        return true;
    }

    /**
     * Returns an array of fields. Fields can be both column fields and
     * association fields.
     *
     * @param ClassMetadataInfo $metadata
     * @return array $fields
     */
    private function getFieldsFromMetadata(ClassMetadataInfo $metadata)
    {
        $fields = (array) $metadata->fieldNames;

        // Remove the primary key field if it's not managed manually
        if (!$metadata->isIdentifierNatural()) {
            $fields = array_diff($fields, $metadata->identifier);
        }

        foreach ($metadata->associationMappings as $fieldName => $relation) {
            if ($relation['type'] !== ClassMetadataInfo::ONE_TO_MANY) {
                $fields[] = $fieldName;
            }
        }

        return $fields;
    }
}
