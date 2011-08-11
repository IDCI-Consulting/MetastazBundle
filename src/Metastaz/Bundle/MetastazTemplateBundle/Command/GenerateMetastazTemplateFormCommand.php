<?php

namespace Metastaz\Bundle\MetastazTemplateBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Metastaz\Bundle\MetastazTemplateBundle\Generator\MetastazTemplateFormGenerator;

class GenerateMetastazTemplateFormCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('metastaz:generate:form')
            ->setDescription('Generates all form type classes based on a MetastazTemplates')
            ->addOption('connection', null, InputOption::VALUE_OPTIONAL, 'The connection to use for this command')
            ->addOption('overwrite', null, InputOption::VALUE_NONE, 'To overwrite existing MetastazTemplates forms')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connection = $input->getOption('connection') ? $input->getOption('connection') : 'metastaz_template';
        $overwrite = $input->getOption('overwrite') ? true : false;
        $em = $this->getContainer()->get('doctrine')->getEntityManager($connection);

        try {
            $re = $em->getRepository('MetastazTemplateBundle:MetastazTemplate');
            $templates = $re->findAll();

            if(!$templates) {
                $output->writeln(sprintf('No metastaz templates define, go on <info>%s</info> to add template',
                    '/metastaz/template/'
                ));
                return;
            }

            $rootPath = $this->getContainer()->get('kernel')->getRootDir();
            $dir = sprintf('%s/Application/Form', $rootPath);
            if(!is_dir($dir)) {
                $output->writeln(sprintf('Generating MetastazTemplate form directory <comment>%s</comment>', $dir));
                mkdir($dir, 0755, true);
            }

            $output->writeln('Start metastaz template forms generation...');
            foreach($templates as $template) {

                $generator = new MetastazTemplateFormGenerator(
                    $this->getContainer()->get('filesystem'),
                    $rootPath.'/../vendor/metastaz/src/Metastaz/Bundle/MetastazTemplateBundle/Resources/skeleton/form'
                );

                if($generator->generate($template, $dir, $overwrite)) {
                    $output->writeln(sprintf(
                        '  > Generate %s template form [<comment>%s</comment>] in <info>%s</info>',
                        $template,
                        $generator->getClassName(),
                        $generator->getClassPath()
                    ));
                }
                else {
                    $output->writeln(sprintf(
                        '  > MetastazTemplate form for <comment>%s</comment> already exist in <comment>%s</comment>',
                        $template,
                        $generator->getClassPath()
                    ));
                }

            }
            $output->writeln('<info>Done succesfuly</info>');
        }
        catch(Exception $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
        }
    }
}
